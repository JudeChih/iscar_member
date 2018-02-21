<?php

namespace App\Http\Controllers\ViewControllers;

use Request;
use App\Http\Controllers\Controller;
use \Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Session;
use DB;

if (!defined('Expired_Time')) define('Expired_Time', config('global.expired_time'));
if (!defined('REDIRECT_URI')) define('REDIRECT_URI', config('global.redirect_uri'));
if (!defined('FB_GRAPH_VERSION')) define('FB_GRAPH_VERSION', config('global.fb_graph_version'));
if (!defined('FB_DEVELOPER_ID')) define('FB_DEVELOPER_ID', config('global.fb_developer_id'));
if (!defined('GOOGLE_API_ID')) define('GOOGLE_API_ID', config('global.google_api_id'));

class ThirdPartyController extends Controller {

    /**
     * 回傳串接好的第三方路徑
     * @param  [type] $action    [動作，判斷是登入還是註冊]
     * @param  [type] $account   [帳號類別，判斷是哪種第三方帳號]
     * @param  [type] $parameter [參數]
     */
    public function getThirdPartyUrl($action,$account,$parameter) {
        try {
            //轉換格式，並檢查回傳是否為陣列格式
            if ((!$paraArray = \App\Library\CommonTools::decodeAndCheckParameter($parameter)) || !is_array($paraArray)){
                return redirect('/error');
            }
            if($account == 'facebook'){
                //串接要回傳的FB登入路徑
                $urlstring = "https://www.facebook.com/" . FB_GRAPH_VERSION . "/dialog/oauth?client_id=" . FB_DEVELOPER_ID . "&display=popup&response_type=token&redirect_uri=" . REDIRECT_URI . "&auth_type=rerequest&scope=publish_actions";
            }
            if($account == 'google'){
                //串接要回傳的
                $urlstring = "https://accounts.google.com/o/oauth2/auth?response_type=token&client_id=". GOOGLE_API_ID ."&redirect_uri=". REDIRECT_URI ."&scope=https://www.googleapis.com/auth/userinfo.profile + https://www.googleapis.com/auth/userinfo.email";
            }

            return $urlstring;
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return redirect()->route('error');
        }
    }

    /**
     * 透過第三方帳號登入前動作，每個第三方有其不同的要資料方式
     * @param  Request $request   [前端post過來的值]
     * @param  [type]  $action    [動作，判斷是登入還是註冊]
     * @param  [type]  $account   [帳號類別，判斷是哪種第三方帳號]
     * @param  [type]  $parameter [參數]
     */
    public function loginThirdParty(Request $request,$action=null,$account=null,$parameter=null) {
        $sso_r = new \App\Repositories\SsoDataRepository;
        $mod_r = new \App\Repositories\ModuleAccPass_rRepository;
        $requiredData = Request::all();
        $fb = new \Facebook\Facebook(config('facebook.config'));
        $helper = $fb->getRedirectLoginHelper();
        $loginUrl = $helper->getLoginUrl(REDIRECT_URI.'loginthirdparty');
        $button = '<a class="button button-big fb_login external" href="' . htmlspecialchars($loginUrl) . '">Facebook 登入</a>';
        $loginByWeb = false;// 設定一個變數判斷是用什麼瀏覽器做登入的動作
        try {
            //檢查有無access token
            if(isset($requiredData['access_token'])){
                $access_token = $requiredData['access_token'];
            }else{
                $loginByWeb = true;// 設定一個變數判斷是用什麼瀏覽器做登入的動作
                $accessToken = $helper->getAccessToken();
                if(! isset($accessToken)){
                    $result['message'] = "請先登入Facebook";
                    return redirect('/')->withErrors(['error' => $result['message']]);
                }else{
                    $access_token = $accessToken->getValue();
                }
            }
            // 檢查parameter是否已經存在cookie
            if(!isset($_COOKIE['parameter'])){
                $result['message'] = "parameter尚未存到cookie";
                if($loginByWeb){
                    return redirect()->back()->withErrors(['error' => $result['message']]);
                }else{
                    return $result;
                }
            }else{
                $parameter = $_COOKIE['parameter'];
            }
            //轉換格式，並檢查回傳是否為陣列格式
            if ((!$paraArray = \App\Library\CommonTools::decodeAndCheckParameter($parameter)) || !is_array($paraArray)){
                return redirect()->route('error');
            }
            //檢查account action是否已經存在cookie
            if(!isset($_COOKIE["account"]) || !isset($_COOKIE["action"])){
                $result['message'] = "所需的值尚未存到cookie";
                if($loginByWeb){
                    return redirect('/')->withErrors(['error' => $result['message']]);
                }else{
                    return $result;
                }
            }else{
                $account = $_COOKIE["account"];
                $action = $_COOKIE["action"];
            }

            if($account == 'facebook'){
                //透過access token去跟FB要會員資料
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/" . FB_GRAPH_VERSION . "/me?fields=id%2Cname%2Cemail%2Cfirst_name%2Clast_name%2Clocale%2Cgender%2Cbirthday%2Ctimezone&access_token=" . $access_token);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                $err = curl_error($ch);
                curl_close($ch);
                if($err){
                    throw new \Exception($err);
                }
            }
            if($account == 'google'){
                //透過access token去跟Google要會員資料
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/oauth2/v3/userinfo?alt=json&access_token=" . $requiredData['access_token']);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                $err = curl_error($ch);
                curl_close($ch);
                if($err){
                    throw new \Exception($err);
                }
            }
            // 字串轉陣列
            $response = \App\Library\CommonTools::ConvertStringToArray($response);
            // 取會員資料
            $userData = $sso_r->getDataByFBID($response['id']);

            if($action == 'login'){
                // 取得模組資料
                $modData = $mod_r->getDataByAccount($paraArray['modacc']);
                if(count($modData) != 0){
                    $modData = $modData[0];
                }
                // 檢查是否為商家後台的模組
                if($modData['mapr_functiontype'] == 2){
                    if(count($userData) == 1){
                        $userData_b = $userData[0];
                    }
                    // 檢查會員是否為特約商
                    if($userData_b['md_clienttype'] != 1){
                        $result['code'] = "1003";
                        $result['message'] = '尚未成為特約商身分，請到"就是行"官網申請';
                        if($loginByWeb){
                            return redirect('/')->withErrors(['error' => $result['message']]);
                        }else{
                            return $result;
                        }
                    }
                    // 檢查會員是否為品牌商
                    if($userData_b['md_clienttype'] != 3){
                        $result['code'] = "1003";
                        $result['message'] = '尚未成為品牌商身分，請到"就是行"官網申請';
                        if($loginByWeb){
                            return redirect('/')->withErrors(['error' => $result['message']]);
                        }else{
                            return $result;
                        }
                    }
                    // 檢查會員是否為宮廟管理員
                    if($userData_b['md_clienttype'] != 99){
                        $result['code'] = "1003";
                        $result['message'] = '尚未成為宮廟管理員身分，請到"就是行"官網申請';
                        if($loginByWeb){
                            return redirect('/')->withErrors(['error' => $result['message']]);
                        }else{
                            return $result;
                        }
                    }
                }
                if(count($userData) == 0 || is_null($userData)){
                    $result['code'] = "1002";
                    $result['message'] = "尚未成為會員";
                    if($loginByWeb){
                        return redirect('/')->withErrors(['error' => $result['message']]);
                    }else{
                        return $result;
                    }
                }
                if(count($userData) > 1){
                    $result['code'] = "1001";
                    $result['message'] = "登入失敗";
                    if($loginByWeb){
                        return redirect('/')->withErrors(['error' => $result['message']]);
                    }else{
                        return $result;
                    }
                }
                if(count($userData) == 1){
                    $userData = $userData[0];
                }
                if(!$sat = \App\Library\CommonTools::createServiceAccessToken($userData['md_id'],$paraArray['mur'])){
                    $result['message'] = "sat建立失敗";
                    if($loginByWeb){
                        return redirect('/')->withErrors(['error' => $result['message']]);
                    }else{
                        return $result;
                    }
                }
                //檢查會員是否存在親屬資料表
                if(!\App\Library\CommonTools::synchronizeTempleRelative($userData['md_id'])){
                    $result['code'] = "1004";
                    $result['message'] = "會員資料同步到親屬資料表失敗";
                    if($loginByWeb){
                        return redirect('/')->withErrors(['error' => $result['message']]);
                    }else{
                        return $result;
                    }
                }

                // 轉到登入成功頁
                $result['code'] = "1000";
                $result['redirect_uri'] = $paraArray['redirect_uri'];
                $result['sat'] = $sat;
                $result['mur'] = $paraArray['mur'];
                if(! $sso_r->updateSsotoken($userData['sso_serno'],$access_token)){
                    $result['message'] = "sso_token更新失敗";
                    if($loginByWeb){
                        return redirect('/')->withErrors(['error' => $result['message']]);
                    }else{
                        return $result;
                    }
                }
                if($loginByWeb){
                    return redirect('/#!/thirdpartyloginsuccess?redirect_uri=' . $result["redirect_uri"] . '&sat=' . $result["sat"] . '&mur=' . $result["mur"]);
                }else{
                    return $result;
                }
            }
            if($action == 'regiest'){
                // 註冊失敗
                if(count($userData) > 1){
                    $result['code'] = "2001";
                    $result['message'] = "註冊失敗";
                    if($loginByWeb){
                        return redirect('/')->withErrors(['error' => $result['message']]);
                    }else{
                        return $result;
                    }
                }
                // 已為會員
                // if(count($userData) == 1){
                //     $result['code'] = "2002";該帳號已註冊為會員
                //     return $result;
                // }
                
                if($loginByWeb){
                    $response['access_token'] = $access_token;
                    $response['sso_photourl'] = 'http://graph.facebook.com/' . $response['id'] . '/picture?type=large';
                    $result['ssodata'] = $response;
                    setcookie('fb_data',json_encode($result['ssodata']),time()+3600);
                    return redirect('/#!/regiestthirdparty/'.$parameter);
                }else{
                    $result['code'] = "2000";
                    $result['ssodata'] = $response;
                    $result['message'] = "註冊成功";
                    return $result;
                }
            }
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return redirect()->route('error');
        }
    }

    /**
     * 檢查註冊資料並暫存於Session，完成後發送簡訊﹙驗證碼﹚
     * @param Request $request 前端POST進來的值
     * @param type $parameter 驗證用的參數
     * @return type
     */
    public function checkRegiestData(Request $request, $parameter) {
        $requiredData = Request::all();
        $ssodata = $requiredData['ssodata'];
        $ssodata = json_decode($ssodata, true);
        try {
            //轉換格式，並檢查回傳是否為陣列格式
            if ((!$paraArray = \App\Library\CommonTools::decodeAndCheckParameter($parameter)) || !is_array($paraArray)) {
                return redirect()->route('error');
            }
            //檢查前端POST進來的參數格式
            $checkResult = $this->checkPostValueFormat_Regiest($requiredData, $parameter);
            if ($checkResult !== true) {
                return $checkResult;
            }
            //取得會員帳號
            $memberRepo = new \App\Repositories\MemberDataRepository();
            $memberData = $memberRepo->getDataBySso_AcountID($ssodata["id"]);

            //檢查電話號碼是否已經註冊過
            $phoneData = $memberRepo->getDataByMd_RegiestMobile($requiredData['cellphone']);
            if(count($phoneData) > 0){
                return redirect('/#!/regiestthirdparty/' . $parameter)->withInput()->withErrors(['電話號碼已註冊過']);
            }

            //檢查取得的會員資料
            if (count($memberData) > 1) {
                return redirect('/#!/regiestthirdparty/' . $parameter)->withInput()->withErrors(['資料輸入錯誤']);
            }
            //檢查該帳號是否以存在
            if (count($memberData) == 1) {
                return redirect('/#!/regiestthirdparty/' . $parameter)->withInput()->withErrors(['帳號已註冊過']);
            }
            //將資料暫存於Session中
            Session::put('regdata', $requiredData);

            //寄送驗證碼
            $snc_serno = \App\Services\SmsService::sendVerifyCodeRegiest($requiredData['countrycode'], $requiredData['cellphone']);
            if ($snc_serno === false) {
                return redirect('/#!/regiestthirdparty/' . $parameter)->withInput()->withErrors(['資料輸入錯誤']);
            }
            return redirect('/#!/regiestthirdparty/verify/' . $parameter);
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return redirect()->back()->withInput()->withErrors(['資料輸入錯誤']);
        }
    }

    /**
     * 進入「輸入驗證碼」頁面
     * @param Request $request 前端POST進來的值
     * @param type $parameter 驗證用的參數
     * @return type
     */
    public function verify(Request $request, $parameter) {
        try {
            //轉換格式，並檢查回傳是否為陣列格式
            if ((!$paraArray = \App\Library\CommonTools::decodeAndCheckParameter($parameter)) || !is_array($paraArray)) {
                return redirect()->route('error');
            }
            $regiest = 'regiestthirdparty';
            return View::make('login/regiestverify', compact('parameter','regiest'));
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return redirect()->route('error');
        }
    }

    /**
     * 檢查驗證碼成功後將資料存於資料庫中
     * @param Request $request 前端POST進來的值
     * @param type $parameter 驗證用的參數
     * @return type
     */
    public function saveRegiestData(Request $request, $parameter) {
        $requiredData = Request::all();
        try {
            //轉換格式，並檢查回傳是否為陣列格式
            if ((!$paraArray = \App\Library\CommonTools::decodeAndCheckParameter($parameter)) || !is_array($paraArray)) {
                return redirect()->route('error');
            }
            //檢查前端POST進來的參數格式
            $checkResult = $this->checkPostValueFormat_Verify($requiredData, $parameter);
            if ($checkResult !== true) {
                return $checkResult;
            }
            //檢查暫存於Session的資料
            if (!($regData = $this->checkSessionData())) {
                return redirect('/#!/regiestthirdparty/' . $parameter)->withErrors(['重新輸入資料']);
            }
            //取得簡訊驗證資料
            $smsRepo = new \App\Repositories\SmsNumberCodeRepository();
            $smsData = $smsRepo->getDataBySnc_TargetPhone($regData['cellphone']);
            //檢查驗證碼是否已經過期
            if(!$this->checkVerifyCodeValid($smsData[0])){
                return redirect('/#!/regiest/verify/' . $parameter)->withInput()->withErrors(['驗證碼已過期，請按重新發送']);
            }
            if (!isset($smsData) || count($smsData) !== 1) {
                return redirect('/#!/regiestthirdparty/verify/' . $parameter)->withInput()->withErrors(['驗證碼輸入錯誤']);
            }
            //檢查驗證碼是否相符
            if ($smsData[0]->snc_code !== $requiredData['verifycode']) {
                return redirect('/#!/regiestthirdparty/verify/' . $parameter)->withInput()->withErrors(['驗證碼輸入錯誤']);
            }
            DB::beginTransaction();
            //建立會員資料
            if (!($md_id = $this->createMemberData($regData))) {
                DB::rollback();
                return redirect('/#!/regiestthirdparty/verify/' . $parameter)->withInput()->withErrors(['此FB帳號已註冊過']);
            }
            //建立SSO資料
            if (!$this->createSsoData($regData)) {
                DB::rollback();
                return redirect('/#!/regiestthirdparty/verify/' . $parameter)->withInput()->withErrors(['建立資料失敗']);
            }
            //產生授權憑證
            $sat = \App\Library\CommonTools::createServiceAccessToken($md_id, $paraArray['mur']);
            if ($sat === false) {
                DB::rollback();
                return redirect('/#!/regiestthirdparty/verify/' . $parameter)->withInput()->withErrors(['註冊失敗']);
            }
            //寫入會員帳號異動記錄
            \App\Library\CommonTools::writeAccountModifyRecode($md_id, $paraArray['mur'], '2', '1', null, $smsData[0]->snc_serno);
            //更新「icr_smsnumbercode」為已驗證
            $smsRepo->update(['snc_verifyresult' => '1'], $smsData[0]->snc_serno);

            //清除Session資料
            $this->clearSessionData();

            //檢查會員是否存在親屬資料表
            if(!\App\Library\CommonTools::synchronizeTempleRelative($md_id)){
                DB::rollback();
                return redirect('/#!/regiestthirdparty/verify/' . $parameter)->withInput()->withErrors(['註冊失敗']);
            }

            //導回登入前頁
            DB::commit();
            $queryString = 'sat=' . $sat;
            $redirect_uri = \App\Library\CommonTools::appendQueryStringInUri($paraArray['redirect_uri'], $queryString);

            $app_s = new \App\Services\AppService;
            $modifypoint = $app_s->getGiftPointsAmount(1);
            $app_s->modifyGiftPoint($md_id, 1, $md_id, 4, true,$modifypoint);


            return redirect('/#!/regiestsuccess?modifypoint='.$modifypoint.'&redirect_uri=' . urlencode($redirect_uri));
            // return redirect('/#!/regiestsuccess?redirect_uri=' . urlencode($redirect_uri));

        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return redirect()->route('error');
        }
    }

    /**
     * 註冊成功
     * @param Request $request 前端POST進來的值
     * @return type
     */
    public function success(Request $request) {
        try {
            $Data = $request->all();
            $modifypoint = $Data['modifypoint'];
            return View::make('login/regiestsuccess', compact('redirect_uri','modifypoint'));
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return redirect()->route('error');
        }
    }

    /**
     * 檢查前端POST進來的參數格式 註冊資料
     * @param type $request
     * @return type
     */
    private function checkPostValueFormat_Regiest($request, $parameter) {
        try {
            //檢查〈account〉格式
            if (!\App\Library\CommonTools::checkValueFormat($request['account'], 64, false, false)) {
                return redirect('/#!/regiestthirdparty/' . $parameter)->withInput()->withErrors(['帳號輸入錯誤']);
            }
            //檢查〈nickname〉格式
            if (!\App\Library\CommonTools::checkValueFormat($request['nickname'], 20, false, true)) {
                return redirect('/#!/regiestthirdparty/' . $parameter)->withInput()->withErrors(['名稱輸入錯誤']);
            }
            //檢查〈countrycode〉格式
            if (!\App\Library\CommonTools::checkValueFormat($request['countrycode'], 2, false, false)) {
                return redirect('/#!/regiestthirdparty/' . $parameter)->withInput()->withErrors(['行動電話國家代碼輸入錯誤']);
            }
            //檢查〈cellphone〉格式
            if (!\App\Library\CommonTools::checkValueFormat($request['cellphone'], 11, false, false)) {
                return redirect('/#!/regiestthirdparty/' . $parameter)->withInput()->withErrors(['行動電話輸入錯誤']);
            }
            return true;
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return redirect()->route('error');
        }
    }

    /**
     * 檢查前端POST進來的參數格式 驗證碼
     * @param type $request
     * @return type
     */
    private function checkPostValueFormat_Verify($request, $parameter) {
        try {
            //檢查〈verifycode〉格式
            if (!\App\Library\CommonTools::checkValueFormat($request['verifycode'], 6, false, false)) {
                return redirect('/#!/regiestthirdparty/verify/' . $parameter)->withInput()->withErrors(['驗證碼輸入錯誤']);
            }
            return true;
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return redirect()->route('error');
        }
    }

    /**
     * 檢查驗證碼是否過期
     * @param  array    $smsData      [簡訊驗證資料]
     * @return boolean  true or false
     */
    private function checkVerifyCodeValid($smsData){
        if(count($smsData) > 0){
            $smsData = $smsData[0];
        }
        try {
            $sendVerifyCodeDate = \Carbon\Carbon::parse($smsData['create_date']);
            //超過30分鐘就算過期
            $expiredTimePoint = \Carbon\Carbon::now()->subMinutes(Expired_Time);
            if($expiredTimePoint > $sendVerifyCodeDate ){
                return false;
            }
            return true;
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return redirect()->route('error');
        }
    }

    /**
     * 檢查暫存於Session的資料
     * @return boolean
     */
    private function checkSessionData() {
        try {
            $regData = Session::get('regdata');

            if (!isset($regData)) {
                Session::put('regdata', null);
                return false;
            }
            return $regData;
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return false;
        }
    }

    /**
     * 清除暫存於Session的資料
     * @return boolean
     */
    private function clearSessionData() {
        try {
            Session::put('regdata', null);
            Session::forget('regdata');
            return true;
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return false;
        }
    }

    /**
     * 建立會員資料
     * @param type $regData 註冊輸入的資料
     * @return type
     */
    private function createMemberData($regData) {
        $ssodata = $regData['ssodata'];
        $ssodata = json_decode($ssodata, true);
        $memRepo = new \App\Repositories\MemberDataRepository();
        try {
            // 先判斷有無同樣的sso_accountid
            $data = $memRepo->getDataBySso_AcountID($ssodata['id']);
            if(count($data) > 0){
                return false;
            }
            $guid = \App\Library\CommonTools::generateGUID(false);
            $saveData['md_id'] = $guid;
            $saveData['md_logintype'] = '0';
            $saveData['sso_accountid'] = $ssodata['id'];
            $saveData['md_ssobind_status'] = 1;
            $saveData['md_ssobind_type'] = 1;
            $saveData['md_account'] = $ssodata['id'];
            $saveData['md_cname'] = $regData['nickname'];
            $saveData['rl_city_code'] = '1';
            $saveData['rl_zip'] = '104';
            $saveData['md_countrycode'] = $regData['countrycode'];
            $saveData['md_mobile'] = $regData['cellphone'];
            $saveData['md_regiestmobile'] = $regData['cellphone'];
            $saveData['md_contactmail'] = $regData['account'];
            $saveData['md_first_login'] = \Carbon\Carbon::now();
            $saveData['md_last_login'] = \Carbon\Carbon::now();
            $saveData['md_clienttype'] = 0;
            $saveData['mcls_serno'] = 1;
            $saveData['md_clubjoinstatus'] = 0;
            $saveData['md_picturepath'] = $ssodata['sso_photourl'];

            if ($memRepo->create($saveData)) {
                return $guid;
            }
            return false;
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return false;
        }
    }

    /**
     * 建立sso資料
     * @param type $regData 註冊輸入的資料
     * @return type
     */
    private function createSsoData($regData) {
        $ssodata = $regData['ssodata'];
        $ssodata = json_decode($ssodata, true);
        $sso_r = new \App\Repositories\SsoDataRepository();
        try {
            // 先判斷有無同樣的sso_accountid
            $data = $sso_r->getDataByAccountID($ssodata['id']);
            if(count($data) > 0){
                return false;
            }
            $saveData['sso_bindtype'] = '1';
            $saveData['sso_accountid'] = $ssodata['id'];
            if(isset($ssodata['access_token'])){
                $saveData['sso_token'] = $ssodata['access_token'];
            }
            if(isset($ssodata['first_name'])){
                $saveData['sso_firstname'] = $ssodata['first_name'];
            }
            if(isset($ssodata['last_name'])){
                $saveData['sso_lastname'] = $ssodata['last_name'];
            }
            if(isset($ssodata['name'])){
                $saveData['sso_name'] = $ssodata['name'];
            }
            if(isset($ssodata['locale'])){
                $saveData['sso_locale'] = $ssodata['locale'];
            }
            if(isset($ssodata['access_token'])){
                $saveData['sso_token'] = $ssodata['access_token'];
            }
            if(isset($ssodata['gender'])){
                if($ssodata['gender'] == 'male'){
                    $saveData['sso_gender'] = 1;
                }elseif($ssodata['gender'] == 'female'){
                    $saveData['sso_gender'] = 2;
                }else{
                    $saveData['sso_gender'] = 0;
                }
            }
            if(isset($ssodata['email'])){
                $saveData['sso_email'] = $ssodata['email'];
            }
            if(isset($ssodata['birthday'])){
                $saveData['sso_birthday'] = $ssodata['birthday'];
            }
            if(isset($ssodata['countrycode'])){
                $saveData['sso_location'] = $ssodata['countrycode'];
            }
            if(isset($ssodata['timezone'])){
                $saveData['sso_timezon'] = $ssodata['timezone'];
            }
            if(isset($ssodata['sso_photourl'])){
                $saveData['sso_photourl'] = $ssodata['sso_photourl'];
            }

            if($sso_r->create($saveData)){
                return true;
            }
            return false;
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return false;
        }
    }

    /**
     * 登入成功
     * @param Request $request 前端POST進來的值
     * @return type
     */
    public function loginsuccess(Request $request) {
        try {
            return View::make('login/thirdpartyloginsuccess');
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return redirect()->route('error');
        }
    }

}
