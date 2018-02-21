<?php

namespace App\Repositories;

use \App\Models\icr_ModuleLogin_r;

class ModuleLogin_rRepository {

     /**
     * ██████████▍CREATE 建立資料
     */
      public function InsertData($arraydata) {

        try {
              if (  !\App\Library\CommonTools::CheckArrayValue($arraydata, "mlr_id")  || !\App\Library\CommonTools::CheckArrayValue($arraydata, "mlr_calleraccount")
                 || !\App\Library\CommonTools::CheckArrayValue($arraydata, "mlr_jwt") || !\App\Library\CommonTools::CheckArrayValue($arraydata, "mlr_expiretime")
                 ) {
                return false;
              }
              $savadata['mlr_id'] = $arraydata['mlr_id'];
              $savadata['mlr_calleraccount'] = $arraydata['mlr_calleraccount'];
              $savadata['mlr_jwt'] = $arraydata['mlr_jwt'];
              $savadata['mlr_expiretime'] = $arraydata['mlr_expiretime'];


              if (\App\Library\CommonTools::CheckArrayValue($arraydata, "isflag")) {
                $savadata['isflag'] = $arraydata['isflag'];
              } else {
                $savadata['isflag'] = '1';
              }
              if (\App\Library\CommonTools::CheckArrayValue($arraydata, "create_user")) {
                $savadata['create_user'] = $arraydata['create_user'];
              } else {
                $savadata['create_user'] = 'webapi';
              }
              if (\App\Library\CommonTools::CheckArrayValue($arraydata, "last_update_user")) {
                $savadata['last_update_user'] = $arraydata['last_update_user'];
              } else {
                $savadata['last_update_user'] = 'webapi';
              }
             if( icr_ModuleLogin_r::insert($savadata) ) {
                return true;
             } else {
                return false;
             }
        } catch (Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

    /**
     * ██████████▍READ 讀取資料
     */
     public function QueryModulLoginData($account, $token) {
        try {
          if(is_null($account) || is_null($token)) {
             return null;
          }
          $result = icr_ModuleLogin_r::where('icr_modulelogin_r.mlr_calleraccount', '=', $account)
                                    ->where('icr_modulelogin_r.mlr_jwt','=',$token)
                                    ->get()->toArray();
            return $result;
        } catch (Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
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
