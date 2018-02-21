<?php

namespace App\Http\Controllers\APIControllers;

use Illuminate\Support\Facades\Input;

class QueryMember {

    /**
     * 有在使用這API的模組 iscar_admin
     */

    /**
     * 驗證該會員是否存在
     * @param  [string] $modacc           [模組帳號]
     * @param  [string] $modvrf           [模組驗證碼]
     * @param  [string] $md_regiestmobile [註冊時所用的電話]
     * @param  [string] $md_contactmail   [會員聯絡信箱]
     */
    public function query_member() {
        $functionName = 'query_member';
        $inputString = Input::All();
        if (!is_null($inputString) && count($inputString) != 0 && is_array($inputString)) {
            $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        $md_r = new \App\Repositories\MemberDataRepository;
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
            // 透過傳入的md_regiestmobile以及md_contactmail搜尋完全符合的會員
            if(! $userdata = $md_r->getDataByMobileContactmail($inputData['md_regiestmobile'],$inputData['md_contactmail'])){
                $messageCode = '010160001'; // 傳入的值異常
                throw new \Exception($messageCode);
            }
            if(count($userdata) == 0 || count($userdata) > 1){
                $messageCode = '010160002'; // 查無會員或是會員資料異常
                throw new \Exception($messageCode);
            }
            if(count($userdata) == 1){
                $userdata = $userdata[0];
            }

            $messageCode = '000000000';
            $resultData['md_account'] = $userdata['md_account'];
            $resultData['md_id'] = $userdata['md_id'];
            $resultData['md_logintype'] = $userdata['md_logintype'];
            $resultData['md_clienttype'] = $userdata['md_clienttype'];
            $resultData['md_cname'] = $userdata['md_cname'];
            $resultData['md_ename'] = $userdata['md_ename'];
            $resultData['md_tel'] = $userdata['md_tel'];
            $resultData['md_regiestmobile'] = $userdata['md_regiestmobile'];
            $resultData['md_addr'] = $userdata['md_addr'];
            $resultData['md_contactmail'] = $userdata['md_contactmail'];

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
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'md_regiestmobile', 10, false, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'md_contactmail', 0, false, false)) {
            return false;
        }
        return $inputData;
    }
}
