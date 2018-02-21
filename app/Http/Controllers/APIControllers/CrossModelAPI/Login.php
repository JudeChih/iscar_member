<?php

namespace App\Http\Controllers\APIControllers\CrossModelAPI;

use Illuminate\Support\Facades\Input;

/** prelogin	呼叫API取得密碼傳遞保護碼 * */
class Login {

    public function login() {
        $functionName = 'login';
        $inputString = Input::All();
        $inputData = \App\Library\CommonTools::ConvertStringToArray($inputString);
        if (!is_null($inputString) && count($inputString) != 0 && is_array($inputString)) {
            $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        try {
            if ($inputData == null) {
                $messageCode = '999999995';
                throw new \Exception($messageCode);
            }
            if (!$this->CheckInput($inputData)) {
                $messageCode = '999999995';
                throw new \Exception($messageCode);
            }
            if (!$this->CheckModulAccount($inputData['mlr_calleraccount'], $messageCode, $password)) {
                throw new \Exception($messageCode);
            }
            if (!$this->CheckModulPassWord_Salt($inputData['psr_serno'], $password, $inputData['pacontent'], $messageCode)) {
                // $resultData = ['hhhhh' => $messageCode];
                // $messageCode = '000000008';
                throw new \Exception($messageCode);
            }
            if (!$this->InsertModuleLoginR($inputData['mlr_calleraccount'], $mlr_jwt)) {
                $messageCode = '999999988';
                throw new \Exception($messageCode);
            }
            $resultData['api_token'] = $mlr_jwt;
            $messageCode = '000000000';
        } catch (\Exception $e) {
            if (is_null($messageCode)) {
                $messageCode = '999999999';
                \App\Library\CommonTools::writeErrorLogByException($e);
            }
        }
        //回傳值
        $resultArray = \App\Library\CommonTools::ResultProcess($messageCode, $resultData);
        \App\Library\CommonTools::WriteExecuteLog($functionName, $inputString, json_encode($resultArray), $messageCode);
        $result = [$functionName . 'result' => $resultArray];
        return $result;
    }

    /**
     * 檢查輸入值是否正確
     * @param type $value
     * @return boolean
     */
    public function CheckInput(&$value) {
        if ($value == null) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($value, 'mlr_calleraccount', 20, false, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($value, 'pacontent', 0, false, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($value, 'psr_serno', 0, false, false)) {
            return false;
        }
        return true;
    }

    /**
     * 檢查跨模組帳號
     * @param type $account 帳號
     * @param type $messageCode
     * @param type $password 密碼
     * @return boolean
     */
    private function CheckModulAccount($account ,&$messageCode ,&$password) {
        $accpass = new \App\Repositories\ModuleAccPass_rRepository;
        $queryData = $accpass->GetModulUserData($account);
        if (is_null($queryData) || count($queryData) == 0 ) {
            $messageCode = '999999996';
            return false;
        } else if (count($queryData) > 1) {
            $messageCode = '999999986';
            return false;
        }
        $password = $queryData[0]['mapr_modulepassword'];
        return true;
    }
    /**
     * 檢查跨模組sha256(password . salt)
     * @param type $serno 加密序號
     * @param type $password 密碼
     * @param type $pacontent 傳入的加密值
     * @param type $messageCode
     * @return boolean
     */
    private function CheckModulPassWord_Salt($serno, $password, $pacontent, &$messageCode) {
        $wordsalt = new \App\Repositories\PasswordSalt_rRepository;
        $queryData = $wordsalt->GetSaltBySerno($serno);
        if (is_null($queryData) || count($queryData) == 0 ) {
            $messageCode = '040100001';
            return false;
        } else if (count($queryData) > 1) {
            $messageCode = '999999986';
            return false;
        }
        $Hash256_PS = Hash('sha256', $password.$queryData[0]['psr_salt']);
        if ($Hash256_PS != $pacontent) {
            // $messageCode = $Hash256_PS;
            $messageCode = '000000008';
            return false;
        }
        return true;
    }

    /**
     * 建立模組登入紀錄
     * @param type $account 帳號
     * @param type $mlr_jwt 有效的token
     * @return boolean
     */
    private function InsertModuleLoginR($account, &$mlr_jwt) {
        try {
            $modulelogin = new \App\Repositories\ModuleLogin_rRepository;
            //生成記錄編號
            $mlr_id = \App\Library\CommonTools::NewGUIDWithoutDash();
            //生成JWT的時效性
            $datenow = new \Datetime();
            $mlr_expiretime =  $datenow-> modify('+'.\App\Services\JWTService::$JWT_expiretimes) -> format('Y-m-d H:i:s');
            //生成JWToken
            $playload = '{"Verify_code": "'.Hash('sha256', $account.$mlr_id).'","mlr_calleraccount": "'.$account.'","mlr_expiretime":"'.$mlr_expiretime.'"}';

            $mlr_jwt =  \App\Services\JWTService::CreateJwtToken($playload);
            //記錄DB資料
            $insertdata = [
                            'mlr_id'            => $mlr_id ,
                            'mlr_calleraccount' => $account,
                            'mlr_jwt'           => $mlr_jwt,
                            'mlr_expiretime'    => $mlr_expiretime
                          ];
            return $modulelogin->InsertData($insertdata);
        } catch (\Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }
}
