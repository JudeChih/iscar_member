<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');


Route::get('/', function () {
    return view('welcome');
});



//// Account ////////////////////////////////////////////////////////////////////////////////////////////////
// machineconnect	行動端首次連線WCF，登錄裝置資訊
Route::post('account/machineconnect', 'APIControllers\AccountController@machineconnect');
// userdatacollect	接收使用者個人資訊存表，回覆處理結果
Route::post('account/userdatacollect', 'APIControllers\AccountController@userdatacollect');
// duserbindfb	接收會員FB資料，進行帳號綁定
Route::post('account/duserbindfb', 'APIControllers\AccountController@duserbindfb');
// logoutmember會員登出，設置對應服務憑證為失效
Route::post('account/logoutmember', 'APIControllers\AccountController@logoutmember');

//// MemberCar //////////////////////////////////////////////////////////////////////////////////////////////
// querymembercarlist	會員車庫列表
Route::post('membercar/querymembercarlist', 'APIControllers\MemberCarController@querymembercarlist');
// querymembercardetail	會員車庫詳細內容
Route::post('membercar/querymembercardetail', 'APIControllers\MemberCarController@querymembercardetail');
// createnewmembercar	會員新增車輛
Route::post('membercar/createnewmembercar', 'APIControllers\MemberCarController@createnewmembercar');
// modifymembercar	會員修改車輛
Route::post('membercar/modifymembercar', 'APIControllers\MemberCarController@modifymembercar');
// queryiscarpolicy	平台法條查詢
Route::post('membercar/queryiscarpolicy', 'APIControllers\MemberCarController@queryiscarpolicy');

//// CrossModelAPI ///////////////////////////////////////////////////////////////////////////////////////////
// querymemberlevelinfo 查詢用戶等級相關資訊
Route::post('api/query_member_levelinfo', 'APIControllers\CrossModelAPIController@querymemberlevelinfo');
// querymemberbasicinfo 查詢用戶等級相關資訊
Route::post('api/query_member_basicinfo', 'APIControllers\CrossModelAPIController@querymemberbasicinfo');

//// Verify sat udc Jwt //////////////////////////////////////////////////////////////////////////////////////
Route::post('verify/accesstoken', 'APIControllers\VerifyTokenDeviceCodeController@VerifyTokenDeviceCode');

//// RefreshServiceToken /////////////////////////////////////////////////////////////////////////////////////
///當用戶SAT過期時,呼叫此API進行更新 ////////////////////////////////////////////////////////////////
Route::post('vrf/refresh_servicetoken','APIControllers\RefreshServiceToken@refresh_servicetoken');///
/////////////////////////////////////////////////////////////////////////////////////////////////////

/// VerifySat 驗證SAT有效性 ///////////////////////////////////////////
Route::post('vrf/verify_sat','APIControllers\VerifySat@verify_sat');///
///////////////////////////////////////////////////////////////////////

/// VerifyApiFrom 驗證跨模無SAT時,呼叫方有效性 ////////////////////////////////////
Route::post('vrf/verify_apifrom','APIControllers\VerifyApiFrom@verify_apifrom');///
///////////////////////////////////////////////////////////////////////////////////

/// QuerySalt 即時鹽值查詢 ////////////////////////////////////////////
Route::post('vrf/query_salt','APIControllers\QuerySalt@query_salt');///
///////////////////////////////////////////////////////////////////////

/// QueryMemberBasicInfo 會員資料查詢 ////////////////////////////////////////////////////////////////
Route::post('query_member_basicinfo','APIControllers\QueryMemberBasicInfo@query_member_basicinfo');///
//////////////////////////////////////////////////////////////////////////////////////////////////////

/// QueryMemberLevelInfo 查詢用戶等級相關資訊 ////////////////////////////////////////////////////////
Route::post('query_member_levelinfo','APIControllers\QueryMemberLevelInfo@query_member_levelinfo');///
//////////////////////////////////////////////////////////////////////////////////////////////////////

/// ModifyMemberData 修改會員資料<app、pm模組用> /////////////////////////////////////////
Route::post('modify_member_data','APIControllers\ModifyMemberData@modify_member_data');///
//////////////////////////////////////////////////////////////////////////////////////////

/// APNS 商家設置未到用戶為失約用戶 ////////////////////////////
Route::post('apns','APIControllers\CrossModelAPI\APNS@apns');///
////////////////////////////////////////////////////////////////

/// GCM 商家設置未到用戶為失約用戶 //////////////////////////
Route::post('gcm','APIControllers\CrossModelAPI\GCM@gcm');///
/////////////////////////////////////////////////////////////

/// ModifyMemberClientType 更新用戶端運行類別 //////////////////////////////////////////////////////////////
Route::post('modify_member_clienttype','APIControllers\ModifyMemberClientType@modify_member_clienttype');///
////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// PushNotification 進行推撥 //////////////////////////////////////////////////////////
Route::post('push_notification','APIControllers\PushNotification@push_notification');///
////////////////////////////////////////////////////////////////////////////////////////

/// UploadModuleData 模組上傳資料 //////////////////////////////////////////////////////
Route::post('upload_moduledata','APIControllers\UploadModuleData@upload_moduledata');///
////////////////////////////////////////////////////////////////////////////////////////

/// QueryRelativeList 查詢會員親屬資料 ////////////////////////////////////////////////////
Route::post('query_relativelist','APIControllers\QueryRelativeList@query_relativelist');///
///////////////////////////////////////////////////////////////////////////////////////////

/// ModifyRelative 異動會員親屬資料 //////////////////////////////////////////////
Route::post('modify_relative','APIControllers\ModifyRelative@modify_relative');///
//////////////////////////////////////////////////////////////////////////////////

/// VerifySales 驗證SAT有效性 ///////////////////////////////////////////////
Route::post('vrf/verify_sales','APIControllers\VerifySales@verify_sales');///
/////////////////////////////////////////////////////////////////////////////

/// QueryMember 驗證該會員是否存在 //////////////////////////////////////
Route::post('query_member','APIControllers\QueryMember@query_member');///
/////////////////////////////////////////////////////////////////////////

/// ModifyMember 修改會員資料<admin模組用> /////////////////////////////////
Route::post('modify_member','APIControllers\ModifyMember@modify_member');///
////////////////////////////////////////////////////////////////////////////

/// QueryAdvanceData 會員資料查詢 //////////////////////////////////////////////////////
Route::post('query_advancedata','APIControllers\QueryAdvanceData@query_advancedata');///
////////////////////////////////////////////////////////////////////////////////////////

/// ModifyAdvanceData 修改會員資料 ////////////////////////////////////////////////////////
Route::post('modify_advancedata','APIControllers\ModifyAdvanceData@modify_advancedata');///
///////////////////////////////////////////////////////////////////////////////////////////

/// CreateNewMemberCar 會員新增車輛 /////////////////////////////////////////////////////////////////////
Route::post('create_new_member_car','APIControllers\MemberCar\CreateNewMemberCar@create_new_member_car');///
/////////////////////////////////////////////////////////////////////////////////////////////////////////

/// ModifyMemberCar 會員修改車輛 //////////////////////////////////////////////////////////////////
Route::post('modify_member_car','APIControllers\MemberCar\ModifyMemberCar@modify_member_car');///
/////////////////////////////////////////////////////////////////////////////////////////////////

/// QueryMemberCarDetail 會員車庫詳細內容 ///////////////////////////////////////////////////////////////////////////
Route::post('query_member_car_detail','APIControllers\MemberCar\QueryMemberCarDetail@query_member_car_detail');///
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// QueryMemberCarList 會員車庫列表 //////////////////////////////////////////////////////////////////////////
Route::post('query_member_car_list','APIControllers\MemberCar\QueryMemberCarList@query_member_car_list');///
////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// ModifyMemberIntroducer 修改會員介紹人 ////////////////////////////////////////////////////////////////////
Route::post('modify_member_introducer','APIControllers\ModifyMemberIntroducer@modify_member_introducer');///
///////////////////////////////////////////////////////////////////////////////////////////////////////////

/// CheckMemberData 檢查有無此會員存在 ///////////////////////////////////////////////////////////////////////
Route::post('check_member_data','APIControllers\CheckMemberData@check_member_data');///
///////////////////////////////////////////////////////////////////////////////////////////////////////////


//////準備廢棄/////////////////////////////////////////////////////////
//ssologin	接收消費者使用第三方登入訊息，並判斷是否需要新增會員資料
Route::post('account/ssologin', 'APIControllers\AccountController@ssologin');
/** prelogin	呼叫API取得密碼傳遞保護碼 * */
Route::post('prelogin', 'APIControllers\CrossModelAPIController@prelogin');
/** login  呼叫API執行模組登入作業 **/
Route::post('login', 'APIControllers\CrossModelAPIController@login');
/** postumlmessage  新增用戶收件匣訊息 **/
Route::post('postumlmessage', 'APIControllers\CrossModelAPIController@postumlmessage');
/** querymembercoininfo	接收會員資訊，回傳當前代幣持有數額  **/
Route::post('querymembercoininfo', 'APIControllers\CrossModelAPIController@querymembercoininfo');
/** modifymembercoininfo處理使用者購買瀏覽權限需求，判斷並增減會員代幣或紅利餘額後回傳  **/
Route::post('modifymembercoininfo', 'APIControllers\CrossModelAPIController@modifymembercoininfo');
/**verifyapitoken 驗正Api_Token **/
Route::post('verifyapitoken', 'APIControllers\CrossModelAPIController@verifyapitoken');
Route::post('verify/apitoken', 'APIControllers\CrossModelAPIController@verifyapitoken');
//// PostGetPushId ///////////////////////////////////////////////////
Route::post('push/getid','APIControllers\PostGetPushIdController@postGetPushId');
//// PostPassSalt ////////////////////////////////////////////////////
Route::post('salt','APIControllers\PostPassSaltController@postPassSalt');

Route::post('querymemberidinfo', 'APIControllers\ShopManageController@querymemberidinfo');

//// 用戶安全碼驗證、新增、修改
Route::post('vrf/verify_memberseccode', 'APIControllers\VerifyMemberSecCode@verify_memberseccode');
Route::post('modify_memberseccode', 'APIControllers\ModifyMemberSecCode@modify_memberseccode');

//
Route::post('apiCheck', 'APIControllers\APICheckController@APICheck');

