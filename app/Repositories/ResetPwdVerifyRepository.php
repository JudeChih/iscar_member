<?php

namespace App\Repositories;

use App\Models\icr_ResetPwdVerify;

class ResetPwdVerifyRepository {

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
        return icr_ResetPwdVerify::find($primarykey);
    }

    /**
     * 
     * @param type $rpv_serno
     * @return type
     */
    public function getDataByRpvSerno($rpv_serno) {
        try {
            return icr_ResetPwdVerify::
                            where('rpv_serno', '=', $rpv_serno)
                            ->where('rpv_status', '=', '1')
                            ->where('rpv_expiredate', '>', \Carbon\Carbon::now())
                            ->get();
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
            return false;
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
            if (!isset($arraydata['snc_serno']) || !isset($arraydata['rpv_verifycode']) || !isset($arraydata['md_id'])) {
                return null;
            }
            //將資料填入對應的欄位
            $savedata['snc_serno'] = $arraydata['snc_serno'];
            $savedata['rpv_verifycode'] = $arraydata['rpv_verifycode'];
            $savedata['md_id'] = $arraydata['md_id'];

            $savedata['rpv_hash'] = hash('sha512', $arraydata['snc_serno'] . $arraydata['rpv_verifycode'] . $arraydata['md_id']);
            $savedata['rpv_status'] = 1;
            $savedata['rpv_expiredate'] = \Carbon\Carbon::now()->addMonths(1);

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
            return icr_ResetPwdVerify::insertGetId($savedata);
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

        try {
            icr_ResetPwdVerify::where('rpv_serno', '=', $primarykey)
                    ->update($arraydata);
            return true;
        } catch (Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return false;
        }
    }

    /**
     * 刪除該「$primarykey」的資料
     * @param type $primarykey 主鍵值
     */
    public function delete($primarykey) {
        return null;
    }

}
