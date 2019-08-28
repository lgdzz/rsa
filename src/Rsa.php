<?php
/**
 * Created by rsa.
 * User: lgdz
 * Date: 2019/4/15
 */

namespace lgdz;

class Rsa
{
    protected $privateKey = '';
    protected $publicKey = '';

    public function setPrivateKey(string $privateKey)
    {
        $this->privateKey = $privateKey;
        return $this;
    }

    public function setPublicKey(string $publicKey)
    {
        $this->publicKey = $publicKey;
        return $this;
    }

    public function privateEncrypt(string $decrypted)
    {
        return $this->_usePrivateKey(function ($privateId) use ($decrypted) {
            $encrypted = '';
            foreach (str_split($decrypted, 117) as $chunk) {
                openssl_private_encrypt($chunk, $temp, $privateId);
                $encrypted .= $temp;
            }
            return $this->urlsafeB64encode($encrypted);
        });
    }

    public function privateDecrypt(string $encrypted)
    {
        return $this->_usePrivateKey(function ($privateId) use ($encrypted) {
            $decrypted = '';
            foreach (str_split($this->urlsafeB64decode($encrypted), 128) as $chunk) {
                openssl_private_decrypt($chunk, $temp, $privateId);
                $decrypted .= $temp;
            }
            return $decrypted;
        });
    }

    public function publicEncrypt(string $decrypted)
    {
        return $this->_usePublicKey(function ($publicId) use ($decrypted) {
            $encrypted = '';
            foreach (str_split($decrypted, 117) as $chunk) {
                openssl_public_encrypt($chunk, $temp, $publicId);
                $encrypted .= $temp;
            }
            return $this->urlsafeB64encode($encrypted);
        });
    }

    public function publicDecrypt(string $encrypted)
    {
        return $this->_usePublicKey(function ($publicId) use ($encrypted) {
            $decrypted = '';
            foreach (str_split($this->urlsafeB64decode($encrypted), 128) as $chunk) {
                openssl_public_decrypt($chunk, $temp, $publicId);
                $decrypted .= $temp;
            }
            return $decrypted;
        });
    }

    private function _usePrivateKey($func)
    {
        $privateId = openssl_pkey_get_private($this->privateKey);
        if (false === $privateId) {
            return false;
        } else {
            return $func($privateId);
        }
    }

    private function _usePublicKey($func)
    {
        $publicId = openssl_pkey_get_public($this->publicKey);
        if (false === $publicId) {
            return false;
        } else {
            return $func($publicId);
        }
    }

    public function urlsafeB64encode($string)
    {
        $data = base64_encode($string);
        $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
        return $data;
    }


    public function urlsafeB64decode($string)
    {
        $data = str_replace(array('-', '_'), array('+', '/'), $string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }
}