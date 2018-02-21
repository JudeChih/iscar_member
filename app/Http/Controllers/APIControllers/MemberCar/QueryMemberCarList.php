<?php

namespace App\Http\Controllers\APIControllers\MemberCar;

use Request;
use DB;

class QueryMemberCarList {

    /**
     * 會員車庫列表
     * @param  [string] $modacc [模組帳號]
     * @param  [string] $modvrf [模組驗證碼]
     * @param  [string] $sat    [用戶登入存取憑證]
     */
    public function query_member_car_list() {
        $functionName = 'query_member_car_list';
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
            // 驗證SAT
            if(!$satData = \App\Library\CommonTools::CheckServiceAccessToken($inputData['sat'])){
                $messageCode = '999999960';
                throw new \Exception($messageCode);
            }
            // 抓取會員車輛資訊
            $querydata = \App\Library\CommonTools::QueryMemberOwnCars($satData['md_id'], $messageCode);
            if (!is_null($messageCode)) {
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
    public function convertAndCheckApiInput($inputString) {
        $inputData = \App\Library\CommonTools::ConvertStringToArray($inputString);
        if ($inputData == null) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'modacc', 0, false, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'modvrf', 0, false, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'sat', 0, false, false)) {
            return false;
        }
        return $inputData;
    }

    public function CreateResultData($querydata) {
        foreach ($querydata as $row) {
           $resultData['owncar_list'][] = [
                                             'moc_id'            => $row['moc_id']
                                            ,'ncbi_id'           => $row['ncbi_id']
                                            ,'moc_purchasedate'  => $row['moc_purchasedate']
                                            ,'mocp_picpath'      => $row['mocp_picpath']
                                            ,'moc_ownstatus'     => $row['moc_ownstatus']
                                            ,'create_date'       => $row['create_date']
                                            ,'cbl_fullname'      => $row['cbl_fullname']
                                            ,'cbm_fullname'      => $row['cbm_fullname']
                                            ,'cms_fullname'      => $row['cms_fullname']
                                            ,'ci_car_year_style' => $row['ci_car_year_style']
                                          ];
        }
        return $resultData;
    }

}
