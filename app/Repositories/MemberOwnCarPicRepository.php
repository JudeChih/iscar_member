<?php

namespace App\Repositories;

use \App\Models\isCarMemberOwnCarPic;
use DB;

class MemberOwnCarPicRepository {



    public function getDataByMocId($moc_id){
        try {
            return isCarMemberOwnCarPic::where('moc_id',$moc_id)->where('isflag',1)->get();
        } catch (Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }
     /**
     * ██████████▍CREATE 建立資料
     */
      public function InsertData($arraydata) {

        try {
             if (  !\App\Library\CommonTools::CheckArrayValue($arraydata, "moc_id")
                || !\App\Library\CommonTools::CheckArrayValue($arraydata, "mocp_picpath")
                 ) {
                return false;
              }

              $savadata['moc_id'] = $arraydata['moc_id'];
              $savadata['mocp_picpath'] = $arraydata['mocp_picpath'];


              if (\App\Library\CommonTools::CheckArrayValue($arraydata, "mocp_picscategory")) {
                $savadata['mocp_picscategory'] = $arraydata['mocp_picscategory'];
              } else {
                $savadata['mocp_picscategory'] = '0' ;
              }
              if (\App\Library\CommonTools::CheckArrayValue($arraydata, "mocp_picfilename")) {
                $savadata['mocp_picfilename'] = $arraydata['mocp_picfilename'];
              }
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
              $savedata['create_date'] = \Carbon\Carbon::now();
              $savedata['last_update_date'] = \Carbon\Carbon::now();
              DB::beginTransaction();
             if (isCarMemberOwnCarPic::insert($savadata)) {
                DB::commit();
                return true;
             } else {
                DB::rollBack();
                return false;
             }
        } catch (Exception $e) {
            DB::rollBack();
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

    /**
     * ██████████▍UPDATE 更新資料
     */
      public function UpdateData(array $arraydata) {
        try {
            if (/*!\App\Library\CommonTools::CheckArrayValue($arraydata, "mocp_serno") || */
                !\App\Library\CommonTools::CheckArrayValue($arraydata, "moc_id")) {
                return false;
            }
            /*$savedata['mocp_serno'] = $arraydata['mocp_serno'];*/
            $savadata['moc_id'] = $arraydata['moc_id'];

            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "mocp_picscategory")) {
              $savadata['mocp_picscategory'] = $arraydata['mocp_picscategory'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "mocp_picpath")) {
              $savadata['mocp_picpath'] = $arraydata['mocp_picpath'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "mocp_picfilename")) {
              $savadata['mocp_picfilename'] = $arraydata['mocp_picfilename'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "isflag")) {
              $savadata['isflag'] = $arraydata['isflag'];
            }
            $savadata['last_update_user'] = 'webapi';
            $savadata['last_update_date'] = \Carbon\Carbon::now();
            DB::beginTransaction();
            if(isCarMemberOwnCarPic::where('moc_id', '=', $savadata['moc_id'])
                    ->update($savadata)){
                DB::commit();
                return true;
            }else{
                DB::rollBack();
                return false;
            }
        } catch (Exception $ex) {
            DB::rollBack();
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
