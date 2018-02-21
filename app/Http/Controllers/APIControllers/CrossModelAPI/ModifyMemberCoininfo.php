<?php

namespace App\Http\Controllers\APIControllers\CrossModelAPI;

use Illuminate\Support\Facades\Input;
use DB;

class ModifyMemberCoininfo {

     public function modifymembercoininfo() {

        $functionName = 'modifymembercoininfo';
        $inputString = Input::All();
        $inputData = \App\Library\CommonTools::ConvertStringToArray($inputString);

        $resultData = null;
        $messageCode = null;
        DB::beginTransaction();
        try {
            if (!is_null($inputString) && count($inputString) != 0 && is_array($inputString)) {
               $inputString = $inputString[0];
            }
            if ($inputData == null) {
                $messageCode = '999999995';
                throw new \Exception($messageCode);
            }
            //檢查輸入值
            if (!$this->CheckRequest_CoinInfoQuery($inputData)) {//輸入值有問題
                $messageCode = '999999995';
                throw new \Exception($messageCode);
            }
            //檢查JWT驗正。
            if( !\App\Services\JWTService::JWTokenVerification($inputData['api_token'], $messageCode)) {
                throw new \Exception($messageCode);
            }return 1;
            //檢查Token、DeviceCode
            if (!\App\Library\CommonTools::CheckAccessTokenDeviceCode($inputData['servicetoken'], $inputData['userdevicecode'], $md_id, $messageCode)) {
                throw new \Exception($messageCode);
            }
            $cos_type      = $inputData['userpay']['modify_way'];
            $modify_type   = $inputData['userpay']['modify_type'];
            $modify_way    = $cos_type;
            $modify_amount = $inputData['userpay']['modify_amount'];
            //檢查 SignContent
            if (! $this->CheckSignContent($md_id, $inputData['salt_no'], $inputData['sign_content'], $inputData['userpay'], $messageCode) ) {
                throw new \Exception($messageCode);
            }
            if (! $this->CheckModiftyType($modify_type, $modify_way, $md_id, $cos_type, $modify_amount, $cos_end, $pmr_modify, $messageCode)) {
                throw new \Exception($messageCode);
            }
            //記錄領取寶箱記錄
            if (! $this->InsertBoxUserCoinModifyR($md_id, $inputData['userpay'], $bucm_id)) {
                throw new \Exception($messageCode);
            }
            //異動庫存資料並建立庫存異動檔
            if (!\App\Library\CommonTools::UpdateStockAndModifyRecord($pmr_modify, $cos_type, $md_id, $bucm_id, $modify_amount, $cos_end)) {
               throw new \Exception($messageCode);
            }
            $messageCode = '000000000';
        } catch (\Exception $e) {
             DB::rollBack();
             if (is_null($messageCode)) {
                $messageCode = '999999999';
                \App\Library\CommonTools::writeErrorLogByException($e);
            }
        }
         DB::commit();
         $resultArray = \App\Library\CommonTools::ResultProcess($messageCode, $resultData);
         \App\Library\CommonTools::WriteExecuteLog($functionName, $inputString, json_encode($resultArray), $messageCode);
         $result = [$functionName . 'result' => $resultArray];
         return $result;
    }


    /**
     * 檢查傳入資料
     * @param type $value 傳入資料
     * @return boolean 檢查結果
     */
    public function CheckRequest_CoinInfoQuery(&$value) {
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
        /*if (!\App\Library\CommonTools::CheckRequestArrayValue($value, 'userpay', 0, false, false)) {
            return false;
        } */
        if (!\App\Library\CommonTools::CheckRequestArrayValue($value, 'salt_no', 0, false, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($value, 'sign_content', 0, false, false)) {
            return false;
        }
       /* if (!\App\Library\CommonTools::CheckRequestArrayValue($value['userpay'], 'modify_type', 0, false, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($value['userpay'], 'modify_amount', 0, false, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($value['userpay'], 'modify_way', 0, false, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($value['userpay'], 'modify_record', 0, false, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($value['userpay'], 'modify_time', 0, false, false)) {
            return false;
        }*/

        return true;
    }

    /**
     * 檢查signcontent
     * @param type $md_id
     * @param type $serno
     * @param type $signContent
     * @param type $userpay
     * @param type $messageCode
     * @return boolean 檢查結果
     */
    private function CheckSignContent($md_id, $serno, $signContent, $userpay, &$messageCode) {
        $passwordsalt = new \App\Repositories\PasswordSalt_rRepository;
        try {
            $queryData = $passwordsalt->GetSaltBySerno($serno);
            if (is_null($queryData) || count($queryData) == 0 ) {
                $messageCode = '040100001';
                return false;
            } else if (count($queryData) > 1) {
                $messageCode = '999999986';
                return false;
            } else if ($signContent != Hash('sha256',  str_replace("\\",'',json_encode($userpay)).$queryData[0]['psr_salt'])) {
                $messageCode = '040101001';
                return false;
            }
            return true;
        } catch(\Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

    public function CheckModiftyType($modify_type, $modify_way, $md_id, $cos_type, $modify_amount, &$cos_end, &$pmr_modify, &$messageCode) {
       try {
            if ( in_array($modify_type,array(1,2,3,5) )) {
               if (! $this->CheckCoinStock($md_id, $cos_type, $modify_amount, $cos_end, $messageCode) ) {
                   return false;
               }
               $pmr_modify = 9;
            } else if ( $modify_type == 4 ) {
               if ($modify_way != 1 ) {
                  $messageCode = '999999989';
                  return false;
               } else if( 1 > $modify_amount || $modify_amount > 990000 ) {
                  $messageCode = '999999978';
                  return false;
               }
               $this->CheckCoinStock($md_id, $cos_type, $modify_amount, $cos_end, $messageCode);
               $pmr_modify = 10;
            } else {
              $messageCode = '999999989';
              return false;
            }
            return true;
       } catch (\Exception $e) {
          \App\Library\CommonTools::writeErrorLogByException($e);
          return false;
       }
    }

    /**
     * 檢查點數餘額，並返回剩餘點數
     * @param type $md_id
     * @param type $cos_type
     * @param type $pay_amount
     * @param type $cos_end
     * @return boolean 檢查結果
     */
    private function CheckCoinStock($md_id, $cos_type, $modify_amount, &$cos_end, &$messageCode) {
        try {
            $queryData = \App\models\IsCarCoinStock::GetStockByMDID_COSType($md_id, $cos_type);
            $cos_end = $queryData[0]['cos_end'];
            if ($modify_amount > $cos_end ) {
               $messageCode = '999999981';
               return false;
            }
            return true;
        } catch(\Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

    /**
     * 記錄寶箱消費紀錄
     * @param type $md_id
     * @param type $arrayData
     * @param type $bmpr_id
     * @return boolean 檢查結果
     */
    private function InsertBoxUserCoinModifyR($md_id, $arrayData, &$bucm_id) {
        try {
           $bucm_id = \App\Library\CommonTools::NewGUIDWithoutDash();
           $insertdata = [
                          'bucm_id'            => $bucm_id,
                          'md_id'              => $md_id,
                          'bucm_modify_type'   => $arrayData['modify_type'],
                          'bucm_modify_amount' => $arrayData['modify_amount'],
                          'bucm_modify_way'    => $arrayData['modify_way'],
                          'bucm_modify_record' => $arrayData['modify_record'],
                          'bucm_modify_time'   => $arrayData['modify_time'],
                         ];
           return \App\models\ICR_BoxUserCoinModify_R::InsertData($insertdata);
        } catch(\Exception $e) {
          \App\Library\CommonTools::writeErrorLogByException($e);
          return false;
        }
    }
}
