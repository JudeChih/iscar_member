<?php

namespace App\Http\Controllers\APIControllers;

use Illuminate\Support\Facades\Input;

class RefreshServiceToken {

    /**
     * 當用戶SAT過期時,呼叫此API進行更新
     * @param  [string] $modacc      [模組帳號]
     * @param  [string] $modvrf       [模組驗證碼]
     * @param  [string] $sat       [用戶登入存取憑證]
     * @return [type] [description]
     */
    public function refresh_servicetoken() {
        $functionName = 'refresh_servicetoken';
        $inputString = Input::All();
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
            if(!$data = $this->checkServiceAccessToken($inputData['sat'])){
                $messageCode = '010110001';
                throw new \Exception($messageCode);
            }
            // 建立新的SAT
            if(!$sat = \App\Library\CommonTools::createServiceAccessToken($data->md_id,$data->mur_id)){
                $messageCode = '010110002';
                throw new \Exception($messageCode);
            }
            $messageCode = '000000000';
            $resultData['newservicetoken'] = $sat;
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
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'modacc', 20, false, false)) {
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

    /**
     * 檢查SAT。若為過期的Token，需使用字串拆解的方式取得裡面的值來檢查
     * @param  [string] $sat [Service Access Token]
     * @return [mixed]  $satData or false
     */
    public function checkServiceAccessToken($sat) {
        try {
            $jwt = new \App\Services\JWTService;
            $sat_r = new \App\Repositories\ServiceAccessTokenRepository;
            // decode SAT
            $data = $jwt->decodeToken($sat);
            if($data == 'expired'){
              $tokenarray = explode(".",$sat);
              if(count($tokenarray) != 3){
                return false;
              }
              $arraydata = json_decode(base64_decode($tokenarray[1]));
              $data = $arraydata->data;
            }
            // 透過md_id mur_id抓取資料庫中的SAT
            $satdata = $sat_r->getDataByMdIDMurID($data->md_id,$data->mur_id);
            if(is_null($satdata) || count($satdata) == 0){
              return false;
            }
            if(count($satdata) != 0){
              $satdata = $satdata[0];
            }
            if($satdata['sat_token'] != $sat){
              return false;
            }
            return $satdata;
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return false;
        }
    }

}
