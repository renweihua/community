<?php

namespace App\Library\Encrypt;

/**
 * Class Aes
 *
 * 对称加密
 *
 * @package Encrypt
 */
class Aes
{
    /**
     * var string $method 加解密方法，可通过openssl_get_cipher_methods()获得
     */
    protected $method;

    /**
     * var string $secret_key 加解密的密钥
     */
    protected $secret_key;

    /**
     * var string $iv 加解密的向量，有些方法需要设置比如CBC
     */
    protected $iv;

    /**
     * var string $options （不知道怎么解释，目前设置为0没什么问题）
     */
    protected $options;

    /**
     * 构造函数
     *
     * @param string $key 密钥
     * @param string $method 加密方式
     * @param string $iv iv向量
     * @param mixed $options 还不是很清楚
     *
     */
    public function __construct(string $key = '☺☹', string $method = 'AES-128-ECB', string $iv = '', int $options = 0)
    {
        // key是必须要设置的
        $this->secret_key = $key;

        $this->method = $method;

        $this->iv = $iv;

        $this->options = $options;
    }

    /**
     * 加密方法，对数据进行加密，返回加密后的数据
     *
     * @param string $data 要加密的数据
     *
     * @return string
     *
     */
    public function encrypt(array $data):string
    {
        return openssl_encrypt(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), $this->method, $this->secret_key, $this->options, $this->iv);
    }

    /**
     * 解密方法，对数据进行解密，返回解密后的数据
     *
     * @param string $data 要解密的数据
     *
     * @return string
     *
     */
    public function decrypt(string $data)
    {
        return json_decode(openssl_decrypt($data, $this->method, $this->secret_key, $this->options, $this->iv), true);
    }
}

$ace_class = new Aes;

$string = $ace_class->encrypt(['user_id' => 1, 'nick_name' => '小丑路人']);
var_dump($string);

var_dump($ace_class->decrypt($string));
