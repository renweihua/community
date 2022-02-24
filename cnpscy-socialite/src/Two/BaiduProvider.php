<?php

namespace Cnpscy\Socialite\Two;

use Exception;
use Illuminate\Support\Arr;

/**
 * Class BaiduProvider
 *
 * @see https://developer.baidu.com/wiki/index.php?title=docs/oauth [OAuth 2.0 授权机制说明]
 *
 * @package Cnpscy\Socialite\Two
 */
class BaiduProvider extends AbstractProvider implements ProviderInterface
{
    /**
     * Unique Provider Identifier.
     */
    public const IDENTIFIER = 'BAIDU';

    /**
     * The scopes being requested.
     *
     * @var array
     */
    protected $scopes = ['user:email'];

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://openapi.baidu.com/oauth/2.0/authorize', $state);
    }

    /**
     * {@inheritdoc}.
     */
    protected function getCodeFields($state = null)
    {
        return array_merge([
            'response_type' => 'code',
            'client_id'     => $this->clientId,
            'redirect_uri'  => $this->redirectUrl,
            'scope'         => $this->formatScopes($this->scopes, $this->scopeSeparator),
            'display'       => $this->display,
        ], $this->parameters);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return 'https://openapi.baidu.com/oauth/2.0/access_token';
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
        return parent::getTokenFields($code) + ['grant_type' => 'authorization_code'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $userUrl = 'https://openapi.baidu.com/rest/2.0/passport/users/getInfo';

        $response = $this->getHttpClient()->get(
            $userUrl, $this->getRequestOptions($token)
        );

        return json_decode($response->getBody(), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'id' => $user['userid'],
            'nickname' => $user['realname'],
            'name' => Arr::get($user, 'username'),
            'avatar' => $user['portrait'],
        ]);
    }

    /**
     * Get the default options for an HTTP request.
     *
     * @param string $token
     * @return array
     */
    protected function getRequestOptions($token)
    {
        return [
            'query'   => [
                'access_token' => $token->getToken(),
            ]
            'headers' => [
                'Accept' => 'application/json',
            ],
        ];
    }
}
