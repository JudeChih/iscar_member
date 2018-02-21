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
    <div class="view view-main" data-page="changepwdsuccess">
        <!-- Navbar -->
        <div class="navbar">
            <div class="navbar-inner">
                <div class="left sliding changepwdsuccess-left">   {{-- 返回鍵 --}}
                    {{-- <a class="link icon-only">
                        <span class="icon-chevron-left"></span>
                    </a> --}}
                </div>
                <div class="center sliding">密碼修改成功</div>
                <div class="right">

                </div>
            </div>
        </div>
        <!-- Pages -->
        <div class="pages">
            <div class="page">

                <!-- 內容 -->
                <div class="page-content changepwdsuccess-content">

                    <img src="app/image/mem_img_check.png" class="logo animated bounceIn">

                    <div class="title animated fadeIn">密碼修改成功</div>
                    <p class="changepwd_p">下次登入請使用新密碼</p>

                    <a class="button button-big goto change_pwd_success_btn">立即返回</a>
                </div>
            </div>
        </div>
    </div>



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
    <script src="app/js/Login/changepwdsuccess.js"></script>
    <!-- 瀏覽器裝置機碼 -->
    <script type="text/javascript">
            

    </script>
    <script src="app/js/Login/login.js"></script>

    <!-- 裝置console -->
    <!-- <script src="app/libs/vConsole-dev/dist/vconsole.min.js"></script> -->


</body>

</html>