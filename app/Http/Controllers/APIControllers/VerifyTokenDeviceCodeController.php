<?php

namespace App\Http\Controllers\APIControllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;

class VerifyTokenDeviceCodeController extends Controller {

    function VerifyTokenDeviceCode() {
        $functionName = 'verifytokendevicecode';
        $inputString = Input::All();
        $inputData = \App\Library\CommonTools::ConvertStringToArray($inputString);
        if (!is_null($inputString) && count($inputString) != 0 && is_array($inputString)) {
            $inputString = $inputString[0];
        }
        $resultString;
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
             //檢查Token、DeviceCode
            if(!\App\Library\CommonTools::CheckAccessTokenDeviceCode($inputData['servicetoken'], $inputData['userdevicecode'], $md_id, $messageCode)) {
               throw new \Exception($messageCode);
            }
            $resultData = ["md_id" => $md_id];
            $messageCode = '000000000';
        } catch (\Exception $e) {
            if ($messageCode == null) {
                \App\Library\CommonTools::writeErrorLogByException($e);
                $messageCode = '999999999';
            }
        }
        //回傳值
        $resultArray = \App\Library\CommonTools::ResultProcess($messageCode, $resultData);
        \App\Library\CommonTools::WriteExecuteLog($functionName, $inputString, json_encode($resultArray), $messageCode);
        $result = [ $functionName . 'result' => $resultArray ];
        return $result;
      
    }
    
     /**
     * 檢查輸入值是否正確
     * @param type $value
     * @return boolean
     */
    public static function CheckInput(&$value) {
        if ($value == null) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($value, 'servicetoken', 0, false, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($value, 'userdevicecode', 0, false, false)) {
            return false;
        }
        return true;
    }
}
