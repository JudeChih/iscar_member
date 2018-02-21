<?php

namespace App\Repositories;

use \App\Models\icr_ShopBackEnd_Register;
use DB;

class ShopBackEnd_RegisterRepository {

     /**
     * InsertData
     * @param array $arraydata
     */
    public function InsertData($arraydata) {

        try {
              if (!\App\Library\CommonTools::CheckArrayValue($arraydata, "md_id") || !\App\Library\CommonTools::CheckArrayValue($arraydata, "shop_backend_Id")) {
                return false;
              }
              $savadata['md_id'] = $arraydata['md_id'];
              $savadata['shop_backend_Id'] = $arraydata['shop_backend_Id'];
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

              icr_ShopBackEnd_Register::insert($savadata);
              return true;
        } catch (Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }



    /**
     * ██████████▍READ 讀取資料
     */
     public function GetDataByBackendId ($backendId) {
        try {
             $result = icr_ShopBackEnd_Register::where('icr_shopbackend_register.shop_backend_Id', '=', $backendId)
                                               ->where('icr_shopbackend_register.isflag','=',1)
                                               ->get()->toArray();
            return $result;
        } catch (\Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
     }


    /**
     * ██████████▍UPDATE 更新資料
     */
    public function UpdateData(array $arraydata) {
        try {
            if (!\App\Library\CommonTools::CheckArrayValue($arraydata, "sbr_no")) {
                return false;
            }
           // DB::beginTransaction();
            $savedata['sbr_no'] = $arraydata['sbr_no'];

            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "md_id")) {
                $savedata['md_id'] = $arraydata['md_id'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "shop_backend_Id")) {
                $savedata['shop_backend_Id'] = $arraydata['shop_backend_Id'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "create_user")) {
                $savedata['create_user'] = $arraydata['create_user'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "isflag")) {
                $savedata['isflag'] = $arraydata['isflag'];
            } else {
                $savedata['isflag'] = '1';
            }
            $savedata['last_update_user'] = 'webapi';
            $savedata['last_update_date'] = date('Y-m-d H:i:s');
            icr_ShopBackEnd_Register::where('sbr_no', '=', $savedata['sbr_no'])
                    ->update($savedata);
            //DB::commit();
            return true;
        } catch (Exception $ex) {
            //DB::rollBack();
            \App\Library\CommonTools::writeErrorLogByException($ex);
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
