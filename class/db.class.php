<?php
header("Content-Type:text/html;charset=utf-8");
 
/**
 *php操作mysql的工具类
 */
class db{
    private $_db = null;//数据库连接句柄
    private $_table = null;//表名
    private $_where = null;//where条件
    private $_order = null;//order排序
    private $_limit = null;//limit限定查询
    private $_group = null;//group分组
    private $_configs = array(
                'hostname' => '127.0.0.1',
                'dbname'   => 'uptotx',
                'username' => 'root',
                'password' => ''
            );//数据库配置
 
    /**
     * 构造函数，连接数据库
     */
    public function __construct(){
        $link = $this->_db;
        if(!$link){
            $db = mysqli_connect($this->_configs['hostname'],$this->_configs['username'],$this->_configs['password'],$this->_configs['dbname']);
            mysqli_query($db,"set names utf8");
            if(!$db){
                $this->ShowException("错误信息".mysqli_connect_error());
            }
            $this->_db = $db;
        }
    }
 
    /**
     * 获取所有数据
     *
     * @param      <type>   $table  The table
     *
     * @return     boolean  All.
     */
    public function getAll($table=null){
        $link = $this->_db;
        if(!$link)return false;
        $sql = "SELECT * FROM {$table}";
        $result = $this->execute($sql);
        $data = mysqli_fetch_all($this->execute($sql),MYSQLI_ASSOC); 
        //$data = mysqli_fetch_all($this->execute($sql));
        return $data;
    }
 
    public function table($table){
        $this->_table = $table;
        return $this;
    }
 
    /**
     * 实现查询操作
     *
     * @param      string   $fields  The fields
     *
     * @return     boolean  ( description_of_the_return_value )
     */
    public function select($fields="*"){
        $fieldsStr = '';
        $link = $this->_db;
        if(!$link)return false;
        if(is_array($fields)){
            $fieldsStr = implode(',', $fields);
        }elseif(is_string($fields)&&!empty($fields)){
            $fieldsStr = $fields;
        }
        $sql = "SELECT {$fields} FROM {$this->_table} {$this->_where} {$this->_order} {$this->_limit}";

        //$data = mysqli_fetch_all($this->execute($sql));
        $data = null;
        $re = $this->execute($sql);
        while ($row = mysqli_fetch_assoc($re)) {
            $data[] = $row;
        }
        //$data = mysqli_fetch_all($this->execute($sql),MYSQLI_ASSOC); 
        
        return $data;
    }


    public function count(){
        $link = $this->_db;
        if(!$link)return false;
        
        $sql = "SELECT count(*) as n FROM {$this->_table} {$this->_where}";

        $data = mysqli_fetch_assoc($this->execute($sql));
        
        return $data['n'];
    }
 
    /**
     * order排序
     *
     * @param      string   $order  The order
     *
     * @return     boolean  ( description_of_the_return_value )
     */
    public function order($order=''){
        $orderStr = '';
        $link = $this->_db;
        if(!$link)return false;
        if(is_string($order)&&!empty($order)){
            $orderStr = "ORDER BY ".$order;
        }
        $this->_order = $orderStr;
        return $this;
    }
 
    /**
     * where条件
     *
     * @param      string  $where  The where
     *
     * @return     <type>  ( description_of_the_return_value )
     */
    public function where($where=''){
        $whereStr = '';
        $link = $this->_db;
        if(!$link)return $link;
        if(is_array($where)){
            foreach ($where as $key => $value) {
                if($value == end($where)){
                    $whereStr .= "`".$key."` = '".$value."'";
                }else{
                    $whereStr .= "`".$key."` = '".$value."' AND ";
                }
            }
            $whereStr = "WHERE ".$whereStr;
        }elseif(is_string($where)&&!empty($where)){
            $whereStr = "WHERE ".$where;
        }
        $this->_where = $whereStr;
        return $this;
    }
 
    /**
     * group分组
     *
     * @param      string   $group  The group
     *
     * @return     boolean  ( description_of_the_return_value )
     */
    public function group($group=''){
        $groupStr = '';
        $link = $this->_db;
        if(!$link)return false;
        if(is_array($group)){
            $groupStr = "GROUP BY ".implode(',',$group);
        }elseif(is_string($group)&&!empty($group)){
            $groupStr = "GROUP BY ".$group;
        }
        $this->_group = $groupStr;
        return $this;
    }
 
    /**
     * limit限定查询
     *
     * @param      string  $limit  The limit
     *
     * @return     <type>  ( description_of_the_return_value )
     */
    public function limit($limit=''){
        $limitStr = '';
        $link = $this->_db;
        if(!$link)return $link;
        if(is_string($limit)||!empty($limit)){
            $limitStr = "LIMIT ".$limit;
        }elseif(is_numeric($limit)){
            $limitStr = "LIMIT ".$limit;
        }
        $this->_limit = $limitStr;
        return $this;
    }
 
    /**
     * 执行sql语句
     *
     * @param      <type>   $sql    The sql
     *
     * @return     boolean  ( description_of_the_return_value )
     */
    public function execute($sql=null){
        $link = $this->_db;
        if(!$link)return false;
        $res = mysqli_query($this->_db,$sql);
        if(!$res){
            echo '此SQL有毒!<hr>'.$sql.'<hr>';
            echo mysqli_error($this->_db);
            /*
            $errors = mysqli_error($this->_db);
            $this->ShowException("报错啦！<br/>错误号：".$errors[0]['errno']."<br/>SQL错误状态：".$errors[0]['sqlstate']."<br/>错误信息：".$errors[0]['error']);
            */
            //exit;
            die();
        }
        $this->_table = null;
        $this->_where = null;
        $this->_order = null;
        $this->_limit = null;
        $this->_group = null;

        return $res;
    }
 
    /**
     * 插入数据
     *
     * @param      <type>   $data   The data
     *
     * @return     boolean  ( description_of_the_return_value )
     */
    public function insert($data){
        $link = $this->_db;
        if(!$link)return false;
        if(is_array($data)){
            $keys = '';
            $values = '';
            foreach ($data as $key => $value) {
                $keys .= "`".$key."`,";
                $values .= "'".$value."',";
            }
            $keys = rtrim($keys,',');
            $values = rtrim($values,',');
        }
        $sql = "INSERT INTO `{$this->_table}`({$keys}) VALUES({$values})";
        mysqli_query($this->_db,$sql);
        $insertId = mysqli_insert_id($this->_db);
        return $insertId;
    }
 
    /**
     * 更新数据
     *
     * @param      <type>  $data   The data
     *
     * @return     <type>  ( description_of_the_return_value )
     */
    public function update($data){
        $link = $this->_db;
        if(!$link)return $link;
        if(is_array($data)){
            $dataStr = '';
            foreach ($data as $key => $value) {
                $dataStr .= "`".$key."`='".$value."',";
            }
            $dataStr = rtrim($dataStr,',');
        }
        $sql = "UPDATE `{$this->_table}` SET {$dataStr} {$this->_where} {$this->_order} {$this->_limit}";
        //echo $sql;
        $res = $this->execute($sql);
        return $res;
    }
 
    /**
     * 删除数据
     *
     * @return     <type>  ( description_of_the_return_value )
     */
    public function delete(){
        $link = $this->_db;
        if(!$link)return $link;
        $sql = "DELETE FROM `{$this->_table}` {$this->_where}";
        $res = $this->execute($sql);
        return $res;
    }
 
    /**
     * 异常信息输出
     *
     * @param      <type>  $var    The variable
     */
    private function ShowException($var){
        if(is_bool($var)){
            var_dump($var);
        }else if(is_null($var)){
            var_dump(NULL);
        }else{
            echo "<pre style='position:relative;z-index:1000;padding:10px;border-radius:5px;background:#F5F5F5;border:1px solid #aaa;font-size:14px;line-height:18px;opacity:0.9;'>".print_r($var,true)."</pre>";
        }
    }
 
}

/*
$db = new db();
//查询操作
var_dump($db->table('article')->where('id > 2')->order('id desc')->limit('2,4')->select());
 
//插入操作
var_dump($db->table('user')->insert(array('username'=>'user','password'=>'pwd')));
 
//更新操作
var_dump($db->table('user')->where('id = 1')->update(array('username'=>'user1','password'=>'pwd1')));
 
//删除操作
var_dump($db->table('user')->where('id = 1')->delete());
*/