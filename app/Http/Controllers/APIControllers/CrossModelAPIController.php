<?php

namespace App\Http\Controllers\APIControllers;
use App\Http\Controllers\Controller;

class CrossModelAPIController extends Controller {

    /** prelogin	呼叫API取得密碼傳遞保護碼 **/
    function prelogin() {
        $Prelogin = new \App\Http\Controllers\APIControllers\CrossModelAPI\Prelogin;
        return $Prelogin->prelogin();
    }

     /** login  呼叫API執行模組登入作業 **/
    function login() {
        $Login = new \App\Http\Controllers\APIControllers\CrossModelAPI\Login;
        return $Login->login();
    }

     /** postumlmessage  新增用戶收件匣訊息 **/
    function postumlmessage() {
        $PostUmlMessage = new \App\Http\Controllers\APIControllers\CrossModelAPI\PostUmlMessage;
        return $PostUmlMessage->postumlmessage();
    }

     /**querymembercoininfo	接收會員資訊，回傳當前代幣持有數額  **/
    function querymembercoininfo() {
        $querymembercoininfo = new \App\Http\Controllers\APIControllers\CrossModelAPI\QueryMemberCoinInfo;
        return $querymembercoininfo->querymembercoininfo();
    }

     /**modifymembercoininfo處理使用者購買瀏覽權限需求，判斷並增減會員代幣或紅利餘額後回傳**/
     function modifymembercoininfo() {
        $modifymembercoininfo = new \App\Http\Controllers\APIControllers\CrossModelAPI\ModifyMemberCoininfo;
        return $modifymembercoininfo->modifymembercoininfo();
    }

     /**querymemberlevelinfo 查詢用戶等級相關資訊 **/
     function querymemberlevelinfo() {
        $querymemberlevelinfo = new \App\Http\Controllers\APIControllers\CrossModelAPI\QueryMemberLevelinfo();
        return $querymemberlevelinfo->querymemberlevelinfo();
    }

    /**verifyapitoken 驗正Api_Token **/
     function verifyapitoken() {
        $verifyapitoken = new \App\Http\Controllers\APIControllers\CrossModelAPI\VerifyApiToken();
        return $verifyapitoken->verifyapitoken();
    }
}
