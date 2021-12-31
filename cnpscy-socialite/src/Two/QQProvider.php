<?php

namespace Cnpscy\Socialite\Two;

use Exception;
use Illuminate\Support\Arr;

/**
 * Class QQProvider.
 *
 * @see http://wiki.connect.qq.com/oauth2-0%E7%AE%80%E4%BB%8B [QQ - OAuth 2.0 登录QQ]
 */
class QQProvider extends AbstractProvider implements ProviderInterface
{
    /**
     * The base url of QQ API.
     *
     * @var string
     */
    protected $baseUrl = 'https://graph.qq.com';

    /**
     * User openid.
     *
     * @var string
     */
    protected $openId;


    /**
     * User unionid.
     *
     * @var string
     */
    protected $unionId;

    /**
     * The scopes being requested.
     *
     * @var array
     */
    protected $scopes = ['get_user_info'];

    /**
     * The uid of user authorized.
     *
     * @var int
     */
    protected $uid;

    /**
     * Get the authentication URL for the provider.
     *
     * @param string $state
     *
     * @return string
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://graph.qq.com/oauth2.0/authorize', $state);
    }

    /**
     * Get the token URL for the provider.
     *
     * @return string
     */
    protected function getTokenUrl()
    {
        return 'https://graph.qq.com/oauth2.0/token';
    }

    /**
     * Get the Post fields for the token request.
     *
     * @param string $code
     *
     * @return array
     */
    protected function getTokenFields($code)
    {
        return parent::getTokenFields($code) + ['grant_type' => 'authorization_code', 'fmt' => 'json'];
    }

    /**
     * Get the access token for the given code.
     *
     * @param string $code
     *
     * @return \Cnpscy\Socialite\AccessToken
     */
    public function getAccessToken($code)
    {
        $response = $this->getHttpClient()->get($this->getTokenUrl(), [
            'query' => $this->getTokenFields($code),
        ]);

        return $this->parseAccessToken($response->getBody()->getContents());
    }

    /**
     * Get the access token from the token response body.
     *
     * @param string $body
     *
     * @return \Cnpscy\Socialite\AccessToken
     */
    public function parseAccessToken($body)
    {
        parse_str($body, $token);

        return parent::parseAccessToken($token);
    }

    /**
     * Get the access token response for the given code.
     *
     * @param  string  $code
     * @return array
     */
    public function getAccessTokenResponse($code)
    {
        $token_url = $this->getTokenUrl() . '?';
        
        foreach ($this->getTokenFields($code) as $k => $v) $token_url .= "$k=" . urlencode($v) . "&";

        $response = $this->getHttpClient()->get($token_url);

        return json_decode($response->getBody(), true);
    }

    /**
     * Get the raw user for the given access token.
     *
     * @param string $token
     *
     * @return array
     */
    protected function getUserByToken($token)
    {
        $url = $this->baseUrl.'/oauth2.0/me?access_token='.$token . '&fmt=json&unionid=1';

        $response = $this->getHttpClient()->get($url);

        $me = json_decode($this->removeCallback($response->getBody()->getContents()), true);

        $this->openId = $me['openid'];
        $this->unionId = isset($me['unionid']) ? $me['unionid'] : '';

        $queries = [
            'access_token' => $token,
            'openid' => $this->openId,
            'oauth_consumer_key' => $this->clientId,
        ];

        $response = $this->getHttpClient()->get('https://graph.qq.com/user/get_user_info?'.http_build_query($queries));

        return json_decode($this->removeCallback($response->getBody()->getContents()), true);
    }

    /**
     * Map the raw user array to a Socialite User instance.
     *
     * @param array $user
     *
     * @return \Cnpscy\Socialite\User
     */
    protected function mapUserToObject(array $user)
    {
        return new User([
            'id' => $this->openId,
            'unionid' => $this->unionId,
            'nickname' => $user['nickname'],
            'gender' => $user['gender'],
            'avatar' => $user['figureurl_qq_2'],
        ]);
    }

    /**
     * Remove the fucking callback parentheses.
     *
     * @param string $response
     *
     * @return string
     */
    protected function removeCallback($response)
    {
        if (false !== strpos($response, 'callback')) {
            $lpos = strpos($response, '(');
            $rpos = strrpos($response, ')');
            $response = substr($response, $lpos + 1, $rpos - $lpos - 1);
        }

        return $response;
    }
}
