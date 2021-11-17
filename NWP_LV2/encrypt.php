<?php
$cipher = "AES-128-CTR";
$iv_length = openssl_cipher_iv_length($cipher);
$encryption_key = md5("neki extremno veliki ali sigurni kljuc valjda");
$options = 0;

$EIV_file_name = "./EIV.txt";
if(!is_file($EIV_file_name)){//osigurati isti IV među sesijama, nadam se
    $encryption_iv = random_bytes($iv_length);
    file_put_contents($EIV_file_name, $encryption_iv);
} else {
    $encryption_iv = file_get_contents($EIV_file_name);
}