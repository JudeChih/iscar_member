<?php

namespace App\Repositories;

use App\Models\icr_ModIncom_r;

class ModIncom_rRepository {

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
        try {
            //檢查必填欄位
            if (!isset($arraydata['md_id']) || !isset($arraydata['mapr_serno']) || !isset($arraydata['mir_datapack']) || !isset($arraydata['mfr_serno'])) {
                return false;
            }
            //將資料填入對應的欄位
            $savedata['md_id'] = $arraydata['md_id'];
            $savedata['mapr_serno'] = $arraydata['mapr_serno'];
            $savedata['mfr_serno'] = $arraydata['mfr_serno'];
            $savedata['mir_datapack'] = $arraydata['mir_datapack'];
            if (isset($arraydata['mir_usestatus'])) {
                $savedata['mir_usestatus'] = $arraydata['mir_usestatus'];
            }
            if (isset($arraydata['create_user'])) {
                $savedata['create_user'] = $arraydata['create_user'];
            }
            if (isset($arraydata['last_update_user'])) {
                $savedata['last_update_user'] = $arraydata['last_update_user'];
            }
            $savedata['isflag'] = 1;
            $savedata['create_date'] = \Carbon\Carbon::now();
            $savedata['last_update_date'] = \Carbon\Carbon::now();

            return icr_ModIncom_r::insert($savedata);
        } catch (\Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
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
