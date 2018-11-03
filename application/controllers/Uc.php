<?php
use Yaf\Application;
use Yaf\Dispatcher;
class UcController extends Yaf\Controller_Abstract{
    public $db;
    public $user;
    public function init(){
        $this->db = new dbModel();
        $user = $this->getRequest()->getCookie("account");
        if(!empty($user)){
            $this->user = $user;
        }else{
            success("请先登陆!","http://www.zhishengwh.com/index/Login/login.html?login=shop");
            exit;
        }
    }
    public function indexAction(){
        $username = $this->user;
        $this->getView()->assign(["username"=>$username]);
    }
    public function buygoodsAction(){
        $this->getView()->assign(["xxx"=>"yyy"]);
    }
    public function productreleaseAction(){
        if($this->getRequest()->isPost()){
            $input = post();
            $fileicon = files("fileicon");
            $filecontent = files("filecontent");
            //保存路径
            $time = time();
            $dir = APP_PATH."/uploads/".$time."/";
            if(!file_exists($dir)){
                mkdir($dir,0777);
            }
            //文件上传
            $pathicon = $dir.$fileicon['name'];
            $boolicon = move_uploaded_file( $fileicon['tmp_name'],$pathicon);
            //多文件上传filecontent
            $filepath = "";
            $boolcontent = "";
            for ($i=0;$i<count($filecontent['name']);$i++){
                $pathcontent = $dir.$filecontent['name'][$i];
                $boolcontent = move_uploaded_file($filecontent['tmp_name'][$i],$pathcontent);
                $filepath .= $time."/".$filecontent['name'][$i]."@";
            }
            //上传成功写入数据
            if($boolicon && $boolcontent){
                $input['fileicon'] = $time."/".$fileicon['name'];
                $input['filecontent'] = substr($filepath,0,-1);
                $input['createtime'] = time();
                $input['account'] = $this->user;
                $this->db->action($this->db->insertSql("apk_shop",$input));
                alertText("发布信息成功","/Uc/index");
            }else{
                alertText("发布信息成功","/Uc/productrelease");
            }
        }else{
            $gametype = $this->db->field("id,type")->table("tab_apk_type")->select();
            $this->getView()->assign(["gametype"=>$gametype]);
        }
    }
    public function sellgoodsAction(){
        $this->getView()->assign(["xxx"=>"yyyy"]);
    }
    public function releaseguideAction(){
        $this->getView()->assign(["xxx"=>"yyyy"]);
    }
    public function commonproblemAction(){
        $this->getView()->assign(["xxx"=>"yyyy"]);
    }
    public function buyguideAction(){
        $this->getView()->assign(["xxx"=>"yyyy"]);
    }
    public function collectionAction(){
        $data = $this->db->field()->table("tab_apk_collection")->where("user = {$this->user}")->select();
        $this->getView()->assign(["data"=>$data]);
    }
    public function modifypwdAction(){
        $this->getView()->assign(["xxx"=>"yyyy"]);
    }
    public function addressAction(){
        $this->getView()->assign(["xxx"=>"yyyy"]);
    }
    public function shopAction(){
        $color = "";
        $text = "";
        $user = $this->user;
        $type = get('type');
        $werstr = "";
        switch ($type){
            case "s":
                $color = "color: #f5ae3e;";$text = '审核中';
                $werstr = "account	= {$user} and status = '0'";
                break;
            case "e":
                $color = "color: #ff0000;";$text = '审核失败';
                $werstr = "account	= {$user} and status = '2'";
                break;
            case "p":
                $color = "color: #49afcd;";$text = '上架';
                $werstr = "account	 = {$user} and status = '1' and shop_status = '1'";
                break;
            case "n":
                $color = "color: #999999;";$text = '下架';
                $werstr = "account	= {$user} and status = '1' and shop_status = '0'";
                break;
            case "sell":
                $color = "color: #00ff00;";$text = '已卖出';
                $werstr = "account	= {$user} and shop_status = '1' and sell_status > 0";
                break;
        }
        $data = $this->db->field()->table("tab_apk_shop")->where($werstr)->select();
        foreach ($data as $k1=>$v1){
            $nameselect = $this->db->field("title")->table("tab_apk")->where("id = {$v1['name_select']}")->find();
            $data[$k1]['name_select'] = $nameselect['title'];
        }
        $this->getView()->assign(["color"=>$color,"text"=>$text,"data"=>$data]);
    }
    public function ajaxproductAction(){
        Dispatcher::getInstance()->autoRender(false);
        $id = $this->getRequest()->getPost("d");
        $type = $this->getRequest()->getPost("t");
        if($type == 'type_select'){
            $gamename = $this->db->field("id,title")->table("tab_apk")->where("t_id = {$id}")->select();
            echo json_encode($gamename,320);
        }else{
            //待开发
            echo json_encode([['id'=>'待开发游戏区功能','title'=>'待开发功能']],320);
        }
    }
    public function ajaxcollectAction(){
        Dispatcher::getInstance()->autoRender(false);
        $u = get("u");
        $id = get("id");
        $t = get("t");
        if($t == 'click2'){
            $input['user'] = $u;
            $input['shop_id'] = $id;
            $bool = $this->db->action($this->db->insertSql("apk_collection",$input));
            if($bool){
                echo json_encode(['code'=>0],320);
            }
        }else{
            $bool = $this->db->action($this->db->deleteSql("apk_collection","shop_id = {$id} and user = {$u}"));
            if($bool){
                echo json_encode(['code'=>0],320);
            }
        }
    }
}