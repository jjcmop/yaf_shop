<?php
use Yaf\Application;
use Yaf\Dispatcher;
class ListController extends Yaf\Controller_Abstract{
    public $db;
    public function init(){
        $this->db = new dbModel();
    }
    public function indexAction(){
        $type = get("type");
        $apkname = get("apkname");
        $shoptype = get("shoptype");
        $apk = $this->db->field("id")->table("tab_apk")->where("title = '{$apkname}'")->find();
        if($shoptype == "all"){
            $content = $this->db->field()->table("tab_apk_shop")->where("name_select	 = '{$apk['id']}'")->select();
        }else{
            $content = $this->db->field()->table("tab_apk_shop")->where("shop_type = '{$shoptype}' and name_select	 = '{$apk['id']}'")->select();
        }
        foreach ($content as $k1=>$v1){
            $nameselect = $this->db->field("title")->table("tab_apk")->where("id = {$v1['name_select']}")->find();
            $content[$k1]['name_select'] = $nameselect['title'];
        }
        $this->getView()->assign(["content"=>$content,"type"=>$type]);
    }
    public function allindexAction(){
        Dispatcher::getInstance()->autoRender(false);
        $type = get("type");
        $wherestr = "";
        switch ($type){
            case "index":$wherestr = ""; break;
            case "gold":$wherestr = " and shop_type = '金币交易' "; break;
            case "account":$wherestr = " and shop_type = '账号交易' "; break;
            case "equipment":$wherestr = " and shop_type = '装备交易' "; break;
            case "practice":$wherestr = " and shop_type = '游戏代练' "; break;
        }
        $content = $this->db->field()->table("tab_apk_shop")->where("status = '1' and shop_status = '1' {$wherestr} ")->select();
        foreach ($content as $k1=>$v1){
            $nameselect = $this->db->field("title")->table("tab_apk")->where("id = {$v1['name_select']}")->find();
            $content[$k1]['name_select'] = $nameselect['title'];
        }
        $this->getView()->display("list/index.phtml", ["content"=>$content,"type"=>$type]);
    }
    public function searchAction(){
        Dispatcher::getInstance()->autoRender(false);
        $d = preg_replace("/[%_\s]+/","",ltrim(addslashes(request('d'))));
        $type = get("type");
        $apk = $this->db->field("id")->table("tab_apk")->where("title = '{$d}'")->find();
        $content = $this->db->field()->table("tab_apk_shop")->where("status = '1' and shop_status = '1' and name_select	= '{$apk['id']}'")->select();
        foreach ($content as $k1=>$v1){
            $nameselect = $this->db->field("title")->table("tab_apk")->where("id = {$v1['name_select']}")->find();
            $content[$k1]['name_select'] = $nameselect['title'];
        }
        $this->getView()->display("list/index.phtml", ["content"=>$content,"type"=>$type]);
    }
}