<?php

namespace App\Http\Controllers\APIControllers\MemberCar;

use Request;
use DB;

class QueryMemberCarDetail {

    /**
     * 會員車庫詳細內容
     * @param  [string] $modacc [模組帳號]
     * @param  [string] $modvrf [模組驗證碼]
     * @param  [string] $sat    [用戶登入存取憑證]
     * @param  [string] $moc_id [會員車輛持有記錄編號ID]
     */
    public function query_member_car_detail() {
        $functionName = 'query_member_car_detail';
        $inputString = Request::All();
        if (!is_null($inputString) && count($inputString) != 0 && is_array($inputString)) {
            $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        try {
            // 轉換成陣列並檢查
            $inputData = $this->convertAndCheckApiInput($inputString);
            if (!is_array($inputData)) {
                $messageCode = '999999995';
                throw new \Exception($messageCode);
            }
            // 模組身分驗證
            if(!\App\Library\CommonTools::checkModuleAccount($inputData['modacc'],$inputData['modvrf'])){
                $messageCode = '999999961';
                throw new \Exception($messageCode);
            }
            if(isset($inputData['sat'])){
                // 驗證SAT
                if(!$satData = \App\Library\CommonTools::CheckServiceAccessToken($inputData['sat'])){
                    $messageCode = '999999960';
                    throw new \Exception($messageCode);
                }
                $md_id = $satData['md_id'];
            }else{
                // 透過moc_id抓取md_id
                $moc_r = new \App\Repositories\MemberOwnCarRepository;
                $mocData = $moc_r->getDataByMocID($inputData['moc_id']);
                if(count($mocData) == 1){
                    $mocData = $mocData[0];
                }else{
                    //資料有誤，不是沒有資料就是有重複的資料
                    $messageCode = '011300001';
                    throw new \Exception($messageCode);
                }
                $md_id = $mocData['md_id'];
            }

            // 驗證用戶修改權限
            if(!$querydata = $this->CheckMemberOwnCar($md_id, $inputData['moc_id'], $messageCode)){
              throw new \Exception($messageCode);
            }
            // 編輯要回傳的資料
            $resultData = $this->CreateResultData($querydata);
            $messageCode = '000000000';
        } catch (\Exception $e) {
            if (is_null($messageCode)) {
                $messageCode = '999999999';
                \App\Library\CommonTools::writeErrorLogByException($e);
            }
        }
        //回傳值
        $resultArray = \App\Library\CommonTools::ResultProcess($messageCode, $resultData);
        \App\Library\CommonTools::WriteExecuteLog($functionName, $inputString, json_encode($resultArray), $messageCode);
        $result = [$functionName . 'result' => $resultArray];
        return $result;
    }

    /**
     * 檢查輸入值是否正確
     * @param type $value
     * @return boolean
     */
    public static function convertAndCheckApiInput($inputString) {
        $inputData = \App\Library\CommonTools::ConvertStringToArray($inputString);
        if ($inputData == null) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($inputData, 'modacc', 0, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($inputData, 'modvrf', 0, false, false)) {
            return false;
        }
        // if (!\App\Library\Commontools::CheckRequestArrayValue($inputData, 'sat', 0, false, false)) {
        //     return false;
        // }
        if (!\App\Library\Commontools::CheckRequestArrayValue($inputData, 'moc_id', 32, false, false)) {
            return false;
        }
        return $inputData;
    }

    /**
     * 驗證用戶修改權限
     * @param [string] $md_id        [會員代碼]
     * @param [string] $moc_id       [車輛記錄編號]
     * @param [string] $messageCode  [錯誤代碼]
     */
    public function CheckMemberOwnCar($md_id, $moc_id, &$messageCode) {
        $oc_r = new \App\Repositories\MemberOwnCarRepository;
        try {
            $querydata = $oc_r->QueryMemberCarDetails($md_id, $moc_id);
            if (is_null($querydata) || count($querydata) == 0 ) {
                $messageCode = '011300001';
                return false;
            }
            // else if ( $querydata[0]['isflag'] == 0 ) {
            //     $messageCode = '011300002';
            //     return false;
            // }
            return $querydata;
        } catch(\Exception $e) {
            CommonTools::writeErrorLogByException($e);
            return null;
        }
    }

    /**
     * 編排好要回傳的資料
     * @param [type] $querydata [description]
     */
    public static function CreateResultData($querydata) {
           $resultData['moc_id'] = $querydata[0]['moc_id'];
           $resultData['ncbi_id'] = $querydata[0]['ncbi_id'];
           $resultData['moc_purchasedate'] = $querydata[0]['moc_purchasedate'];
           $resultData['moc_enginenumber'] = $querydata[0]['moc_enginenumber'];
           $resultData['moc_vin'] = $querydata[0]['moc_vin'];
           $resultData['moc_cartypecode'] = $querydata[0]['moc_cartypecode'];
           //$resultData['moc_cartypedescript'] = $querydata['moc_cartypedescript'];
           $resultData['moc_carbodycolor'] = $querydata[0]['moc_carbodycolor'];
           $resultData['moc_remark'] = $querydata[0]['moc_remark'];
           $resultData['moc_ownstatus'] = $querydata[0]['moc_ownstatus'];
           $resultData['create_date'] = $querydata[0]['create_date'];
           $resultData['cbl_fullname'] = $querydata[0]['cbl_fullname'];
           $resultData['cbm_fullname'] = $querydata[0]['cbm_fullname'];
           $resultData['cms_fullname'] = $querydata[0]['cms_fullname'];
           $resultData['ci_car_year_style'] = $querydata[0]['ci_car_year_style'];
           $resultData['moc_licensenum'] = $querydata[0]['moc_licensenum'];

           foreach ($querydata as $row) {
               $resultData['mocp_pic'][] = [
                                              'mocp_serno'        => $row['mocp_serno']
                                             ,'mocp_picpath'      => $row['mocp_picpath']
                                             ,'mocp_picscategory' => $row['mocp_picscategory']
                                           ];

           }
        return $resultData;
    }

}
