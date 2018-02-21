<?php

namespace App\Http\Controllers\APIControllers;

use Illuminate\Support\Facades\Input;
use Request;

class ModifyMemberData {

    /**
     * 修改會員資料
     * @param  [string] $modacc      [模組帳號]
     * @param  [string] $modvrf      [模組驗證碼]
     * @param  [string] $md_id       [會員代碼]
     */
    public function modify_member_data() {
        $functionName = 'modify_member_data';
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
            if(!$this->checkModuleAccount($inputData['modacc'])){
                $messageCode = '999999911';
                throw new \Exception($messageCode);
            }
            if(!\App\Library\CommonTools::checkModuleAccount($inputData['modacc'],$inputData['modvrf'])){
                $messageCode = '999999922';
                throw new \Exception($messageCode);
            }
            if(!$this->updateMemberData($inputData)){
                $messageCode = '999999933';
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
     * 檢查是誰call的
     * @param string   $modacc [模組帳號]
     * @return boolean
     */
    public function checkModuleAccount($modacc) {

        if ($modacc != "iscar_pm" && $modacc != "iscar_app") {
            return false;
        }
        return true;
    }

    /**
     * 更新會員資料
     * @param array $arraydata [會員資料]
     */
    public function updateMemberData(Array $arraydata,&$messageCode){
        $md_r = new \App\Repositories\MemberDataRepository;
        return $md_r->updateMemberData($arraydata);
    }
}
