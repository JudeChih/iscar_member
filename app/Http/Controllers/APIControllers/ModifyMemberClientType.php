<?php

namespace App\Http\Controllers\APIControllers;

use Illuminate\Support\Facades\Input;

class ModifyMemberClientType {

    /**
     * 更新用戶端運行類別
     * @param  [string] $modacc      [模組帳號]
     * @param  [string] $modvrf      [模組驗證碼]
     * @param  [string] $sat         [用戶登入存取憑證]
     * @param  [string] $md_id       [欲成為商家用戶的IsCar會員代碼][可填可不填]
     * @param  [string] $clienttype  [ 1:商家用戶 3:品牌用戶]
     */
    public function modify_member_clienttype() {
        $functionName = 'modify_member_clienttype';
        $inputString = Input::All();
        if (!is_null($inputString) && count($inputString) != 0 && is_array($inputString)) {
            $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        $md_id = null;
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
            // 檢查SAT
            if(!$satData = \App\Library\CommonTools::CheckServiceAccessToken($inputData['sat'])){
                $messageCode = '010110002';
                throw new \Exception($messageCode);
            }
            // 更新會員資料的clienttype
            $md_r = new \App\Repositories\MemberDataRepository;
            if(!strlen($inputData['md_id']) == 0){
                $md_id = $inputData['md_id'];
            }else{
                $md_id = $satData['md_id'];
            }
            if(!$md_r->UpdateData_ClientType($md_id,$inputData['clienttype'])){
                $messageCode = '999999998';
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
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'sat', 0, false, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'clienttype',3, false, false)) {
            return false;
        }
        return $inputData;
    }
}
