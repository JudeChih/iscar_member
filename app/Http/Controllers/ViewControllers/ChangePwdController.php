<?php

namespace App\Http\Controllers\ViewControllers;

use Request;
use App\Http\Controllers\Controller;
use \Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;

class ChangePwdController extends Controller {

    /**
     * 進入「修改密碼」頁面
     * @param string $sat          [會員授權憑證]
     * @param string $mur          [設備代碼]
     * @return type
     */
    public function changePwd() {
        $arraydata = Input::All();
        try {
            // 檢查所傳入的參數格式
            if(!$boolean = $this->checkInputData($arraydata)){
                return redirect()->route('error');
            }
            // 檢查SAT有效性
            if(!\App\Library\CommonTools::CheckServiceAccessToken($arraydata['sat'])){
                return redirect()->route('error');
            }
            // 抓取現在的路徑，包含?後面的參數
            $posturi = Request::fullurl();

            return View::make('login/changepwd', compact('posturi'));
            // return view::make('welcome', compact('posturi'));
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return redirect()->route('error');
        }
    }

    /**
     * 執行「修改密碼」動作
     * @param string $redirect_uri
     * @param string $sat
     * @param string $mur
     * @param string $passwordold
     * @param string $passwordnew
     * @param string $passwordnewconfirm
     * @return view
     */
    public function executeChangePwd(Request $request ) {
        $arraydata = Input::All();
        // 抓取現在的路徑，包含?後面的參數
        $posturi = Request::fullurl();
        $requireddata = Request::all();
        try {
            // 檢查必須傳入的參數格式
            if(!$boolean = $this->checkRequiredData($requireddata)){
                return redirect()->away('error');
            }
            // 檢查SAT時效性
            if(!\App\Library\CommonTools::CheckServiceAccessToken($arraydata['sat'])){
                return redirect()->away('error');
            }
            // 檢查所傳入的參數格式
            if(!$boolean = $this->checkInputData($arraydata)){
                $error = '資料輸入錯誤';
                return View::make('login/changepwd', compact('posturi','error'));
            }
            // 新密碼不能跟舊密碼一樣，新密碼跟新密碼確認要一樣
            if($requireddata['passwordnew'] == $requireddata['passwordold'] || $requireddata['passwordnew'] != $requireddata['passwordnewconfirm']){
                $error = '舊密碼不能與新密碼相同';
                return View::make('login/changepwd', compact('posturi','error'));
            }
            // 透過sat取到md_id
            $jwt = new \App\Services\JWTService;
            $satdata = $jwt->decodeToken($arraydata['sat']);
            $md_id = $satdata['md_id'];
            // 透過md_id取會員資料
            $md_r = new \App\Repositories\MemberDataRepository;
            $mddata = $md_r->GetMemberData($md_id);
            if(count($mddata) == 0 || is_null($mddata)){
                $error = '密碼輸入錯誤';
                return View::make('login/changepwd', compact('posturi','error'));
            }
            if(count($mddata) == 1){
                $mddata = $mddata[0];
            }
            // 檢查傳入的舊密碼是否跟會員資料的密碼一樣
            if($requireddata['passwordold'] != $mddata['md_password']){
                // return redirect()->away('/changepwd?sat='.$arraydata['sat'].'&mur='.$arraydata['mur'])->withInput()->withErrors(['密碼輸入錯誤']);
                $error = '密碼輸入錯誤';
                return View::make('login/changepwd', compact('posturi','error'));
            }
            // 更新會員資料的密碼
            $updatedata['md_id'] = $md_id;
            $updatedata['md_password'] = $requireddata['passwordnew'];
            if(!$md_r->UpdateData($updatedata)){
                $error = '系統發生錯誤，請稍後再試';
                return View::make('login/changepwd', compact('posturi','error'));
            }
            // 建立會員帳號異動紀錄
            if(!\App\Library\CommonTools::writeAccountModifyRecode($md_id,$arraydata['mur'],4,1)){
                $error = '系統發生錯誤，請稍後再試';
                return View::make('login/changepwd', compact('posturi','error'));
            }
            // $redirect_uri = $arraydata['redirect_uri'];
            // return View::make('/changepwdsuccess', compact('redirect_uri'));
            //導到修改密碼成功頁
            $queryString = 'sat=' . $arraydata['sat'];
            // $redirect_uri = \App\Library\CommonTools::appendQueryStringInUri($arraydata['redirect_uri'], $queryString);

            return View::make('login/changepwdsuccess');
            // return redirect('/changepwdsuccess?redirect_uri=' . urlencode($redirect_uri));
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return redirect()->route('error');
        }
    }

    /**
     * 修改密碼成功頁面
     * @param Request $request 前端POST進來的值
     * @return type
     */
    public function success(Request $request) {
        try {
            return View::make('login/changepwdsuccess');
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return redirect()->route('error');
        }
    }

///////////////////////////////////////////////////// check function //////////////////////////////////////////////////////////

    /**
     * 檢查所傳入的參數格式
     * @param  array   $arraydata Controller所接收到的所有參數
     * @return boolean true or false
     */
    private function checkInputData($arraydata) {
        try {

            // if(!\App\Library\CommonTools::CheckRequestArrayValue($arraydata,'redirect_uri',0,false,false)){
            //     return false;
            // }
            if(!\App\Library\CommonTools::CheckRequestArrayValue($arraydata,'sat',0,false,false)){
                return false;
            }
            if(!\App\Library\CommonTools::CheckRequestArrayValue($arraydata,'mur',0,false,false)){
                return false;
            }

            return true;
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return redirect()->route('error');
        }
    }

    /**
     * 檢查必須傳入的參數格式
     * @param  array   $arraydata Controller所接收到的所有參數
     * @return boolean true or false
     */
    private function checkRequiredData($requireddata) {
        try {

            if(!\App\Library\CommonTools::CheckRequestArrayValue($requireddata,'passwordnew',0,false,false)){
                return false;
            }
            if(!\App\Library\CommonTools::CheckRequestArrayValue($requireddata,'passwordnewconfirm',0,false,false)){
                return false;
            }
            if(!\App\Library\CommonTools::CheckRequestArrayValue($requireddata,'passwordold',0,false,false)){
                return false;
            }

            return true;
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return redirect()->route('error');
        }
    }

}
