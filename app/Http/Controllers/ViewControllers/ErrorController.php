<?php

namespace App\Http\Controllers\ViewControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \Illuminate\Support\Facades\View;
use App\Library\CommonTools;

class ErrorController extends Controller {

    /**
     * 進入「登入」頁面
     * @param Request $request 前端POST進來的值
     * @param type $parameter 驗證用的參數
     * @return type
     */
    public function error(Request $request) {

        return View::make('errors/verifyerror');
    }

}
