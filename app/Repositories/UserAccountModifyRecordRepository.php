<?php

namespace App\Repositories;

use App\Models\icr_UserAccountModifyRecord;

class UserAccountModifyRecordRepository {

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
        try {
            return icr_UserAccountModifyRecord::find($primarykey);
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return null;
        }
    }

    /**
     * 建立一筆新的資料
     * @param array $arraydata 要新增的資料
     * @return type
     */
    public function create(array $arraydata) {
        try {
            $result = $this->createGetId($arraydata);
            if (!isset($result) || $result == null) {
                return false;
            }
            return true;
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return null;
        }
    }

    /**
     * 建立一筆新的資料並取得自動新增的PriamryKey
     * @param array $arraydata 要異動的資料
     * @return type 自動新增的PriamryKey
     */
    public function createGetId(array $arraydata) {
        try {
            //檢查必填欄位
            if (!isset($arraydata['md_id']) || !isset($arraydata['uamr_operationtype']) || !isset($arraydata['mur_id'])) {
                return null;
            }

            //將資料填入對應的欄位
            $savedata['md_id'] = $arraydata['md_id'];
            $savedata['uamr_operationtype'] = $arraydata['uamr_operationtype'];
            $savedata['mur_id'] = $arraydata['mur_id'];

            if (isset($arraydata['jio_id'])) {
                $savedata['jio_id'] = $arraydata['jio_id'];
            }
            if (isset($arraydata['sso_serno'])) {
                $savedata['sso_serno'] = $arraydata['sso_serno'];
            }
            if (isset($arraydata['snc_serno'])) {
                $savedata['snc_serno'] = $arraydata['snc_serno'];
            }

            if (isset($arraydata['isflag'])) {
                $savedata['isflag'] = $arraydata['isflag'];
            }
            if (isset($arraydata['create_user'])) {
                $savedata['create_user'] = $arraydata['create_user'];
            }
            if (isset($arraydata['create_date'])) {
                $savedata['create_date'] = $arraydata['create_date'];
            }
            if (isset($arraydata['last_update_user'])) {
                $savedata['last_update_user'] = $arraydata['last_update_user'];
            }
            if (isset($arraydata['last_update_date'])) {
                $savedata['last_update_date'] = $arraydata['last_update_date'];
            }
            //執行新增資料，並取得自動新增的欄位
            return icr_UserAccountModifyRecord::insertGetId($savedata);
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return null;
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
