<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */
// Route::get('/testpush', 'ViewControllers\LoginController@testPush');


Route::get('/', function () {
    return View('login/index');
});

/* FB導頁回來的路徑 */
Route::get('/loginthirdparty', 'ViewControllers\ThirdPartyController@loginThirdParty');


/* 轉址接值頁 */
Route::get('/transform', function () {
    return view('login/transform');
});

/* 登入後轉址接值頁 */
Route::get('/login-transform', function () {
    return view('login/login-transform');
});

/* call api測試工具 */
Route::get('/callAPI', function () {
    return view('login/callAPI');
});


/**
 * 進入「社群註冊」頁面
 */
Route::get('/regiestthirdparty/{parameter}', function ($parameter) {
    return View::make('login/regiestthirdparty', compact('parameter'));
    // return view('login/regiestthirdparty',compact('parameter'));
});


/**
 * 進入「取得社群用戶資訊」頁面
 */
Route::get('/callbackthirdparty', function () {
    return view('login/callbackthirdparty');
});


/**
 * 進入「登入」頁面
 */
Route::get('/error', 'ViewControllers\ErrorController@error')->name('error');

/**
 * 進入「登入」頁面
 */
Route::get('/login/{parameter}', 'ViewControllers\LoginController@login')->name('login');
/**
 * 執行「登入」動作
 */
Route::post('/login/{parameter}', 'ViewControllers\LoginController@checkAccountPwd');
/**
 * 進入「登入成功」頁面
 */
Route::get('/loginsuccess', 'ViewControllers\LoginController@success')->name('loginsuccess');
/**
 * 進入「第三方登入成功」頁面
 */
Route::get('/thirdpartyloginsuccess', 'ViewControllers\ThirdPartyController@loginsuccess')->name('thirdpartyloginsuccess');
/**
 * 進入「註冊選擇」頁面
 */
Route::get('/regiest/{parameter}', 'ViewControllers\RegiestController@regiest')->name('regiest');
/**
 * 進入「註冊-輸入資料」頁面
 */
Route::get('/regiestiscar/{parameter}', 'ViewControllers\RegiestController@regiestiscar')->name('regiestiscar');
/**
 * 執行「註冊」動作，發送簡訊﹙驗證碼﹚
 */
Route::post('/regiestiscar/{parameter}', 'ViewControllers\RegiestController@checRegistkData');
/**
 * 進入「輸入驗證碼」頁面
 */
Route::get('/regiest/verify/{parameter}', 'ViewControllers\RegiestController@verify')->name('regiest/verify');
/**
 * 執行「檢查驗證碼」動作並儲存註冊資料
 */
Route::post('/regiest/verify/{parameter}', 'ViewControllers\RegiestController@saveRegiestData');
/**
 * 進入「註冊成功」頁面
 */
Route::get('/regiestsuccess/', 'ViewControllers\RegiestController@success')->name('regiestsuccess');
/**
 * 進入「忘記密碼」頁面
 */
Route::get('/forgetpwd/{parameter}', 'ViewControllers\ForgetPwdController@forgetPwd')->name('forgetpwd');
/**
 * 執行「檢查輸入帳號動作」
 */
Route::post('/forgetpwd/{parameter}', 'ViewControllers\ForgetPwdController@checkAccount');
/**
 * 進入「忘記密碼-發送成功」頁面
 */
Route::get('/forgetpwdsuccess', 'ViewControllers\ForgetPwdController@success')->name('forgetpwdsuccess');
/**
 * 進入「密碼重置」頁面
 */
Route::get('/resetpwd/{parameter}', 'ViewControllers\ResetPwdController@resetPwd')->name('resetpwd');
/**
 * 執行「密碼重置」動作
 */
Route::post('/resetpwd/{parameter}', 'ViewControllers\ResetPwdController@saveNewPwd');
/**
 * 進入「密碼重置成功」頁面
 */
Route::get('/resetpwdsuccess', 'ViewControllers\ResetPwdController@success')->name('resetpwdsuccess');
/**
 * 進入「修改密碼」頁面
 */
Route::get('/changepwd', 'ViewControllers\ChangePwdController@changePwd')->name('changepwd');
/**
 * 執行「修改密碼」動作
 */
Route::post('/changepwd', 'ViewControllers\ChangePwdController@executeChangePwd');
/**
 * 進入「修改密碼成功」頁面
 */
Route::get('/changepwdsuccess', 'ViewControllers\ChangePwdController@success')->name('changepwdsuccess');
/**
 * 回傳facebook路徑
 */
Route::post('/{action}/geturl/{account}/{parameter}', 'ViewControllers\ThirdPartyController@getThirdPartyUrl');
/**
 * 登入第三方
 */
Route::post('/{action}/callback/{account}/{parameter}', 'ViewControllers\ThirdPartyController@loginThirdParty');
/**
 * 藉由第三方資料註冊member會員
 */
Route::post('/regiestthirdparty/{parameter}', 'ViewControllers\ThirdPartyController@checkRegiestData');
/**
 * 檢察第三方驗證碼，並存資料
 */
Route::get('/regiestthirdparty/verify/{parameter}', 'ViewControllers\ThirdPartyController@verify');
/**
 * 檢察第三方驗證碼，並存資料
 */
Route::post('/regiestthirdparty/verify/{parameter}', 'ViewControllers\ThirdPartyController@saveRegiestData');
/**
 * 重新發送驗證碼(一般的跟第三方通用)
 */
Route::post('/resendVerifyCode/verify', 'ViewControllers\RegiestController@resendVerifyCode');
/**
 * 登出
 */
Route::get('/logout', 'ViewControllers\LogoutController@logout');
/**
 * 登出失敗
 */
Route::get('/logout/failed', 'ViewControllers\LogoutController@logoutFailed');