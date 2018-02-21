<?php

namespace App\Repositories;

use App\Models\icr_ModuleAccPass_r;
use DB;

class ModuleAccPass_rRepository {

    /**
     * ██████████▍READ 讀取資料
     */
     public function GetModulUserData($account) {
        try {
             $query = icr_ModuleAccPass_r::where('icr_moduleaccpass_r.mapr_moduleaccount', '=', $account);

             $result = $query->select( 'icr_moduleaccpass_r.mapr_serno'
                                      ,'icr_moduleaccpass_r.mapr_moduleaccount'
                                      ,'icr_moduleaccpass_r.mapr_modulepassword'
                                     )
                                    ->get()->toArray();
            return $result;
        } catch(Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return null;
        }
     }

    /**
     * ██████████▍UPDATE 更新資料
     */
     public function UpdateData(array $arraydata) {


        try {
            if (!\App\Library\CommonTools::CheckArrayValue($arraydata, "mapr_serno")) {
                return false;
            }
            DB::beginTransaction();
            $savedata['mapr_serno'] = $arraydata['mapr_serno'];

            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "mapr_moduleaccount")) {
                $savedata['mapr_moduleaccount'] = $arraydata['mapr_moduleaccount'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "mapr_modulepassword")) {
                $savedata['mapr_modulepassword'] = $arraydata['mapr_modulepassword'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "mapr_modulename")) {
                $savedata['mapr_modulename'] = $arraydata['mapr_modulename'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "mapr_modulefunction")) {
                $savedata['mapr_modulefunction'] = $arraydata['mapr_modulefunction'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "mapr_module_ip")) {
                $savedata['mapr_module_ip'] = $arraydata['mapr_module_ip'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "isflag")) {
                $savedata['isflag'] = $arraydata['isflag'];
            } else {
                $savedata['isflag'] = '1';
            }

            $savedata['last_update_user'] = 'webapi';
            $savedata['last_update_date'] = date('Y-m-d H:i:s');
            icr_ModuleAccPass_r::where('mapr_serno', '=', $savedata['mapr_serno'])
                    ->update($savedata);
            DB::commit();
            return true;
        } catch (Exception $ex) {
            DB::rollBack();
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return false;
        }
    }

    /**
     * 使用「$moduleaccount」查詢資料表的mapr_moduleaccount欄位
     * @param type $moduleaccount 要查詢的值
     * @return type
     */
    public function getDataByAccount($moduleaccount) {
        return icr_ModuleAccPass_r::where('isflag',1)->where('mapr_moduleaccount',$moduleaccount)->get();
    }

    /**
     * 建立一筆新的資料
     * @param array $arraydata 要新增的資料
     * @return type
     */
    public function create(array $arraydata) {
        return null;
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
