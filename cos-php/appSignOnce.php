<?php
require('./include.php'); 
use Qcloud\Cos\Api;
use Qcloud\Cos\Auth;

$appid = "1254355088";
$secretid = "AKIDMi3KSg1mdMAY4XS0Xv7FF4wf70lSCqW8";
$secretkey = "AMSN8FM7rxtieYHGBz18PEzb8Zq0ukPJ";

$config = array(
    'app_id' => $appid,
    'secret_id' => $secretid,
    'secret_key' => $secretkey,
    'region' => 'sh',
    'timeout' => 60
);

$cosApi = new Api($config);

$auth = new Auth($appId = $appid, $secretId = $secretid , $secretKey = $secretkey);  
$bucket = "txyun";
$filepath = "/";
$sign = $auth->createNonreusableSignature($bucket, $filepath);

echo $sign;