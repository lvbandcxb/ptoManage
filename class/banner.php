<?php
include('db.class.php');
$db = new db;
$key = "photo";

class Banner{
    public function checkLoginStatus($user_name,$user_password){
        $list = $db->table('user')->where("user_name=$username&&user_password=$user_password")->select();
        $list = count($list);
        if($list == 1){
            return true;
        }else{
            return false;
        }
    }
    public function insertPhoto($arr){
    	$res = $db->table('photo')->insert($arr);
    	return $res;
    }
}
