<?php

namespace App\Repositories;

use \App\Models\mb_tpl_Relative;
use DB;

class MbTplRelativeRepository {

    // /**
    //  * 將會員資料同步到親屬資料
    //  * @param  Array  $arraydata [會員資料]
    //  */
    // public function updateTprData(Array $arraydata){
    //     try {
    //         if(!isset($arraydata['md_id'])){
    //             return false;
    //         }
    //         DB::beginTransaction();

    //         if(isset($arraydata['rl_city_code'])){
    //             $savedata['rl_city_code'] = $arraydata['rl_city_code'];
    //         }
    //         if(isset($arraydata['rl_zip'])){
    //             $savedata['rl_zip'] = $arraydata['rl_zip'];
    //         }

    //         $savedata['last_update_user'] = 'Jude';
    //         $savedata['last_update_date'] = \Carbon\Carbon::now();

    //         $result = mb_tpl_Relative::where('md_id',$arraydata['md_id'])->where('tpr_serno',$arraydata['tpr_serno'])->update($savedata);
    //         if($result){
    //             DB::commit();
    //             return true;
    //         }
    //         DB::rollBack();
    //         return false;
    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         \App\Library\CommonTools::writeErrorLogByException($e);
    //         return false;
    //     }
    // }

    /**
     * 取得所有資料
     * @return type
     */
    public function getAllData() {
        return mb_tpl_Relative::get();
    }

    /**
     * 使用「tpr_name」抓取親屬資料
     * @param  [string] $tpr_name [親屬名稱]
     */
    public function getDataByNameAndMdId($tpr_name,$md_id){
        return mb_tpl_Relative::where('md_id',$md_id)->where('tpr_name',$tpr_name)->where('isflag',1)->get();
    }

    /**
     * 使用「$md_id」查詢資料表的主鍵值
     * @param string $md_id 會員代碼
     */
    public function getDataByMdId($md_id) {
        try {
            if(is_null($md_id) || strlen($md_id) == 0) {
                return false;
            }
            $result = mb_tpl_Relative::where('md_id',$md_id)->where('isflag',1)->get();
            return $result;
        } catch (Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

    /**
     * 藉由md_id & tpr_title抓取該會員自己的親屬資料
     * @param  [type] $md_id     [description]
     * @param  [type] $tpr_title [description]
     */
    public function getDataByMdIdTprTitle($md_id,$tpr_title){
        try {
            if(is_null($md_id) || strlen($md_id) == 0 || is_null($tpr_title) || strlen($tpr_title) == 0) {
                return false;
            }
            $result = mb_tpl_Relative::where('md_id',$md_id)->where('tpr_title',$tpr_title)->where('isflag',1)->get();
            return $result;
        } catch (Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

    /**
     * 透過會員資料新增到親屬
     * @param  Array  $arraydata [會員資料]
     */
    public function createByMemberdata(Array $arraydata){
        try {
            if(!isset($arraydata['md_id']) || !isset($arraydata['tpr_title']) || !isset($arraydata['tpr_name']) || !isset($arraydata['tpr_birthdaytime']) || !isset($arraydata['rl_city_code']) || !isset($arraydata['rl_zip']) || !isset($arraydata['tpr_address'])){
                return false;
            }
            DB::beginTransaction();
            $savedata['md_id'] = $arraydata['md_id'];
            $savedata['tpr_title'] = $arraydata['tpr_title'];
            $savedata['tpr_name'] = $arraydata['tpr_name'];
            $savedata['tpr_birthday'] = $arraydata['tpr_birthday'];
            $savedata['tpr_birthdaytime'] = $arraydata['tpr_birthdaytime'];
            $savedata['rl_city_code'] = $arraydata['rl_city_code'];
            $savedata['rl_zip'] = $arraydata['rl_zip'];
            $savedata['tpr_address'] = $arraydata['tpr_address'];

            $savedata['isflag'] = 1;
            $savedata['create_date'] = \Carbon\Carbon::now();
            $savedata['last_update_date'] = \Carbon\Carbon::now();
            if(isset($arraydata['create_user'])){
                $savedata['create_user'] = $arraydata['create_user'];
            }
            if(isset($arraydata['last_update_user'])){
                $savedata['last_update_user'] = $arraydata['last_update_user'];
            }

            $result = mb_tpl_Relative::insert($savedata);

            if($result){
                DB::commit();
                return true;
            }
            DB::rollBack();
            return false;
        } catch (Exception $e) {
            DB::rollBack();
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

    /**
     * 將會員資料同步到親屬資料
     * @param  Array  $arraydata [會員資料]
     */
    public function updateByMemberdata(Array $arraydata){
        try {
            if(!isset($arraydata['md_id'])){
                return false;
            }
            DB::beginTransaction();
            if(isset($arraydata['tpr_name'])){
                $savedata['tpr_name'] = $arraydata['tpr_name'];
            }
            if(isset($arraydata['tpr_birthday'])){
                $savedata['tpr_birthday'] = $arraydata['tpr_birthday'];
            }
            if(isset($arraydata['rl_city_code'])){
                $savedata['rl_city_code'] = $arraydata['rl_city_code'];
            }
            if(isset($arraydata['rl_zip'])){
                $savedata['rl_zip'] = $arraydata['rl_zip'];
            }
            if(isset($arraydata['tpr_address'])){
                $savedata['tpr_address'] = $arraydata['tpr_address'];
            }
            if(isset($arraydata['last_update_user'])){
                $savedata['last_update_user'] = $arraydata['last_update_user'];
            }
            $savedata['last_update_date'] = \Carbon\Carbon::now();

            $result = mb_tpl_Relative::where('md_id',$arraydata['md_id'])->where('tpr_title',99)->update($savedata);
            if($result){
                DB::commit();
                return true;
            }
            DB::rollBack();
            return false;
        } catch (Exception $e) {
            DB::rollBack();
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }


    /**
     * 建立一筆新的資料
     * @param string $md_id     會員編號
     * @param array  $arraydata 要新增的資料
     */
    public function insertData(array $arraydata) {
        try {
            if(!isset($arraydata['md_id']) || !isset($arraydata['tpr_name'])){
                return false;
            }
            // $data = mb_tpl_Relative::where('md_id',$arraydata['md_id'])->where('tpr_name',$arraydata['tpr_name'])->where('isflag',1)->get();
            // if(count($data) == 0){
                //檢查必填欄位
                if (!isset($arraydata['tpr_title']) || !isset($arraydata['tpr_birthday']) || !isset($arraydata['tpr_birthdaytime']) || !isset($arraydata['rl_city_code']) || !isset($arraydata['rl_zip']) || !isset($arraydata['tpr_address'])) {
                    return false;
                }

                $savedata['md_id'] = $arraydata['md_id'];
                $savedata['tpr_title'] = $arraydata['tpr_title'];
                $savedata['tpr_name'] = $arraydata['tpr_name'];
                $savedata['tpr_birthday'] = $arraydata['tpr_birthday'];
                $savedata['tpr_birthdaytime'] = $arraydata['tpr_birthdaytime'];
                $savedata['rl_city_code'] = $arraydata['rl_city_code'];
                $savedata['rl_zip'] = $arraydata['rl_zip'];
                $savedata['tpr_address'] = $arraydata['tpr_address'];

                if(isset($arraydata['create_user'])){
                    $savedata['create_user'] = $arraydata['create_user'];
                }
                if(isset($arraydata['last_update_user'])){
                    $savedata['last_update_user'] = $arraydata['last_update_user'];
                }
                $savedata['create_date'] = \Carbon\Carbon::now();
                $savedata['last_update_date'] = \Carbon\Carbon::now();
                return mb_tpl_Relative::insert($savedata);
            // }
            // return false;
        } catch (Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

    /**
     * 透過[md_id]&[tpr_serno]更新該筆資料
     * @param array  $arraydata 要更新的資料
     * @param string $md_id     會員編號
     */
    public function updateData(array $arraydata) {
        try {
            //檢查必填欄位
            if (!isset($arraydata['md_id']) || !isset($arraydata['tpr_serno']) || !isset($arraydata['tpr_title']) || !isset($arraydata['tpr_name']) || !isset($arraydata['tpr_birthday']) || !isset($arraydata['tpr_birthdaytime']) || !isset($arraydata['rl_city_code']) || !isset($arraydata['rl_zip']) || !isset($arraydata['tpr_address'])) {
                return false;
            }

            $savedata['tpr_title'] = $arraydata['tpr_title'];
            $savedata['tpr_name'] = $arraydata['tpr_name'];
            $savedata['tpr_birthday'] = $arraydata['tpr_birthday'];
            $savedata['tpr_birthdaytime'] = $arraydata['tpr_birthdaytime'];
            $savedata['rl_city_code'] = $arraydata['rl_city_code'];
            $savedata['rl_zip'] = $arraydata['rl_zip'];
            $savedata['tpr_address'] = $arraydata['tpr_address'];

            if(isset($arraydata['create_user'])){
                $savedata['create_user'] = $arraydata['create_user'];
            }
            if(isset($arraydata['last_update_user'])){
                $savedata['last_update_user'] = $arraydata['last_update_user'];
            }

            $savedata['last_update_date'] = \Carbon\Carbon::now();
            return mb_tpl_Relative::where('tpr_serno',$arraydata['tpr_serno'])->where('md_id',$arraydata['md_id'])->update($savedata);
        } catch (Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

    /**
     * 透過傳入的[md_id]&[tpr_serno]刪除該筆資料
     * @param string $md_id     會員編號
     * @param string $tpr_serno 親屬編號
     */
    public function deleteDataByTprSerno($md_id,$tpr_serno) {
        try {
            if (( is_null($md_id) || strlen($md_id) == 0 ) && ( is_null($tpr_serno) || strlen($tpr_serno) == 0 ) ) {
                return false;
             }
            $savedata['last_update_date'] = \Carbon\Carbon::now();
            $savedata['isflag'] = 0;
            return mb_tpl_Relative::where('md_id',$md_id)->where('tpr_serno',$tpr_serno)->update($savedata);
        } catch (Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

}
