<?php

namespace App\Security;

class DataChecker
{
    public function __construct(private readonly string $appKey, private readonly string $method)
    {
    }

    public function encrypt(string $data): string {
        try {
            $iv_length = openssl_cipher_iv_length($this->method);
            $options = 0;
            $encryption_iv = '1234567891011121';
            $data = openssl_encrypt($data, $this->method,
                $this->appKey, $options, $encryption_iv);
        } catch (\Exception $exception) {
        }

        return $data;
    }

    public function decrypt(string $data): string {
        try {
            $iv_length = openssl_cipher_iv_length($this->method);
            $options = 0;
            $decryption_iv = '1234567891011121';
            $data = openssl_decrypt($data, $this->method,
                $this->appKey, $options, $decryption_iv);
        } catch (\Exception $exception) {
        }

        return $data;
    }

}