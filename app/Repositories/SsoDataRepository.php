<?php

namespace App\Repositories;

use App\Models\isCarSsoData;
use DB;

class SsoDataRepository {

    /**
     * 使用facebook_id 抓取會員資料
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function getDataByFBID($id){
        // 要join memberData
        return isCarSsoData::join('iscarmemberdata','iscarssodata.sso_accountid','iscarmemberdata.sso_accountid')->where('iscarssodata.sso_accountid',$id)->where('iscarmemberdata.isflag',1)->get();
    }

    /**
     * 使用sso_accountid抓取ssodata
     * @param  [string] $sso_accountid
     */
    public function getDataByAccountID($sso_accountid){
        return isCarSsoData::where('sso_accountid',$sso_accountid)->where('isflag',1)->get();
    }

    /**
     * 透過FB登入後，更新sso_token
     * @param  [string] $sso_serno [sso代碼]
     * @param  [string] $sso_token [access_token]
     */
    public function updateSsotoken($sso_serno,$sso_token){
        if ($sso_serno == null || strlen($sso_serno) == 0) {
            return null;
        }
        if ($sso_token == null || strlen($sso_token) == 0) {
            return null;
        }
        $savedata['last_update_date'] = \Carbon\Carbon::now();
        $savedata['sso_token'] = $sso_token;
        return isCarSsoData::where('sso_serno',$sso_serno)->update($savedata);
    }

    /**
     * 取得所有資料
     * @return type
     */
    public function getAllData() {
        return null;
    }

    /**
     * 使用「$primarykey」查詢資料表的主鍵值
     * @param type $primarykey 要查詢的值
     * @return type
     */
    public function getData($primarykey) {
        return null;
    }

    /**
     * 建立一筆新的資料
     * @param array $arraydata 要新增的資料
     * @return type
     */
    public function create(array $arraydata) {
        try{
            DB::beginTransaction();
            if (isset($arraydata['sso_bindtype'])) {
                $savedata['sso_bindtype'] = $arraydata['sso_bindtype'];
            }
            if (isset($arraydata['sso_accountid'])) {
                $savedata['sso_accountid'] = $arraydata['sso_accountid'];
            }
            if (isset($arraydata['sso_token'])) {
                $savedata['sso_token'] = $arraydata['sso_token'];
            }
            if (isset($arraydata['sso_firstname'])) {
                $savedata['sso_firstname'] = $arraydata['sso_firstname'];
            }
            if (isset($arraydata['sso_lastname'])) {
                $savedata['sso_lastname'] = $arraydata['sso_lastname'];
            }
            if (isset($arraydata['sso_name'])) {
                $savedata['sso_name'] = $arraydata['sso_name'];
            }
            if (isset($arraydata['sso_locale'])) {
                $savedata['sso_locale'] = $arraydata['sso_locale'];
            }
            if (isset($arraydata['sso_gender'])) {
                $savedata['sso_gender'] = $arraydata['sso_gender'];
            }
            if (isset($arraydata['sso_email'])) {
                $savedata['sso_email'] = $arraydata['sso_email'];
            }
            if (isset($arraydata['sso_birthday'])) {
                $savedata['sso_birthday'] = $arraydata['sso_birthday'];
            }
            if (isset($arraydata['sso_location'])) {
                $savedata['sso_location'] = $arraydata['sso_location'];
            }
            if (isset($arraydata['sso_timezon'])) {
                $savedata['sso_timezon'] = $arraydata['sso_timezon'];
            }
            if (isset($arraydata['ssd_photourl'])) {
                $savedata['sso_photourl'] = $arraydata['sso_photourl'];
            }
            if (isset($arraydata['create_user'])) {
                $savedata['create_user'] = $arraydata['create_user'];   
            }
            if (isset($arraydata['last_update_user'])) {
                $savedata['last_update_user'] = $arraydata['last_update_user'];
            }
            $savedata['create_date'] = \Carbon\Carbon::now();
            $savedata['last_update_date'] = \Carbon\Carbon::now();
            $result = isCarSsoData::insert($savedata);
            if($result){
                DB::commit();
                return true;
            }
            DB::rollback();
            return false;
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            DB::rollback();
            return false;
        }
    }

    /**
     * 更新該「$primarykey」的資料
     * @param array $arraydata 要更新的資料
     * @param type $primarykey 
     * @return type
     */
    public function update(array $arraydata, $primarykey) {
        return null;
    }

    /**
     * 刪除該「$primarykey」的資料
     * @param type $primarykey 主鍵值
     */
    public function delete($primarykey) {
        return null;
    }

}
