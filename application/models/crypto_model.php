<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @property CI_DB_active_record $db
 * @property CI_Security $security
 */
class Crypto_model extends CI_Model
{

    private $key = "123456789012345678901234";
    private $iv = "12345678";
    private $cipher;
    private $block;

    public function __construct()
    {
        parent::__construct();
        $this->cipher = mcrypt_module_open(MCRYPT_3DES, '', 'cbc', '');
        $this->block = mcrypt_get_block_size('tripledes', 'cbc');
    }

    public function encrypt_dotnet($value)
    {
        try
        {
            //PADDING THE TEXT.
            $len = strlen($value);
            $padding = ceil(($len + 1) / $this->block) * $this->block - ($len);
            $value .= str_repeat(chr($padding), $padding);

            mcrypt_generic_init($this->cipher, $this->key, $this->iv);
            $encrypted = mcrypt_generic($this->cipher, $value);

            return base64_encode($encrypted);
        } catch (Exception $e)
        {
            throw $e;
        }
    }

    public function decrypt_dotnet($value)
    {
        try
        {
            $value = base64_decode($value);
            mcrypt_generic_init($this->cipher, $this->key, $this->iv);
            $decrypted = mdecrypt_generic($this->cipher, $value);

            return $decrypted;
        } catch (Exception $e)
        {
            throw $e;
        }
    }

}
