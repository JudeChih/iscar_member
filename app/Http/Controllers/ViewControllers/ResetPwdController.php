<?php

namespace App\Http\Controllers\ViewControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \Illuminate\Support\Facades\View;
use Session;

class ResetPwdController extends Controller {

    /**
     * 進入「密碼重置」頁面
     * @param Request $request 前端POST進來的值
     * @param type $parameter 驗證用的參數
     * @return type
     */
    public function resetPwd(Request $request, $parameter) {
        try {
            $hashArr = \App\Library\CommonTools::decodeResetPwdHashParameter($parameter);
            if (!$hashArr) {
                return redirect()->route('error');
            }
            //取得驗證碼資料
            $resetRepo = new \App\Repositories\ResetPwdVerifyRepository();
            $verifyData = $resetRepo->getData($hashArr['rpv_serno']);
            if (!isset($verifyData) || count($verifyData) != 1) {
                return redirect()->route('error');
            }

            //檢查傳入的Hash是否相符
            if ($verifyData->rpv_hash !== $hashArr['rpv_hash']) {
                return redirect()->route('error');
            }

            return View::make('login/resetpwd', compact('parameter'));
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return redirect()->route('error');
        }
    }

    /**
     * 執行「密碼重置」動作
     * @param Request $request 前端POST進來的值
     * @param type $parameter 驗證用的參數
     * @return type
     */
    public function saveNewPwd(Request $request, $parameter) {
        try {
            $hashArr = \App\Library\CommonTools::decodeResetPwdHashParameter($parameter);
            if (!$hashArr) {
                return redirect()->route('error');
            }

            //取得驗證碼資料
            $resetRepo = new \App\Repositories\ResetPwdVerifyRepository();
            $verifyData = $resetRepo->getDataByRpvSerno($hashArr['rpv_serno']);
            if (!isset($verifyData) || count($verifyData) != 1) {
                return redirect()->route('error');
            }

            //檢查傳入的Hash是否相符
            if ($verifyData[0]->rpv_hash !== $hashArr['rpv_hash']) {
                return redirect()->route('error');
            }

            //檢查前端POST進來的參數格式
            $checkResult = $this->checkPostValueFormat($request, $parameter);
            if ($checkResult !== true) {
                return $checkResult;
            }

            //檢查傳入的驗證碼是否相符
            if ($verifyData[0]->rpv_verifycode !== $request->verifycode) {
                return redirect('/#!/resetpwd/' . $parameter)->withInput()->withErrors(['驗證碼輸入錯誤']);
            }
            //檢查密碼和密碼確認是否相符
            if ($request->password !== $request->passwordconfirm) {
                return redirect('/#!/resetpwd/' . $parameter)->withInput()->withErrors(['密碼和密碼確認不相符']);
            }
            //修改密碼
            if (!$this->updatePassWord($verifyData[0]->md_id, $request->password, $verifyData[0]->rpv_serno)) {
                return redirect('/#!/resetpwd/' . $parameter)->withInput()->withErrors(['密碼修改失敗']);
            }

            //寫入會員帳號異動記錄
            \App\Library\CommonTools::writeAccountModifyRecode($verifyData[0]->md_id, '', '3', '1', null, $verifyData[0]->snc_serno);

            $redirect_uri = 'http://tw.iscarmg.com/';
            return redirect('/#!/resetpwdsuccess?redirect_uri=' . urlencode($redirect_uri));
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return redirect('/#!/resetpwd/' . $parameter)->withInput()->withErrors(['輸入錯誤']);
        }
    }

    /**
     * 檢查前端POST進來的參數格式
     * @param type $request
     * @return type
     */
    private function checkPostValueFormat($request, $parameter) {
        try {
            //檢查〈verifycode〉格式
            if (!\App\Library\CommonTools::checkValueFormat($request->verifycode, 6, false, false)) {
                return redirect('/#!/resetpwd/' . $parameter)->withInput()->withErrors(['驗證碼輸入錯誤']);
            }
            //檢查〈password〉格式
            if (!\App\Library\CommonTools::checkValueFormat($request->password, 0, false, false)) {
                return redirect('/#!/resetpwd/' . $parameter)->withInput()->withErrors(['密碼輸入錯誤']);
            }
            //檢查〈password_confirm〉格式
            if (!\App\Library\CommonTools::checkValueFormat($request->passwordconfirm, 0, false, false)) {
                return redirect('/#!/resetpwd/' . $parameter)->withInput()->withErrors(['密碼輸入錯誤']);
            }
            return true;
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return redirect('/#!/resetpwd/' . $parameter)->withInput()->withErrors(['驗證碼輸入錯誤']);
        }
    }

    /**
     * 修改會員密碼
     * @param type $md_id     會員代碼
     * @param type $password  新密碼
     * @param type $rpv_serno 驗證碼代碼
     * @return boolean
     */
    private function updatePassWord($md_id, $password, $rpv_serno) {
        try {
            \Illuminate\Support\Facades\DB::beginTransaction();
            //修改會員密碼
            $Repo = new \App\Repositories\MemberDataRepository();
            if (!$Repo->updatePassword($md_id, $password)) {
                \Illuminate\Support\Facades\DB::rollback();
                return false;
            }
            //修改「重設密碼驗證碼」驗證碼狀態 為已使用
            $resetRepo = new \App\Repositories\ResetPwdVerifyRepository();
            if (!$resetRepo->update(['rpv_status' => '2'], $rpv_serno)) {
                \Illuminate\Support\Facades\DB::rollback();
                return false;
            }
            \Illuminate\Support\Facades\DB::commit();
            return true;
        } catch (\Exception $ex) {
            \Illuminate\Support\Facades\DB::rollback();
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return false;
        }
    }

    /**
     * 密碼重置成功
     * @param Request $request 前端POST進來的值
     * @return type
     */
    public function success(Request $request) {
        try {
            return View::make('login/resetpwdsuccess');
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return redirect()->route('error');
        }
    }

}
