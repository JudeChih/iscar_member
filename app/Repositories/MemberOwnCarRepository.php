<?php

namespace App\Repositories;

use \App\Models\isCarMemberOwnCar;
use DB;

class MemberOwnCarRepository {

     /**
     * ██████████▍CREATE 建立資料
     */
      public function InsertData($arraydata) {
        
        try {
             if (  !\App\Library\CommonTools::CheckArrayValue($arraydata, "moc_id")
                || !\App\Library\CommonTools::CheckArrayValue($arraydata, "md_id")
                || !\App\Library\CommonTools::CheckArrayValue($arraydata, "ncbi_id")
                 ) {
                return false;
              }

              $savadata['moc_id'] = $arraydata['moc_id'];
              $savadata['md_id'] = $arraydata['md_id'];
              $savadata['ncbi_id'] = $arraydata['ncbi_id'];

              if (\App\Library\CommonTools::CheckArrayValue($arraydata, "moc_remark")) {
                $savadata['moc_remark'] = $arraydata['moc_remark'];
              }
              if (\App\Library\CommonTools::CheckArrayValue($arraydata, "moc_manufacturedate")) {
                $savadata['moc_manufacturedate'] = $arraydata['moc_manufacturedate'];
              }
              if (\App\Library\CommonTools::CheckArrayValue($arraydata, "moc_enginenumber")) {
                $savadata['moc_enginenumber'] = $arraydata['moc_enginenumber'];
              }
              if (\App\Library\CommonTools::CheckArrayValue($arraydata, "moc_vin")) {
                $savadata['moc_vin'] = $arraydata['moc_vin'];
              }
              if (\App\Library\CommonTools::CheckArrayValue($arraydata, "moc_maxpassenger")) {
                $savadata['moc_maxpassenger'] = $arraydata['moc_maxpassenger'];
              }
              if (\App\Library\CommonTools::CheckArrayValue($arraydata, "moc_cabseatamount")) {
                $savadata['moc_cabseatamount'] = $arraydata['moc_cabseatamount'];
              }
              if (\App\Library\CommonTools::CheckArrayValue($arraydata, "moc_weightloadlimit")) {
                $savadata['moc_weightloadlimit'] = $arraydata['moc_weightloadlimit'];
              }
              if (\App\Library\CommonTools::CheckArrayValue($arraydata, "moc_grossweight")) {
                $savadata['moc_grossweight'] = $arraydata['moc_grossweight'];
              }
              if (\App\Library\CommonTools::CheckArrayValue($arraydata, "moc_purchasedate")) {
                $savadata['moc_purchasedate'] = $arraydata['moc_purchasedate'];
              }
              if (\App\Library\CommonTools::CheckArrayValue($arraydata, "moc_licenseissuedate")) {
                $savadata['moc_licenseissuedate'] = $arraydata['moc_licenseissuedate'];
              }
              if (\App\Library\CommonTools::CheckArrayValue($arraydata, "moc_licensechangedate")) {
                $savadata['moc_licensechangedate'] = $arraydata['moc_licensechangedate'];
              }
              if (\App\Library\CommonTools::CheckArrayValue($arraydata, "moc_carbodycolor")) {
                $savadata['moc_carbodycolor'] = $arraydata['moc_carbodycolor'];
              }
              if (\App\Library\CommonTools::CheckArrayValue($arraydata, "moc_cartypecode")) {
                $savadata['moc_cartypecode'] = $arraydata['moc_cartypecode'];
              }
              if (\App\Library\CommonTools::CheckArrayValue($arraydata, "moc_carbrandcode")) {
                $savadata['moc_carbrandcode'] = $arraydata['moc_carbrandcode'];
              }
              if (\App\Library\CommonTools::CheckArrayValue($arraydata, "moc_licensenum")) {
                $savadata['moc_licensenum'] = $arraydata['moc_licensenum'];
              }
              if (\App\Library\CommonTools::CheckArrayValue($arraydata, "moc_ownstatus")) {
                $savadata['moc_ownstatus'] = $arraydata['moc_ownstatus'];
              } else {
                $savadata['moc_ownstatus'] = '0' ;
              }
              if (\App\Library\CommonTools::CheckArrayValue($arraydata, "moc_privatedataallow")) {
                $savadata['moc_privatedataallow'] = $arraydata['moc_privatedataallow'];
              } else {
                $savadata['moc_privatedataallow'] = '0' ;
              }
              if (\App\Library\CommonTools::CheckArrayValue($arraydata, "cbl_fullname")) {
                $savadata['cbl_fullname'] = $arraydata['cbl_fullname'];
              }
              if (\App\Library\CommonTools::CheckArrayValue($arraydata, "cbm_fullname")) {
                $savadata['cbm_fullname'] = $arraydata['cbm_fullname'];
              }
              if (\App\Library\CommonTools::CheckArrayValue($arraydata, "cms_fullname")) {
                $savadata['cms_fullname'] = $arraydata['cms_fullname'];
              }
              if (\App\Library\CommonTools::CheckArrayValue($arraydata, "ci_car_year_style")) {
                $savadata['ci_car_year_style'] = $arraydata['ci_car_year_style'];
              }
              if (\App\Library\CommonTools::CheckArrayValue($arraydata, "isflag")) {
                $savadata['isflag'] = $arraydata['isflag'];
              } else {
                $savadata['isflag'] = '1';
              }
              if (\App\Library\CommonTools::CheckArrayValue($arraydata, "create_user")) {
                $savadata['create_user'] = $arraydata['create_user'];
              } else {
                $savadata['create_user'] = 'webapi';
              }
              if (\App\Library\CommonTools::CheckArrayValue($arraydata, "last_update_user")) {
                $savadata['last_update_user'] = $arraydata['last_update_user'];
              } else {
                $savadata['last_update_user'] = 'webapi';
              }
              $savedata['create_date'] = \Carbon\Carbon::now();
              $savedata['last_update_date'] = \Carbon\Carbon::now();
              // DB::beginTransaction();

              if (isCarMemberOwnCar::insert($savadata)) {
                // DB::commit();
                return true;
              } else {
                // DB::rollBack();
                return false;
              }
        } catch (Exception $e) {
            DB::rollBack();
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

    /**
     * ██████████▍READ 讀取資料
     */
     public function QueryMemberCar($md_id,$moc_id) {
        try {
             if (( is_null($md_id) || strlen($md_id) == 0 ) && ( is_null($moc_id) || strlen($moc_id) == 0 ) ) {
                return null;
             }
             $query = isCarMemberOwnCar::leftJoin('iscarmemberowncarpic', function ($join) {
                                                  $join->on('iscarmemberowncar.moc_id', '=', 'iscarmemberowncarpic.moc_id')
                                                       ->where('iscarmemberowncarpic.isflag',1)
                                                       ->where('iscarmemberowncarpic.mocp_picscategory',0);
                                                  });

             if ( !is_null($md_id) && strlen($md_id) != 0 ) {
                $query -> where('iscarmemberowncar.md_id', '=', $md_id);
             }
             if ( !is_null($moc_id) && strlen($moc_id) != 0 ) {
                $query -> where('iscarmemberowncar.moc_id', '=', $moc_id);
             }
             $query -> where('iscarmemberowncar.isflag','1');
             $query -> select( 'iscarmemberowncar.*'
                                      ,'iscarmemberowncarpic.mocp_picpath'
                                      ,'iscarmemberowncarpic.mocp_serno'
                                      ,'iscarmemberowncarpic.mocp_picscategory'
                                     );
             $result = $query->get();
            return $result;
        } catch(\Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
     }
     
      public function QueryMemberCarDetails($md_id,$moc_id) {
        try {
             if (( is_null($md_id) || strlen($md_id) == 0 ) && ( is_null($moc_id) || strlen($moc_id) == 0 ) ) {
                return null;
             }
             $query = isCarMemberOwnCar::leftJoin('iscarmemberowncarpic', function ($join) {
                                                  $join->on('iscarmemberowncar.moc_id', '=', 'iscarmemberowncarpic.moc_id')
                                                       ->whereRaw('iscarmemberowncarpic.isflag = 1 || iscarmemberowncarpic.isflag is null');
                                                  });
             if ( !is_null($md_id) && strlen($md_id) != 0 ) {
                $query -> where('iscarmemberowncar.md_id', '=', $md_id);
             }
             if ( !is_null($moc_id) && strlen($moc_id) != 0 ) {
                $query -> where('iscarmemberowncar.moc_id', '=', $moc_id);
             }

                $query->where('iscarmemberowncar.isflag',1);
                $query -> select( 'iscarmemberowncar.*'
                                  ,'iscarmemberowncarpic.mocp_picpath'
                                  ,'iscarmemberowncarpic.mocp_serno'
                                  ,'iscarmemberowncarpic.mocp_picscategory'
                                 );
             $result = $query->get();
            return $result;
        } catch(\Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
     }

    /**
     * ██████████▍UPDATE 更新資料
     */
      public function UpdateData(array $arraydata) {
        
        try {
            if (!\App\Library\CommonTools::CheckArrayValue($arraydata, "moc_id")) {
                return false;
            }
            $savadata['moc_id'] = $arraydata['moc_id'];

            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "md_id")) {
              $savadata['md_id'] = $arraydata['md_id'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "ncbi_id")) {
              $savadata['ncbi_id'] = $arraydata['ncbi_id'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "moc_remark")) {
              $savadata['moc_remark'] = $arraydata['moc_remark'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "moc_manufacturedate")) {
              $savadata['moc_manufacturedate'] = $arraydata['moc_manufacturedate'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "moc_enginenumber")) {
              $savadata['moc_enginenumber'] = $arraydata['moc_enginenumber'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "moc_vin")) {
              $savadata['moc_vin'] = $arraydata['moc_vin'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "moc_maxpassenger")) {
              $savadata['moc_maxpassenger'] = $arraydata['moc_maxpassenger'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "moc_cabseatamount")) {
              $savadata['moc_cabseatamount'] = $arraydata['moc_cabseatamount'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "moc_weightloadlimit")) {
              $savadata['moc_weightloadlimit'] = $arraydata['moc_weightloadlimit'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "moc_grossweight")) {
              $savadata['moc_grossweight'] = $arraydata['moc_grossweight'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "moc_purchasedate")) {
              $savadata['moc_purchasedate'] = $arraydata['moc_purchasedate'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "moc_licenseissuedate")) {
              $savadata['moc_licenseissuedate'] = $arraydata['moc_licenseissuedate'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "moc_licensechangedate")) {
              $savadata['moc_licensechangedate'] = $arraydata['moc_licensechangedate'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "moc_carbodycolor")) {
              $savadata['moc_carbodycolor'] = $arraydata['moc_carbodycolor'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "moc_cartypecode")) {
              $savadata['moc_cartypecode'] = $arraydata['moc_cartypecode'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "moc_carbrandcode")) {
              $savadata['moc_carbrandcode'] = $arraydata['moc_carbrandcode'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "moc_licensenum")) {
              $savadata['moc_licensenum'] = $arraydata['moc_licensenum'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "moc_ownstatus")) {
              $savadata['moc_ownstatus'] = $arraydata['moc_ownstatus'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "moc_privatedataallow")) {
              $savadata['moc_privatedataallow'] = $arraydata['moc_privatedataallow'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "cbl_fullname")) {
              $savadata['cbl_fullname'] = $arraydata['cbl_fullname'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "cbm_fullname")) {
              $savadata['cbm_fullname'] = $arraydata['cbm_fullname'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "cms_fullname")) {
              $savadata['cms_fullname'] = $arraydata['cms_fullname'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "ci_car_year_style")) {
              $savadata['ci_car_year_style'] = $arraydata['ci_car_year_style'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "isflag")) {
              $savadata['isflag'] = $arraydata['isflag'];
            }
            $savadata['last_update_user'] = 'webapi';
            $savadata['last_update_date'] = \Carbon\Carbon::now();
            isCarMemberOwnCar::where('moc_id', '=', $savadata['moc_id'])
                    ->update($savadata);
            return true;
        } catch (Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

    /**
     * 透過moc_id抓取資料
     * @param  [type] $moc_id [description]
     * @return [type]         [description]
     */
    public function getDataByMocID($moc_id){
        try {
            return isCarMemberOwnCar::where('moc_id','=',$moc_id)->get();
        } catch (Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
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
