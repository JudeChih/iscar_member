<?php

namespace App\Http\Controllers\APIControllers\Account;


class DUserBindFB {

    /**
     * 
     * @param type $md_id
     * @param type $messageCode
     * @return boolean
     */
    public function Check_MemberData($md_id, &$messageCode) {
        $member = new \App\Repositories\MemberDataRepository;
        $querydata = $member->GetData($md_id);

        if (is_null($querydata) || count($querydata) == 0) {
            //010102001	查無使用者帳號記錄，請確認是否已完成註冊
            $messageCode = '010102001';
            return false;
        }

        if (count($querydata) > 1) {
            //010102002	使用者帳號記錄大於一筆，請聯絡isCar進行處理
            $messageCode = '010102002';
            return false;
        }

        if ($querydata[0]['md_logintype'] != 2) {
            //010102003	該帳號非單機用戶，無法進行綁定
            $messageCode = '010102003';
            return false;
        }

        return true;
    }

    /**
     * 檢查Facebook帳號
     * @param type $md_id
     * @param type $ssd_accountid
     * @param type $ssd_accesstoken
     * @param type $messageCode
     * @return boolean
     */
    public function Check_Facebook($md_id, $ssd_accountid, $ssd_accesstoken, &$messageCode) {
    
        $member = new \App\Repositories\MemberDataRepository;
        //檢查「FaceBook」帳號資料
        $acc_id = \App\Library\CommonTools::GetFacebookAccountID($ssd_accesstoken);

        if ($acc_id == null || $acc_id != $ssd_accountid) {
            $messageCode = '010101001';
            return false;
        }
        //檢查 「$ssd_accountid」
        $querydata = $member->GetDataByAccountID($ssd_accountid);

        if (is_null($querydata) || count($querydata) == 0) {
            //查無資料
            return true;
        }

        if ($querydata[0]['md_id'] == $md_id) {
            //010102004	本帳號已綁定完成，無需重新綁定，請改用FB登入
            $messageCode = '010102004';
            return false;
        } else {
            //010102005	該FB帳號使用，請選用其他帳號進行綁定
            $messageCode = '010102005';
            return false;
        }
        return true;
    }

    /**
     * 更新會員資料
     * @param type $md_id
     * @param type $inputData
     * @return boolean
     */
    public function Update_MemberData($md_id, $inputData) {
        $member = new \App\Repositories\MemberDataRepository;
        $querydata = $member->GetMemberData($md_id);
        if (is_null($querydata) || count($querydata) == 0) {
            //查無資料
            return false;
        }

        $querydata[0]['ssd_accountid'] = $inputData['ssd_accountid'];
        $querydata[0]['ssd_accountmail'] = $inputData['ssd_accountmail'];
        $querydata[0]['ssd_accountname'] = $inputData['ssd_accountname'];
        $querydata[0]['ssd_fbfirstname'] = $inputData['ssd_fbfirstname'];
        $querydata[0]['ssd_fblastname'] = $inputData['ssd_fblastname'];
        $querydata[0]['ssd_fblocallanguage'] = $inputData['ssd_fblocallanguage'];
        $querydata[0]['ssd_fbgender'] = $inputData['ssd_fbgender'];
        $querydata[0]['ssd_birthday'] = $inputData['ssd_birthday'];
        $querydata[0]['ssd_timezone'] = $inputData['ssd_timezone'];
        $querydata[0]['ssd_picturepath'] = $inputData['ssd_picturepath'];
        $querydata[0]['ssd_accesstoken'] = $inputData['ssd_accesstoken'];

        $querydata[0]['md_logintype'] = '0';

        return $member->UpdateData($querydata[0]);
    }

    /**
     * 檢查輸入值是否正確
     * @param type $value
     * @return boolean
     */
    public function CheckRequest_DUserBindFB(&$value) {
    
        if (is_null($value)) {
            return false;
        }
//檢查欄位長度
        if (
                !\App\Library\CommonTools::CheckRequestArrayValue($value, 'servicetoken', 0, false, false)//
                || !\App\Library\CommonTools::CheckRequestArrayValue($value, 'userdevicecode', 0, false, false)//
                || !\App\Library\CommonTools::CheckRequestArrayValue($value, 'ssd_accountid', 0, true, false)//
                || !\App\Library\CommonTools::CheckRequestArrayValue($value, 'ssd_accountmail', 60, true, false)//
                || !\App\Library\CommonTools::CheckRequestArrayValue($value, 'ssd_accountname', 50, true, true)//
                || !\App\Library\CommonTools::CheckRequestArrayValue($value, 'ssd_fbfirstname', 50, true, true)//
                || !\App\Library\CommonTools::CheckRequestArrayValue($value, 'ssd_fblastname', 20, true, true)//
                || !\App\Library\CommonTools::CheckRequestArrayValue($value, 'ssd_fblocallanguage', 20, true, true)//
                || !\App\Library\CommonTools::CheckRequestArrayValue($value, 'ssd_fbgender', 20, true, true)//
                || !\App\Library\CommonTools::CheckRequestArrayValue($value, 'ssd_birthday', 20, true, true)//
                || !\App\Library\CommonTools::CheckRequestArrayValue($value, 'ssd_timezone', 20, true, true)//
                || !\App\Library\CommonTools::CheckRequestArrayValue($value, 'ssd_picturepath', 0, true, true)//
                || !\App\Library\CommonTools::CheckRequestArrayValue($value, 'ssd_accesstoken', 0, true, true)//
        ) {
            return false;
        }

        if (!array_key_exists('ssd_accountid', $value)) {
            $value['ssd_accountid'] = '';
        }
        if (!array_key_exists('ssd_accountmail', $value)) {
            $value['ssd_accountmail'] = '';
        }
        if (!array_key_exists('ssd_accountname', $value)) {
            $value['ssd_accountname'] = '';
        } if (!array_key_exists('ssd_fbfirstname', $value)) {
            $value['ssd_fbfirstname'] = '';
        } if (!array_key_exists('ssd_fblastname', $value)) {
            $value['ssd_fblastname'] = '';
        } if (!array_key_exists('ssd_fblocallanguage', $value)) {
            $value['ssd_fblocallanguage'] = '';
        } if (!array_key_exists('ssd_fbgender', $value)) {
            $value['ssd_fbgender'] = '';
        } if (!array_key_exists('ssd_birthday', $value)) {
            $value['ssd_birthday'] = '';
        } if (!array_key_exists('ssd_timezone', $value)) {
            $value['ssd_timezone'] = '';
        }
        if (!array_key_exists('ssd_picturepath', $value)) {
            $value['ssd_picturepath'] = '';
        }
        if (!array_key_exists('ssd_accesstoken', $value)) {
            $value['ssd_accesstoken'] = '';
        }

        return true;
    }

}
