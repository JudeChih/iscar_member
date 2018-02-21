<?php

namespace App\Repositories;

use App\Models\isCarServiceAccessToken;

class ServiceAccessTokenRepository {

    //新增資料
    public function InsertData($arraydata, &$sat_serno) {

        try {

            if (!\App\Library\CommonTools::CheckArrayValue($arraydata, "sat_apptype") || !\App\Library\CommonTools::CheckArrayValue($arraydata, "md_id") || !\App\Library\CommonTools::CheckArrayValue($arraydata, "mur_id")) {
                return false;
            }

            $savedata['sat_apptype'] = $arraydata['sat_apptype'];
            $savedata['md_id'] = $arraydata['md_id'];
            $savedata['mur_id'] = $arraydata['mur_id'];



            if (!\App\Library\CommonTools::CheckArrayValue($arraydata, "sat_token")) {
                $savedata['sat_token'] = str_replace( '-' ,'' ,\App\Library\CommonTools::NewGUID());
            } else {
                $savedata['sat_token'] = $arraydata['sat_token'];
            }
            if (!\App\Library\CommonTools::CheckArrayValue($arraydata, "sat_effective")) {
                $savedata['sat_effective'] = '1';
            } else {
                $savedata['sat_effective'] = $arraydata['sat_effective'];
            }

            if (!\App\Library\CommonTools::CheckArrayValue($arraydata, "sat_expiredate")) {
                $date = new \DateTime('now');
                $savedata['sat_expiredate'] = $date->add(new \DateInterval('P1D'))->format('Y-m-d H:i:s');
            } else {
                $savedata['sat_expiredate'] = $arraydata['sat_expiredate'];
            }


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


            //新增資料並回傳「自動遞增KEY值」
            $result = isCarServiceAccessToken::insertGetId($savedata);

            if (!is_null($result) && strlen($result) != 0) {
                $sat_serno = $result;

                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return false;
        }
    }

    //修改資料
    public function UpdateData($arraydata) {

        try {

            if (!\App\Library\CommonTools::CheckArrayValue($arraydata, "sat_serno") || !\App\Library\CommonTools::CheckArrayValue($arraydata, "sat_apptype") || !\App\Library\CommonTools::CheckArrayValue($arraydata, "md_id") || !\App\Library\CommonTools::CheckArrayValue($arraydata, "mur_id")) {
                return false;
            }

            $savedata['sat_serno'] = $arraydata['sat_serno'];
            $savedata['sat_apptype'] = $arraydata['sat_apptype'];
            $savedata['md_id'] = $arraydata['md_id'];
            $savedata['mur_id'] = $arraydata['mur_id'];

            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "sat_token")) {
                $savedata['sat_token'] = $arraydata['sat_token'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "sat_effective")) {
                $savedata['sat_effective'] = $arraydata['sat_effective'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "sat_expiredate")) {
                $savedata['sat_expiredate'] = $arraydata['sat_expiredate'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "isflag")) {
                $savedata['isflag'] = $arraydata['isflag'];
            }

        $savedata['last_update_user'] = 'webapi';

            isCarServiceAccessToken::where('sat_serno', $savedata['sat_serno'])
                            ->update($savedata);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 抓取裝置推播ID
     * @param  [string]  $md_id  [會員編號]
     * @return [boolean] true or false
     */
    public function getGcmIdByMdID($md_id){
        return isCarServiceAccessToken::join('iscarmobileunitrec','iscarserviceaccesstoken.mur_id','iscarmobileunitrec.mur_id')
                                        ->where('mur_systemtype',"!=",2)
                                        ->where('sat_expiredate',">=",\Carbon\Carbon::now())
                                        ->where('iscarserviceaccesstoken.isflag',1)
                                        ->where('iscarserviceaccesstoken.md_id',$md_id)
                                        ->orderBy('iscarserviceaccesstoken.last_update_date',"DESC")
                                        ->select('iscarmobileunitrec.mur_gcmid','iscarmobileunitrec.mur_systemtype')
                                        ->first();
    }


    //取得資料，使用「sat_serno」
    public function GetAccessData($sat_serno) {

        if ($sat_serno == null || strlen($sat_serno) == 0) {
            return null;
        }

        $results = isCarServiceAccessToken::where('isflag', '1')
                ->where('sat_serno', $sat_serno)
                ->get()
                ->toArray();
        return $results;
    }

    //取得資料，使用「MD_ID」、「MUR_ID」
    public function GetDataByMDID_MURID($sat_apptype, $md_id, $mur_id) {
        if ( $md_id == null || strlen($md_id) == 0 || $mur_id == null || strlen($mur_id) == 0) {
            return null;
        }

        $results = isCarServiceAccessToken::where('isflag', '1')
                ->where('sat_effective', '1')
                ->where('sat_apptype', $sat_apptype)
                ->where('md_id', $md_id)
                ->where('mur_id', $mur_id)
                ->get()
                ->toArray();
        return $results;
    }

    //取得資料，使用「Sat_Token」
    public function GetDataBySat_Token($sat_token) {
        if ($sat_token == null || strlen($sat_token) == 0) {
            return null;
        }

        $results = isCarServiceAccessToken::where('isflag', '1')
                ->where('sat_effective', '1')
                ->where('sat_token', $sat_token)
                ->get()
                ->toArray();
        return $results;
    }

    /**
     * 透過md_id和mur_id取得最新servicetoken
     * @param  [string] $md_id  [會員代碼]
     * @param  [string] $mur_id [設備代碼]
     * @return [type]        [description]
     */
    public function getDataByMdIDMurID($md_id,$mur_id){
        return isCarServiceAccessToken::where('isflag',1)->where('md_id',$md_id)->where('mur_id',$mur_id)->orderBy('sat_serno','DESC')->get();
    }

    /**
     * 更新SAT狀態，設定為已登出
     * @param [string] $md_id
     * @param [string] $mur_id
     */
    public function UpdateServiceAccessTokenEffective($md_id,$mur_id){
        try {
            $savedata['sat_effective'] = 3;
            return isCarServiceAccessToken::where('md_id',$md_id)->where('mur_id',$mur_id)->update($savedata);
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return false;
        }
    }

    /**
     * 取得所有資料
     * @return type
     */
    public function getAllData() {
        return isCarServiceAccessToken::get();
    }

    /**
     * 使用「$primarykey」查詢資料表的主鍵值
     * @param type $primarykey 要查詢的值
     * @return type
     */
    public function getData($primarykey) {
        return isCarServiceAccessToken::find($primarykey);
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
            if (!isset($arraydata['sat_apptype']) || !isset($arraydata['md_id']) || !isset($arraydata['mur_id'])) {
                return null;
            }
            //將資料填入對應的欄位
            $savedata['sat_apptype'] = $arraydata['sat_apptype'];
            $savedata['md_id'] = $arraydata['md_id'];
            $savedata['mur_id'] = $arraydata['mur_id'];
            if (isset($arraydata['sat_token'])) {
                $savedata['sat_token'] = $arraydata['sat_token'];
            } else {
                $savedata['sat_token'] = \App\Library\CommonTools::generateGUID(false);
            }
            if (isset($arraydata['sat_expiredate'])) {
                $savedata['sat_expiredate'] = $arraydata['sat_expiredate'];
            } else {
                $savedata['sat_expiredate'] = \Carbon\Carbon::now()->addMonths(1);
            }

            //$savedata['sat_effective'] = 1;

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
            return isCarServiceAccessToken::insertGetId($savedata);
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
     * 將同「會員代碼」、「設備代碼」資料的〔isfalg〕全設為〈０〉
     * @param type $md_id 會員代碼
     * @param type $mur_id 設備代碼
     * @return boolean 執行結果
     */
    public function updateIsFlagToZeroByMdIDMurID($md_id, $mur_id) {
        try {
            isCarServiceAccessToken::where('md_id', '=', $md_id)
                    ->where('mur_id', '=', $mur_id)
                    ->update(array('isflag' => 0));

            return true;
        } catch (\Exception $ex) {
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
