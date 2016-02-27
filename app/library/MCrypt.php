<?php

class MCrypt
{
    private $iv = '[.!tQ]'; #Same as in JAVA
    public $key = '{!sQ.}'; #Same as in JAVA

    private $iv_old = 'fedcba987654ashu'; #Same as in JAVA
    public $key_old = '0123456789abashu'; #Same as in JAVA

    public $timestamp;

    function __construct()
    {
    }

    function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    function encrypt($str)
    {
        $iv  = ($this->timestamp)?$this->iv.$this->timestamp:$this->iv_old;
        $key = ($this->timestamp)?$this->key.$this->timestamp:$this->key_old;
        $td = mcrypt_module_open('rijndael-128', '', 'cbc', $iv);
        mcrypt_generic_init($td, $this->key_optimize($key), $iv);
        $encrypted = mcrypt_generic($td, $str);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return bin2hex($encrypted);
    }

    function decrypt($code)
    {
        $code = $this->hex2bin($code);
        $iv  = ($this->timestamp)?$this->iv.$this->timestamp:$this->iv_old;
        $key = ($this->timestamp)?$this->key.$this->timestamp:$this->key_old;
        $td = mcrypt_module_open('rijndael-128', '', 'cbc', $iv);
        mcrypt_generic_init($td, $this->key_optimize($key), $iv);
        $decrypted = mdecrypt_generic($td, $code);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return utf8_encode(trim($decrypted));
    }

    protected function hex2bin($hexdata) {
        $bindata = '';
        for ($i = 0; $i < strlen($hexdata); $i += 2) {
            $bindata .= chr(hexdec(substr($hexdata, $i, 2)));
        }
        return $bindata;
    }

    public function key_optimize($key, $lenght = 16)
    {
        $keyLenght = strlen($key);
        if($keyLenght > $lenght)
            $newKey = substr($key, 0, $lenght);
        elseif($keyLenght < $lenght)
            $newKey = str_pad($key, $lenght, "F");
        else
            $newKey = $key;
       return $newKey;
    }
}