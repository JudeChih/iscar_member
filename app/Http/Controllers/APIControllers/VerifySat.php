<?php

namespace App\Http\Controllers\APIControllers;

use Illuminate\Support\Facades\Input;

class VerifySat {

    /**
     * 驗證SAT有效性
     * @param  [string] $modacc [模組帳號]
     * @param  [string] $modvrf  [模組驗證碼]
     * @param  [string] $sat  [用戶登入存取憑證]
     */
    public function verify_sat() {
        $functionName = 'verify_sat';
        $inputString = Input::All();
        if (!is_null($inputString) && count($inputString) != 0 && is_array($inputString)) {
            $inputString = $inputString[0];
        }
        $resultData = null;
        $md_id = null;
        $messageCode = null;
        try {
            // 轉換成陣列並檢查
            $inputData = $this->convertAndCheckApiInput($inputString);
            if (is_null($inputData)) {
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
                $messageCode = $this->GetServiceAccessTokenEffective($inputData['sat']);
                throw new \Exception($messageCode);
            }
            $messageCode = '010110001';
            $resultData['md_id'] = $satData['md_id'];
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
     * 抓取token的狀態
     * @param [string] $sat [會員授權憑證]
     */
    public static function GetServiceAccessTokenEffective($sat){
        $jwt = new \App\Services\JWTService;
        $sat_r = new \App\Repositories\ServiceAccessTokenRepository;
        $messageCode = null;
        try {
            $data = $jwt->decodeToken($sat);
            // 無該會員
            if(!isset($data['md_id'])){
                $messageCode = '010110002';
                return $messageCode;
            }
            $servicetokendata = $sat_r->getDataByMdIDMurID($data['md_id'],$data['mur_id']);
            // 不存在的SAT
            if(is_null($servicetokendata) || count($servicetokendata) == 0){
                $messageCode = '010110002';
                return $messageCode;
            }
            if(count($servicetokendata) != 0){
              $servicetokendata = $servicetokendata[0];
            }
            // SAT已逾期
            if($servicetokendata['sat_effective'] == 2 ){
                $messageCode = '010110002';
                return $messageCode;
            }
            // SAT已登出
            if($servicetokendata['sat_effective'] == 3 ){
                $messageCode = '010110003';
                return $messageCode;
            }
            $messageCode = '010110002';
            return $messageCode;
        } catch (\Exception $ex) {
            CommonTools::writeErrorLogByException($ex);
            return false;
        }

    }
}
