<?php
namespace App\Http\Middleware;
use Closure;
use Exception;
class authJWT
{

    public function handle($request, Closure $next)
    {
        $jwt = new \App\Services\JWTService;
        try {
            $functionName = 'Middleware_authJWT';
            $inputString = $request->all();
            $inputData = \App\Library\CommonTools::ConvertStringToArray($inputString);
            $resultData = null;
            if( !$this->CheckInput($inputData) ) {
                $messageCode = '999999995';
                throw new \Exception($messageCode);
            }
            if( !$jwt->JWTokenVerification($inputData['api_token'], $messageCode) ) {
                throw new \Exception($messageCode);
            }
            return $next($request);
        } catch (Exception $e) {
            if (is_null($messageCode)) {
                $messageCode = '999999999';
                \App\Library\CommonTools::writeErrorLogByException($e);
            }
            $resultArray = \App\Library\CommonTools::ResultProcess($messageCode, $resultData);
            \App\Library\CommonTools::WriteExecuteLog($functionName, $inputString, json_encode($resultArray), $messageCode);
            $result = [$functionName . 'result' => $resultArray];
            return $result;
        }
    }


    /**
     * 檢查傳入資料
     * @param type $value 傳入資料
     * @return boolean 檢查結果
     */
    public static function CheckInput(&$value) {
        if (is_null($value)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($value, 'api_token', 0, false, false)){
            return false;
        }
        return true;
    }



}
