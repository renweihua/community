<?php

namespace App\Library\Encrypt;

/**
 * Class Rsa
 *
 * 非对称加密
 *
 * @package Encrypt
 */
class Rsa {
    /**
     * 获取私钥
     * @return bool|resource
     */
    private static function getPrivateKey()
    {
        $abs_path = dirname(__FILE__) . '/../../../../rsa-config/rsa_private_key.pem';
        $content = file_get_contents($abs_path);
        return openssl_pkey_get_private($content);
    }

    /**
     * 获取公钥
     * @return bool|resource
     */
    private static function getPublicKey()
    {
        $abs_path = dirname(__FILE__) . '/../../../../rsa-config/rsa_public_key.pem';
        $content = file_get_contents($abs_path);
        return openssl_pkey_get_public($content);
    }

    /**
     * 私钥加密
     * @param string $data
     * @return null|string
     */
    public static function privEncrypt(array $data)
    {
        $data = json_encode($data);
        if (!is_string($data)) {
            return null;
        }
        return openssl_private_encrypt($data,$encrypted,self::getPrivateKey()) ? base64_encode($encrypted) : null;
    }

    /**
     * 公钥加密
     * @param string $data
     * @return null|string
     */
    public static function publicEncrypt(array $data)
    {
        $data = json_encode($data);
        if (!is_string($data)) {
            return null;
        }
        return openssl_public_encrypt($data,$encrypted,self::getPublicKey()) ? base64_encode($encrypted) : null;
    }

    /**
     * 私钥解密
     * @param string $encrypted
     * @return null
     */
    public static function privDecrypt($encrypted = '')
    {
        if (!is_string($encrypted)) {
            return null;
        }
        return json_decode((openssl_private_decrypt(base64_decode($encrypted), $decrypted, self::getPrivateKey())) ? $decrypted : null);
    }

    /**
     * 公钥解密
     * @param string $encrypted
     * @return null
     */
    public static function publicDecrypt($encrypted = '')
    {
        if (!is_string($encrypted)) {
            return null;
        }
        return (openssl_public_decrypt(base64_decode($encrypted), $decrypted, self::getPublicKey())) ? $decrypted : null;
    }

    /**
     * 生成Rsa公钥和私钥
     * @param int $private_key_bits 建议：[512, 1024, 2048, 4096]
     * @return array
     */
    public function generate($private_key_bits = 1024)
    {
        $rsa = [
            "private_key" => "",
            "public_key" => ""
        ];

        $config = [
            "digest_alg" => "sha512",
            "private_key_bits" => $private_key_bits, #此处必须为int类型
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
            // "config" => "D:\phpEnv\php\php-7.3\extras\ssl\openssl.cnf" //如果没有配置这项，openssl_pkey_new会返回false
            "config" => "D:/phpEnv/php/php-7.3/extras/ssl/openssl.cnf" //如果没有配置这项，openssl_pkey_new会返回false
        ];

        //创建公钥和私钥
        $res = openssl_pkey_new($config);

        //提取私钥 由于上一步传入了$config参数，所以这一步也需要传入这个参数，
        openssl_pkey_export($res, $rsa['private_key'],null, $config);//

        //生成公钥
        $rsa['public_key'] = openssl_pkey_get_details($res)["key"];

        // print_r($rsa);die;
        return $rsa;
    }
}
//
//$rsa = new Rsa();
////var_dump($rsa->generate(512));
////exit;
//$data['name'] = 'Tom';
//$data['age']  = '20';
//$privEncrypt = $rsa->privEncrypt(json_encode($data));
//echo '私钥加密后:'.$privEncrypt.'<br>';
//
//$publicDecrypt = $rsa->publicDecrypt($privEncrypt);
//echo '公钥解密后:'.$publicDecrypt.'<br>';
//
//$publicEncrypt = $rsa->publicEncrypt(json_encode($data));
//echo '公钥加密后:'.$publicEncrypt.'<br>';
//
//$privDecrypt = $rsa->privDecrypt($publicEncrypt);
//echo '私钥解密后:'.$privDecrypt.'<br>';
