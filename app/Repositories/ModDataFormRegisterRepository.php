<?php

namespace App\Repositories;

use App\Models\icr_ModDataForm_Register;

class ModDataFormRegisterRepository {

    /**
     * 取得所有資料
     * @return type
     */
    public function getAllData() {
        return null;
    }

    /**
     * 使用「$modacc」and「$packetname」查詢資料表的主鍵值
     * @param  [string] $modacc
     * @param  [string] $packetname
     * @return [mixed]  $data or null
     */
    public function getDataByModAccDataName($modacc,$packetname) {
        if ($modacc == null || strlen($modacc) == 0) {
            return null;
        }
        if ($packetname == null || strlen($packetname) == 0) {
            return null;
        }
        return icr_ModDataForm_Register::join('icr_moduleaccpass_r','icr_moduleaccpass_r.mapr_serno','icr_moddataform_register.mapr_serno')
                                       ->where('icr_moduleaccpass_r.mapr_moduleaccount',$modacc)
                                       ->where('icr_moddataform_register.mrf_dataname',$packetname)
                                       ->get();
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
