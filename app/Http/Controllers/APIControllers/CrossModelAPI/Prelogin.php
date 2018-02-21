<?php

namespace App\Http\Controllers\APIControllers\CrossModelAPI;

use Illuminate\Support\Facades\Input;

/** prelogin	呼叫API取得密碼傳遞保護碼 * */
class Prelogin {

    public function prelogin() {
        $passwordsalt = new \App\Repositories\PasswordSalt_rRepository;
        $functionName = 'prelogin';
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
            //檢查輸入值
            if (!$this->CheckInput($inputData)) {
                $messageCode = '999999995';
                throw new \Exception($messageCode);
            }
            if(!$this->CheckModulAccount($inputData['moduleaccount'], $messageCode)) {
                throw new \Exception($messageCode);
            }
            $querydata = $passwordsalt->GetPassWordSaltData();
            $string_saltkey = $querydata[0]['psr_serno'] . '_' . $querydata[0]['psr_salt'];
            $resultData['prelogin_key'] = base64_encode($string_saltkey);
            $messageCode = '000000000';
        } catch (\Exception $e) {
            if (is_null($messageCode)) {
                $messageCode = '999999999';
                \App\Library\CommonTools::writeErrorLogByException($e);
            }
        }
        //回傳值
        //$resultData['inputString'] =  $inputString;
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
        if (!\App\Library\CommonTools::CheckRequestArrayValue($value, 'moduleaccount', 20, false, false)) {
            return false;
        }
        return true;
    }

    /**
     * 檢查跨模組帳號
     * @param type $account 帳號
     * @param type $messageCode
     * @return boolean
     */
    private function CheckModulAccount($account, &$messageCode) {
        $accpass = new \App\Repositories\ModuleAccPass_rRepository;
        $queryData = $accpass->GetModulUserData($account);
        if (is_null($queryData) || count($queryData) == 0 ) {
            $messageCode = '999999996';
            return false;
        } else if (count($queryData) > 1) {
            $messageCode = '999999986';
            return false;
        }
        return true;
    }





}
