<?php
require 'class.php';

echo "[>] Masukkan cookies anda : "; $cookies = trim(fgets(STDIN));

$class = new InstagramWeb();
$class->cookies = $cookies;
$class->useragent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.157 Safari/537.36';

echo "\n[!] Melakukan login, dengan menggunakan cookies.. ";
try {
    $class->doLogin();
    echo "[OK]\n";
} catch(Exception $e) {
    echo "[FAIL] ".$e->getMessage()."\n";
    exit(0);
}

echo "[=] Get profile data.. \n";
$data = $class->getProfile();

echo "Full Name : ".$data->config->viewer->full_name."\n";
echo "Username : ".$data->config->viewer->username."\n";
echo "Private : ".(($data->config->viewer->is_private === true ? "yes" : "no"))."\n\n";

echo "Selesai\n";