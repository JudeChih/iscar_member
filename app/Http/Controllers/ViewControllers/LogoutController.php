<?php

namespace App\Http\Controllers\ViewControllers;

use Request;
use App\Http\Controllers\Controller;
use \Illuminate\Support\Facades\View;

if (!defined('MAIN')) define('MAIN',config('global.main'));

class LogoutController extends Controller {

    /**
     * 登出流程
     * @param Request $request 前端GET進來的值
     * @param string $parameter 驗證用的參數
     */
    public function logout(Request $request) {
        $sat_r = new \App\Repositories\ServiceAccessTokenRepository;
        $md_r = new \App\Repositories\MemberDataRepository;
        $sso_r = new \App\Repositories\SsoDataRepository;
        $map_r = new \App\Repositories\ModuleAccPass_rRepository;
        $getData = Request::All();
        try {
            // 轉換格式，並檢查回傳是否為陣列格式
            if ((!$paraData = \App\Library\CommonTools::decodeAndCheckParameter($getData['parameter'])) || !is_array($paraData)){
                return redirect('/#!/logout/failed')->withInput()->withErrors(['驗證失敗']);
            }
            // 轉換格式，並檢查SAT
            if(!$satData = \App\Library\CommonTools::CheckServiceAccessToken($getData['sat'])){
                return redirect('/#!/logout/failed')->withInput()->withErrors(['驗證有誤']);
            }
            // 更新token的狀態
            if(!$sat_r->UpdateServiceAccessTokenEffective($satData['md_id'],$satData['mur_id'])){
                return redirect('/#!/logout/failed')->withInput()->withErrors(['更新出錯']);
            }
            // 取得會員資料
            $mdData = $md_r->GetMemberData($satData['md_id']);
            if(count($mdData) == 0 || count($mdData) > 1 || is_null($mdData)){
                return redirect('/#!/logout/failed')->withInput()->withErrors(['會員資料有誤']);
            }
            if(count($mdData)){
                $mdData = $mdData[0];
            }

            // 如果全域變數mod有設定，就抓取這個的redirect_uri
            if(isset($_COOKIE['mod'])){
                $modData = $map_r->getDataByAccount($_COOKIE['mod']);
                if(count($modData) == 1){
                    $modData = $modData[0];
                    $paraData['redirect_uri'] = $modData['mapr_redirect_uri'];
                    setcookie('mod', '', time()-3600, '/', 'iscarmg.com', false);
                }
            }

            // 清掉main除了murid以外的值
            if(isset($_COOKIE[MAIN])){
                $main = json_decode($_COOKIE[MAIN],true);
                $array['murId'] = $main['murId'];
                setcookie(MAIN, json_encode($array), time()+3600, '/', 'iscarmg.com', false);
            }else{
                setcookie(MAIN, '', time()-3600, '/', 'iscarmg.com', false);
            }
            
            // 一般用戶導頁
            if($mdData['md_logintype'] == 3){
                $generalUrlString = $paraData['redirect_uri'];
                return redirect()->to($generalUrlString);
            }
            // FB用戶導頁
            if($mdData['md_logintype'] == 0){
                $ssoData = $sso_r->getDataByAccountID($mdData['sso_accountid']);
                if(count($ssoData) == 0 || count($ssoData) > 1 ||is_null($ssoData)){
                    return redirect('/')->withInput()->withErrors(['會員資料有誤']);
                }
                if(count($ssoData) == 1){
                    $ssoData = $ssoData[0];
                }
                // $fbUrlString = "https://www.facebook.com/logout.php?next=".urlencode($paraData['redirect_uri'])."&access_token=".$ssoData['sso_token'];
                return redirect()->to($paraData['redirect_uri']);
            }

        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return redirect()->route('error');
        }
    }

    public function logoutFailed(){
        return View::make('login/logout');
    }
}
