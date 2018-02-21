<?php

namespace App\Http\Controllers\APIControllers;

use Illuminate\Support\Facades\Input;
use Request;

class ModifyAdvanceData {

    /**
     * 修改會員資料
     * @param  [string] $modacc  [模組帳號]
     * @param  [string] $modvrf  [模組驗證碼]
     * @param  [string] $sat     [用戶服務存取憑證]
     */
    public function modify_advancedata() {
        $functionName = 'modify_advancedata';
        $inputString = Input::All();
        if (!is_null($inputString) && count($inputString) != 0 && is_array($inputString)) {
            $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        $md_r = new \App\Repositories\MemberDataRepository;
        try {
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
            if(!$satdata = \App\Library\CommonTools::checkServiceAccessToken($inputData['sat'])){
                $messageCode = '999999992';
                throw new \Exception($messageCode);
            }
            // 檢查手機是否已經被使用
            // $mobile = $md_r->getDataByMdIdMdMobile($satdata['md_id'],$inputData['md_mobile']);
            // if(count($mobile) > 0){
            //     $messageCode = '999999955';
            //     throw new \Exception($messageCode);
            // }
            // 檢查信箱是否已經被使用
            $contactmail = $md_r->getDataByMdIdMdContactMail($satdata['md_id'],$inputData['md_contactmail']);
            if(count($contactmail) > 0){
                $messageCode = '999999966';
                throw new \Exception($messageCode);
            }
            // 檢查身分證字號是否已經被使用
            $identitycard = $md_r->getDataByMdIdMdIdentityCard($satdata['md_id'],$inputData['md_identitycard']);
            if(count($identitycard) > 0){
                $messageCode = '010190001';
                throw new \Exception($messageCode);
            }
            // 檢查身分證字號是否真實
            if(!\App\Library\CommonTools::strIsSpecial($inputData['md_identitycard'])){
                if(!\App\Library\CommonTools::id_card($inputData['md_identitycard'])){
                    $messageCode = '010190002';
                    throw new \Exception($messageCode);
                }
            }
            $inputData['md_id'] = $satdata['md_id'];
            // 更新會員資料
            if(!$this->updateMemberData($inputData)){
                $messageCode = '999999933';
                throw new \Exception($messageCode);
            }
            // 同步更新親屬資料
            if(!\App\Library\CommonTools::synchronizeTempleRelative($inputData['md_id'])){
                $messageCode = '999999944';
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
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'md_firstname', 15, true, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'md_lastname', 15, true, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'md_cname', 20, true, true)) {
            return false;
        }
        return $inputData;
    }

    /**
     * 更新會員資料
     * @param array $arraydata [會員資料]
     */
    public function updateMemberData(Array $arraydata){
        $md_r = new \App\Repositories\MemberDataRepository;
        try {
            $savedata = $this->changeDefaultValue($arraydata);
            // if(isset($arraydata['md_picturepath'])){
            //     $savedata['md_picturepath'] = $arraydata['md_picturepath'];
            // }else{
            //     return false;
            // }
            return $md_r->updateAdvanceData($savedata);
        } catch (Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

    /**
     * 沒有值就設定預設值
     * @param  [type] $arraydata [description]
     */
    public function changeDefaultValue($arraydata){
        $md_r = new \App\Repositories\MemberDataRepository;
        try {
            $savedata['md_id'] = $arraydata['md_id'];
            if(isset($arraydata['md_mobile']) && $arraydata['md_mobile'] != ''){
                $savedata['md_mobile'] = $arraydata['md_mobile'];
            }
            // else{
            //     $savedata['md_mobile'] = '0988888888';
            // }
            if(isset($arraydata['md_cname']) && $arraydata['md_cname'] != ''){
                $savedata['md_cname'] = $arraydata['md_cname'];
            }else{
                // $savedata['md_cname'] = '王大明';
            }
            if(isset($arraydata['md_contactmail']) && $arraydata['md_contactmail'] != ''){
                $savedata['md_contactmail'] = $arraydata['md_contactmail'];
            }
            if(isset($arraydata['md_identitycard'])){
                if($arraydata['md_identitycard'] != '' && !\App\Library\CommonTools::strIsSpecial($arraydata['md_identitycard'])){
                    $savedata['md_identitycard'] = $arraydata['md_identitycard'];
                }
                // elseif(\App\Library\CommonTools::strIsSpecial($arraydata['md_identitycard'])){

                // }else{
                //     // $savedata['md_identitycard'] = 'A000000000';
                // }
            }
            if(isset($arraydata['md_fbgender'])){
                if($arraydata['md_fbgender'] == 'male' || $arraydata['md_fbgender'] == 'female'){
                    $savedata['md_fbgender'] = $arraydata['md_fbgender'];
                }else{
                    // $savedata['md_fbgender'] = 'male';
                }
            }else{
                // $savedata['md_fbgender'] = 'male';
            }
            // else{
            //     $result = $md_r->getDataByMdId($arraydata['md_id']);
            //     if(count($result) == 1){
            //         $result = $result[0];
            //     }
            //     if(is_null($result['md_account'])){
            //         $savedata['md_contactmail'] = 'example@iscarmg.com';
            //     }else{
            //         $savedata['md_contactmail'] = $result['md_account'];
            //     }
            // }
            if(isset($arraydata['rl_city_code']) && $arraydata['rl_city_code'] != ''){
                $savedata['rl_city_code'] = $arraydata['rl_city_code'];
            }else{
                // $savedata['rl_city_code'] = 1;
            }
            if(isset($arraydata['rl_zip']) && $arraydata['rl_zip'] != ''){
                $savedata['rl_zip'] = $arraydata['rl_zip'];
            }else{
                // $savedata['rl_zip'] = 104;
            }
            if(isset($arraydata['md_birthday']) && $arraydata['md_birthday'] != ''){
                $savedata['md_birthday'] = $arraydata['md_birthday'];
            }else{
                // $savedata['md_birthday'] = \Carbon\Carbon::now()->toDateString();
            }
            if(isset($arraydata['md_addr']) && $arraydata['md_addr'] != ''){
                $savedata['md_addr'] = $arraydata['md_addr'];
            }else{
                // $savedata['md_addr'] = '八德路二段260號二樓';
            }
            if(isset($arraydata['md_firstname']) && $arraydata['md_firstname'] != ''){
                $savedata['md_firstname'] = $arraydata['md_firstname'];
            }else{
                // $savedata['md_firstname'] = '大明';
            }
            if(isset($arraydata['md_lastname']) && $arraydata['md_lastname'] != ''){
                $savedata['md_lastname'] = $arraydata['md_lastname'];
            }else{
                // $savedata['md_lastname'] = '王';
            }
            if(isset($arraydata['md_tel'])){
                $savedata['md_tel'] = $arraydata['md_tel'];
            }else{
                // $savedata['md_tel'] = '0888888888';
            }
            return $savedata;
        } catch (Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return false;
        }
    }
}
