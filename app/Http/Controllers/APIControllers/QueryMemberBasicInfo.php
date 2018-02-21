<?php
namespace App\Http\Controllers\APIControllers;

use Illuminate\Http\Request;

class QueryMemberBasicInfo {

    /**
     * 會員資料查詢
     * @param  [string] $sat   [用戶服務存取憑證]
     * @param  [string] $mur   [用戶運行環境編號]
     */
    public function query_member_basicinfo(Request $request) {
        $functionName = 'query_member_basicinfo';
        $inputString = $request->all();
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
            $jwt = new \App\Services\JWTService;
            $data = $jwt->decodeToken($inputData['sat']);
            // 判斷SAT裡面的mur_id跟傳入的mur_id是否相同
            if($data['mur_id'] != $inputData['mur']){
                $messageCode = '999999992';
                throw new \Exception($messageCode);
            }
            // 驗證SAT
            if(!$satdata = \App\Library\CommonTools::checkServiceAccessToken($inputData['sat'])){
                $messageCode = '999999992';
                throw new \Exception($messageCode);
            }
            // 檢查md_id存在性
            if(!$memberdata = $this->checkMdID($satdata['md_id'],$messageCode)){
                if(!isset($messageCode)){
                    $messageCode = '999999980';
                }
                throw new \Exception($messageCode);
            }
            // 判斷是否為第三方登入
            // 是：進入ssoData抓sso_token
            // 否：設定sso_token為空字串
            if(!$ssoToken = $this->checkLoginType($memberdata)){
                $messageCode = '999999970';
                throw new \Exception($messageCode);
            }

            $messageCode = '000000000';
            $resultData['md_id'] = $memberdata['md_id'];
            $resultData['md_cname'] = $memberdata['md_cname'];
            $resultData['md_city'] = $memberdata['md_city'];// 要註解
            $resultData['md_country'] = $memberdata['md_country'];// 要註解
            $resultData['rl_city_code'] = $memberdata['rl_city_code'];
            $resultData['rl_zip'] = $memberdata['rl_zip'];
            $resultData['md_picturepath'] = $memberdata['md_picturepath'];
            $resultData['md_logintype'] = $memberdata['md_logintype'];
            $resultData['md_clienttype'] = $memberdata['md_clienttype'];
            $resultData['md_clubjoinstatus'] = $memberdata['md_clubjoinstatus'];
            $resultData['sso_token'] = $ssoToken;
            $resultData['md_fbgender'] = $memberdata['md_fbgender'];
            $resultData['md_birthday'] = $memberdata['md_birthday'];
            if(is_null($memberdata['md_securitycode']) || $memberdata['md_securitycode'] == ""){
                $resultData['md_seccode_created'] = 0;
            }else{
                $resultData['md_seccode_created'] = 1;
            }
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
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'sat', 0, false, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'mur', 36, false, false)) {
            return false;
        }
        return $inputData;
    }

    /**
     * 檢查md_id存在性
     * @param  [type] $md_id        [description]
     * @param  [type] &$messageCode [description]
     * @return [type]               [description]
     */
    public function checkMdID($md_id ,&$messageCode){
        $memberdata_r = new \App\Repositories\MemberDataRepository;
        // $regionlist_r = new \App\Repositories\RegionListRepository;
        $resultData = null;
        $functionName = 'checkMdID';
        try {
            $data = $memberdata_r->GetMemberData($md_id);
            if(is_null($data) || count($data) == 0){
            return false;
            }
            if(count($data) > 1){
                $messageCode = "999999987";
                \App\Library\CommonTools::WriteExecuteLogGetId($functionName,$data,$resultData,$messageCode,$jio_id);
                $message = "資料庫存取有誤，請檢查。".$jio_id;

                // 此推播目前暫時無效
                \App\Library\CommonTools::pushnotification($md_id,$message);
                return false;
            }
            if(count($data) == 1 ){
                $data = $data[0];
            }
            // 將rl_city_code以及rl_zip轉換成中文
            // if($data['rl_city_code']!=0 && $data['rl_zip']!=0){
            //     $rldata = $regionlist_r->turnToString($data['rl_city_code'],$data['rl_zip']);
            //     if(count($rldata) == 1 ){
            //         $rldata = $rldata[0];
            //     }
            //     $data['rl_city_code'] = $rldata['rl_city'];
            //     $data['rl_zip'] = $rldata['rl_district'];
            // }
            return $data;
        } catch (\Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

    /**
     * 判斷md_logintype !=3 抓取sso_token
     * @param  [array]  $memberdata [會員資料]
     * @return [string] $sso_token
     */
    public function checkLoginType($memberdata){
        try{
            if($memberdata['md_logintype'] == 3){
                return ' ';
            }
            $sso_r = new \App\Repositories\SsoDataRepository;
            $ssoData = $sso_r->getDataByAccountID($memberdata['sso_accountid']);
            if(count($ssoData) == 0 || is_null($ssoData) || count($ssoData) > 1){
                return false;
            }
            if(count($ssoData) == 1){
                $ssoData = $ssoData[0];
            }
            return $ssoData['sso_token'];
        } catch (\Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }
}
