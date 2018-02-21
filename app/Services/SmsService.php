<?php

namespace App\Services;

define('SMS_USER_NAME', config('global.sms_user_name'));
define('SMS_PASSWORD', config('global.sms_password'));
define('SMS_API_URL', config('global.sms_api_url'));

class SmsService {

    /**
     * 發送驗證碼-註冊
     * @param type $countryCode 發送區域：０測試假值、１台灣、２大陸
     * @param type $cellPhone 手機號碼
     * @return boolean 失敗會回傳「False」，成功會回傳「簡訊註冊代碼的記錄序號﹙icr_smsnumbercode.snc_serno﹚」
     */
    public static function sendVerifyCodeRegiest($countryCode, $cellPhone) {
        try {
            if (!isset($countryCode) || !isset($cellPhone)) {
                return false;
            }
            //產生驗證碼
            $verifyCode = \App\Library\CommonTools::generateRandomNumberString(6);
            $smsVerify = '您的isCar驗證碼為：' . $verifyCode . '。此驗證碼30分鐘內有效。提醒您，請勿將此驗證碼提供給其他人以保障您的使用安全。';
            //發送簡訊
            $sendResult = SmsService::sendSMS($countryCode, $cellPhone, $smsVerify);
            //寫入簡訊發送記錄
            $snc_serno = SmsService::createSmsNumberCode($countryCode, $cellPhone, $verifyCode, $sendResult ? '1' : '0');
            if (!$snc_serno) {
                return false;
            }
            return $snc_serno;
        } catch (\Exception $ex) {
            return false;
        }
    }

    /**
     * 發送驗證碼-忘記密碼
     * @param type $md_id 會員代碼
     * @return boolean 失敗會回傳「False」，成功會回傳「簡訊註冊代碼的記錄序號﹙icr_smsnumbercode.snc_serno﹚」
     */
    public static function sendVerifyCodeResetPwd($md_id) {
        try {
            //取得會員手機資料
            $memRepo = new \App\Repositories\MemberDataRepository();
            $memData = $memRepo->getData($md_id);

            if (!isset($memData) || !isset($memData->md_countrycode) || !isset($memData->md_mobile)) {
                return false;
            }
            //產生驗證碼
            $verifyCode = \App\Library\CommonTools::generateRandomNumberString(6);
            $smsVerify = '您的isCar驗證碼為：' . $verifyCode . '。此驗證碼30分鐘內有效。提醒您，請勿將此驗證碼提供給其他人以保障您的使用安全。';
            //發送簡訊
            $sendResult = SmsService::sendSMS($memData->md_countrycode, $memData->md_mobile, $smsVerify);
            //寫入簡訊發送記錄
            $snc_serno = SmsService::createSmsNumberCode($memData->md_countrycode, $memData->md_mobile, $verifyCode, $sendResult ? '1' : '0');
            if (!$snc_serno) {
                return false;
            }
            return $snc_serno;
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);

            return false;
        }
    }

    /**
     * 發送簡訊
     * @param type $countryCode 發送區域：０測試假值、１台灣、２大陸
     * @param type $mobile 手機號碼
     * @param type $smsMessage 發送訊息
     * @return boolean
     */
    public static function sendSMS($countryCode, $mobile, $smsMessage) {
        try {
            //return true;
            // 三竹帳號
            $username = SMS_USER_NAME;
            //三竹密碼
            $password = urlencode(SMS_PASSWORD);
            //接收手機號碼
            $dstaddr = SmsService::transPhoneAddCountryCode($countryCode, $mobile);
            //訊息內容，需先轉為「BIG5」再使用「urlencode」
            $smbody = urlencode(mb_convert_encoding($smsMessage, "BIG5"));
            //簡訊預約發送時間，建議設定為"0"即時發送
            $dlvtime = '0';
            //發送簡訊的有效期限，設定為0秒時有效時間將依簡訊中心流量設定值配送約4~24小時，不宣告此參數時，有效期限為預設值8小時
            $vldtime = '1800';
            /*
              //發送簡訊的有效期限，設定為0秒時有效時間將依簡訊中心流量設定值配送約4~24小時，不宣告此參數時，有效期限為預設值8小時
              $vldtime = '0';
              //發送簡訊是否成功的狀態回報網址, 若不宣告此參數時為不回報
              $response = '';
             */
            $smsApiUrl = SMS_API_URL . "?username={$username}&password={$password}&dstaddr={$dstaddr}&smbody={$smbody}&dlvtime={$dlvtime}"; //&vldtime={$vldtime}&response={$response}";

            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, $smsApiUrl);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

            $strResponse = curl_exec($curl);
            curl_close($curl);

            $position = strpos($strResponse, '-');
            if ($position == false) {
                return true;
            }
            return false;
        } catch (\Exception $ex) {
            return false;
        }
    }

    /**
     * 建立簡訊發送記錄
     * @param type $countryCode 發送區域
     * @param type $mobile 手機號碼
     * @param type $smsMessage 發送訊息
     * @param type $sendResult 發送結果
     * @return boolean
     */
    private static function createSmsNumberCode($countryCode, $mobile, $smsMessage, $sendResult) {
        try {
            $arrayData['snc_destination'] = $countryCode;
            $arrayData['snc_targetphone'] = $mobile;
            $arrayData['snc_code'] = $smsMessage;
            $arrayData['snc_sendresult'] = $sendResult;
            $arrayData['snc_verifyresult'] = '0';


            \Illuminate\Support\Facades\DB::beginTransaction();
            $smsRepo = new \App\Repositories\SmsNumberCodeRepository();
            //將該手機舊的驗證碼設為失效
            $result = $smsRepo->updateOldToInvalidByTargetPhone($mobile);
            if (!$result) {
                return false;
            }

            $result = $smsRepo->createGetId($arrayData);
            if (!isset($result) || $result == null) {
                return false;
            }
            \Illuminate\Support\Facades\DB::commit();
            return $result;
        } catch (\Exception $ex) {
            \Illuminate\Support\Facades\DB::rollBack();
            return false;
        }
    }

    /**
     * 將電話號加上國家碼
     * @param type $countryCode 國家碼類別：１﹦Taiwan、２﹦China
     * @param type $mobile 行動電話
     * @return mix 當執行失敗，回傳［False］，執行成功，回傳［加上國家碼的手機號碼］
     */
    private static function transPhoneAddCountryCode($countryCode, $mobile) {
        try {
            if (!isset($countryCode) || !isset($mobile)) {
                return false;
            }
            switch ($countryCode) {
                case '1'://Taiwan
                    return '886' . substr($mobile, 1);
                case '2'://China
                    return '86' . substr($mobile, 1);
                default:
                    return false;
            }
            return false;
        } catch (\Exception $ex) {
            return false;
        }
    }

}
