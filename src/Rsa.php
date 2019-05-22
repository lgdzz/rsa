<?php
/**
 * Created by rsa.
 * User: lgdz
 * Date: 2019/4/15
 */

namespace lgdz;

class Rsa
{
    protected $private_key = '';
    protected $public_key = '';

    public function setPrivateKey(string $private_key)
    {
        $this->private_key = $private_key;
        return $this;
    }

    public function setPublicKey(string $public_key)
    {
        $this->public_key = $public_key;
        return $this;
    }

    public function privateEncrypt(string $decrypted)
    {
        return $this->_usePrivateKey(function ($private_id) use ($decrypted) {
            $encrypted = '';
            foreach (str_split($decrypted, 117) as $chunk) {
                openssl_private_encrypt($chunk, $temp, $private_id);
                $encrypted .= $temp;
            }
            return $this->urlsafeB64encode($encrypted);
        });
    }

    public function privateDecrypt(string $encrypted)
    {
        return $this->_usePrivateKey(function ($private_id) use ($encrypted) {
            $decrypted = '';
            foreach (str_split($this->urlsafeB64decode($encrypted), 128) as $chunk) {
                openssl_private_decrypt($chunk, $temp, $private_id);
                $decrypted .= $temp;
            }
            return $decrypted;
        });
    }

    public function publicEncrypt(string $decrypted)
    {
        return $this->_usePublicKey(function ($public_id) use ($decrypted) {
            $encrypted = '';
            foreach (str_split($decrypted, 117) as $chunk) {
                openssl_public_encrypt($chunk, $temp, $public_id);
                $encrypted .= $temp;
            }
            return $this->urlsafeB64encode($encrypted);
        });
    }

    public function publicDecrypt(string $encrypted)
    {
        return $this->_usePublicKey(function ($public_id) use ($encrypted) {
            $decrypted = '';
            foreach (str_split($this->urlsafeB64decode($encrypted), 128) as $chunk) {
                openssl_public_decrypt($chunk, $temp, $public_id);
                $decrypted .= $temp;
            }
            return $decrypted;
        });
    }

    private function _usePrivateKey($func)
    {
        $private_id = openssl_pkey_get_private($this->private_key);
        if (false === $private_id) {
            return false;
        } else {
            return $func($private_id);
        }
    }

    private function _usePublicKey($func)
    {
        $public_id = openssl_pkey_get_public($this->public_key);
        if (false === $public_id) {
            return false;
        } else {
            return $func($public_id);
        }
    }

    protected function urlsafeB64encode($string)
    {
        $data = base64_encode($string);
        $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
        return $data;
    }


    protected function urlsafeB64decode($string)
    {
        $data = str_replace(array('-', '_'), array('+', '/'), $string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }
}