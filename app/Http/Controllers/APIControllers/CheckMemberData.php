<?php

namespace App\Http\Controllers\APIControllers;

use Illuminate\Support\Facades\Input;
use Request;

class CheckMemberData {

    /**
     * 檢查會員是否存在
     * @param  [string] $modacc      [模組帳號]
     * @param  [string] $modvrf      [模組驗證碼]
     * @param  [string] $md_id       [會員代碼]
     */
    public function check_member_data() {
        $functionName = 'check_member_data';
        $inputString = Input::All();
        if (!is_null($inputString) && count($inputString) != 0 && is_array($inputString)) {
            $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        try {
            $inputData = $this->convertAndCheckApiInput($inputString);
            if (!is_array($inputData)) {
                $messageCode = '999999995';
                throw new \Exception($messageCode);
            }
            if(!\App\Library\CommonTools::checkModuleAccount($inputData['modacc'],$inputData['modvrf'])){
                $messageCode = '999999961';
                throw new \Exception($messageCode);
            }
            if(!$this->checkMemberData($inputData['md_id'])){
                $messageCode = '010200001';
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
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'md_id', 36, false, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'modacc', 20, false, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'modvrf', 0, false, false)) {
            return false;
        }
        return $inputData;
    }

    /**
     * 檢查有無此會員
     * @param array $md_id [會員代碼]
     */
    public function checkMemberData($md_id){
        $md_r = new \App\Repositories\MemberDataRepository;
        if(!$memberdata = $md_r->checkMemberData($md_id)){
            return false;
        }
        if(count($memberdata) != 1){
            return false;
        }
        return true;
    }
}
