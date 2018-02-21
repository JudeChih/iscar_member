<?php

namespace App\Http\Controllers\APIControllers\CrossModelAPI;

use Illuminate\Support\Facades\Input;

class QueryMemberCoinInfo {

     public function querymembercoininfo() {
        $functionName = 'querymembercoininfo';
        $inputString = Input::All();
        $inputData = \App\Library\CommonTools::ConvertStringToArray($inputString);
        if (!is_null($inputString) && count($inputString) != 0 && is_array($inputString)) {
            $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        try {
            if ($inputData == null) {
                $messageCode = '999999995';
                throw new \Exception($messageCode);
            }
            //檢查輸入值
            if (!$this->CheckInput($inputData)) {//輸入值有問題
                $messageCode = '999999995';
                throw new \Exception($messageCode);
            }
            //檢查JWT驗正。
            if( !\App\Services\JWTService::JWTokenVerification($inputData['api_token'], $messageCode)) {
                throw new \Exception($messageCode);
            }
            //檢查Token、DeviceCode
            if (!\App\Library\CommonTools::CheckAccessTokenDeviceCode($inputData['servicetoken'], $inputData['userdevicecode'], $md_id, $messageCode)) {
                throw new \Exception($messageCode);
            }

            if (!\App\models\IsCarCoinStock::GetStockByMDID($md_id, $coin, $bonus)) {
                $messageCode = '999999999';
                throw new \Exception($messageCode);
            }

            $resultData = $this->CreateResultData($md_id);
            $messageCode = '000000000';
        } catch (\Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            if (!isset($messageCode) || is_null($messageCode)) {
                $messageCode = '999999999';
            }
        }
        if (!isset($resultData)) {
            $resultData = null;
        }

        //回傳值
        $resultArray = \App\Library\CommonTools::ResultProcess($messageCode, $resultData);
        \App\Library\CommonTools::WriteExecuteLog($functionName, $inputString, json_encode($resultArray), $messageCode);
        $result = [ $functionName.'result' => $resultArray];

        return $result;
    }


    /**
     * 檢查傳入資料
     * @param type $value 傳入資料
     * @return boolean 檢查結果
     */
    public function CheckInput(&$value) {
        if (is_null($value)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($value, 'api_token', 0, false, false)){
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($value, 'servicetoken', 0, false, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($value, 'userdevicecode', 0, false, false)) {
            return false;
        }

        return true;
    }

    /**
     * 建立回傳資料
     * @param type $md_id
     * @return type
     */
    public function CreateResultData($md_id) {
        try {
            $resultData['cos_end_coin'] = 0;
            $resultData['cos_end_giftpoint'] = 0;
            $resultData['cos_end_exp'] = 0;
            //$resultData['factorybonus'] = null;

            //0：代幣點數
            $querydata = \App\models\IsCarCoinStock::GetStockByMDID_COSTypeQ($md_id, 0 , 0);
            if (count($querydata) != 0) {
                $resultData['cos_end_coin'] = $querydata[0]['cos_end'];
            }

            //1：APP禮點
            $querydata = \App\models\IsCarCoinStock::GetStockByMDID_COSTypeQ($md_id, 1 , 0);
            if (count($querydata) != 0) {
                $resultData['cos_end_giftpoint'] = $querydata[0]['cos_end'];
            }

            //3：經驗值
            $querydata = \App\models\IsCarCoinStock::GetStockByMDID_COSTypeQ($md_id, 3 , 0);
            if (count($querydata) != 0) {
                $resultData['cos_end_exp'] = $querydata[0]['cos_end'];
            }

            //2：活動券紅利
            /*$querydata = \App\models\IsCarCoinStock::GetStockByMDID_COSType($md_id, 2);
            if (count($querydata) != 0) {

                foreach ($querydata as $rowdata) {
                    $array[] = array(
                        'fd_id' => ($rowdata['cos_fdid_link']), 'cos_end_fdbonus' => ($rowdata['cos_end'])
                    );
                }
                $resultData['factorybonus'] = $array;
            }*/

            return $resultData;
        } catch (Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return null;
        }
    }

}
