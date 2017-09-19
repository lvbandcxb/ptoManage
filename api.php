<?php
require_once('class/banner.php');
$banner = new Banner;
if( empty($_POST['status']) ){
	
}
if( $_POST['status'] == "checkLogin" ){
    $user_name = $_POST['user_name'];
    $user_password = md5(md5($_POST['user_password']).$key);
    $res = $banner->checkLoginStatus($user_name,$user_password);
    return $res;
}else if( $_POST['status'] == "insertPhoto" ){
	$arr['pto_name'] = $_POST['pto_name'];
	$arr['pto_album_pid'] = $_POST['pto_album_pid'];
	$arr['pto_url'] = $_POST['pto_url'];
	$arr['pto_create_time'] = time();
	$res = $banner->insertPhoto($arr);
	return $res;
}else{
    echo "出现未知错误！";
    exit;
}
