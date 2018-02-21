<?php

namespace App\Http\Controllers\APIControllers;

use Request;
use Illuminate\Support\Facades\DB;

class ModifyRelative {

    /**
     * 異動會員親屬資料
     * @param  [string] $modacc           [模組帳號]
     * @param  [string] $modvrf           [模組驗證碼]
     * @param  [string] $sat              [用戶登入存取憑證]
     * @param  [string] $modify           [１：新增、２：修改、３：刪除]
     * @param  [string] $tpr_serno        [親屬編號][$modify = 1 & 2(值為字串)，$modify = 3(值為jsonArray的字串)]
     * @param  [string] $tpr_title        [親屬稱謂]
     * @param  [string] $tpr_name         [親屬姓名]
     * @param  [string] $tpr_birthday     [親屬生日]
     * @param  [int]    $tpr_birthdaytime [親屬時辰][0~12]
     * @param  [string] $rl_city_code     [親屬縣市]
     * @param  [string] $rl_zip           [親屬區域]
     * @param  [string] $tpr_address      [親屬地址]
     */
    public function modify_relative() {
        $functionName = 'modify_relative';
        $inputString = Request::all();
        if (!is_null($inputString) && count($inputString) != 0 && is_array($inputString)) {
            $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;

        try {
            // 轉換成陣列並檢查
            $inputData = $this->convertAndCheckApiInput($inputString);
            // \App\Library\CommonTools::writeErrorLogByMessage(json_encode($inputData));
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
            // 整理傳入的親屬需異動的資料
            // 必要傳入的資料
            $inputData['md_id'] = $satData['md_id'];
            // 檢查親屬代碼不能為99，不可以新增自己
            if(isset($inputData['tpr_title'])){
                if($inputData['tpr_title'] == 99){
                    $messageCode = '010170003'; // 不能新增自己為親屬
                    throw new \Exception($messageCode);
                }
            }
            // 根據$inputData['modify']的值傳入不一樣的資料
            if($inputData['modify'] == 1){ // 新增

                $relativelist = $this->changeDefaultValue($inputData);

                // 檢查要新增的親屬，是否已經建檔
                // $mb_r = new \App\Repositories\MbTplRelativeRepository;
                // $data = $mb_r->getDataByNameAndMdId($relativelist['tpr_name'],$relativelist['md_id']);
                // \App\Library\CommonTools::writeErrorLogByMessage(json_encode($data));
                // if(count($data) >= 1){
                //     $messageCode = '010170002';
                //     throw new \Exception($messageCode);
                // }
            }
            if($inputData['modify'] == 2){ // 修改
                $relativelist = $this->changeDefaultValue($inputData);
                // 檢查要新增的親屬，是否已經建檔
                // $mb_r = new \App\Repositories\MbTplRelativeRepository;
                // $data = $mb_r->getDataByNameAndMdId($relativelist['tpr_name'],$relativelist['md_id']);
                // \App\Library\CommonTools::writeErrorLogByMessage(json_encode($data));
                // if(count($data) >= 1){
                //     $messageCode = '010170002';
                //     throw new \Exception($messageCode);
                // }
            }
            if($inputData['modify'] == 3){ // 刪除
                // 將字串轉成陣列
                $relativelist['tpr_serno'] = json_decode($inputData['tpr_serno'],true);
                $relativelist['modify'] = $inputData['modify'];
                $relativelist['md_id'] = $inputData['md_id'];
            }
            // 異動親屬資料
            if(!$this->modifyRelativeData($relativelist,$messageCode)){
                if(!isset($messageCode)){
                    $messageCode = '010170001';
                }
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
     * 沒有值就設定預設值
     * @param  [type] $inputData [description]
     */
    public function changeDefaultValue($inputData){

        try {
            $relativelist['md_id'] = $inputData['md_id'];
            $relativelist['modify'] = $inputData['modify'];
            if($inputData['modify'] == 2){
                $relativelist['tpr_serno'] = $inputData['tpr_serno'];
            }
            if(isset($inputData['tpr_title']) && $inputData['tpr_title'] != ''){
                $relativelist['tpr_title'] = $inputData['tpr_title'];
            }else{
                $relativelist['tpr_title'] = 1;
            }
            if(isset($inputData['tpr_name']) && $inputData['tpr_name'] != ''){
                $relativelist['tpr_name'] = $inputData['tpr_name'];
            }else{
                $relativelist['tpr_name'] = '王大明';
            }
            if(isset($inputData['tpr_birthday']) && $inputData['tpr_birthday'] != ''){
                $relativelist['tpr_birthday'] = $inputData['tpr_birthday'];
            }else{
                $relativelist['tpr_birthday'] = \Carbon\Carbon::now()->toDateString();
            }
            if(isset($inputData['rl_city_code']) && $inputData['rl_city_code'] != ''){
                $relativelist['rl_city_code'] = $inputData['rl_city_code'];
            }else{
                $relativelist['rl_city_code'] = '1';
            }
            if(isset($inputData['rl_zip']) && $inputData['rl_zip'] != ''){
                $relativelist['rl_zip'] = $inputData['rl_zip'];
            }else{
                $relativelist['rl_zip'] = '104';
            }
            if(isset($inputData['tpr_address']) && $inputData['tpr_address'] != ''){
                $relativelist['tpr_address'] = $inputData['tpr_address'];
            }else{
                $relativelist['tpr_address'] = '八德路二段260號2樓';
            }
            if(isset($inputData['tpr_birthdaytime']) && $inputData['tpr_birthdaytime'] != ''){
                $relativelist['tpr_birthdaytime'] = $inputData['tpr_birthdaytime'];
            }else{
                $relativelist['tpr_birthdaytime'] = '0';
            }
            return $relativelist;
        } catch (Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return false;
        }
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
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'modify', 1, false, false)) {
            return false;
        }
        // 判斷modify的代號，檢查所需要有的欄位
        if($inputData['modify'] == 1){ // 新增
            if(!isset($inputData['tpr_title']) || !isset($inputData['tpr_name']) || !isset($inputData['tpr_birthday']) || !isset($inputData['tpr_birthdaytime']) || !isset($inputData['rl_city_code']) || !isset($inputData['rl_zip']) || !isset($inputData['tpr_address'])){
                return false;
            }
        }
        if($inputData['modify'] == 2){ // 修改
            if(!isset($inputData['tpr_serno']) || !isset($inputData['tpr_title']) || !isset($inputData['tpr_name']) || !isset($inputData['tpr_birthday']) || !isset($inputData['tpr_birthdaytime']) || !isset($inputData['rl_city_code']) || !isset($inputData['rl_zip']) || !isset($inputData['tpr_address'])){
                return false;
            }
        }
        if($inputData['modify'] == 3){ // 刪除
            if(!isset($inputData['tpr_serno'])){
                return false;
            }
        }
        return $inputData;
    }


    public function modifyRelativeData($relativelist,&$messageCode){
        $mb_r = new \App\Repositories\MbTplRelativeRepository;
        $result = false;
        try {
            // 判斷所有傳入的欄位的值的限制是否符合規定
            if(isset($relativelist['md_id'])){
                if (!\App\Library\CommonTools::CheckRequestArrayValue($relativelist, 'md_id', 32, false, false)) {
                    return false;
                }
            }
            if(isset($relativelist['modify'])){
                if (!\App\Library\CommonTools::CheckRequestArrayValue($relativelist, 'modify', 1, false, false)) {
                    return false;
                }
            }
            if(isset($relativelist['tpr_serno'])){
                if($relativelist['modify'] != 3){
                    if (!\App\Library\CommonTools::CheckRequestArrayValue($relativelist, 'tpr_serno', 0, false, false)) {
                        return false;
                    }
                }
                // 刪除功能，是傳入陣列，需要如此判斷
                if($relativelist['modify'] == 3){
                    foreach ($relativelist['tpr_serno'] as $k => $v) {
                        if (!\App\Library\CommonTools::CheckRequestArrayValue($relativelist['tpr_serno'][$k], 'tpr_serno', 0, false, false)) {
                            return false;
                        }
                    }
                }
            }
            if(isset($relativelist['tpr_title'])){
                if (!\App\Library\CommonTools::CheckRequestArrayValue($relativelist, 'tpr_title', 0, false, false)) {
                    return false;
                }
            }
            if(isset($relativelist['tpr_name'])){
                if (!\App\Library\CommonTools::CheckRequestArrayValue($relativelist, 'tpr_name', 50, false, false)) {
                    return false;
                }
            }
            if(isset($relativelist['tpr_birthday'])){
                if (!\App\Library\CommonTools::CheckRequestArrayValue($relativelist, 'tpr_birthday', 10, false, false)) {
                    return false;
                }
            }
            if(isset($relativelist['tpr_birthdaytime'])){
                if (!\App\Library\CommonTools::CheckRequestArrayValue($relativelist, 'tpr_birthdaytime', 2, false, false)) {
                    return false;
                }
            }
            if(isset($relativelist['rl_city_code'])){
                if (!\App\Library\CommonTools::CheckRequestArrayValue($relativelist, 'rl_city_code', 3, false, false)) {
                    return false;
                }
            }
            if(isset($relativelist['rl_zip'])){
                if (!\App\Library\CommonTools::CheckRequestArrayValue($relativelist, 'rl_zip', 3, false, false)) {
                    return false;
                }
            }
            if(isset($relativelist['tpr_address'])){
                if (!\App\Library\CommonTools::CheckRequestArrayValue($relativelist, 'tpr_address', 200, false, false)) {
                    return false;
                }
            }
            DB::beginTransaction();

            // 新增
            if($relativelist['modify'] == 1){
                $result = $mb_r->insertData($relativelist);
            }
            // 修改
            if($relativelist['modify'] == 2){
                $result = $mb_r->updateData($relativelist);
            }
            // 刪除
            if($relativelist['modify'] == 3){
                foreach ($relativelist['tpr_serno'] as $k => $v) {
                    $result = $mb_r->deleteDataByTprSerno($relativelist['md_id'],$v);
                    if(!$result){
                        DB::rollBack();
                        break;
                    }
                }
            }

            if($result){
                DB::commit();
                return true;
            }
            DB::rollBack();
            return false;
        } catch (Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return false;
        }
    }
}
