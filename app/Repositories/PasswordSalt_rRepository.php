<?php

namespace App\Repositories;

use \App\Models\icr_PasswordSalt_r;

class PasswordSalt_rRepository {

    /**
     * ██████████▍READ 讀取資料
     */
     public function GetPassWordSaltData() {
        try {
             $result = icr_PasswordSalt_r::orderBy('create_date', 'desc')
                                         ->skip(0)->take(1)
                                         ->get()->toArray();

            return $result;
        } catch(Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return null;
        }
     }


     public function GetSaltBySerno($serno) {
        try {
             $result = icr_PasswordSalt_r::where('icr_passwordsalt_r.psr_serno', '=', $serno)
                                           ->get();
            return $result;
        } catch(Exception $e) {
          \App\Library\CommonTools::writeErrorLogByException($e);
            return null;
        }
     }

     /**
      * 取得最新一筆的資料
      * @return [type] [description]
      */
    public function getNewestData(){
        return icr_PasswordSalt_r::where('isflag',1)->orderBy('psr_serno',"DESC")->first();
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
