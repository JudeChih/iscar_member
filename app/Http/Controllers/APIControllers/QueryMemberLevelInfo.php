<?php
namespace App\Http\Controllers\APIControllers;

use Illuminate\Http\Request;

class QueryMemberLevelInfo {

    /**
     * 會員資料查詢
     * @param  [string] $sat   [用戶服務存取憑證]
     * @param  [string] $mur   [用戶運行環境編號]
     */
    public function query_member_levelinfo(Request $request) {
        $functionName = 'query_member_levelinfo';
        $inputString = $request->all();
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
            $jwt = new \App\Services\JWTService;
            $data = $jwt->decodeToken($inputData['sat']);
            // 判斷SAT裡面的mur_id跟傳入的mur_id是否相同
            if($data['mur_id'] != $inputData['mur']){
                $messageCode = '999999992';
                throw new \Exception($messageCode);
            }
            // 驗證SAT
            if(!$satdata = \App\Library\CommonTools::checkServiceAccessToken($inputData['sat'])){
                $messageCode = '999999992';
                throw new \Exception($messageCode);
            }
            // 檢查md_id存在性
            if(!$memberdata = $this->checkMdID($satdata->md_id , $messageCode)){
                if(!isset($messageCode)){
                    $messageCode = '999999980';
                }
                throw new \Exception($messageCode);
            }
            $messageCode = '000000000';
            $resultData['mcls_gradename'] = $memberdata['mcls_gradename'];
            $resultData['mcls_gradeicon'] = $memberdata['mcls_gradeicon'];
            $resultData['mcls_levelweight'] = $memberdata['mcls_levelweight'];
            $resultData['mcls_nextlevelexp'] = $memberdata['mcls_nextlevelexp'];
            $resultData['mcls_functioncontent'] = $memberdata['mcls_functioncontent'];
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
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'sat', 0, false, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'mur', 0, false, false)) {
            return false;
        }
        return $inputData;
    }

    public function checkMdID($md_id , &$messageCode){
        $memberdata_r = new \App\Repositories\MemberDataRepository;
        try {
            $data = $memberdata_r->GetMemberLevelData($md_id);
            if(is_null($data) || count($data) == 0){
              $messageCode = "011204002";
              return false;
            }
            if(count($data) > 1){
              $messageCode = "999999988";
              $message = "資料庫錯誤，請檢查。";

              // 此推播目前暫時無效
              \App\Library\CommonTools::pushnotification($md_id,$message);
              return false;
            }
            if(count($data) == 1){
                $data = $data[0];
            }
            return $data;
        } catch (\Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }
}
