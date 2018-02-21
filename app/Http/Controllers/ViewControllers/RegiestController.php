<?php

namespace App\Http\Controllers\ViewControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \Illuminate\Support\Facades\View;
use Session;

if (!defined('REDIRECT_URI')) define('REDIRECT_URI', config('global.redirect_uri'));
if (!defined('Expired_Time')) define('Expired_Time', config('global.expired_time'));


class RegiestController extends Controller {

    /**
     * 進入「註冊選擇」頁面
     * @param Request $request 前端POST進來的值
     * @param type $parameter 驗證用的參數
     * @return type
     */
    public function regiest(Request $request, $parameter) {
        try {
            //轉換格式，並檢查回傳是否為陣列格式
            if ((!$paraArray = \App\Library\CommonTools::decodeAndCheckParameter($parameter)) || !is_array($paraArray)) {
                return redirect()->route('error');
            }
            $fb = new \Facebook\Facebook(config('facebook.config'));
            $helper = $fb->getRedirectLoginHelper();

            // $permissions = ['email']; // Optional permissions
            $loginUrl = $helper->getLoginUrl(REDIRECT_URI.'loginthirdparty');
            $button = '<a class="animated zoomIn button button-big fb-regiest fb_regiest_btn external" href="' . htmlspecialchars($loginUrl) . '"><img src="app/image/mem_btn_signup_fb.png"><div>Facebook</div></a>';
            return View::make('login/regiest', compact('parameter','button'));
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return redirect()->route('error');
        }
    }

    /**
     * 進入「註冊-輸入資料」頁面
     * @param Request $request 前端POST進來的值
     * @param type $parameter 驗證用的參數
     * @return type
     */
    public function regiestiscar(Request $request, $parameter) {
        try {
            //轉換格式，並檢查回傳是否為陣列格式
            if ((!$paraArray = \App\Library\CommonTools::decodeAndCheckParameter($parameter)) || !is_array($paraArray)) {
                return redirect()->route('error');
            }
            $regData = Session::get('regdata');
            if (!isset($regData)) {
                return View::make('login/regiestiscar', compact('parameter'));
            }
            Session::forget('regdata');
            // \App\Library\CommonTools::writeErrorLogByMessage(json_encode($regData));
            return View::make('login/regiestiscar', compact('parameter','regData'));
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return redirect()->route('error');
        }
    }

    /**
     * 檢查註冊資料並暫存於Session，完成後發送簡訊﹙驗證碼﹚
     * @param Request $request 前端POST進來的值
     * @param type $parameter 驗證用的參數
     * @return type
     */
    public function checRegistkData(Request $request, $parameter) {
        try {
            //轉換格式，並檢查回傳是否為陣列格式
            if ((!$paraArray = \App\Library\CommonTools::decodeAndCheckParameter($parameter)) || !is_array($paraArray)) {
                return redirect()->route('error');
            }
            //檢查前端POST進來的參數格式
            $checkResult = $this->checkPostValueFormat_Regiest($request, $parameter);
            if ($checkResult !== true) {
                return $checkResult;
            }
            //將資料暫存於Session中
            Session::put('regdata', $request->all());

            //取得會員帳號
            $memberRepo = new \App\Repositories\MemberDataRepository();
            $memberData = $memberRepo->getDataByMd_Account($request->account);

            //檢查電話號碼是否已經註冊過
            $phoneData = $memberRepo->getDataByMd_RegiestMobile($request->cellphone);
            if(count($phoneData) > 0){
                return redirect('/#!/regiestiscar/' . $parameter)->withInput()->withErrors(['電話號碼已註冊過']);
            }

            //檢查取得的會員資料
            if (!isset($memberData) || count($memberData) > 1) {
                return redirect('/#!/regiestiscar/' . $parameter)->withInput()->withErrors(['資料輸入錯誤']);
            }
            //檢查該帳號是否以存在
            if (count($memberData) == 1) {
                return redirect('/#!/regiestiscar/' . $parameter)->withInput()->withErrors(['帳號已註冊過']);
            }
            //將資料暫存於Session中
            // Session::put('regdata', $request->all());

            //寄送驗證碼
            $snc_serno = \App\Services\SmsService::sendVerifyCodeRegiest($request->countrycode, $request->cellphone);
            if ($snc_serno === false) {
                return redirect('/#!/regiestiscar/' . $parameter)->withInput()->withErrors(['資料輸入錯誤']);
            }
            return redirect('/#!/regiest/verify/' . $parameter);
            //return redirect()->route('regiest/verify', compact('parameter'));
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return redirect()->back()->withInput()->withErrors(['資料輸入錯誤']);
        }
    }

    /**
     * 進入「輸入驗證碼」頁面
     * @param Request $request 前端POST進來的值
     * @param type $parameter 驗證用的參數
     * @return type
     */
    public function verify(Request $request, $parameter) {
        try {
            //轉換格式，並檢查回傳是否為陣列格式
            if ((!$paraArray = \App\Library\CommonTools::decodeAndCheckParameter($parameter)) || !is_array($paraArray)) {
                return redirect()->route('error');
            }

            $regiest = 'regiest';

            return View::make('login/regiestverify', compact('parameter','regiest'));
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return redirect()->route('error');
        }
    }

    /**
     * 重新發送驗證碼
     * @param  Request $request [description]
     */
    public function resendVerifyCode(Request $request){
        try {
            $requiredData = $request->all();
            // 重發驗證碼
            $snc_serno = \App\Services\SmsService::sendVerifyCodeRegiest($requiredData['countrycode'], $requiredData['cellphone']);
            if ($snc_serno === false) {
                $result['code'] = "9999";
                $result['message'] = "重發失敗";
                return $result;
            }
            $result['code'] = "0000";
            $result['message'] = "重發成功";
            return $result;
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return redirect()->route('error');
        }
    }

    /**
     * 檢查驗證碼成功後將資料存於資料庫中
     * @param Request $request 前端POST進來的值
     * @param type $parameter 驗證用的參數
     * @return type
     */
    public function saveRegiestData(Request $request, $parameter) {
        try {
            //轉換格式，並檢查回傳是否為陣列格式
            if ((!$paraArray = \App\Library\CommonTools::decodeAndCheckParameter($parameter)) || !is_array($paraArray)) {
                return redirect()->route('error');
            }
            //檢查前端POST進來的參數格式
            $checkResult = $this->checkPostValueFormat_Verify($request, $parameter);
            if ($checkResult !== true) {
                return $checkResult;
            }

            //檢查暫存於Session的資料
            if (!($regData = $this->checkSessionData())) {
                return redirect('/#!/regiestiscar/' . $parameter)->withErrors(['重新輸入資料']);
            }
            //取得簡訊驗證資料
            $smsRepo = new \App\Repositories\SmsNumberCodeRepository();
            $smsData = $smsRepo->getDataBySnc_TargetPhone($regData['cellphone']);
            //檢查驗證碼是否已經過期
            if(!$this->checkVerifyCodeValid($smsData[0])){
                return redirect('/#!/regiest/verify/' . $parameter)->withInput()->withErrors(['驗證碼已過期，請按重新發送']);
            }
            if (!isset($smsData) || count($smsData) !== 1) {
                return redirect('/#!/regiest/verify/' . $parameter)->withInput()->withErrors(['驗證碼輸入錯誤']);
            }
            //檢查驗證碼是否相符
            if ($smsData[0]->snc_code !== $request->verifycode) {
                return redirect('/#!/regiest/verify/' . $parameter)->withInput()->withErrors(['驗證碼輸入錯誤']);
            }
            //建立會員資料
            if (!($md_id = $this->createMemberData($regData))) {
                return redirect('/#!/regiest/verify/' . $parameter)->withInput()->withErrors(['建立資料失敗']);
            }

            //產生授權憑證
            $sat = \App\Library\CommonTools::createServiceAccessToken($md_id, $paraArray['mur']);
            if ($sat === false) {
                return redirect('/#!/regiest/verify/' . $parameter)->withInput()->withErrors(['註冊失敗']);
            }
            //寫入會員帳號異動記錄
            \App\Library\CommonTools::writeAccountModifyRecode($md_id, $paraArray['mur'], '2', '1', null, $smsData[0]->snc_serno);
            //更新「icr_smsnumbercode」為已驗證
            $smsRepo->update(['snc_verifyresult' => '1'], $smsData[0]->snc_serno);

            //清除Session資料
            $this->clearSessionData();

            // //檢查會員是否存在親屬資料表
            if(!\App\Library\CommonTools::synchronizeTempleRelative($md_id)){
                return redirect('/#!/regiest/verify/' . $parameter)->withInput()->withErrors(['註冊失敗']);
            }

            //導回登入前頁
            $queryString = 'sat=' . $sat;
            $redirect_uri = \App\Library\CommonTools::appendQueryStringInUri($paraArray['redirect_uri'], $queryString);
            $app_s = new \App\Services\AppService;
            $modifypoint = $app_s->getGiftPointsAmount(1);
            $app_s->modifyGiftPoint($md_id, 1, $md_id, 4, true,$modifypoint);

            return redirect('/#!/regiestsuccess?modifypoint='.$modifypoint.'&redirect_uri=' . urlencode($redirect_uri));

        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return redirect()->route('error');
        }
    }

    /**
     * 註冊成功
     * @param Request $request 前端POST進來的值
     * @return type
     */
    public function success(Request $request) {
        try {
            $Data = $request->all();
            $modifypoint = $Data['modifypoint'];
            return View::make('login/regiestsuccess', compact('redirect_uri','modifypoint'));
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return redirect()->route('error');
        }
    }

    /**
     * 檢查前端POST進來的參數格式 註冊資料
     * @param type $request
     * @return type
     */
    private function checkPostValueFormat_Regiest($request, $parameter) {
        try {
            //檢查〈account〉格式
            if (!\App\Library\CommonTools::checkValueFormat($request->account, 64, false, false)) {
                return redirect('/#!/regiestiscar/' . $parameter)->withInput()->withErrors(['帳號輸入錯誤']);
            }
            //檢查〈password〉格式
            if (!\App\Library\CommonTools::checkValueFormat($request->password, 64, false, false)) {
                return redirect('/#!/regiestiscar/' . $parameter)->withInput()->withErrors(['密碼輸入錯誤']);
            }
            //檢查〈passwordconfirm〉格式
            if (!\App\Library\CommonTools::checkValueFormat($request->passwordconfirm, 64, false, false)) {
                return redirect('/#!/regiestiscar/' . $parameter)->withInput()->withErrors(['密碼確認輸入錯誤']);
            }
            //檢查〈nickname〉格式
            if (!\App\Library\CommonTools::checkValueFormat($request->nickname, 20, false, false)) {
                return redirect('/#!/regiestiscar/' . $parameter)->withInput()->withErrors(['名稱輸入錯誤']);
            }
            //檢查〈countrycode〉格式
            if (!\App\Library\CommonTools::checkValueFormat($request->countrycode, 2, false, false)) {
                return redirect('/#!/regiestiscar/' . $parameter)->withInput()->withErrors(['行動電話國家代碼輸入錯誤']);
            }
            //檢查〈cellphone〉格式
            if (!\App\Library\CommonTools::checkValueFormat($request->cellphone, 11, false, false)) {
                return redirect('/#!/regiestiscar/' . $parameter)->withInput()->withErrors(['行動電話輸入錯誤']);
            }
            return true;
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return redirect()->route('error');
        }
    }

    /**
     * 檢查前端POST進來的參數格式 驗證碼
     * @param type $request
     * @return type
     */
    private function checkPostValueFormat_Verify($request, $parameter) {
        try {
            //檢查〈verifycode〉格式
            if (!\App\Library\CommonTools::checkValueFormat($request->verifycode, 6, false, false)) {
                return redirect('/#!/regiest/verify/' . $parameter)->withInput()->withErrors(['驗證碼輸入錯誤']);
            }
            return true;
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return redirect()->route('error');
        }
    }

    /**
     * 檢查驗證碼是否過期
     * @param  array    $smsData      [簡訊驗證資料]
     * @return boolean  true or false
     */
    private function checkVerifyCodeValid($smsData){
        if(count($smsData) > 0){
            $smsData = $smsData[0];
        }
        try {
            $sendVerifyCodeDate = \Carbon\Carbon::parse($smsData['create_date']);
            //超過30分鐘就算過期
            $expiredTimePoint = \Carbon\Carbon::now()->subMinutes(Expired_Time);
            if($expiredTimePoint > $sendVerifyCodeDate ){
                return false;
            }
            return true;
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return redirect()->route('error');
        }
    }

    /**
     * 檢查暫存於Session的資料
     * @return boolean
     */
    private function checkSessionData() {
        try {
            $regData = Session::get('regdata');
            if (!isset($regData)) {
                Session::put('regdata', null);
                return false;
            }
            return $regData;
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return false;
        }
    }

    /**
     * 清除暫存於Session的資料
     * @return boolean
     */
    private function clearSessionData() {
        try {
            Session::put('regdata', null);
            Session::forget('regdata');
            return true;
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return false;
        }
    }

    /**
     * 建立會員資料
     * @param type $regData 註冊輸入的資料
     * @return type
     */
    private function createMemberData($regData) {
        try {
            $guid = \App\Library\CommonTools::generateGUID(false);

            $saveData['md_id'] = $guid;
            $saveData['md_logintype'] = '3';
            //$saveData['sso_accountid'] = '';
            $saveData['md_ssobind_status'] = 0;
            $saveData['md_ssobind_type'] = 0;
            $saveData['md_account'] = $regData['account'];
            $saveData['md_password'] = $regData['password'];
            //$saveData['rl_sn'] = $regData->QQQ;
            $saveData['md_cname'] = $regData['nickname'];
            //$saveData['md_ename'] = $regData->QQQ;
            //$saveData['md_tel'] = $regData->QQQ;
            $saveData['md_countrycode'] = $regData['countrycode'];
            $saveData['md_mobile'] = $regData['cellphone'];
            $saveData['md_regiestmobile'] = $regData['cellphone'];
            $saveData['rl_city_code'] = '1';
            $saveData['rl_zip'] = '104';
            //$saveData['md_addr'] = $regData->QQQ;
            $saveData['md_contactmail'] = $regData['account'];
            $saveData['md_first_login'] = \Carbon\Carbon::now();
            $saveData['md_last_login'] = \Carbon\Carbon::now();
            $saveData['md_clienttype'] = 0;
            $saveData['mcls_serno'] = 1;
            $saveData['md_clubjoinstatus'] = 0;

            $memRepo = new \App\Repositories\MemberDataRepository();
            if (!$memRepo->create($saveData)) {
                return false;
            }
            return $guid;
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return false;
        }
    }

}
