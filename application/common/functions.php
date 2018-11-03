<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/20
 * Time: 13:21
 */
function alertText($data,$url) {
    echo "<script>
    var divNode = document.createElement('div');
    divNode.setAttribute('id','msg');
    divNode.style.position = 'fixed';
    divNode.style.top = '50%';
    divNode.style.width = '400px';
    divNode.style.left = '50%';
    divNode.style.marginLeft = '-220px';
    divNode.style.height = '30px';
    divNode.style.lineHeight = '30px';
    divNode.style.marginTop = '-35px';
    var pNode = document.createElement('p');
    pNode.style.background = 'rgba(0,0,0,0.6)';
    pNode.style.width = '300px';
    pNode.style.color = '#fff';
    pNode.style.textAlign = 'center';
    pNode.style.padding = '20px';
    pNode.style.margin = '0 auto';
    pNode.style.fontSize = '16px';
    pNode.style.borderRadius = '4px';
    pNode.innerText = '".$data."';
    divNode.appendChild(pNode);
    var htmlNode = document.documentElement;
    htmlNode.style.background = 'rgba(0,0,0,0)';
    htmlNode.appendChild(divNode);
    var t = setTimeout(next,2000);
    function next(){
        htmlNode.removeChild(divNode);
        window.location.href='".$url."';
    }
    </script>";
}
function success($msg,$url){
    echo "<script>alert('".$msg."');window.location.href='".$url."';</script>";
}
function error($msg){
    echo "<script>alert('".$msg."');window.history.back();</script>";
}
function statusUrl($bool,string $success_msg, string $success_url,string $error_msg){
    if($bool){
        success($success_msg,$success_url);
    }else{
        error($error_msg);
    }
}
function server($data = null){
    if(is_null($data)){
        return $_SERVER;
    }else{
        $key = strtoupper($data);
        return $_SERVER[$key];
    }
}
function request($data = null){
    if(is_null($data)){
        return $_REQUEST;
    }else{
        return $_REQUEST[$data];
    }
}
function post($data = null){
    if(is_null($data)){
        return $_POST;
    }else{
        return $_POST[$data];
    }
}
function get($data = null){
    if(is_null($data)){
        return $_GET;
    }else{
        return $_GET[$data];
    }
}
function files($data = null){
    if(is_null($data)){
        return $_FILES;
    }else{
        return $_FILES[$data];
    }
}

function load_view($filename=null){
    include_once APP_PATH."/application/views/{$filename}.phtml";
}

function p($data){
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}

function dump($data){
    switch (true){
        case is_string($data) || is_int($data) || is_float($data): echo $data ; break; exit;
        case is_array($data) || is_object($data) : echo "<pre>";print_r($data);echo "</pre>"; break;exit;
        case is_bool($data) || is_null($data) : var_dump($data) ; break;exit;
        default: var_dump($data) ;break;exit;
    }
    exit;
}

//写一个数据类型的检测
function dataType($data){
    if(is_string($data)){
        echo "这是字符串";
    }else if(is_int($data)){
        echo "这是整型";
    }else if(is_object($data)){
        echo "这是对象";
    }else if(is_float($data)){
        echo "这是浮点类型";
    }else if(is_bool($data)){
        echo "这是布尔类型";
    }else if(is_null($data)){
        echo "这是NULL";
    }else if(is_array($data)){
        echo "这是数组";
    }else{
        echo "这是资源类型";
    }
    exit;
}

//删除文件
function file_delete($filename=null,$mktime=null){
    if(file_exists($filename)){
        $t1 = fileatime($filename);//获取上一次访问时间
        $t2 = time(); //获取本次访问时间
        $t3 = $t2-$t1;//时间差
        $t4 = $mktime;// 过期时间秒
        if($t3 >= $t4){//过期
            unlink($filename); //删除文件
        }
    }else{
        die($filename." not file code：404");
    }
}

//强化readfile函数安全
function Exreadfile($fileName = null,$tags=true){
    if($tags){
        ob_start();//打开输出缓冲
        readfile($fileName);  //写数据到输出缓冲
        $strData = ob_get_flush();//提前输出缓冲数据和关闭
        ob_clean();//清空输出缓冲里面的内容
        return htmlspecialchars($strData);
    }else{
        ob_start();//打开输出缓冲
        readfile($fileName);  //写数据到输出缓冲
        $strData = ob_get_flush();//提前输出缓冲数据和关闭
        ob_clean();//清空输出缓冲里面的内容
        return $strData;
    }
}

//点击率
function file_addclick($fileName = null){
    $L = filesize($fileName)+1;
    $fileRes1 = fopen($fileName,"r");
    $str = fread($fileRes1,$L);
    $str+=1;
    $fileRes2 = fopen($fileName,"w+");
    fwrite($fileRes2,$str);
    rewind($fileRes2);
    return fread($fileRes2,$L);
}

//PHP生成日历
function datetime(){
    $y = isset($_GET['y'])?$_GET['y']:date("Y"); //当前年
    $m = isset($_GET['m'])?$_GET['m']:date("m"); //当前月
    $d = isset($_GET['d'])?$_GET['d']:date("d"); //当前日
    $days = date("t",mktime(0,0,0,$m,$d,$y));//获取当月的天数
    $statweek = date("w",mktime(0,0,0,$m,1,$y));//获取当月的第一天是星期几
    $str = "";
    $str .="<table border='1' align='center'>";
    $str .="<caption>当前为{$y}年{$m}月</caption>";
    $str .="<tr><th>星期天</th><th>星期一</th><th>星期二</th><th>星期三</th><th>星期四</th><th>星期五</th><th>星期六</th></tr>";
    $str .="<tr>";
    for($i=0;$i<$statweek;$i++){
        $str .="<td>&nbsp;</td>";
    }
    for($j=1;$j<=$days;$j++){
        $i++;
        if($j == $d){
            $str .="<td bgcolor='cyan'>{$j}</td>";
        }else{
            $str .="<td>{$j}</td>";
        }
        if($i % 7 == 0){
            $str .="</tr><tr>";
        }
    }
    while($i % 7 !== 0){
        $str .="<td>&nbsp;</td>";
        $i++;
    }
    $str .="</tr>";
    $str .="</table>";
    return $str;
}

//转静态化
function static_page($url,$descname){
    set_time_limit(0);
    //实现HTML静态化
    $data = base64_encode(file_get_contents($url));
    file_put_contents($descname,$data);  //W+
    $strData = base64_decode(file_get_contents($descname));
    return $strData;
}

//文件下载
function download($file){
    $mime = mime_content_type($file);
    $size = filesize($file);
    // 下载文件mime类型
    header('Content-type: '.$mime);
    // 下载文件保存
    header("Content-Disposition: attachment; filename=".$file);
    //下载文件大小显示
    header("Content-Length:".$size);
    //读取下载文件
    readfile($file);
}

function StrX_shuffle($str=null){
    $a1 = range("a","z");
    shuffle($a1);
    $a2 = range("a","z");
    shuffle($a2);
    $a3 = range("a","z");
    shuffle($a3);
    $a4 = range("a","z");
    shuffle($a4);
    $a5 = range("a","z");
    shuffle($a5);
    $a6 = range("a","z");
    shuffle($a6);
    $strData = $str.$a1[0].$a2[0].$a3[0].$a4[0].$a5[0].$a6[0];
    return $strData;
}

//随机字符串
function Mer_shuffle($string,$maxlen = 20){
    $int_arr = range(0,9);
    $str_arr = range("a","z");
    $str1 = mb_splitchar($string);
    $new_arr = array_merge($int_arr,$str_arr);
    shuffle($new_arr);
    $strData = $str1.date("YmdHi",time()).implode($new_arr);
    $new_str = substr($strData,0,$maxlen);
    //file_put_contents("./c.html",$new_str);
    return $new_str;
}
//订单生成
function build_order_no(){
    return date('Ymd').substr(implode(array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
}
//获取单个汉字拼音首字母。注意:此处不要纠结。汉字拼音是没有以U和V开头的
function getfirstchar($s0){
    $fchar = ord($s0{0});
    if($fchar >= ord("A") and $fchar <= ord("z") )return strtoupper($s0{0});
    $s1 = iconv("UTF-8","gb2312", $s0);
    $s2 = iconv("gb2312","UTF-8", $s1);
    if($s2 == $s0){$s = $s1;}else{$s = $s0;}
    $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
    if($asc >= -20319 and $asc <= -20284) return "A";
    if($asc >= -20283 and $asc <= -19776) return "B";
    if($asc >= -19775 and $asc <= -19219) return "C";
    if($asc >= -19218 and $asc <= -18711) return "D";
    if($asc >= -18710 and $asc <= -18527) return "E";
    if($asc >= -18526 and $asc <= -18240) return "F";
    if($asc >= -18239 and $asc <= -17923) return "G";
    if($asc >= -17922 and $asc <= -17418) return "H";
    if($asc >= -17922 and $asc <= -17418) return "I";
    if($asc >= -17417 and $asc <= -16475) return "J";
    if($asc >= -16474 and $asc <= -16213) return "K";
    if($asc >= -16212 and $asc <= -15641) return "L";
    if($asc >= -15640 and $asc <= -15166) return "M";
    if($asc >= -15165 and $asc <= -14923) return "N";
    if($asc >= -14922 and $asc <= -14915) return "O";
    if($asc >= -14914 and $asc <= -14631) return "P";
    if($asc >= -14630 and $asc <= -14150) return "Q";
    if($asc >= -14149 and $asc <= -14091) return "R";
    if($asc >= -14090 and $asc <= -13319) return "S";
    if($asc >= -13318 and $asc <= -12839) return "T";
    if($asc >= -12838 and $asc <= -12557) return "W";
    if($asc >= -12556 and $asc <= -11848) return "X";
    if($asc >= -11847 and $asc <= -11056) return "Y";
    if($asc >= -11055 and $asc <= -10247) return "Z";
    return NULL;
}
//获取整条字符串汉字拼音首字母
function mb_splitchar($str){
    $strX = "";
    for($i=0;$i<mb_strlen($str);$i++){
        $strData = mb_substr($str,$i,1);
        if(ord($strData) > 160){
            $strX .= getfirstchar($strData);
        }else{
            $strX .= $strData;
        }
    }
    return $strX;
}

//获取ip
function getIp() {

    $arr_ip_header = array(
        'HTTP_CDN_SRC_IP',
        'HTTP_PROXY_CLIENT_IP',
        'HTTP_WL_PROXY_CLIENT_IP',
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'REMOTE_ADDR',
    );
    $client_ip = 'unknown';
    foreach ($arr_ip_header as $key)
    {
        if (!empty($_SERVER[$key]) && strtolower($_SERVER[$key]) != 'unknown')
        {
            $client_ip = $_SERVER[$key];
            break;
        }
    }
    return $client_ip;
}
//获取具体错误信息
function getE($num="") {
    switch($num) {
        case -1:  $error = '用户名长度必须在6-30个字符以内！'; break;
        case -2:  $error = '用户名被禁止注册！'; break;
        case -3:  $error = '用户名被占用！'; break;
        case -4:  $error = '密码长度不合法'; break;
        case -5:  $error = '邮箱格式不正确！'; break;
        case -6:  $error = '邮箱长度必须在1-32个字符之间！'; break;
        case -7:  $error = '邮箱被禁止注册！'; break;
        case -8:  $error = '邮箱被占用！'; break;
        case -9:  $error = '手机格式不正确！'; break;
        case -10: $error = '手机被禁止注册！'; break;
        case -11: $error = '手机号被占用！'; break;
        case -12: $error = '手机号码必须由11位数字组成';break;
        case -13: $error = '手机号已被其他账号绑定';break;

        case -20: $error = '请填写正确的姓名';break;
        case -21: $error = '用户名必须由字母、数字或下划线组成,以字母开头';break;
        case -22: $error = '用户名必须由6~30位数字、字母或下划线组成';break;
        case -31: $error = '密码错误';break;
        case -32: $error = '用户不存在或被禁用';break;
        case -41: $error = '身份证无效';break;
        default:  $error = '未知错误';
    }
    return $error;
}

//获取CURD请求类型
function Get_method(){
    $method = $_SERVER['REQUEST_METHOD'];
    return $method;
}
//获取CURD请求数据
function Resp_curl(){
    parse_str(file_get_contents('php://input'), $data);
    $data = array_merge($_GET, $_POST, $data);
    return $data;
}
//建立CURD请求模式
function Rest_curl($url,$type='GET',$data="",$bool=false,array $headers=["content-type: application/x-www-form-urlencoded;charset=UTF-8"]){
    //post 新增  get查询  put修改  delete删除
    $curl= curl_init();
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_URL,$url);
    if($bool == true){
        curl_setopt($curl, CURLOPT_HEADER, $bool);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    switch ($type){
        case "GET":break;
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "DELETE":
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        default:break;
    }
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,false);
    if(curl_exec($curl) === false){
        return "error code:".curl_getinfo($curl, CURLINFO_HTTP_CODE).',error message:'.curl_error($curl);
    }
    $strData = curl_exec($curl);
    curl_close($curl);
    return $strData;
}

//数据库备份
//function mysqldump($tableName){
//    $username = Yii::$app->params['user'];//你的MYSQL用户名
//    $password = Yii::$app->params['pass'];;//密码
//    $hostname = Yii::$app->params['host'];;//MYSQL服务器地址
//    $dbname   = Yii::$app->params['dbname'];;//数据库名
//    $port   = Yii::$app->params['port'];;//数据库端口
//    $dumpfname = $tableName . "_" . date("YmdHi").".sql";
//    $path = dirname(dirname(__FILE__))."/data/".$dumpfname;
//    $command = "mysqldump -P{$port} -h{$hostname} -u{$username} -p{$password} {$dbname} {$tableName} > {$path}";
//    system($command,$retval);
//    exit;
//}
//
////数据库备份
//function mysqldumpall($tableName){
//    $username = Yii::$app->params['user'];//你的MYSQL用户名
//    $password = Yii::$app->params['pass'];;//密码
//    $hostname = Yii::$app->params['host'];;//MYSQL服务器地址
//    $dbname   = Yii::$app->params['dbname'];;//数据库名
//    $port   = Yii::$app->params['port'];;//数据库端口
//    $dumpfname =  "localhost_" . date("YmdHi").".sql";
//    $path = dirname(dirname(__FILE__))."/data/".$dumpfname;
//    $command = "mysqldump -P{$port} -h{$hostname} -u{$username} -p{$password} {$dbname} {$tableName} > {$path}";
//    system($command,$retval);
//    $zipfname = "localhost_" . date("YmdHi").".zip";
//    $zippath = dirname(dirname(__FILE__))."/data/".$zipfname;
//    $zip = new \ZipArchive();
//    if($zip->open($zippath,ZIPARCHIVE::CREATE))
//    {
//        $zip->addFile($path,$path);
//        $zip->close();
//    }
//    if (file_exists($zippath)) {
//        header('Content-Description: File Transfer');
//        header('Content-Type: application/octet-stream');
//        header('Content-Disposition: attachment; filename='.basename($zippath));
//        flush();
//        readfile($zippath);
//        exit;
//    }
//}