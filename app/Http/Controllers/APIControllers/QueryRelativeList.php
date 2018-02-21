<?php

namespace App\Http\Controllers\APIControllers;

use Request;

class QueryRelativeList {

    /**
     * 查詢會員親屬資料
     * @param  [string] $modacc [模組帳號]
     * @param  [string] $modvrf [模組驗證碼]
     * @param  [string] $sat    [用戶登入存取憑證]
     */
    public function query_relativelist() {
        $functionName = 'query_relativelist';
        $inputString = Request::all();
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
            if(!\App\Library\CommonTools::checkModuleAccount($inputData['modacc'],$inputData['modvrf'])){
                $messageCode = '999999961';
                throw new \Exception($messageCode);
            }
            // 驗證SAT
            if(!$satData = \App\Library\CommonTools::CheckServiceAccessToken($inputData['sat'])){
                $messageCode = '999999960';
                throw new \Exception($messageCode);
            }
            // 取得親屬資料
            $mb_r = new \App\Repositories\MbTplRelativeRepository;
            $tprData = $mb_r->getDataByMdId($satData['md_id']);
            if(!$tprData || count($tprData) == 0 ){
                $messageCode = '010160001';
                throw new \Exception($messageCode);
            }

            //$messageCode = '010160002';
            $messageCode = '000000000';
            $resultData['relativelist'] = $tprData;
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
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'sat', 0, false, false)) {
            return false;
        }
        return $inputData;
    }

}
