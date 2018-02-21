<?php

namespace App\Http\Controllers\APIControllers;

use Illuminate\Support\Facades\Input;

class VerifyMemberSecCode {

    /**
     * 驗證用戶安全碼
     * @param  [string] $modacc          [模組帳號]
     * @param  [string] $modvrf          [模組驗證碼]
     * @param  [string] $md_id           [會員代碼]
     * @param  [string] $md_securitycode [用戶安全碼] 經過sha256()雜湊處理
     * 錯誤代碼開頭 01019XXXX
     */
    public function verify_memberseccode() {
        $functionName = 'verify_memberseccode';
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
            // 透過傳入的md_id、md_securitycode抓取符合的會員資料
            if(!$memberdata = $this->checkSecCode($inputData['md_id'], $inputData['md_securitycode'])){
                $messageCode = '000000010';
                throw new \Exception($messageCode);
            }
            $messageCode = '000000000';
            // $resultData['account'] = $memberdata['md_account'];
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
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'md_id', 36, false, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'md_securitycode', 0, false, false)) {
            return false;
        }
        return $inputData;
    }

    /**
     * 查找是否有符合的會員
     * @param  [string] $md_id           [會員代碼]
     * @param  [string] $md_securitycode [用戶安全碼]
     */
    public function checkSecCode($md_id , $md_securitycode){
        $md_r = new \App\Repositories\MemberDataRepository;
        try {
            $data = $md_r->checkSecCode($md_id,$md_securitycode);
            // 查無此會員
            if(is_null($data) || count($data) == 0){
              return false;
            }
            // 驗證成功
            if(count($data) == 1){
                $data = $data[0];
                return $data;
            }
            // 未知錯誤
            return false;
        } catch (\Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }
}
