<?php

namespace App\Http\Controllers\APIControllers;

use Illuminate\Support\Facades\Input;

class ModifyMemberSecCode {

    /**
     * 修改用戶安全碼
     * @param  [string] $modacc              [模組帳號]
     * @param  [string] $modvrf              [模組驗證碼]
     * @param  [string] $md_id               [會員代碼]
     * @param  [string] $old_md_securitycode [舊用戶安全碼]
     * @param  [string] $new_md_securitycode [新用戶安全碼] 如果$old_md_securitycode有值就表示要修改，如果沒有就表示要新增
     * 錯誤代碼開頭 01020XXXX
     */
    public function modify_memberseccode() {
        $functionName = 'modify_memberseccode';
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
            if(is_null($inputData['old_md_securitycode']) || $inputData['old_md_securitycode'] == ''){
                $md_r = new \App\Repositories\MemberDataRepository;
                $memberdata = $md_r->getDataByMdId($inputData['md_id']);
                if(count($memberdata) == 1){
                    $memberdata = $memberdata[0];
                }else{
                    // 會員資料異常
                    $messageCode = '010200001';
                    throw new \Exception($messageCode);
                }
                if(!is_null($memberdata['md_securitycode'])){
                    // 會員已設定用戶安全碼
                    $messageCode = '010200002';
                    throw new \Exception($messageCode);
                }
            }else{
                $md_r = new \App\Repositories\MemberDataRepository;
                if(!$memberdata = $md_r->checkSecCode($inputData['md_id'],$inputData['old_md_securitycode'])){
                    // 用戶安全碼錯誤
                    $messageCode = '000000010';
                    throw new \Exception($messageCode);
                }
                if(count($memberdata) == 1){
                    $memberdata = $memberdata[0];
                }elseif(count($memberdata) == 0){
                    // 用戶安全碼錯誤
                    $messageCode = '000000010';
                    throw new \Exception($messageCode);
                }else{
                    // 會員資料異常
                    $messageCode = '010200001';
                    throw new \Exception($messageCode);
                }
                if($inputData['old_md_securitycode'] == $inputData['new_md_securitycode']){
                    // 新舊用戶安全碼相同
                    $messageCode = '010200003';
                    throw new \Exception($messageCode);
                }
            }
            // 新增或修改用戶安全碼
            if(!$this->updateSecCode($inputData['md_id'],$inputData['new_md_securitycode'])){
                // 用戶安全碼更新失敗
                $messageCode = '010200004';
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
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'new_md_securitycode', 0, false, false)) {
            return false;
        }
        return $inputData;
    }

    /**
     * 新增或修改用戶安全碼
     * @param  [string] $md_id    [會員代碼]
     * @param  [string] $new_code [新用戶安全碼]
     */
    public function updateSecCode($md_id,$new_code){
        $md_r = new \App\Repositories\MemberDataRepository;
        try {
            if(!$md_r->updateSecCode($md_id,$new_code)){
                return false;
            }
            return true;
        } catch (Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
        }
    }
}