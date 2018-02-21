<?php

namespace App\Repositories;

use App\Models\isCarMemberMobileLink;
class MemberMobileLinkRepository {

    /** 新增資料
     *
     *
     * @param   $arraydata
     * @return  Boolean
     */
    public function InsertData($arraydata, &$mml_serno) {

        if (!\App\Library\CommonTools::CheckArrayValue($arraydata, "mml_apptype") || !\App\Library\CommonTools::CheckArrayValue($arraydata, "mur_id")) {
            return false;
        }

        $savedata['mml_apptype'] = $arraydata['mml_apptype'];
        //$savedata['md_id'] = $arraydata['md_id'];
        $savedata['mur_id'] = $arraydata['mur_id'];

        if ( in_array( $savedata['mml_apptype'], array(0,3,4,5)) ) {
            if (!\App\Library\CommonTools::CheckArrayValue($arraydata, "md_id")) {
                return false;
            }
            $savedata['md_id'] = $arraydata['md_id'];
        } else if ($savedata['mml_apptype'] == '1') {
            if (!\App\Library\CommonTools::CheckArrayValue($arraydata, "scd_id")) {
                return false;
            }
            $savedata['scd_id'] = $arraydata['scd_id'];
        } else {
            return false;
        }



        if (\App\Library\CommonTools::CheckArrayValue($arraydata, "isflag")) {
            $savedata['isflag'] = $arraydata['isflag'];
        } else {
            $savedata['isflag'] = '1';
        }

        if (\App\Library\CommonTools::CheckArrayValue($arraydata, "create_user")) {
            $savedata['create_user'] = $arraydata['create_user'];
        } else {
            $savedata['create_user'] = 'webapi';
        }
        if (\App\Library\CommonTools::CheckArrayValue($arraydata, "last_update_user")) {
            $savedata['last_update_user'] = $arraydata['last_update_user'];
        } else {
            $savedata['last_update_user'] = 'webapi';
        }


        //新增資料並回傳「自動遞增KEY值」
        $result = isCarMemberMobileLink::insertGetId($savedata);

        if (!is_null($result) && strlen($result) != 0) {
            $mml_serno = $result;

            return true;
        } else {
            return false;
        }
    }

    //修改資料
    public function UpdateData($arraydata) {

        if (!\App\Library\CommonTools::CheckArrayValue($arraydata, 'mml_serno') || !\App\Library\CommonTools::CheckArrayValue($arraydata, "mml_apptype") || !\App\Library\CommonTools::CheckArrayValue($arraydata, "md_id") || !\App\Library\CommonTools::CheckArrayValue($arraydata, "mur_id")) {
            return false;
        }

        $savedata['mml_serno'] = $arraydata['mml_serno'];
        $savedata['mml_apptype'] = $arraydata['mml_apptype'];
        $savedata['md_id'] = $arraydata['md_id'];
        $savedata['mur_id'] = $arraydata['mur_id'];


        if (\App\Library\CommonTools::CheckArrayValue($arraydata, "isflag")) {
            $savedata['isflag'] = $arraydata['isflag'];
        }

        /*
          if (\App\Library\CommonTools::CheckArrayValue($arraydata, "create_user")) {
          $savedata['create_user'] = $arraydata['create_user'];
          }

          if (\App\Library\CommonTools::CheckArrayValue($arraydata, "create_date")) {
          $savedata['create_date'] = $arraydata['create_date'];
          }
         */

        $savedata['last_update_user'] = 'webapi';

        return(boolean) isCarMemberMobileLink::where('mml_serno', $savedata['mml_serno'])
                        ->update($savedata);
    }

    //刪除資料
    public function DeleteData($mur_id) {

    }

    //取得資料，使用「mml_serno」
    public function GetData($mml_serno) {
        if ($mml_serno == null || strlen($mml_serno) == 0) {
            return null;
        }

        $results = isCarMemberMobileLink::where('isflag', '1')
                ->where('mml_serno', $mml_serno)
                ->get()
                ->toArray();

        return $results;
    }

    //取得資料，使用「mur_id」
    public function GetDataByMDID_MURID($mml_apptype, $md_id, $mur_id) {
        if ($mml_apptype == null || strlen($mml_apptype) == 0 || $md_id == null || strlen($md_id) == 0 || $mur_id == null || strlen($mur_id) == 0) {
            return null;
        }

        $results = isCarMemberMobileLink::where('isflag', '1')
                ->where('mml_apptype', $mml_apptype)
                ->where('md_id', $md_id)
                ->where('mur_id', $mur_id)
                ->get()
                ->toArray();

        return $results;
    }

    /**
     * 取得資料
     * @param type $mml_apptype
     * @param type $account_id
     * @param type $mur_id
     * @return type
     */
    public function GetDataByAppType_MURID($mml_apptype, $account_id, $mur_id) {
        if ($mml_apptype == null || strlen($mml_apptype) == 0 || $account_id == null || strlen($account_id) == 0 || $mur_id == null || strlen($mur_id) == 0) {
            return null;
        }

        $query = isCarMemberMobileLink::where('isflag', '1')
                ->where('mml_apptype', $mml_apptype)
                ->where('mur_id', $mur_id);

        if ($mml_apptype == '0') {
            $query->where('md_id', $account_id);
        } else {
            $query->where('scd_id', $account_id);
        }

        $results = $query->get()->toArray();

        return $results;
    }

    /**
     * 取得所有資料
     * @return type
     */
    public function getAllData() {
        return null;
    }

    /**
     * 建立一筆新的資料
     * @param array $arraydata 要新增的資料
     * @return boolean
     */
    public function create(array $arraydata) {
        try {
            $result = $this->createGetId($arraydata);
            if (!isset($result) || $result == null) {
                return false;
            }
            return true;
        } catch (\Exception $ex) {
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
            if (!isset($arraydata['mml_apptype']) || !isset($arraydata['mur_id'])) {
                return null;
            }
            //「md_id」、「scd_id」必需有其中一個
            if (!isset($arraydata['md_id']) && !isset($arraydata['scd_id'])) {
                return null;
            }

            //將資料填入對應的欄位
            $savedata['mml_apptype'] = $arraydata['mml_apptype'];
            $savedata['mur_id'] = $arraydata['mur_id'];
            if (isset($arraydata['md_id'])) {
                $savedata['md_id'] = $arraydata['md_id'];
            }
            if (isset($arraydata['scd_id'])) {
                $savedata['scd_id'] = $arraydata['scd_id'];
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
            return isCarMemberMobileLink::insertGetId($savedata);
        } catch (\Exception $ex) {
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
     * 將同「會員代碼」資料的〔isfalg〕全設為〈０〉
     * @param type $md_id 會員代碼
     * @return boolean 執行結果
     */
    public function updateIsFlagToZeroByMdID($md_id) {
        try {
            isCarMemberMobileLink::where('md_id', '=', $md_id)
                    ->update(array('isflag' => 0));

            return true;
        } catch (\Exception $ex) {
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
