<?php

namespace App\Http\Controllers\ViewControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;

if (!defined('REDIRECT_URI')) define('REDIRECT_URI', config('global.redirect_uri'));
if (!defined('MAIN')) define('MAIN',config('global.main'));

class LoginController extends Controller {

    /**
     * 進入「登入」頁面
     * @param Request $request 前端POST進來的值
     * @param type $parameter 驗證用的參數
     * @return type
     */
    public function login(Request $request, $parameter) {
        $map_r = new \App\Repositories\ModuleAccPass_rRepository;
        try {
            //轉換格式，並檢查回傳是否為陣列格式
            if ((!$paraArray = \App\Library\CommonTools::decodeAndCheckParameter($parameter)) || !is_array($paraArray)){
                return redirect('/')->withInput()->withErrors(['驗證失敗']);
            }
            // 判斷是前台還是後台，跳轉的頁面不一樣
            $mod_r = new \App\Repositories\ModuleAccPass_rRepository;
            $modData = $mod_r->getDataByAccount($paraArray['modacc']);
            if(count($modData) ==0 || is_null($modData)){
                return redirect()->route('error');
            }
            if(count($modData) == 1){
                $modData = $modData[0];
            }
            if($modData['mapr_functiontype'] == 2){
                return View::make('login/login_b', compact('parameter'));
            }
            $fb = new \Facebook\Facebook(config('facebook.config'));
            $helper = $fb->getRedirectLoginHelper();

            // $permissions = ['email']; // Optional permissions
            $loginUrl = $helper->getLoginUrl(REDIRECT_URI.'loginthirdparty');
            $button = '<a class="button button-big fb_login external" href="' . htmlspecialchars($loginUrl) . '">Facebook 登入</a>';

            // 如果全域變數mod有設定，就抓取這個的redirect_uri
            if(isset($_COOKIE['mod'])){
                $modData = $map_r->getDataByAccount($_COOKIE['mod']);
                if(count($modData) == 1){
                    $modData = $modData[0];
                    $redirect_uri = $modData['mapr_redirect_uri'];
                }else{
                    $redirect_uri = '';
                }
            }

            return View::make('login/login', compact('parameter','button','redirect_uri'));
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return redirect()->route('error');
        }
    }

    /**
     * 執行「登入」動作
     * @param Request $request 前端POST進來的值
     * @param type $parameter 驗證用的參數
     * @return type
     */
    public function checkAccountPwd(Request $request, $parameter) {
        try {
            //轉換格式，並檢查回傳是否為陣列格式
            if ((!$paraArray = \App\Library\CommonTools::decodeAndCheckParameter($parameter)) || !is_array($paraArray)) {
                return redirect('/index' . $parameter)->withInput()->withErrors(['驗證失敗']);
            }
            //檢查前端POST進來的參數格式
            $checkResult = $this->checkPostValueFormat($request, $parameter);
            if ($checkResult !== true) {
                return $checkResult;
            }
            // 取得模組資料
            $mod_r = new \App\Repositories\ModuleAccPass_rRepository;
            $modData = $mod_r->getDataByAccount($paraArray['modacc']);
            if(count($modData) != 0){
                $modData = $modData[0];
            }
            //取得會員資料
            $memberRepo = new \App\Repositories\MemberDataRepository();
            $memberData = $memberRepo->getDataByMd_Account($request->account);

            //檢查取得的會員資料
            if (!isset($memberData) || count($memberData) > 1) {
                return redirect('/#!/login/' . $parameter)->withInput()->withErrors(['登入失敗，帳號或密碼輸入錯誤']);
            }
            if (count($memberData) == 0) {
                return redirect('/#!/login/' . $parameter)->withInput()->withErrors(['登入失敗，帳號或密碼輸入錯誤']);
            }
            //檢查是否為商家後台的帳號
            if($modData['mapr_functiontype'] == 2){
                if(count($memberData) != 0){
                    $memberData_b = $memberData[0];
                }
                // 檢查會員是否為特約商
                if($modData['mapr_moduleaccount'] == 'iscarshop_b'){
                    if($memberData_b['md_clienttype'] != 1){
                        return redirect('/#!/login/' . $parameter)->withInput()->withErrors(['尚未成為特約商身分，請到"就是行"官網申請']);
                    }
                }
                // 檢查會員是否為品牌商
                if($modData['mapr_moduleaccount'] == 'iscarbrand_b'){
                    if($memberData_b['md_clienttype'] != 3){
                        return redirect('/#!/login/' . $parameter)->withInput()->withErrors(['尚未成為品牌商身分，請到"就是行"官網申請']);
                    }
                }
                // 檢查會員是否為宮廟管理員
                if($modData['mapr_moduleaccount'] == 'iscartemple_b'){
                    if($memberData_b['md_clienttype'] != 99){
                        return redirect('/#!/login/' . $parameter)->withInput()->withErrors(['尚未成為宮廟管理員身分，請到"就是行"官網申請']);
                    }
                }
            }
            //檢查密碼是否相符
            if ($memberData[0]->md_password !== $request->password) {
                return redirect('/#!/login/' . $parameter)->withInput()->withErrors(['登入失敗，帳號或密碼輸入錯誤']);
            }
            //產生授權憑證，建立「ServiceAccessToken」、「MemberMobileLink」
            $sat = \App\Library\CommonTools::createServiceAccessToken($memberData[0]->md_id, $paraArray['mur']);
            if ($sat === false) {
                return redirect('/#!/login/' . $parameter)->withInput()->withErrors(['登入失敗，帳號或密碼輸入錯誤']);
            }
            //寫入會員帳號異動記錄
            \App\Library\CommonTools::writeAccountModifyRecode($memberData[0]->md_id, $paraArray['mur'], '1', '1');
            //檢查會員是否存在親屬資料表
            if(!\App\Library\CommonTools::synchronizeTempleRelative($memberData[0]->md_id)){
                return redirect('/#!/login/' . $parameter)->withInput()->withErrors(['登入失敗，未知的錯誤']);
            }
            //存sat murID到cookie
            // $array['murId'] = $paraArray['mur'];
            // $array['sat'] =　$sat;
            // setcookie(MAIN, json_encode($array), time()+3600, '/', 'iscarmg.com', false);
            //導回登入前頁
            return redirect('/#!/loginsuccess?redirect_uri=' . $paraArray['redirect_uri'].'&sat=' . $sat . '&mur=' . $paraArray['mur']);
            // return redirect($redirect_uri);
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return redirect('/#!/login/' . $parameter)->withInput()->withErrors(['登入失敗，帳號或密碼輸入錯誤']);
        }
    }

    /**
     * 檢查前端POST進來的參數格式
     * @param type $request
     * @return boolean
     */
    private function checkPostValueFormat($request, $parameter) {
        try {
            //檢查〈account〉格式
            if (!\App\Library\CommonTools::checkValueFormat($request->account, 64, false, false)) {
                return redirect('/#!/login/' . $parameter)->withInput()->withErrors(['登入失敗，帳號或密碼輸入錯誤']);
            }
            //檢查〈password〉格式
            if (!\App\Library\CommonTools::checkValueFormat($request->password, 0, false, false)) {
                return redirect('/#!/login/' . $parameter)->withInput()->withErrors(['登入失敗，帳號或密碼輸入錯誤']);
            }
            return true;
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return redirect('/#!/login/' . $parameter)->withInput()->withErrors(['登入失敗，帳號或密碼輸入錯誤']);
        }
    }

    /**
     * 登入成功
     */
    public function success() {
        try {

            return View::make('login/loginsuccess');
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return redirect()->route('error');
        }
    }
}
