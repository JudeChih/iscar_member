<?php

namespace App\Http\Controllers\APIControllers\Account;

class UserDataCollect {

    /**
     * 更新會員資料
     * @param type $md_id
     * @param type $inputData
     * @return boolean
     */
    public function Update_MemberData($md_id, $inputData) {
        $mamber = new \App\Repositories\MemberDataRepository;
        $querydata = $mamber->GetMemberData($md_id);
        if (is_null($querydata) || count($querydata) == 0) {
            return false;
        }

        $arrayData = $querydata[0];
        unset($arrayData['md_first_login']);
        unset($arrayData['md_last_login']);
        if (\App\Library\CommonTools::CheckArrayValue($inputData, 'md_cname')) {
            $arrayData['md_cname'] = $inputData['md_cname'];
        }
        if (\App\Library\CommonTools::CheckArrayValue($inputData, 'md_tel')) {
            $arrayData['md_tel'] = $inputData['md_tel'];
        }
        if (\App\Library\CommonTools::CheckArrayValue($inputData, 'md_mobile')) {
            $arrayData['md_mobile'] = $inputData['md_mobile'];
        }
        if (\App\Library\CommonTools::CheckArrayValue($inputData, 'md_addr')) {
            $arrayData['md_addr'] = $inputData['md_addr'];
        }
        if (\App\Library\CommonTools::CheckArrayValue($inputData, 'md_contactmail')) {
            $arrayData['md_contactmail'] = $inputData['md_contactmail'];
        }
        if (\App\Library\CommonTools::CheckArrayValue($inputData, 'rl_sn')) {
            $arrayData['rl_sn'] = $inputData['rl_sn'];
        }

        if (\App\Library\CommonTools::CheckArrayValue($inputData, 'ssd_birthday')) {
            $arrayData['ssd_birthday'] = $inputData['ssd_birthday'];
        }
        if (\App\Library\CommonTools::CheckArrayValue($inputData, 'ssd_fbgender')) {
            $arrayData['ssd_fbgender'] = $inputData['ssd_fbgender'];
        }

        return $mamber->UpdateData($arrayData);
    }

    /**
     * 檢查輸入值是否正確
     * @param type $value
     * @return boolean
     */
    public function CheckInput_UserDataCollect($value) {
        if (is_null($value)) {
            return false;
        }
        //檢查欄位長度
        if (
            !\App\Library\CommonTools::CheckRequestArrayValue($value, 'servicetoken', 0, false, false)//
            || !\App\Library\CommonTools::CheckRequestArrayValue($value, 'userdevicecode', 0, false, false)//
            || !\App\Library\CommonTools::CheckRequestArrayValue($value, 'md_cname', 20, false, false)//
            || !\App\Library\CommonTools::CheckRequestArrayValue($value, 'md_tel', 20, true, false)//
            || !\App\Library\CommonTools::CheckRequestArrayValue($value, 'md_mobile', 20, true, false)//
            || !\App\Library\CommonTools::CheckRequestArrayValue($value, 'md_addr', 100, true, false)//
            || !\App\Library\CommonTools::CheckRequestArrayValue($value, 'md_contactmail', 50, true, false)//
            || !\App\Library\CommonTools::CheckRequestArrayValue($value, 'rl_sn', 0, true, false)//
            || !\App\Library\CommonTools::CheckRequestArrayValue($value, 'ssd_birthday', 50, true, false)//
            || !\App\Library\CommonTools::CheckRequestArrayValue($value, 'ssd_fbgender', 1, true, false)//
        ) {
            return false;
        }

        if (!array_key_exists('md_tel', $value)) {
            $value['md_tel'] = '';
        }
        if (!array_key_exists('md_mobile', $value)) {
            $value['md_mobile'] = '';
        }
        if (!array_key_exists('md_addr', $value)) {
            $value['md_addr'] = '';
        }
        if (!array_key_exists('md_contactmail', $value)) {
            $value['md_contactmail'] = '';
        }
        if (!array_key_exists('rl_sn', $value)) {
            $value['rl_sn'] = '';
        }
        if (!array_key_exists('ssd_birthday', $value)) {
            $value['ssd_birthday'] = '';
        }
        if (!array_key_exists('ssd_fbgender', $value)) {
            $value['ssd_fbgender'] = '';
        }


        return true;
    }

}
