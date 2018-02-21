<?php

namespace App\Http\Controllers\ViewControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \Illuminate\Support\Facades\View;

class ForgetPwdController extends Controller {

    /**
     * 進入「忘記密碼」頁面
     * @param Request $request 前端POST進來的值
     * @param type $parameter 驗證用的參數
     * @return type
     */
    public function forgetPwd(Request $request, $parameter) {
        try {
            //轉換格式，並檢查回傳是否為陣列格式
            if ((!$paraArray = \App\Library\CommonTools::decodeAndCheckParameter($parameter)) || !is_array($paraArray)) {
                return redirect()->route('error');
            }
            return View::make('login/forgetpwd', compact('parameter'));
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return redirect('/#!/forgetpwd/' . $parameter)->withInput()->withErrors(['帳號輸入錯誤']);
        }
    }

    /**
     * 執行「檢查輸入帳號動作」
     * @param Request $request 前端POST進來的值
     * @param type $parameter 驗證用的參數
     * @return type
     */
    public function checkAccount(Request $request, $parameter) {
        try {
            //轉換格式，並檢查回傳是否為陣列格式
            if ((!$paraArray = \App\Library\CommonTools::decodeAndCheckParameter($parameter)) || !is_array($paraArray)) {
                return redirect()->route('error');
            }
            //檢查前端POST進來的參數格式
            $checkResult = $this->checkPostValueFormat($request);
            if ($checkResult !== true) {
                return $checkResult;
            }

            //取得會員帳號
            $memberRepo = new \App\Repositories\MemberDataRepository();
            $memberData = $memberRepo->getDataByMd_Account($request->account);
            //檢查取得的會員資料
            if (!isset($memberData) || count($memberData) > 1) {
                return redirect('/#!/forgetpwd/' . $parameter)->withInput()->withErrors(['帳號輸入錯誤']);
            }
            //檢查該帳號是否存在
            if (count($memberData) == 0) {
                return redirect('/#!/forgetpwd/' . $parameter)->withInput()->withErrors(['帳號輸入錯誤']);
            }
            //發送 驗證E-Mail、簡訊
            if (!$this->sendResetPwdVerify($memberData[0]->md_id)) {
                return redirect('/#!/forgetpwd/' . $parameter)->withInput()->withErrors(['帳號輸入錯誤']);
            }

            //導向至成功頁面
            $redirect_uri = "/";
            return redirect('/#!/forgetpwdsuccess?redirect_uri=' . urlencode($redirect_uri));
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return redirect('/#!/forgetpwd/' . $parameter)->withInput()->withErrors(['帳號輸入錯誤']);
        }
    }

    /**
     * 寄送重設密碼郵件簡訊成功
     * @param Request $request 前端POST進來的值
     * @return type
     */
    public function success(Request $request) {
        try {
            $redirect_uri = 'http://tw.iscarmg.com/';
            if (isset($request->redirect_uri)) {
                $redirect_uri = $request->redirect_uri;
            }

            return View::make('login/forgetpwdsuccess', compact('redirect_uri'));
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
        }
    }

    /**
     * 檢查前端POST進來的參數格式
     * @param type $request
     * @return type
     */
    private function checkPostValueFormat($request) {
        try {
            //檢查〈account〉格式
            if (!\App\Library\CommonTools::checkValueFormat($request->account, 64, false, false)) {
                return redirect()->back()->withErrors(['帳號輸入錯誤']);
            }
            return true;
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return redirect()->back()->withErrors(['帳號輸入錯誤']);
        }
    }

    /**
     * 發送 驗證E-Mail、簡訊
     * @param type $account 會員帳號
     * @return boolean
     */
    private function sendResetPwdVerify($md_id) {
        try {
            //發送簡訊
            $snc_serno = \App\Services\SmsService::sendVerifyCodeResetPwd($md_id);
            if (!$snc_serno) {
                return false;
            }

            //建立「icr_resetpwdverify」
            $rpv_serno = $this->createResetPwdVerify($snc_serno, $md_id);

            //發送E-Mail
            $result = \App\Services\EMailService::send_ResetPassword($md_id, $rpv_serno);
            if (!$result) {
                return false;
            }

            return true;
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return false;
        }
    }

    /**
     * @param type $snc_serno 驗證碼代碼
     * @param type $md_id     會員代碼
     * @return boolean
     */
    private function createResetPwdVerify($snc_serno, $md_id) {
        try {
            $smsRepo = new \App\Repositories\SmsNumberCodeRepository();
            $smsData = $smsRepo->getData($snc_serno);
            if (!isset($smsData) || count($smsData) == 0) {
                return false;
            }

            $rpvRepo = new \App\Repositories\ResetPwdVerifyRepository();

            $arrayData['snc_serno'] = $smsData->snc_serno;
            $arrayData['rpv_verifycode'] = $smsData->snc_code;
            $arrayData['md_id'] = $md_id;

            $result = $rpvRepo->createGetId($arrayData);
            if (!isset($result)) {
                return false;
            }
            return $result;
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return false;
        }
    }
}
