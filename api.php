<?php
include('./class/db.class.php');
$db = new db;
$key = "photo";
if( empty($_POST['status']) ){
	echo "出现未知错误！";
    exit;
}else if( $_POST['status'] == "checkLogin" ){
    $user_name = $_POST['user_name'];
    $user_password = md5( md5( $_POST['user_password'] ).$key );
    $list = $db->table('user')->where("user_name='$user_name' and user_password='$user_password'")->select();
    $list = count($list);
    $url='http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]; 
    if($list == 1){
        echo "<script>location.replace(".$url."/upfile/uploadfile.html".")</script>";
        echo "1";
    }else{
        echo "<script>location.replace(".$url."login.html?error".")</script>";
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
}else if( $_POST['status'] == "addAlbum" ){
	$arr['alb_name'] = $_POST['alb_name'];
	$arr['alb_pid'] = $_POST['alb_pid'];
	$arr['alb_create_time'] = time();
	$res = $db->table('album')->insert($arr);
	if($res){
		echo "增加成功！";
	}else{
		echo "增加失败！";
	}
}else if ( $_POST['status'] == "addUser" ){
	$arr['user_name'] = $_POST['user_name'];
	$arr['user_password'] = md5( md5( $_POST['user_password'] ).$key );
	$arr['user_power'] = $_POST['user_power'];
	$res = $db->table("user")->insert($arr);
	if($res){
		echo "增加成功！";
	}else{
		echo "增加失败！";
	}
}
