<?php

namespace App\Http\Controllers\APIControllers;

use Illuminate\Support\Facades\Input;

class VerifyApiFrom {

    /**
     * 驗證跨模無SAT時,呼叫方有效性
     * @param  [string] $modacc      [模組帳號]
     * @param  [string] $modvrf       [模組驗證碼]
     * @param  [string] $from_modacc [呼叫方 模組帳號]
     * @param  [string] $from_modvrf  [呼叫方 模組驗證碼]
     * @return [type] [description]
     */
    public function verify_apifrom() {
        $functionName = 'verify_apifrom';
        $inputString = Input::All();
        if (!is_null($inputString) && count($inputString) != 0 && is_array($inputString)) {
            $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        try {
            // 轉換成陣列並檢查
            $inputData = $this->convertAndCheckApiInput($inputString);
            if (is_null($inputData)) {
                $messageCode = '999999995';
                throw new \Exception($messageCode);
            }
            // 模組身分驗證
            if(!\App\Library\CommonTools::checkModuleAccount($inputData['modacc'],$inputData['modvrf'])){
                $messageCode = '999999961';
                throw new \Exception($messageCode);
            }
            // 呼叫方 模組身分驗證
            if(!\App\Library\CommonTools::checkModuleAccount($inputData['from_modacc'],$inputData['from_modvrf'])){
                $messageCode = '010120001';
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
    public function convertAndCheckApiInput($inputString) {
        $inputData = \App\Library\CommonTools::ConvertStringToArray($inputString);

        if ($inputData == null) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'modacc', 20, false, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'modvrf', 0, false, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'from_modacc', 20, false, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'from_modvrf', 0, false, false)) {
            return false;
        }
        return $inputData;
    }
}
