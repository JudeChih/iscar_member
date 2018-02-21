<?phpnamespace App\Http\Controllers\APIControllers\CrossModelAPI;// use App\Http\Controllers\CarClub;use Illuminate\Support\Facades\Input;//querymemberlevelinfo 查詢用戶等級相關資訊class QueryMemberLevelinfo {     public function querymemberlevelinfo() {        $functionName = 'querymemberlevelinfo';        $inputString = Input::All();        $inputData = \App\Library\CommonTools::ConvertStringToArray($inputString);        if (!is_null($inputString) && count($inputString) != 0 && is_array($inputString)) {            $inputString = $inputString[0];        }        $resultData = null;        $messageCode = null;        try {            if ($inputData == null) {                $messageCode = '999999995';                throw new \Exception($messageCode);            }            //檢查輸入值            if (!$this->CheckInput($inputData)) {//輸入值有問題                $messageCode = '999999995';                throw new \Exception($messageCode);            }            //檢查JWT驗正。            if( !\App\Services\JWTService::JWTokenVerification($inputData['api_token'], $messageCode)) {                throw new \Exception($messageCode);            }            //檢查Token、DeviceCode            if (!\App\Library\CommonTools::CheckAccessTokenDeviceCode($inputData['servicetoken'], $inputData['userdevicecode'], $md_id, $messageCode)) {                throw new \Exception($messageCode);            }            if (!$this->CreateResultData($md_id, $resultData, $messageCode)) {                throw new \Exception($messageCode);            }            $messageCode = '000000000';        } catch (\Exception $e) {            \App\Library\CommonTools::writeErrorLogByException($e);            if (!isset($messageCode) || is_null($messageCode)) {                $messageCode = '999999999';            }        }        if (!isset($resultData)) {            $resultData = null;        }        //回傳值        $resultArray = \App\Library\CommonTools::ResultProcess($messageCode, $resultData);        \App\Library\CommonTools::WriteExecuteLog($functionName, $inputString, json_encode($resultArray), $messageCode);        $result = [ $functionName.'result' => $resultArray];        return $result;    }    /**     * 檢查傳入資料     * @param type $value 傳入資料     * @return boolean 檢查結果     */    public function CheckInput(&$value) {        if (is_null($value)) {            return false;        }        if (!\App\Library\CommonTools::CheckRequestArrayValue($value, 'api_token', 0, false, false)){            return false;        }        if (!\App\Library\CommonTools::CheckRequestArrayValue($value, 'servicetoken', 0, false, false)) {            return false;        }        if (!\App\Library\CommonTools::CheckRequestArrayValue($value, 'userdevicecode', 0, false, false)) {            return false;        }        return true;    }    /**     * 建立回傳資料     * @param type $md_id 使用者id     * @param type $resultData     * @param type $messageCode     * @return boolean 檢查結果     */    private function CreateResultData($md_id, &$resultData, &$messageCode) {        try {          $queryMemberclubData = app('App\Http\Controllers\CarClub\CarClub')-> Query_member_club_joinstatus($md_id,$messageCode);          if (!is_null($messageCode)) {              return false;          }          $resultData = [                          "mcls_gradename"       => $queryMemberclubData['mem_mcls_gradename'],                          "mcls_gradeicon"       => $queryMemberclubData['mem_mcls_gradeicon'],                          "mcls_levelweight"     => $queryMemberclubData['mcls_levelweight'],                          "mcls_nextlevelexp"    => $queryMemberclubData['mcls_nextlevelexp'],                          "mcls_functioncontent" => $queryMemberclubData['mcls_functioncontent']                        ];          return true;        } catch(\Exception $e) {           \App\Library\CommonTools::writeErrorLogByException($e);           return false;        }    }}