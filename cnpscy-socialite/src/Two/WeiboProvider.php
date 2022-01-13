<?php

namespace Cnpscy\Socialite\Two;

use Exception;
use Illuminate\Support\Arr;

class WeiboProvider extends AbstractProvider implements ProviderInterface
{
    /**
     * Unique Provider Identifier.
     */
    public const IDENTIFIER = 'WEIBO';

    /**
     * The scopes being requested.
     *
     * @var array
     */
    protected $scopes = ['email'];

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://api.weibo.com/oauth2/authorize', $state);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return 'https://api.weibo.com/oauth2/access_token';
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $userUrl = 'https://api.weibo.com/2/users/show.json';

        $response = $this->getHttpClient()->get(
            $userUrl, $this->getRequestOptions($token)
        );

        return json_decode($this->removeCallback((string) $response->getBody()), true);
    }

    /**
     * @param mixed $response
     *
     * @return string
     */
    protected function removeCallback($response)
    {
        if (strpos($response, 'callback') !== false) {
            $lpos = strpos($response, '(');
            $rpos = strrpos($response, ')');
            $response = substr($response, $lpos + 1, $rpos - $lpos - 1);
        }

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id'     => $user['idstr'],
            'nickname' => $user['screen_name'],
            'avatar' => $user['avatar_large'],
            'name' => $user['name'],
            'email' => null,
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
            'query' => [
                'access_token' => $token,
                'uid'          => $this->getUid($token),
            ],
        ];
    }

    /**
     * @param $token
     *
     * @return string
     */
    protected function getUid($token)
    {
        $response = $this->getHttpClient()->get('https://api.weibo.com/2/account/get_uid.json', [
            'query' => ['access_token' => $token],
        ]);

        return json_decode((string) $response->getBody(), true)['uid'];
    }
}
