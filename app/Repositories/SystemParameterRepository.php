<?php

namespace App\Repositories;

use \App\Models\icr_SystemParameter;

class SystemParameterRepository {

    public function InsertData($arraydata){

      try {
             if (!\App\Library\CommonTools::CheckArrayValue($arraydata, "sp_fitmodule") || !\App\Library\CommonTools::CheckArrayValue($arraydata, "sp_modulename")
              || !\App\Library\CommonTools::CheckArrayValue($arraydata, "sp_fitfunction")  || !\App\Library\CommonTools::CheckArrayValue($arraydata, "sp_functionname")
              || !\App\Library\CommonTools::CheckArrayValue($arraydata, "sp_parameterkey")  || !\App\Library\CommonTools::CheckArrayValue($arraydata, "sp_paramatervalue")
              || !\App\Library\CommonTools::CheckArrayValue($arraydata, "sp_paramatertype")  || !\App\Library\CommonTools::CheckArrayValue($arraydata, "sp_paramaterdescribe")) {
                return false;
              }
              $savadata['sp_fitmodule'] = $arraydata['sp_fitmodule'];
              $savadata['sp_modulename'] = $arraydata['sp_modulename'];
              $savadata['sp_fitfunction'] = $arraydata['sp_fitfunction'];
              $savadata['sp_functionname'] = $arraydata['sp_functionname'];
              $savadata['sp_parameterkey'] = $arraydata['sp_parameterkey'];
              $savadata['sp_paramatervalue'] = $arraydata['sp_paramatervalue'];
              $savadata['sp_paramatertype'] = $arraydata['sp_paramatertype'];
              $savadata['sp_paramaterdescribe'] = $arraydata['sp_paramaterdescribe'];

              if (\App\Library\CommonTools::CheckArrayValue($arraydata, "isflag")) {
                $savadata['isflag'] = $arraydata['isflag'];
              } else {
                $savadata['isflag'] = '1';
              }
              $savadata['create_user'] = 'webapi';
              $savadata['last_update_user'] = 'webapi';
              icr_SystemParameter::insert($savadata);
              return true;
       } catch(Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
       }
    }



    /**
     * 修改資料
     * @param array $arraydata 要更新的資料
     * @return boolean
     */
    public function UpdateData(array $arraydata) {

        try {
            if (!\App\Library\CommonTools::CheckArrayValue($arraydata, 'sp_serno')) {
                return false;
            }

            $savedata['sp_serno'] = $arraydata['sp_serno'];

            if (\App\Library\CommonTools::CheckArrayValue($arraydata, 'sp_fitmodule')) {
                $savedata['sp_fitmodule'] = $arraydata['sp_fitmodule'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, 'sp_modulename')) {
                $savedata['sp_modulename'] = $arraydata['sp_modulename'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, 'sp_fitfunction')) {
                $savedata['sp_fitfunction'] = $arraydata['sp_fitfunction'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, 'sp_functionname')) {
                $savedata['sp_functionname'] = $arraydata['sp_functionname'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, 'sp_parameterkey')) {
                $savedata['sp_parameterkey'] = $arraydata['sp_parameterkey'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, 'sp_paramatervalue')) {
                $savedata['sp_paramatervalue'] = $arraydata['sp_paramatervalue'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, 'sp_paramatertype')) {
                $savedata['sp_paramatertype'] = $arraydata['sp_paramatertype'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, 'sp_paramaterdescribe')) {
                $savedata['sp_paramaterdescribe'] = $arraydata['sp_paramaterdescribe'];
            }

            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "isflag")) {
                $savedata['isflag'] = $arraydata['isflag'];
            }

            $savedata['last_update_user'] = 'webapi';


            icr_SystemParameter::where('sapd_serno', $savedata['sapd_serno'])
                    ->update($savedata);
            return true;
        } catch (Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return false;
        }
    }



    public function GetFunctionParamater($sp_fitmodule, $sp_fitfunction) {
        try {
              if ( is_null($sp_fitmodule) || mb_strlen($sp_fitmodule) == 0 || is_null($sp_fitfunction) || mb_strlen($sp_fitfunction) == 0 ) {
                  return null;
              }
              $result = icr_SystemParameter::where('icr_systemparameter.sp_fitmodule', '=', $sp_fitmodule)
                                           ->where('icr_systemparameter.sp_fitfunction','=',$sp_fitfunction)
                                           ->get()->toArray();
             return $result;
        } catch(\Exception $e) {
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
