<?php

namespace App\Http\Controllers\APIControllers\CrossModelAPI;

use Illuminate\Support\Facades\Input;

/** prelogin	呼叫API取得密碼傳遞保護碼 * */
class VerifyApiToken {

    public function verifyapitoken() {
        $functionName = 'verifyapitoken';
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
            if( !\App\Services\JWTService::JWTokenVerification($inputData['api_token'], $messageCode)) {
                throw new \Exception($messageCode);
            }
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
        if (!\App\Library\CommonTools::CheckRequestArrayValue($value, 'api_token', 0, false, false)) {
            return false;
        }

        return true;
    }


}
