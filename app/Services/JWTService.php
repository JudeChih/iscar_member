<?php

namespace App\Services;

use \Firebase\JWT\JWT;

define('ISSUER', config('jwt.ISSUER'));
define('SECRET_KEY', config('jwt.SECRET_KEY'));
define('ALGORITHM', config('jwt.ALGORITHM'));
define('EXPIREDAYS', config('jwt.EXPIREDAYS'));

class JWTService {

    /**
     *JWToken sign需要sha256的密碼
     *@var type
     */
    public static $secret_key = 'apicontrolsecretkey';

    /**
     *JWToken 存活天數
     *@var type
     */
    public static $JWT_expiretimes = '3 day';


    /**
     * 產生「JWT Token」
     * @param type $payload 將存於data中的資料
     * @return type 若產生失敗，回傳「False」
     */
    public function generateJWT($payload) {
        try {
            $data = [
                //"iss" (Issuer)：jwt簽發者
                'iss' => ISSUER,
                //"sub" (Subject)：jwt所面向的用戶
                //"aud" (Audience)：接收jwt的一方
                //"exp" (Expiration Time)：jwt的過期時間，這個過期時間必須要大於簽發時間
                'exp' => \Carbon\Carbon::now()->addDays(EXPIREDAYS)->timestamp,
                //"nbf" (Not Before)：定義在什麼時間之前，該jwt都是不可用的.
                //'nbf' => \Carbon\Carbon::now()->timestamp,
                //"iat" (Issued At)：jwt的簽發時間。格式〔timestamp〕
                'iat' => \Carbon\Carbon::now()->timestamp,
                //"jti" (JWT ID)：jwt的唯一身份標識，主要用來作為一次性token,從而迴避重放攻擊。
                //'jti' => base64_encode(mcrypt_create_iv(32)),
                //使用者登入資料
                'data' => $payload
            ];
            $secretKey = base64_decode(SECRET_KEY);
            $jwt = JWT::encode($data, $secretKey, ALGORITHM);

            return $jwt;
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return false;
        }
    }

    /**
     * 取得「Token」中的資料
     * @param type $jwt Json Web Token
     * @return type
     */
    public function getTokenPayload($jwt) {
        try {
            if (!isset($jwt)) {
                return null;
            }
            //解碼 驗證Token
            $decodeJWT = JWT::decode($jwt, base64_decode(SECRET_KEY), array(ALGORITHM));
            //檢查是否有「使用者登入資料」
            if (!isset($decodeJWT->data)) {
                return false;
            }
            return $decodeJWT->data;
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return false;
        }
    }

  /**
   * 建立JWToken
   * @param type $playload 第二段需要base64的playload
   * @return $jwt_token
   */
  public function CreateJwtToken($playload) {
        $encoded_header = base64_encode('{"alg": "HS256","typ": "JWT"}');
        $encoded_payload = base64_encode($playload);
        $header_and_payload_combined = $encoded_header . '.' . $encoded_payload;
        $signature = base64_encode(hash_hmac('sha256', $header_and_payload_combined, JWTService::$secret_key, true));
        $jwt_token = $header_and_payload_combined . '.' . $signature;
        return $jwt_token;
  }

    /**
     * 檢查JWToken，第三段Signature
     * @param type $token
     * @return boolean
     */
    public function CheckVerifyingSignature($token) {
       $jwt_values = explode('.', $token);
       $recieved_signature = $jwt_values[2];
       $recieved_header_and_payload = $jwt_values[0] . '.' . $jwt_values[1];
       $what_signature_should_be = base64_encode(hash_hmac('sha256', $recieved_header_and_payload, JWTService::$secret_key, true));
       if( $what_signature_should_be != $recieved_signature) {
          return false;
       }
       return true;
    }

    /**
     * 檢查JWToken，驗正是否正確
     * @param type $token
     * @param type $messageCode
     * @return boolean
     */
    public function JWTokenVerification($token, &$messageCode) {
       try {
          $datenow = new \Datetime();
          $jwt_values = explode('.', $token);
          $playload = json_decode(str_replace("\"",'"',base64_decode($jwt_values[1])), true);
          $module = new \App\Repositories\ModuleLogin_rRepository;
          $queryData = $module->QueryModulLoginData($playload['mlr_calleraccount'],$token);
          $boolverifycode = false;
          foreach ( $queryData as $rowData ) {
             $verify_code = Hash('sha256', $rowData['mlr_calleraccount'].$rowData['mlr_id']);
             if ( $verify_code == $playload['Verify_code'] ) {
                $boolverifycode = true;
             }
          }
          if ( !\App\Services\JWTService::CheckVerifyingSignature($token) ) {
             $messageCode = '999999984';
             return false;
          } else if( date_create_from_format("Y-m-d H:i:s",$playload['mlr_expiretime']) <= $datenow ) {
             $messageCode = '999999983';
             return false;
          } else if ( is_null($queryData) || count($queryData) == 0 ) {
             $messageCode = '999999982';
             return false;
          } else if ( date_create_from_format("Y-m-d H:i:s",$queryData[0]['mlr_expiretime']) <= $datenow ) {
             $messageCode = '999999982';
             return false;
          } else if ( $boolverifycode == false) {
             $messageCode = '999999982';
             return false;
          }
          return true;
       } catch(\Exception $e) {
          $messageCode = '999999999';
          $errorlog = new \App\Repositories\ErrorLogRepository;
          $errorlog->InsertData($e);
          return false;
       }
    }

    /**
     * 將「Token」解碼並取得Payload的data
     * @param  [type] $token [description]
     * @return [type]        [description]
     */
    public function decodeToken($token){
        try {
            if (!isset($token)) {
                return null;
            }
            //解碼 驗證Token
            $secretKey = base64_decode(SECRET_KEY);
            $decodeJWT = JWT::decode($token, $secretKey , array(ALGORITHM));
            //檢查是否有「使用者登入資料」
            if (!isset($decodeJWT->data)) {
                $string = 'expired';
                return $string;
            }
            return (array)$decodeJWT->data;
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return false;
        }
    }

}
