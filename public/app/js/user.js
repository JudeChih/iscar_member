var app_version = '';
var isMobile = {
    Android: function() {
        return navigator.userAgent.match(/Android/i);
    },
    iOS: function() {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    any: function() {
        return (isMobile.Android() || isMobile.iOS());
    }
};

var WEB_URL = server_type + _region + '-app.iscarmg.com';
var MEMBER_URL = server_type + _region + '-member.iscarmg.com';

var browser_width = $(window).width();
var browser_height = $(window).height();

//頭像初始化
loginInit = function(callback) {
    if (Cookies.get(_main) !== undefined) {
        //get
        var mainSg = JSON.parse(Cookies.get(_main));
    } else {
        var mainSg ={};
    }

    if (mainSg.mdId) {
        //已登入狀態
        userAvatarInit();
        callback(mainSg);
    } else {
        //未登入狀態
        if (mainSg.sat != undefined) {
            //會員登入資料建置作業
            var data = {
                sat: mainSg.sat,
                mur: mainSg.murId
            };
            //console.log(JSON.stringify(data));
            $.ajax({
                contentType: "application/json; charset=utf-8",
                dataType: 'json',
                type: 'POST',
                url: 'http://' + MEMBER_URL + '/api/query_member_basicinfo',
                data: JSON.stringify(JSON.stringify(data)),
                success: function(r) {
                    jsonUrlDecode(r);
                    // alert(JSON.stringify(r));
                    if (r.query_member_basicinforesult) {
                        var rObj = JSON.parse(JSON.stringify(r.query_member_basicinforesult));

                        if (rObj.message_no === "000000000") {
                            mainSg.mdId = rObj.md_id;
                            mainSg.md_cname = rObj.md_cname;
                            mainSg.md_picturepath = rObj.md_picturepath;
                            mainSg.loginType = rObj.md_logintype;
                            mainSg.md_city = rObj.md_city;
                            mainSg.md_country = rObj.md_country;
                            mainSg.rl_zip = rObj.rl_zip;
                            mainSg.rl_city_code = rObj.rl_city_code;
                            mainSg.md_clienttype = rObj.md_clienttype;
                            mainSg.md_fbgender = rObj.md_fbgender;
                            mainSg.md_birthday = rObj.md_birthday;
                            mainSg.md_clubjoinstatus = rObj.md_clubjoinstatus;
                            mainSg.sso_token = rObj.sso_token;
                            mainSg.md_seccode_created = rObj.md_seccode_created;
                            Cookies.set(_main, JSON.stringify(mainSg), { domain: 'iscarmg.com' });
                            localStorage.setItem(_main, JSON.stringify(mainSg));
                            userAvatarInit();
                            callback(mainSg);
                        } else {
                            switch (rObj.message_no) {
                                case '999999992':
                                    alert('憑證有誤');
                                    break;
                            }
                            /*stringObj.return_header(rObj.message_no);
                            if (_tip) {
                                myApp.alert(_tip, stringObj.text.warn);
                                _tip = null;
                            }*/
                        }

                    }

                },
                error: function(r) {
                    console.log(JSON.stringify(r));

                }
            });
        } else {
            userAvatarInit();
            callback(mainSg);
        }
    }

};


//快捷鈕圖示
// $('.hot-key').html('<img src="http://' + MEMBER_URL + '/app/image/steering_wheel.png">');
$('.hot-key').html('<span>回主頁</span>');

//快捷鈕點擊
$('.hot-key').click(function() {
    if (Cookies.get(_main) !== undefined) {
        //get
        var mainSg = JSON.parse(Cookies.get(_main));
    } else {
        var mainSg ={};
    }
    // var mainSg = JSON.parse(localStorage.getItem('main')) || {};
    window.location = 'http://' + WEB_URL + '/Shortcut-menu/transform?user_info=' + encodeURIComponent(JSON.stringify(mainSg));
});

/**
 * 頭像初始化
 */
var login_status;
userAvatarInit = function() {
    if (Cookies.get(_main) !== undefined) {
        //get
        var mainSg = JSON.parse(Cookies.get(_main));
    } else {
        var mainSg ={};
    }
    // var mainSg = JSON.parse(localStorage.getItem('main')) || {};
    if (mainSg.mdId) {
        login_status = true;
        if (mainSg.loginType === '0') {

            //FB身分
            checkImage(mainSg.md_picturepath, function() {
                if (login_status) {
                    //存在
                    $('.iscar_member_icon').css('background-image', 'url(' + mainSg.md_picturepath + ')');
                }
            }, function() {
                //不存在
                $('.iscar_member_icon').html(mainSg.md_cname.substring(0, 1));
                $('.iscar_member_icon').css('background-image', 'url("")');
                $('.iscar_member_icon').css('background', 'orange');
                $('.iscar_member_icon').css('width', '35px');
                $('.iscar_member_icon').css('height', '35px');
                $('.iscar_member_icon').css('text-align', 'center');
                $('.iscar_member_icon').css('line-height', '35px');
                $('.iscar_member_icon').css('font-weight', 'bold');
            });


        } else if (mainSg.loginType === '2') {
            //訪客身分
            $('.iscar_member_icon').css('background-image', 'url(http://' + MEMBER_URL + '/app/image/general_user.png)');
        } else if (mainSg.loginType === '3') {
            $('.iscar_member_icon').html(mainSg.md_cname.substring(0, 1));
            $('.iscar_member_icon').css('background-image', 'url("")');
            $('.iscar_member_icon').css('background', 'orange');
            $('.iscar_member_icon').css('width', '35px');
            $('.iscar_member_icon').css('height', '35px');
            $('.iscar_member_icon').css('text-align', 'center');
            $('.iscar_member_icon').css('line-height', '35px');
            $('.iscar_member_icon').css('font-weight', 'bold');
        }
        $('.iscar_member_icon').css('background-size', '35px');
    } else {
        login_status = false;
        $('.navbar .center').css('left', '8px');
        $('.navbar .right').css('width', '66px');
        $('.iscar_member_icon').html('登入/註冊');
        $('.iscar_member_icon').css('background', 'rgba(0,0,0,0)');
        $('.iscar_member_icon').css('background-image', 'rgba(0,0,0,0)');
        $('.iscar_member_icon').css('width', '66px');
        $('.iscar_member_icon').css('height', '44px');
        $('.iscar_member_icon').css('line-height', '44px');
        $('.iscar_member_icon').css('font-size', '.8em');
        //$('.iscar_member_icon').css('width', '44px');
        //$('.iscar_member_icon').css('height', '44px');
        /*$('.iscar_member_icon').css('line-height', '1');
        $('.iscar_member_icon').css('background-image', 'url(http://' + MEMBER_URL + '/app/image/user_icon.png)');
        $('.iscar_member_icon').css('background-size', '25px');
        $('.iscar_member_icon').css('background-repeat', 'no-repeat');
        $('.iscar_member_icon').css('background-position', 'center');*/
    }


    $('.iscar_member_login').click(function() {
        var param = Cookies.get('parameter');
        param = atob(decodeURIComponent(param));
        param = JSON.parse(param);
        var mod = param.modacc;
        Cookies.set('mod', mod, { domain: 'iscarmg.com' });
        if (Cookies.get(_main) !== undefined) {
            //get
            var mainSg = JSON.parse(Cookies.get(_main));
        } else {
            var mainSg ={};
        }
        app_version = localStorage.getItem('app_version');

        if (app_version) {
            //App平台
            if (mainSg.mdId) {
                //已登入
                window.location = 'http://' + WEB_URL + '/User-menu/transform?user_info=' + encodeURIComponent(JSON.stringify(mainSg));
            } else {
                //未登入
                window.location = 'http://' + MEMBER_URL + '/transform?user_info=' + encodeURIComponent(JSON.stringify(mainSg)) + '&parameter=' + getCookie('parameter');
            }
        } else {
            //非App平台

            if (!window.location.href.match('webend_admin')) {
                if (mainSg.mdId) {
                    //已登入
                    window.location = 'http://' + WEB_URL + '/User-menu/transform?user_info=' + encodeURIComponent(JSON.stringify(mainSg));
                } else {
                    //未登入
                    window.location = 'http://' + MEMBER_URL + '/transform?user_info=' + encodeURIComponent(JSON.stringify(mainSg)) + '&parameter=' + getCookie('parameter');
                }
            }

        }
    });


    $('body').prepend('<div class="draggable-block"></div>');

    if (browser_width < 992) {
        $('.draggable-block').css('width', browser_width);
        $('.draggable-block').css('height', browser_height);
    } else {
        //console.log($('body').width());
        $('.draggable-block').css('width', $('body').width());
        $('.draggable-block').css('height', browser_height);
        $('.draggable-block').css('margin', '0 auto');

    }

    //快捷鈕圖示
    // $('.hot-key').html('<img src="http://' + MEMBER_URL + '/app/image/steering_wheel.png">');
    $('.hot-key').html('回主頁');
    //快捷鈕點擊
    $('.hot-key').click(function() {
        if (Cookies.get(_main) !== undefined) {
            //get
            var mainSg = JSON.parse(Cookies.get(_main));
        } else {
            var mainSg ={};
        }
        // var mainSg = JSON.parse(localStorage.getItem('main')) || {};
        window.location = 'http://' + WEB_URL + '/Shortcut-menu/transform?user_info=' + encodeURIComponent(JSON.stringify(mainSg));
    });
    //快捷鈕設置可拖動
    $('.hot-key').draggable({
        containment: ".draggable-block",
        distance: 25,
        scroll: false,
        zIndex: 5500
    });
};

//檢查圖片是否存在
checkImage = function(imageSrc, exists, notexists) {
    var img = new Image();
    img.onload = exists;
    img.onerror = notexists;
    img.src = imageSrc;
};


//綁定確認
bindingCheck = function(type) {

    if ($$('.modal.modal-in').length > 0) {
        myApp.closeModal('.binding-modal');
    }

    switch (type) {
        case 'facebook':
            myApp.modal({
                title: stringObj.text.warn,
                text: stringObj.text.fbBindingContext,
                buttons: [{
                    text: stringObj.text.cancel,
                    onClick: function() {

                    }
                }, {
                    text: stringObj.text.binding,
                    onClick: function() {
                        fbEvent = 'binding';

                        redirect_uri = 'http://' + WEB_URL + '/fb-binding';

                        window.location = "https://www.facebook.com/v2.8/dialog/oauth?client_id=875839542533172&display=popup&response_type=token&redirect_uri=" + redirect_uri + "&auth_type=rerequest&scope=publish_actions";
                    }
                }]
            });
            break;
        default:
            myApp.alert('<div class="row">' +
                '<div class="col-50" onclick="bindingCheck(\'facebook\')" style="margin: 0 auto;">' +
                '<img src="../app/image/facebook_icon.png" onerror=\'this.src="../app/image/imgDefault.png"\' />' +
                '</div>' +

                /*'<div class="col-50">' +
                '<img src="../../../image/google_icon.png" onerror=\'this.src="assets/themes/car/img/imgDefault.png"\' />' +
                '</div>' +*/

                /*'<div class="col-33">' +
                '<img src="../../../image/google_icon2.png" onerror=\'this.src="assets/themes/car/img/imgDefault.png"\' />' +
                '</div>' +*/

                '</div>', '<div>' + stringObj.text.noBinding + '</div><div>' + stringObj.text.bindingType + '</div>');

            $('.modal').addClass('binding-modal');

            break;
    }

};

jsonUrlDecode = function(obj) {
    for (var index in obj) {
        if (typeof obj[index] == 'object') {
            this.jsonUrlDecode(obj[index]);
        } else {
            obj[index] = decodeURIComponent(obj[index]);
        }
    }
};

/**
 * 開啟新Web畫面
 * @param  url 網址路徑
 */
toWeb = function(url) {
    if (isMobile.Android()) {
        Android.toWeb(url);
    } else if (isMobile.iOS()) {
        iPhone.toWeb(url);
    } else {
        var win = window.open(url, '_blank');
        win.focus();
    }
};

/**
 * 取cookies函數
 * @param  name 欄位名稱
 * @return 該欄位對應之value
 */
getCookie = function(name) {
    var arr = document.cookie.match(new RegExp("(^| )" + name + "=([^;]*)(;|$)"));
    if (arr != null) return unescape(arr[2]);
    return null;
};