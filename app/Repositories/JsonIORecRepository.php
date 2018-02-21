<?php

namespace App\Repositories;

use App\Models\isCarJsonIORec;

class JsonIORecRepository {

    //新增資料
    public function InsertData($arraydata) {
        //檢查「必填值」
        if (!\App\Library\CommonTools::CheckArrayValue($arraydata, "jio_receive") || !\App\Library\CommonTools::CheckArrayValue($arraydata, "jio_return") || !\App\Library\CommonTools::CheckArrayValue($arraydata, "jio_wcffunction") || !\App\Library\CommonTools::CheckArrayValue($arraydata, "ps_id")) {
            return false;
        }

        $savedata['jio_receive'] = $arraydata['jio_receive'];
        $savedata['jio_return'] = $arraydata['jio_return'];
        $savedata['jio_wcffunction'] = $arraydata['jio_wcffunction'];
        $savedata['ps_id'] = $arraydata['ps_id'];


        if (!\App\Library\CommonTools::CheckArrayValue($arraydata, "isflag")) {
            $savedata['isflag'] = '1';
        } else {
            $savedata['isflag'] = $arraydata['isflag'];
        }

        if (!\App\Library\CommonTools::CheckArrayValue($arraydata, "create_user")) {
            $savedata['create_user'] = 'webapi';
        } else {
            $savedata['create_user'] = $arraydata['create_user'];
        }
        if (!\App\Library\CommonTools::CheckArrayValue($arraydata, "last_update_user")) {
            $savedata['last_update_user'] = 'webapi';
        } else {
            $savedata['last_update_user'] = $arraydata['last_update_user'];
        }

        if (isCarJsonIORec::insert($savedata)) {
            return true;
        } else {
            return false;
        }
    }

     public function InsertDataGetId($arraydata, &$jio_id) {
        //檢查「必填值」
        if (!\App\Library\CommonTools::CheckArrayValue($arraydata, "jio_receive") || !\App\Library\CommonTools::CheckArrayValue($arraydata, "jio_return") || !\App\Library\CommonTools::CheckArrayValue($arraydata, "jio_wcffunction") || !\App\Library\CommonTools::CheckArrayValue($arraydata, "ps_id")) {
            return false;
        }

        $savedata['jio_receive'] = $arraydata['jio_receive'];
        $savedata['jio_return'] = $arraydata['jio_return'];
        $savedata['jio_wcffunction'] = $arraydata['jio_wcffunction'];
        $savedata['ps_id'] = $arraydata['ps_id'];


        if (!\App\Library\CommonTools::CheckArrayValue($arraydata, "isflag")) {
            $savedata['isflag'] = '1';
        } else {
            $savedata['isflag'] = $arraydata['isflag'];
        }

        if (!\App\Library\CommonTools::CheckArrayValue($arraydata, "create_user")) {
            $savedata['create_user'] = 'webapi';
        } else {
            $savedata['create_user'] = $arraydata['create_user'];
        }
        if (!\App\Library\CommonTools::CheckArrayValue($arraydata, "last_update_user")) {
            $savedata['last_update_user'] = 'webapi';
        } else {
            $savedata['last_update_user'] = $arraydata['last_update_user'];
        }
        if ($jio_id = isCarJsonIORec::insertGetId($savedata)) {
            return true;
        } else {
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
