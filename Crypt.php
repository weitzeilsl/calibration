<!DOCTYPE html>
<?php
//File:   Crypt.php
//Author: Steven L. Weitzeil
//Date:   11 May 2013
//Desc:   Encrypts and decrypts strings
//Review:
//Example:
//   include 'Crypt.php';
//   $mix = new Crypt();
//   $encrypted = $mix->encrypt("Joseph Smith");
//   $decrypted = $mix->decrypt($encrypted);
gc_enable();
            
class Crypt {
    private $block;
    private $key = "021319570515182911241960";
    
    function __construct() {
        $this->block = mcrypt_get_block_size('des', 'ecb');
    }
        
    function encrypt($str)
    {
        $pad = $this->block - (strlen($str) % $this->block);
        $str .= str_repeat(chr($pad), $pad);

        return mcrypt_encrypt(MCRYPT_DES, $this->key, $str, MCRYPT_MODE_ECB);
    }

    function decrypt($str)
    {   
        $str = mcrypt_decrypt(MCRYPT_DES, $this->key, $str, MCRYPT_MODE_ECB);

        $pad = ord($str[($len = strlen($str)) - 1]);
        return substr($str, 0, strlen($str) - $pad);
    }
}

gc_disable();
?>
