<?php

namespace App\Http\Controllers\APIControllers;

use Illuminate\Support\Facades\Input;
use Request;

class QuerySalt {

    /**
     * 驗證跨模無SAT時,呼叫方有效性
     * @param  [string] $modacc      [模組帳號]
     */
    public function query_salt() {
        $functionName = 'query_salt';
        $inputString = Input::All();
        if (!is_null($inputString) && count($inputString) != 0 && is_array($inputString)) {
            $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        try {
            // 轉換成陣列並檢查
            $inputData = $this->convertAndCheckApiInput($inputString);
            if (!is_array($inputData)) {
                $messageCode = '999999995';
                throw new \Exception($messageCode);
            }
            // 模組身分驗證
            if(!\App\Library\CommonTools::checkModuleAccount($inputData['modacc'])){
                $messageCode = '999999961';
                throw new \Exception($messageCode);
            }
            $pws_r = new \App\Repositories\PasswordSalt_rRepository;
            $data = $pws_r->getNewestData();
            if(is_null($data) || count($data) == 0){
                $messageCode = '010120001';
                throw new \Exception($messageCode);
            }
            $messageCode = '000000000';
            $resultData['salt'] = base64_encode($data->psr_serno."_".$data->psr_salt);
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
        return $inputData;
    }
}
