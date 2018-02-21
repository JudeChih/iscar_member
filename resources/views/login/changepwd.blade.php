<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/png" href="app/image/iscar_icon.png">

    <meta name="theme-color" content="#ffffff">

    <title>修改密碼</title>

    <link rel="stylesheet" href="app/libs/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="app/libs/Framework7/dist/css/framework7.ios.min.css">
    <link rel="stylesheet" href="app/libs/Framework7/dist/css/framework7.ios.colors.min.css">
    <link rel="stylesheet" href="app/libs/Toast-for-Framework7-master/toast.css">

    <link rel="stylesheet" href="app/libs/slick-1.5.9/slick/slick.css">
    <link rel="stylesheet" href="app/libs/slick-1.5.9/slick/slick-theme.css">
    <link rel="stylesheet" href="app/css/style.css" id="theme-style">
    <link rel="stylesheet" href="app/css/animations.css">
    <link rel="stylesheet" href="app/libs/animate.css">
    <link rel="stylesheet" href="app/libs/jquery.countdown.package-2.1.0/css/jquery.countdown.css">
    <link rel="stylesheet" href="app/libs/swiper/dist/css/swiper.min.css">

    <link rel="stylesheet" href="app/css/Login/login-style.css" id="theme-style">

</head>

<body class="framework7-root">

    <div class="statusbar-overlay"></div>

        <div class="view view-main" data-page="changepwd">
            <!-- Navbar -->
            <div class="navbar">
                <div class="navbar-inner">
                    <div class="left sliding changepwd-left">
                        <a class="link icon-only">
                            <span class="icon-chevron-left"></span>
                        </a>
                    </div>
                    <div class="center sliding">修改密碼</div>
                    <div class="right sliding">
                        <a class="link icon-only">
                        </a>
                    </div>
                </div>
            </div>
            <!-- Pages -->
            <div class="pages">
                <div class="page">

                    <form action="{{$posturi}}" class="changepwd-form" method="post">
                    {{-- <form> --}}
                        {!! csrf_field() !!}
                        <!-- 內容 -->
                        <div class="page-content changepwd-content animated fadeIn">

                            <img src="app/image/mem_img_passwd.png" class="logo animated bounceIn">

                            <div class="title animated fadeIn">修改密碼</div>

                            <div class="list-block">
                                <ul>
                                    <li class="align-top passwordold-block">
                                        <div class="item-content">
                                            <div class="item-media"><i class="fa fa-lock" aria-hidden="true"></i></div>
                                            <div class="item-inner">
                                                <div class="item-input">
                                                    <input class="passwordold" name="passwordold" type="password" placeholder="舊密碼">
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="align-top passwordnew-block">
                                        <div class="item-content">
                                            <div class="item-media"><i class="fa fa-lock" aria-hidden="true"></i></div>
                                            <div class="item-inner">
                                                <div class="item-input">
                                                    <input name="passwordnew" class="passwordnew" type="password" placeholder="新密碼">
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="align-top passwordnewconfirm-block">
                                        <div class="item-content">
                                            <div class="item-media"><i class="fa fa-lock" aria-hidden="true"></i></div>
                                            <div class="item-inner">
                                                <div class="item-input">
                                                    <input name="passwordnewconfirm" class="passwordnewconfirm" type="password" placeholder="新密碼確認">
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="noUse">

                                    </li>
                                </ul>
                            </div>
                            <div class="send">
                                <a class="button button-big change_pwd_btn">送出</a>
                            </div>
                        </div>

                    </form>
                    {{-- {{ Form::close() }} --}}

                </div>
            </div>
        </div>
    </div>



    <div class="modal modal-in" style="display: none; margin-top: -63px;">
        <div class="modal-inner">
            <div class="modal-title">提醒</div>
            <div class="modal-text"></div>
        </div>
        @if(isset($error))
            <div class="modal-buttons modal-buttons-1 "><span class="modal-button modal-button-bold have_error">確認</span></div>
        @else
            <div class="modal-buttons modal-buttons-1 "><span class="modal-button modal-button-bold without_error">確認</span></div>
        @endif;
    </div>
    <div class="modal-overlay modal-overlay-visible" style="display: none;"></div>

    @if(isset($error))
    <input class="error" type="hidden" value="{{ $error }}">
    @endif


    <script type="text/javascript" src="app/libs/jquery/dist/jquery-1.11.3.min.js"></script>
    <script src="app/libs/swiper/dist/js/swiper.min.js"></script>
    <script type="text/javascript" src="app/libs/vendor/jflickrfeed.min.js"></script>
    <script type="text/javascript" src="app/libs/vendor/sha256.js"></script>
    <script type="text/javascript" src="app/libs/vendor/enc-base64-min.js"></script>
    <script src="app/libs/Toast-for-Framework7-master/toast.js"></script>
    <script src="app/libs/swiper/dist/js/swiper.min.js"></script>


    <!-- 驗證工具 -->
    <script type="text/javascript" src="app/libs/is/is.js"></script>

    <!-- JS-cookie -->
    <script src="app/libs/js-cookie/src/js.cookie.js"></script>

    <!-- 倒數計時器 -->
    <script src="app/libs/jquery.countdown.package-2.1.0/js/jquery.plugin.js"></script>
    <script src="app/libs/jquery.countdown.package-2.1.0/js/jquery.countdown.js"></script>
    <script src="{{ URL::asset('app/js/config.js') }}"></script>
    <script src="app/js/webview.js"></script>
    <script src="app/js/iPhone.js"></script>
    <script src="app/js/string.js"></script>
    <script src="app/js/Login/changepwd.js"></script>
    <!-- 瀏覽器裝置機碼 -->
    <script type="text/javascript">
        document.write('<script type="text/javascript" src="http://' + server_type + _region + '-member.iscarmg.com/app/js/generate_murid.js"><\/script>');
    </script>
    <script src="app/js/Login/login.js"></script>

    <!-- 裝置console -->
    <!-- <script src="app/libs/vConsole-dev/dist/vconsole.min.js"></script> -->


</body>

</html>
