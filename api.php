<?php
include('./class/db.class.php');
$db = new db;
$key = "photo";
if( empty($_POST['status']) ){
	echo "出现未知错误！";
    exit;
}else if( $_POST['status'] == "checkLogin" ){
    $user_name = $_POST['user_name'];
    $user_password = md5( md5('123456').'photo' );
    $list = $db->table('user')->where("user_name='$user_name' and user_password='$user_password'")->select();
    $list = count($list);
    if($list == 1){
        echo "登录成功";
    }else{
        echo "登录失败";
    }
}else if( $_POST['status'] == "insertPhoto" ){
	$arr['pto_name'] = $_POST['pto_name'];
	$arr['pto_album_pid'] = floatval( $_POST['pto_album_pid'] );
	$arr['pto_url'] = $_POST['pto_url'];
	$arr['pto_create_time'] = time();
	$res = $db->table('photo')->insert($arr);
	if($res){
		echo "增加成功！";
	}else{
		echo "增加失败！";
	}
}
