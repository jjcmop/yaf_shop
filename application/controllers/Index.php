<?php
use Yaf\Application;
use Yaf\Dispatcher;
class IndexController extends Yaf\Controller_Abstract {
    public $db;
    public function init(){
        $this->db = new dbModel();
    }
    public function indexAction() {//默认Action
        $arrData = $this->db->action("SELECT * FROM tab_apk WHERE t_id = 2 or t_id = 4 ");
        $newData = $this->db->action("SELECT * FROM tab_article_log WHERE status='1' and recover='1' ORDER BY id DESC LIMIT 0,7 ");
        $this->getView()->assign(["arrData"=>$arrData,"newData"=>$newData]);
    }
    public function goldAction() {//默认Action
        $content = $this->db->field()->table("tab_apk_shop")->where(" status = '1' and shop_status = '1' and shop_type = '金币交易'")->select();
        foreach ($content as $k1=>$v1){
            $nameselect = $this->db->field("title")->table("tab_apk")->where("id = {$v1['name_select']}")->find();
            $content[$k1]['name_select'] = $nameselect['title'];
        }
        $this->getView()->assign("content", $content);
    }
    public function accountAction() {//默认Action
        $content = $this->db->field()->table("tab_apk_shop")->where(" status = '1' and shop_status = '1' and shop_type = '账号交易'")->select();
        foreach ($content as $k1=>$v1){
            $nameselect = $this->db->field("title")->table("tab_apk")->where("id = {$v1['name_select']}")->find();
            $content[$k1]['name_select'] = $nameselect['title'];
        }
        $this->getView()->assign("content", $content);
    }
    public function equipmentAction() {//默认Action
        $content = $this->db->field()->table("tab_apk_shop")->where(" status = '1' and shop_status = '1' and shop_type = '装备交易'")->select();
        foreach ($content as $k1=>$v1){
            $nameselect = $this->db->field("title")->table("tab_apk")->where("id = {$v1['name_select']}")->find();
            $content[$k1]['name_select'] = $nameselect['title'];
        }
        $this->getView()->assign("content", $content);
    }
    public function practiceAction() {//默认Action
        $content = $this->db->field()->table("tab_apk_shop")->where(" status = '1' and shop_status = '1' and shop_type = '游戏代练'")->select();
        foreach ($content as $k1=>$v1){
            $nameselect = $this->db->field("title")->table("tab_apk")->where("id = {$v1['name_select']}")->find();
            $content[$k1]['name_select'] = $nameselect['title'];
        }
        $this->getView()->assign("content", $content);
    }
    public function detailsAction(){
        $id = get("id");
        $content = $this->db->field()->table("tab_apk_shop")->where("id = {$id}")->find();
        $nameselect = $this->db->field("title")->table("tab_apk")->where("id = {$content['name_select']}")->find();
        $content['name_select'] = $nameselect['title'];
        $content['expiredtime'] = date("Y-m-d H:i:s",$content['createtime']+3600*24*7);
        $content['createtime'] = date("Y-m-d H:i:s",$content['createtime']);
        $arrpic = explode("@",$content['filecontent']);
        unset($content['filecontent']);
        //收藏
        $user = $this->getRequest()->getCookie("account");
        if(!empty($user)){
            $mycollectionData = $this->db->field()->table("tab_apk_collection")->where("shop_id = {$id} and user = {$user}")->find();
        }else{
            $mycollectionData = "";
        }
        $this->getView()->assign(["content"=>$content,"arrpic"=>$arrpic,"mycollectionData"=>$mycollectionData]);
    }
    public function goodsAction() {//默认Action
        $this->getView()->assign("content", "xxxxxx");
    }
    public function moreAction() {//默认Action
        $nav = $this->db->field()->table("tab_apk_type")->select();
        $game = $this->db->field()->table("tab_apk")->where("t_id = 1")->select();
        $this->getView()->assign(["nav"=>$nav,"game"=>$game]);
    }
    public function ajaxmoreAction(){
        Dispatcher::getInstance()->autoRender(false);
        $id = $this->getRequest()->get("id");
        $gameData = $this->db->field()->table("tab_apk")->where("t_id = {$id}")->select();
        echo json_encode($gameData,320);
    }
    public function shopdetailsAction(){
        $this->getView()->assign(["xxx"=>"yyy"]);
    }
    public function emptyAction()
    {
        // TODO: Implement __call() method.
    }

}
?>