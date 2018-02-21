<?php

namespace App\Http\Controllers\APIControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class AccountController extends Controller {

    /**
     * machineconnect    行動端首次連線WCF，登錄裝置資訊
     *
     *
     *
     *
     *
     */
    function machineconnect() {
        $machineconnect = new \App\Http\Controllers\APIControllers\Account\MachineConnect;
        $mobileunitrec = new \App\Repositories\MobileUnitRecRepository;

        $functionName = 'machineconnect';
        $inputString = Input::All();
        $inputData = \App\Library\CommonTools::ConvertStringToArray($inputString);
        if (!is_null($inputString) && count($inputString) != 0 && is_array($inputString)) {
            $inputString = $inputString[0];
        }
        $resultString;
        $resultData = null;
        $messageCode = null;

        try {
            if ($inputData == null) {
                $messageCode = '999999995';
                throw new \Exception($messageCode);
            }
            //檢查輸入值
            if (!$machineconnect->CheckInput($inputData)) {//輸入值有問題
                $messageCode = '999999995';
                throw new \Exception($messageCode);
            }

            //取得資料
            $querydata = $mobileunitrec->GetDataByMUR_UUID($inputData['mur_uuid'], $inputData['mur_systemtype']);

            if (is_null($querydata) || count($querydata) == 0) {//無資料
                if (!$mobileunitrec->InsertData($inputData, $mur_id)) {
                    $messageCode = '999999999';
                    throw new \Exception($messageCode);
                }
            } else {
                $mur_id = $querydata[0]['mur_id'];

                $querydata[0]['mur_gcmid'] = $inputData['mur_gcmid'];
                $querydata[0]['mur_apptype'] = $inputData['mur_apptype'];
                $querydata[0]['mur_systemtype'] = $inputData['mur_systemtype'];
                $querydata[0]['mur_systeminfo'] = $inputData['mur_systeminfo'];
                $querydata[0]['mur_reconnecttimes'] = $querydata[0]['mur_reconnecttimes'] + 1;

                if (!$mobileunitrec->UpdateData($querydata[0])) {
                    $messageCode = '999999999';
                    throw new \Exception($messageCode);
                }
            }

            $resultData = array('mur_id' => $mur_id);
            $messageCode = '000000000';
        } catch (\Exception $e) {
            if ($messageCode == null) {
                \App\Library\CommonTools::writeErrorLogByException($e);
                $messageCode = '999999999';
            }
        }


        //回傳值
        $resultArray = \App\Library\CommonTools::ResultProcess($messageCode, $resultData);
        \App\Library\CommonTools::WriteExecuteLog($functionName, $inputString, json_encode($resultArray), $messageCode);
        $result = [ $functionName . 'result' => $resultArray];

        return $result;
    }

    //ssologin  接收消費者使用第三方登入訊息，並判斷是否需要新增會員資料
    function ssologin() {
        $mobileunitrec = new \App\Repositories\MobileUnitRecRepository;
        $functionName = 'ssologin';
        $inputString = \Input::All();
        $inputData = \App\Library\CommonTools::ConvertStringToArray($inputString);
        if (!is_null($inputString) && count($inputString) != 0 && is_array($inputString)) {
            $inputString = $inputString[0];
        }
        $resultString;
        $resultData = null;
        $messageCode = null;

        try {

            if ($inputData == null) {
                $messageCode = '999999995';
                throw new \Exception($messageCode);
            }
            //檢查輸入值
            // if (!Account\SSOLogin::CheckInput_SSOLogin($inputData)) {
            //     $messageCode = '999999995';
            //     throw new \Exception($messageCode);
            // }
            /*
              //檢查使用平台
              if ($inputData['mml_apptype'] != '0') {//APP類型辨識錯誤，請更新APP版本
              $messageCode = '010101002';
              throw new \Exception($messageCode);
              }
             */
            //檢查「iscarmobileunitrec」
            // if (!Account\SSOLogin::Check_MobileUnitRecData($inputData['mur_id'], $messageCode)) {
            //     throw new \Exception($messageCode);
            // }

            //檢查或建立「iscarmemberdata」
            // if (!Account\SSOLogin::CreateOrUpdate_MemberData($inputData, $md_id, $messageCode)) {
            //     throw new \Exception($messageCode);
            // }

            //檢查並新增「使用者行動裝置對應表」
            // if (!Account\SSOLogin::CheckExistAndCreate_MemberMobileLink($inputData['mml_apptype'], $md_id, $inputData['mur_id'])) {
            //     $messageCode = '010101001';
            //     throw new \Exception($messageCode);
            // }
            //檢查並建立「使用者登入憑證記錄」
            // if (!Account\SSOLogin::CreateOrUpdate_ServiceAccessToken($inputData['mml_apptype'], $md_id, $inputData['mur_id'], $serviceaccesstoken)) {

            //     $messageCode = '999999992';
            //     throw new \Exception($messageCode);
            // }
            //將「重新登入次數」加〔１〕

            $mobileunitrec->UpdateDataAddReConnectTimes($inputData['mur_id']);

            //用戶端功能類別檢查
            // if (Account\SSOLogin::Check_is_ICR($md_id, $shopdata)) {

            // }

            // $resultData = Account\SSOLogin::CreateResultData($md_id, $serviceaccesstoken, $shopdata);
            $messageCode = '010101000';
        } catch (\Exception $e) {


            if ($messageCode == null) {
                \App\Library\CommonTools::writeErrorLogByException($e);
                $messageCode = '999999999';
            }
        }


        //回傳值
        $resultArray = \App\Library\CommonTools::ResultProcess($messageCode, $resultData);
        \App\Library\CommonTools::WriteExecuteLog($functionName, $inputString, json_encode($resultArray), $messageCode);
        $result = [ $functionName . 'result' => $resultArray];

        return $result;
    }

    //userdatacollect   接收使用者個人資訊存表，回覆處理結果
    function userdatacollect() {
        $userdatacollect = new \App\Http\Controllers\APIControllers\Account\UserDataCollect;
        $functionName = 'userdatacollect';
        $inputString = Input::All();
        $inputData = \App\Library\CommonTools::ConvertStringToArray($inputString);
        if (!is_null($inputString) && count($inputString) != 0 && is_array($inputString)) {
            $inputString = $inputString[0];
        }
        //$resultString;
        //$resultData = null;
        //$messageCode = '';

        try {
            if ($inputData == null) {
                $messageCode = '999999995';
                throw new \Exception($messageCode);
            }
            //檢查輸入值
            if (!$userdatacollect->CheckInput_UserDataCollect($inputData)) {//輸入值有問題
                $messageCode = '999999995';
                throw new \Exception($messageCode);
            }
            $md_id = null;
            //檢查Token、DeviceCode
            if (!\App\Library\CommonTools::CheckAccessTokenDeviceCode($inputData['servicetoken'], $inputData['userdevicecode'], $md_id, $messageCode)) {
                throw new \Exception($messageCode);
            }
            //更新會員資料
            if (!$userdatacollect->Update_MemberData($md_id, $inputData)) {
                $messageCode = '9999999123';
                $resultData = ['md_id' => $md_id];
                throw new \Exception($messageCode);
            }

            $resultData = null;
            $messageCode = '000000000';
        } catch (\Exception $e) {
            //return $e;
            if (!isset($messageCode) || is_null($messageCode)) {
                \App\Library\CommonTools::writeErrorLogByException($e);
                $messageCode = '999999999';
            }
        }
        if (!isset($resultData)) {
            $resultData = null;
        }

        //回傳值
        $resultArray = \App\Library\CommonTools::ResultProcess($messageCode, $resultData);
        \App\Library\CommonTools::WriteExecuteLog($functionName, $inputString, json_encode($resultArray), $messageCode);
        $result = [ $functionName . 'result' => $resultArray];

        return $result;
    }

    function duserbindfb() {
        $duserbindfb = new \App\Http\Controllers\APIControllers\Account\DUserBindFB;
        $functionName = 'duserbindfb';
        $inputString = Input::All();
        $inputData = \App\Library\CommonTools::ConvertStringToArray($inputString);
        if (!is_null($inputString) && count($inputString) != 0 && is_array($inputString)) {
            $inputString = $inputString[0];
        }
        //$resultString;
        //$resultData = null;
        //$messageCode = '';

        try {

            if ($inputData == null) {
                $messageCode = '999999995';
                throw new \Exception($messageCode);
            }
            //檢查輸入值
            if (!$duserbindfb->CheckRequest_DUserBindFB($inputData)) {
                $messageCode = '999999995';
                throw new \Exception($messageCode);
            }
            $md_id = null;
            //檢查Token、DeviceCode
            if (!\App\Library\CommonTools::CheckAccessTokenDeviceCode($inputData['servicetoken'], $inputData['userdevicecode'], $md_id, $messageCode)) {
                throw new \Exception($messageCode);
            }

            //檢查會員帳號
            if (!$duserbindfb->Check_MemberData($md_id, $messageCode)) {
                throw new \Exception($messageCode);
            }
            //檢查Facebook帳號資料
            if (!$duserbindfb->Check_Facebook($md_id, $inputData['ssd_accountid'], $inputData['ssd_accesstoken'], $messageCode)) {
                throw new \Exception($messageCode);
            }
            //更新會員資料
            if (!$duserbindfb->Update_MemberData($md_id, $inputData)) {
                throw new \Exception($messageCode);
            }

            //010102000 綁定完成，後續請改用FB登入，感謝您的使用
            $messageCode = '010102000';
        } catch (\Exception $e) {

            if (!isset($messageCode) || is_null($messageCode)) {
                \App\Library\CommonTools::writeErrorLogByException($e);
                $messageCode = '999999999';
            }
        }
        if (!isset($resultData)) {
            $resultData = null;
        }

        //回傳值
        $resultArray = \App\Library\CommonTools::ResultProcess($messageCode, $resultData);
        \App\Library\CommonTools::WriteExecuteLog($functionName, $inputString, json_encode($resultArray), $messageCode);
        $result = [ $functionName . 'result' => $resultArray];

        return $result;
    }

    /** logoutmember 會員登出，設置對應服務憑證為失效 **/
    function logoutmember() {
        $logoutmember = new \App\Http\Controllers\APIControllers\Account\LogoutMember;
        return $logoutmember->logoutmember();
    }

}
