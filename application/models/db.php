<?php
use Yaf\Application;
class dbModel{
    public $yafpdo;
    public $dbconfig;
    public $sqlstr;
    //构造函数
    public function __construct($db_config = array()) {
        if(empty($db_config)){
            $db_config = Application::app()->getConfig()->database;
            $this->dbconfig = $db_config;
        }
        $this->yafpdo = $this->connect($db_config["driver"],$db_config["hostname"], $db_config["username"], $db_config["password"], $db_config["database"],$db_config["port"],$db_config["charset"]);
    }
    //数据库连接
    public function connect($driver, $dbhost, $dbuser, $dbpw, $dbname, $port, $charset) {
        $dsn = $driver.":dbname=".$dbname.";host=".$dbhost.";port=".$port.";charset=".$charset;
        try {
            $this->yafpdo = new \PDO($dsn, $dbuser, $dbpw);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
        return $this->yafpdo;
    }
    //数据库删除
    public function  deleteSql($tbname = null,$where=null){
        $sql = "DELETE FROM ".$this->dbconfig["prefix"].$tbname." WHERE {$where}";
        return  $sql;
    }
    //数据库增加
    public function insertSql($tbname = null,array $data=[]){
        $strkey = "";
        $strval = "";
        foreach ($data as $key=>$value){
            $strkey .= "`$key`,";
            if(is_int($value)){
                $strval .= "$value,";
            }else if(is_null($value)){
                $strval .= "null,";
            }else{
                $strval .= "'$value',";
            }
        }
        $sql = "INSERT INTO ".$this->dbconfig["prefix"].$tbname." (".substr($strkey,0,-1)." ) VALUES (".substr($strval,0,-1).")";
        return  $sql;
    }
    //数据库修改
    public function updateSql($tbname=null,array $data=[],$where=null){
        $strData = "";
        foreach ($data as $key=>$value){
            if(is_int($value)){
                $strData .= $key."=".$value.",";
            }else{
                $strData .= $key."='".$value."',";
            }
        }
        $sql = "UPDATE ".$this->dbconfig["prefix"].$tbname." SET ".substr($strData,0,-1)." WHERE {$where} ";
        return  $sql;
    }
    //执行SQL语句
    //$ftype = 2：返回一个索引为结果集列名的数组
    //$ftype = 4：返回一个索引为结果集列名和以0开始的列号的数组
    //$ftype = 6：返回 TRUE ，并分配结果集中的列值给 PDOStatement::bindColumn() 方法绑定的 PHP 变量。
    //$ftype = 8：返回一个请求类的新实例，映射结果集中的列名到类中对应的属性名。如果 fetch_style 包含 PDO::FETCH_CLASSTYPE（例如：PDO::FETCH_CLASS |PDO::FETCH_CLASSTYPE），则类名由第一列的值决定
    //$ftype = 9：更新一个被请求类已存在的实例，映射结果集中的列到类中命名的属性
    //$ftype = 1：结合使用 PDO::FETCH_BOTH 和 PDO::FETCH_OBJ，创建供用来访问的对象变量名
    //$ftype = 3：返回一个索引为以0开始的结果集列号的数组
    //$ftype = 5：返回一个属性名对应结果集列名的匿名对象
    public function  action($sql,$ftype = 2){
        if(stripos($sql,"SELECT") !==  false){
            try {
                $result = $this->yafpdo->query($sql);
                $data = $result->fetchAll($ftype);
                return $data;
            } catch (PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
            }
        }else{
            $bool = $this->yafpdo->exec($sql);
            return $bool;
        }
    }
    public function field(string $field = "*"){
        $this->sqlstr .= " SELECT {$field} ";
        return $this;
    }
    public function table($table=null){
        $this->sqlstr .= " FROM {$table} ";
        return $this;
    }
    public function where($where=null){
        $this->sqlstr .= " WHERE {$where} ";
        return $this;
    }
    public function limit($start=0,$len=5){
        $this->sqlstr .= " LIMIT {$start},{$len} ";
        return $this;
    }
    public function order($order = "id desc"){
        $this->sqlstr .= " ORDER BY {$order} ";
        return $this;
    }
    public function like($like=null){
        $this->sqlstr .= " LIKE '%{$like}%' ";
        return $this;
    }
    public function join($table=null,$join=null){
        $exp = "/[\da-zA-Z]+_/";
        preg_match($exp,$table,$data);
        if(!empty($data)){
            $this->sqlstr .= " INNER JOIN ".$table." ON {$join} ";
        }else{
            $this->sqlstr .= " INNER JOIN ".$this->dbconfig["prefix"].$table." ON {$join} ";
        }
        return $this;
    }
    public function  group($group=null){
        $this->sqlstr .= " GROUP BY {$group} ";
        return $this;
    }
    public function regexp($regexp=null){
        $this->sqlstr .= " REGEXP '{$regexp}'";
        return $this;
    }
    public function select(){
        $data = $this->action($this->sqlstr);
        $this->sqlstr = "";
        return $data;
    }
    public function find(){
        $result = $this->yafpdo->query($this->sqlstr);
        $data = $result->fetch(2);
        $this->sqlstr = "";
        return $data;
    }
}