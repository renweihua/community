<?php

namespace App\Services;

use App\Library\Encrypt\Aes;
use App\Library\Encrypt\Rsa;
use App\Traits\Instance;

class UserAuthEncryptionService
{
    use Instance;

    protected $encryption_method;
    protected $rsa;
    protected $aes;

    public function __construct($encryption_method = 0)
    {
        $this->encryption_method = $encryption_method;
    }

    public function encryption($data): string
    {
        switch ($this->encryption_method){
            case 1: // rsa
                $result = $this->getRsaClass()->publicEncrypt($data);
                break;
            default: // aes
                $result = $this->getAesClass()->encrypt($data);
                break;
        }
        return $result;
    }

    public function decrypt(string $string, bool $assoc = false)
    {
        switch ($this->encryption_method){
            case 1: // rsa
                $result = $this->getRsaClass()->privDecrypt($string, $assoc);
                break;
            default: // aes
                $result = $this->getAesClass()->decrypt($string, $assoc);
                break;
        }
        return $result;
    }

    public function getRsaClass()
    {
        if (!$this->rsa){
            $this->rsa = new Rsa;
        }
        return $this->rsa;
    }

    public function getAesClass()
    {
        if (!$this->aes){
            $this->aes = new Aes('☺cnpscy☹');
        }
        return $this->aes;
    }
}
