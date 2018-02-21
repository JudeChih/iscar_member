<?php

namespace App\Repositories;

use App\Models\isCarMobileUnitRec;

class MobileUnitRecRepository {

    /*     * 新增資料
     *
     *
     * @param   $arraydata
     * @return  Boolean
     */

    public function InsertData($arraydata, &$mur_id) {


        $mur_id = \App\Library\CommonTools::NewGUIDWithoutDash();
        $savedata['mur_id'] = $mur_id;

        if (!\App\Library\CommonTools::CheckArrayValue($arraydata, "mur_uuid") || !\App\Library\CommonTools::CheckArrayValue($arraydata, "mur_gcmid") || !\App\Library\CommonTools::CheckArrayValue($arraydata, "mur_apptype") || !\App\Library\CommonTools::CheckArrayValue($arraydata, "mur_systemtype")) {
            return false;
        }
        $savedata['mur_uuid'] = $arraydata['mur_uuid'];
        $savedata['mur_gcmid'] = $arraydata['mur_gcmid'];
        $savedata['mur_apptype'] = $arraydata['mur_apptype'];
        $savedata['mur_systemtype'] = $arraydata['mur_systemtype'];


        if (\App\Library\CommonTools::CheckArrayValue($arraydata, "mur_mobileeffective")) {
            $savedata['mur_mobileeffective'] = $arraydata['mur_mobileeffective'];
        } else {
            $savedata['mur_mobileeffective'] = "1";
        }
        if (\App\Library\CommonTools::CheckArrayValue($arraydata, "mur_reconnecttimes")) {
            $savedata['mur_reconnecttimes'] = $arraydata["mur_reconnecttimes"];
        } else {
            $savedata['mur_reconnecttimes'] = "1";
        }

        if (\App\Library\CommonTools::CheckArrayValue($arraydata, "mur_systeminfo")) {
            $savedata['mur_systeminfo'] = $arraydata['mur_systeminfo'];
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


        if (isCarMobileUnitRec::insert($savedata)) {
            return true;
        } else {
            return false;
        }
    }

    /*     * 修改資料
     *
     *
     * @param   $mur_id
     * @param   $arraydata
     * @return  Boolean
     */

    public function UpdateData($arraydata) {
        try {
            if (!\App\Library\CommonTools::CheckArrayValue($arraydata, "mur_id") || !\App\Library\CommonTools::CheckArrayValue($arraydata, "mur_uuid") || !\App\Library\CommonTools::CheckArrayValue($arraydata, "mur_gcmid") || !\App\Library\CommonTools::CheckArrayValue($arraydata, "mur_apptype") || !\App\Library\CommonTools::CheckArrayValue($arraydata, "mur_systemtype")) {
                return false;
            }
            $savedata['mur_id'] = $arraydata['mur_id'];


            $savedata['mur_uuid'] = $arraydata['mur_uuid'];
            $savedata['mur_gcmid'] = $arraydata['mur_gcmid'];
            $savedata['mur_apptype'] = $arraydata['mur_apptype'];
            $savedata['mur_systemtype'] = $arraydata['mur_systemtype'];

            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "mur_systeminfo")) {
                $savedata['mur_systeminfo'] = $arraydata['mur_systeminfo'];
            }

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

            isCarMobileUnitRec::where('mur_id', $savedata['mur_id'])
                    ->update($savedata);

            return true;
        } catch (Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return false;
        }
    }

    /**
     * 更新資料，將重新登入次數加一
     * @param type $mur_id
     * @return boolean
     */
    public function UpdateDataAddReConnectTimes($mur_id) {
        try {

            if (is_null($mur_id) || strlen($mur_id) == 0) {
                return false;
            }

            isCarMobileUnitRec::where('mur_id', $mur_id)
                    ->increment('mur_reconnecttimes', 1, ['last_update_user' => 'webapi']);

            return true;
        } catch (Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return false;
        }
    }

    /*     * 取得資料，使用「mur_uuid」、「mur_gcmid」
     *
     *
     * @param   $mur_uuid
     * @param   $mur_gcmid
     * @return  \App\Models\IsCarMobileUnitRec
     */

    public function GetUnitData($mur_uuid, $mur_gcmid) {
        if ($mur_uuid == null || strlen($mur_uuid) == 0 || $mur_gcmid == null || strlen($mur_gcmid) == 0) {
            return null;
        }

        $results = isCarMobileUnitRec::where('isflag', '1')
                ->where('mur_uuid', $mur_uuid)
                ->where('mur_gcmid', $mur_gcmid)
                ->get()
                ->toArray();

        return $results;
    }

    /*     * 取得資料，使用「mur_id」
     *
     *
     * @param   $mur_id
     * @param   $mur_gcmid
     * @return  \App\Models\IsCarMobileUnitRec
     */

    /**
     * 使用「$mur_id」取得資料
     * @param type $mur_id
     * @return type
     */
    public function GetDataByMUR_ID($mur_id) {
        if ($mur_id == null || strlen($mur_id) == 0) {
            return null;
        }

        $results = isCarMobileUnitRec::where('isflag', '1')
                ->where('mur_id', $mur_id)
                ->get()
                ->toArray();

        return $results;
    }

    public function GetDataByMUR_UUID($mur_uuid, $mur_systemtype) {
        if (is_null($mur_uuid) || strlen($mur_uuid) == 0 || is_null($mur_systemtype) || strlen($mur_systemtype) == 0) {
            return null;
        }

        $results = isCarMobileUnitRec::where('isflag', '1')
                ->where('mur_uuid', $mur_uuid)
                ->where('mur_systemtype', $mur_systemtype)
                ->get()
                ->toArray();

        return $results;
    }

    /**
     * 取得所有資料
     * @return type
     */
    public function getAllData() {
        return isCarMemberData::get();
    }

    /**
     * 使用「$primarykey」查詢資料表的主鍵值
     * @param type $primarykey 要查詢的值
     * @return type
     */
    public function getData($primarykey) {
        return isCarMobileUnitRec::find($primarykey);
    }

    /**
     * 使用「裝置記錄編號」查詢資料
     * @param type $mur_id 裝置記錄編號
     * @return type
     */
    public function getDataByMurID($mur_id) {
        return isCarMobileUnitRec::where('mur_id', '=', $mur_id)
                        ->where('isflag', '=', 1)
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
