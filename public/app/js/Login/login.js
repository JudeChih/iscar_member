(function(window) {
    var t = 3; //設定跳轉倒數秒數
    var timeout;
    var nowPage; //當前頁面名稱

    var loginType;
    var loginObj = {
        _storage: {
            main: 'main',
            fbInfo: 'fbInfo',
            userData: 'userData',
            loginTimes: 'loginTimes',
            fbLoginTimes: 'fbLoginTimes',
            binding: 'binding'
        },
        _templateSet: {},
        //呼叫wcf模組
        _wcfget: function(i) {

            var url = i.url;
            var data = JSON.stringify(JSON.stringify(i.para));
            $$.ajax({
                contentType: "application/json; charset=utf-8",
                dataType: 'json',
                type: 'POST',
                url: url,
                data: data,
                success: function(r) {
                    if (i.success) {
                        i.success(r);
                    }
                },
                error: function(r) {
                    if (i.error) {
                        i.error(r);
                    }
                },
                complete: function(r) {
                    if (i.finish) {
                        i.finish(r);
                    }
                },
                beforeSend: function(r) {
                    if (i.progress) {
                        i.progress(r);
                    }
                },
            });
        },
        //取得,刪除,新增localStorage模組
        _dataStorage: function(name, obj) {
            if (obj === undefined) {
                //get
                return JSON.parse(localStorage.getItem(name));
            } else if (obj === null) {
                //del
                localStorage.removeItem(name);
                return true;
            } else {
                //set
                localStorage.setItem(name, JSON.stringify(obj));
                return true;
            }
            return false;
        },
        //取得,刪除,新增Cookies
        _dataCookies: function(name, obj) {
            if (obj === undefined) {
                if (Cookies.get(name) !== undefined) {
                    //get
                    return JSON.parse(Cookies.get(name));
                } else {
                    return false;
                }
            }
            if (obj === null) {
                //del
                Cookies.remove(name);
                return true;
            }
            if (typeof obj === 'object') {
                //set
                Cookies.set(name, JSON.stringify(obj));
                return true;
            }
            if (typeof obj === 'string') {
                //set
                Cookies.set(name, obj);
                return true;
            }
            return false;
        },
        template: function(name) {
            if (name in loginObj._templateSet) {
                return loginObj._templateSet[name];
            }
            //init
            var temp = ($$('#' + name).length) ? $$('#' + name).html() : '';
            var tempCompile = Template7.compile(temp);
            loginObj._templateSet[name] = tempCompile;
            return loginObj._templateSet[name];
        },
        jsonUrlDecode: function(obj) {
            for (var index in obj) {
                if (typeof obj[index] == 'object') {
                    this.jsonUrlDecode(obj[index]);
                } else {
                    obj[index] = decodeURIComponent(obj[index]);
                }
            }
        },
        jsonUrlEncode: function(obj) {
            for (var index in obj) {
                if (typeof obj[index] == 'object') {
                    this.jsonUrlEncode(obj[index]);
                } else {
                    obj[index] = encodeURIComponent(obj[index]);
                }
            }
        },
        //fb登入
        _fbLogin: function() {

            if (loginObj._dataStorage(loginObj._storage.fbLoginTimes) === null) {
                myApp.modal({
                    title: stringObj.text.welcomeTitle,
                    text: stringObj.text.fbLoginContext,
                    buttons: [{
                        text: stringObj.text.cancel
                    }, {
                        text: stringObj.text.login,
                        onClick: function() {
                            loginType = "0";
                            fbEvent = "login";
                            //webview.fbLogin();

                            var mainSg = loginObj._dataCookies(loginObj._storage.main) || {};

                            redirect_uri = 'http://' + stringObj.WEB_URL;

                            window.location = "https://www.facebook.com/v2.8/dialog/oauth?client_id=875839542533172&display=popup&response_type=token&redirect_uri=" + redirect_uri + "&auth_type=rerequest&scope=publish_actions";

                        }
                    }]
                });
            } else {
                loginType = "0";
                fbEvent = "login";
                //webview.fbLogin();

                var mainSg = loginObj._dataCookies(loginObj._storage.main) || {};

                redirect_uri = 'http://' + stringObj.WEB_URL;

                window.location = "https://www.facebook.com/v2.8/dialog/oauth?client_id=875839542533172&display=popup&response_type=token&redirect_uri=" + redirect_uri + "&auth_type=rerequest&scope=publish_actions";

            }

        },
        //取得FB用戶資訊
        /*getFBData: function(accessToken) {

            loginType = '0';

            $.ajax({
                contentType: "text/plain; charset=utf-8",
                url: 'https://graph.facebook.com/v2.8/me',
                type: 'GET',
                data: 'fields=id%2Cname%2Cemail%2Cfirst_name%2Clast_name%2Clocale%2Cgender%2Cbirthday%2Ctimezone&access_token=' + accessToken,
                dataType: 'json',
                success: function(r) {
                    loginObj.jsonUrlDecode(r);
                    r.accessToken = accessToken;
                    //console.log(JSON.stringify(r));
                    Cookies.set('fb_data', JSON.stringify(r));

                },
                error: function(r) {
                    console.log(JSON.stringify(r));
                    noNetwork();
                }
            });
        },*/
        //登入畫面初始化
        loginInit: function(page) {

            var loginTimes = loginObj._dataStorage(loginObj._storage.loginTimes);

            //關於我們
            $('.aboutLink').click(function() {
                myApp.popup('.popup-about');
            });

            //隱私權
            $('.privacyLink').click(function() {
                myApp.popup('.popup-privacy');
            });

            //返回
            $('.backBtn').click(function() {
                var src = $(this).data('src');
                if(src != ''){
                    window.location.href = src;
                }else{
                    window.history.back(-2);
                }
            });

            //立即註冊
            $('.now_registered').click(function() {
                mainView.router.load({
                    url: 'regiest/' + Cookies.get('parameter')
                });
                // loginObj.queryiscarpolicy('personaldata_policy', 'registered');
            });

            //顯示密碼
            if(Cookies.get('app_version')){
                $('.showpwd').on('touchstart',function(event) {
                    $(".password").prop('type', 'text');
                });
                $('.showpwd').on('touchend',function(event) {
                    $(".password").prop('type', 'password');
                });
            }else{
                $('.showpwd').mousedown(function(event) {
                    $(".password").prop('type', 'text');
                });
                $('.showpwd').mouseup(function(event) {
                    $(".password").prop('type', 'password');
                });
                $('.showpwd').mouseleave(function(event) {
                    $(".password").prop('type', 'password');
                });
            }

            //Facebook登入
            if (Cookies.get('app_version')) {
                $('.fb_login').removeClass('external');
                $('.fb_login').prop('href', 'javascript:void(0)');
            }
            $('.fb_login').unbind('click');
            $('.fb_login').click(function() {
                Cookies.set('action', 'login');
                Cookies.set('account', 'facebook');
                if (Cookies.get('app_version')) {
                    webview.fbLogin();
                }
            });

            //Google登入
            $('.google_login').click(function() {
                Cookies.set('action', 'login');
                Cookies.set('account', 'google');
                loginObj.getThirdPartyUrl('login', 'google');
            });

            //Wechat登入
            $('.wechat_login').click(function() {
                window.location = "test";
            });

            //忘記密碼
            $('.forget_password').click(function() {
                //forget_password();
                mainView.router.load({
                    url: 'forgetpwd/' + Cookies.get('parameter')
                });
            });

            //一般登入
            $('.login-btn').click(function() {

                if ($('.account').val() === '' || $('.password').val() === '') {
                    myApp.alert(stringObj.text.no_input_name_or_pass, stringObj.text.warn);
                } else {
                    myApp.showPreloader(stringObj.text.logining);
                    $('.login-form').submit(); //送出登入表單
                }
            });

        },
        //平台法條查詢
        queryiscarpolicy: function(upr_itemalisa, type) {

            var r = {
                "queryiscarpolicyresult": {
                    "message_no": "000000000",
                    "content01": "",
                    "content02": "",
                    "content03": "",
                    "upr_language_fit": "zh-TW",
                    "upr_itemname": stringObj.regulations_title,
                    "upr_itemalisa": "personaldata_policy",
                    "upr_itemcontent": stringObj.regulations_context,
                    "upr_mustread_tag": "1",
                    "upr_agreetonext_tag": "1"
                }
            };
            var rObj = JSON.parse(JSON.stringify(r.queryiscarpolicyresult));
            if (rObj.message_no === "000000000") {

                var popupHTML = '<div class="popup authorization-popup">' +
                    '<div class="content-block">' +
                    '<div class="title">' + rObj.upr_itemname + '</div>' +
                    '<div class="authorization-content">' +
                    rObj.upr_itemcontent +
                    // '<div class="row btns"><div class="col-45 refuse close-popup">' + stringObj.text.refuse + '</div><div class="col-10"></div><div class="col-45 agree">' + stringObj.text.agree + '</div></div>'
                    '<div class="row btns"><div class="col-100 agree">' + stringObj.text.agree + '</div></div>' +
                    '</div>' +
                    '</div>' +
                    '</div>';
                myApp.popup(popupHTML);


                $('.agree').click(function() {

                    myApp.closeModal('.authorization-popup');

                    localStorage.setItem('moc_privatedataallow', '1');

                    switch (type) {
                        case 'registered':
                            $('.iscarpolicy_style').find('input').prop('checked', true);
                            // mainView.router.load({
                            //     url: 'regiest/' + Cookies.get('parameter')
                            // });
                            //member_registered();

                            break;
                            // case 'fb_registered':
                            //     //mobile_set();
                            //     mainView.router.load({
                            //         url: 'social-regiest/' + Cookies.get('parameter')
                            //     });
                            //     break;
                    }


                });


            }



            /*

             //初始化個資同意使用記錄
             loginObj._dataStorage('moc_privatedataallow', null);

             //語系
             var userLang = navigator.language || navigator.userLanguage;

             var mainSg = loginObj._dataStorage(loginObj._storage.main);
             var data = {
             servicetoken: mainSg.servicetoken,
             userdevicecode: mainSg.userdevicecode,
             upr_itemalisa: upr_itemalisa,
             upr_language_fit: userLang
             };
             console.log(JSON.stringify(data));

             myApp.showIndicator();
             loginObj._wcfget({
             url: myCarObj.dataUrl.queryiscarpolicy,
             para: data,
             success: function (r) {
             loginObj.jsonUrlDecode(r);
             console.log(JSON.stringify(r));
             myApp.hideIndicator();
             if (r.queryiscarpolicyresult) {
             var rObj = JSON.parse(JSON.stringify(r.queryiscarpolicyresult));
             if (rObj.message_no === "000000000") {

             var popupHTML = '<div class="popup authorization-popup">' +
             '<div class="content-block">' +

             '<div class="title">' + rObj.upr_itemname + '</div>' +

             '<div class="authorization-content">' +
             rObj.upr_itemcontent +


             '<div class="row btns"><div class="col-45 refuse close-popup">' + stringObj.text.refuse + '</div><div class="col-10"></div><div class="col-45 agree close-popup">' + stringObj.text.agree + '</div></div>' +


             '</div>' +



             '</div>' +
             '</div>'
             myApp.popup(popupHTML);


             $('.agree').click(function () {

             localStorage.setItem('moc_privatedataallow', '1');


             });


             } else {
             stringObj.return_header(rObj.message_no);
             if (_tip) {
             myApp.alert(_tip + '( ' + rObj.message_no + ' )', stringObj.text.warn);
             _tip = null;
             }
             }

             }
             },
             error: function (r) {
             myApp.hideIndicator();
             noNetwork();
             }
             });*/
        },
        //註冊選擇
        regiestInit: function(page) {
            $('.regiest-left').click(function() {
                window.history.back();
            });
            if (Cookies.get('app_version')) {
                $('.fb-regiest').removeClass('external');
                $('.fb-regiest').prop('href', 'javascript:void(0)');
            }

            $('.fb-regiest').click(function() {
                Cookies.set('action', 'regiest');
                Cookies.set('account', 'facebook');
                if (Cookies.get('app_version')) {
                    webview.fbLogin();
                    // loginObj.getThirdPartyUrl('login', 'facebook');
                }
            });
            $('.google-regiest').click(function() {
                Cookies.set('action', 'regiest');
                Cookies.set('account', 'google');
                loginObj.getThirdPartyUrl('regiest', 'google');
            });
            $('.wechat-regiest').click(function() {
                Cookies.set('action', 'regiest');
                Cookies.set('account', 'wechat');
                loginObj.getThirdPartyUrl('regiest', 'wechat');
            });
        },
        //一般註冊初始化
        regiestiscarInit: function(page) {

            var pickerRegion = myApp.picker({
                input: '.countrycode-picker',
                rotateEffect: false,
                toolbarTemplate: '<div class="toolbar">' +
                    '<div class="toolbar-inner">' +
                    '<div class="left">' +
                    '<a href="#" class="link close-picker"><i class="fa fa-trash-o" aria-hidden="true"></i></a>' +
                    '</div>' +
                    '<div class="right">' +
                    '<a href="#" class="link close-picker"><i class="fa fa-check" aria-hidden="true"></i></a>' +
                    '</div>' +
                    '</div>' +
                    '</div>',
                formatValue: function(p, values, displayValues) {
                    return displayValues[0];
                },
                onOpen: function(p) {
                    //p.setValue([parseInt(data.fbgender)]);
                    $('.left').click(function() {
                        $('.countrycode-picker').val('');
                        $('.countrycode').val('');
                        p.value = '';
                    });
                },
                cols: [{
                        values: [0, 1],
                        displayValues: ['台灣', '大陸'],
                        textAlign: 'center',
                        width: '100%',
                        onChange: function(picker, mouth) {
                            // $('.cellphone').val('');
                            $('.countrycode').val(picker.cols[0].activeIndex + 1);
                            // $('.cellphone').attr('placeholder', stringObj.text.mobile);
                        }

                    }

                ],
                onClose: function(p) {
                    //data.fbgender = p.value.toString();
                }
            });

            //電子郵件
            $$('.account-block').on('click', function() {
                myApp.alert('<input class="input-value" type="text" placeholder="' + stringObj.text.input_email + '">', stringObj.text.email);

                $('.input-value').val($('.regiestiscar-form .account').val());

                $('.input-value').focus();

                $('.modal-button').click(function() {
                    var input_value = $('.input-value').val();
                    $('.regiestiscar-form .account').val(input_value.toLowerCase());
                });
            });

            //密碼
            $$('.password-block').on('click', function() {
                myApp.alert('<input class="input-value" type="password" placeholder="' + stringObj.text.inputPassword + '">', stringObj.text.member_password);

                $('.input-value').val($('.regiestiscar-form .password').val());

                $('.input-value').focus();

                $('.modal-button').click(function() {
                    $('.regiestiscar-form .password').val($('.input-value').val());
                });
            });

            //密碼確認
            $$('.passwordconfirm-block').on('click', function() {
                myApp.alert('<input class="input-value" type="password" placeholder="' + stringObj.text.inputPassword + '">', stringObj.text.pass_check);

                $('.input-value').val($('.regiestiscar-form .passwordconfirm').val());

                $('.input-value').focus();

                $('.modal-button').click(function() {
                    $('.regiestiscar-form .passwordconfirm').val($('.input-value').val());
                });
            });

            //暱稱
            $$('.nickname-block').on('click', function() {
                myApp.alert('<input class="input-value" type="text" placeholder="' + stringObj.text.input_nickname + '" onkeydown="input_limit(this, 50);" onkeyup="input_limit(this, 50);">', stringObj.text.nickname);

                $('.input-value').val($('.regiestiscar-form .nickname').val());

                $('.input-value').focus();

                $('.modal-button').click(function() {
                    $('.regiestiscar-form .nickname').val($('.input-value').val());
                });
            });

            //手機
            $$('.cellphone-block').on('click', function() {
                if ($('.regiestiscar-form .countrycode').val() != '') {
                    myApp.alert('<input class="input-value" type="number" placeholder="' + stringObj.text.input_cellphone + '">', stringObj.text.mobile);

                    $('.input-value').val($('.regiestiscar-form .cellphone').val());

                    $('.input-value').focus();

                    switch ($('.regiestiscar-form .countrycode').val()) {
                        case '1':
                            $('.input-value').attr('onkeydown', 'input_limit(this, 9);');
                            break;
                        case '2':
                            $('.input-value').attr('onkeydown', 'input_limit(this, 10);');
                            break;
                    }

                    $('.modal-button').click(function() {
                        $('.regiestiscar-form .cellphone').val($('.input-value').val());
                    });
                }
            });

            //查看條款
            $('.query_iscarpolicy').click(function() {
                loginObj.queryiscarpolicy('personaldata_policy', 'registered');
            })

            $('.regiestiscar-left').click(function() {
                window.history.back();
            });

            $('.registered').click(function() {

                $('.account-block').removeClass('lengthError');
                $('.nickname-block').removeClass('lengthError');
                $('.cellphone-block').removeClass('lengthError');
                $('.account-block').removeClass('formatError');
                $('.account-block').removeClass('spacesError');
                $('.password-block').removeClass('spacesError');
                $('.passwordconfirm-block').removeClass('spacesError');
                $('.nickname-block').removeClass('spacesError');
                $('.countrycode-block').removeClass('spacesError');
                $('.cellphone-block').removeClass('spacesError');
                $('.account-block').removeClass('nullError');
                $('.password-block').removeClass('nullError');
                $('.passwordconfirm-block').removeClass('nullError');
                $('.nickname-block').removeClass('nullError');
                $('.countrycode-block').removeClass('nullError');
                $('.cellphone-block').removeClass('nullError');
                $('.iscarpolicy_style').removeClass('nullError');


                var account = $('.regiestiscar-form .account').val();
                var password = $('.regiestiscar-form .password').val();
                var passwordconfirm = $('.regiestiscar-form .passwordconfirm').val();
                var nickname = $('.regiestiscar-form .nickname').val();
                var countrycode = $('.regiestiscar-form .countrycode').val();
                var cellphone = $('.regiestiscar-form .cellphone').val();

                var isError = false;

                var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i; //電子郵件格式限制
                if (account.search(emailRegEx) == -1 && account !== "") {
                    $('.account-block').addClass('formatError');
                    isError = true;
                }


                //長度判斷
                if (account.length > 64) {
                    $('.account-block').addClass('lengthError');
                    isError = true;
                }
                if (nickname.length > 50) {
                    $('.nickname-block').addClass('lengthError');
                    isError = true;
                }
                switch (countrycode) {
                    case '1':
                        if (cellphone.length != 10) {
                            $('.cellphone-block').addClass('lengthError');
                            isError = true;
                        }
                        break;
                    case '2':
                        if (cellphone.length != 11) {
                            $('.cellphone-block').addClass('lengthError');
                            isError = true;
                        }
                        break;
                }


                //空白字元判斷
                if (account.indexOf(" ") !== -1) {
                    $('.account-block').addClass('spacesError');
                    isError = true;
                }
                if (password.indexOf(" ") !== -1) {
                    $('.password-block').addClass('spacesError');
                    isError = true;
                }
                if (passwordconfirm.indexOf(" ") !== -1) {
                    $('.passwordconfirm-block').addClass('spacesError');
                    isError = true;
                }
                if (nickname.indexOf(" ") !== -1) {
                    $('.nickname-block').addClass('spacesError');
                    isError = true;
                }
                if (countrycode.indexOf(" ") !== -1) {
                    $('.countrycode-block').addClass('spacesError');
                    isError = true;
                }
                if (cellphone.indexOf(" ") !== -1) {
                    $('.cellphone-block').addClass('spacesError');
                    isError = true;
                }

                //填寫判斷
                if (account === "") {
                    $('.account-block').addClass('nullError');
                    isError = true;
                }
                if (password === "") {
                    $('.password-block').addClass('nullError');
                    isError = true;
                }
                if (passwordconfirm === "") {
                    $('.passwordconfirm-block').addClass('nullError');
                    isError = true;
                }
                if (nickname === "") {
                    $('.nickname-block').addClass('nullError');
                    isError = true;
                }
                if (countrycode === "") {
                    $('.countrycode-block').addClass('nullError');
                    isError = true;
                }
                if (cellphone === "") {
                    $('.cellphone-block').addClass('nullError');
                    isError = true;
                }
                if (!$('.iscarpolicy_style').find('input').prop('checked')) {
                    $('.iscarpolicy_style').addClass('nullError');
                    isError = true;
                }

                if (password != '' && passwordconfirm != '') {
                    //密碼確認判斷
                    if (password === passwordconfirm) {
                        //強度判斷
                        //利用match函數去比較密碼是否符合指定條件：最少一個數字，最少一個小階英文，長度限制為8。
                        var chkPwdStength = password.match(/((?=.*\d)(?=.*[a-z]).{8})/);

                        //若match回傳的值為null，跳出警告並阻止表單送出。
                        if (chkPwdStength == null) {
                            myApp.alert('密碼強度不足，需包含英文字母、數字、大於八位數。', stringObj.text.warn);
                            isError = true;
                        } else {
                            if (!isError) {
                                Cookies.set('countrycode', countrycode);
                                Cookies.set('cellphone', cellphone);
                                $('.regiestiscar-form').submit(); //送出註冊表單
                            }
                        }
                    } else {
                        myApp.alert(stringObj.text.pass_check_error, stringObj.text.warn);
                    }
                }

            });

        },
        //社群會員註冊
        regiestthirdpartyInit: function(page) {
            var q = page.query;
            var fb_data = JSON.parse(Cookies.get('fb_data'));
            $('.account').val(fb_data.email);
            $('.nickname').val(fb_data.name.replace("+", " "));

            var pickerRegion = myApp.picker({
                input: '.regiestthirdparty-content .countrycode-picker',
                rotateEffect: false,
                toolbarTemplate: '<div class="toolbar">' +
                    '<div class="toolbar-inner">' +
                    '<div class="left">' +
                    '<a href="#" class="link close-picker"><i class="fa fa-trash-o" aria-hidden="true"></i></a>' +
                    '</div>' +
                    '<div class="right">' +
                    '<a href="#" class="link close-picker"><i class="fa fa-check" aria-hidden="true"></i></a>' +
                    '</div>' +
                    '</div>' +
                    '</div>',
                formatValue: function(p, values, displayValues) {
                    return displayValues[0];
                },
                onOpen: function(p) {
                    //p.setValue([parseInt(data.fbgender)]);
                    $('.left').click(function() {
                        $('.regiestthirdparty-content .countrycode-picker').val('');
                        $('.regiestthirdparty-content .countrycode').val('');
                        p.value = '';
                    });
                },
                cols: [{
                        values: [0, 1],
                        displayValues: ['台灣', '大陸'],
                        textAlign: 'center',
                        width: '100%',
                        onChange: function(picker, mouth) {
                            // $('.regiestthirdparty-content .cellphone').val('');
                            $('.regiestthirdparty-content .countrycode').val(picker.cols[0].activeIndex + 1);
                            // $('.cellphone').attr('placeholder', stringObj.text.mobile);
                        }

                    }

                ],
                onClose: function(p) {
                    //data.fbgender = p.value.toString();
                }
            });

            //電子郵件
            $$('.account-block').on('click', function() {
                myApp.alert('<input class="input-value" type="text" placeholder="' + stringObj.text.input_email + '">', stringObj.text.email);

                $('.input-value').val($('.regiestthirdparty-form .account').val());

                $('.input-value').focus();

                $('.modal-button').click(function() {
                    var input_value = $('.input-value').val();
                    $('.regiestthirdparty-form .account').val(input_value.toLowerCase());
                });
            });

            //暱稱
            $$('.nickname-block').on('click', function() {
                myApp.alert('<input class="input-value" type="text" placeholder="' + stringObj.text.input_nickname + '" onkeydown="input_limit(this, 50);" onkeyup="input_limit(this, 50);">', stringObj.text.nickname);

                $('.input-value').val($('.regiestthirdparty-form .nickname').val());

                $('.input-value').focus();

                $('.modal-button').click(function() {
                    $('.regiestthirdparty-form .nickname').val($('.input-value').val());
                });
            });

            //手機
            $$('.cellphone-block').on('click', function() {
                if ($('.regiestthirdparty-form .countrycode').val() != '') {
                    myApp.alert('<input class="input-value" type="number" placeholder="' + stringObj.text.input_cellphone + '">', stringObj.text.mobile);

                    $('.input-value').val($('.regiestthirdparty-form .cellphone').val());

                    $('.input-value').focus();

                    switch ($('.regiestiscar-form .countrycode').val()) {
                        case '1':
                            $('.input-value').attr('onkeydown', 'input_limit(this, 9);');
                            break;
                        case '2':
                            $('.input-value').attr('onkeydown', 'input_limit(this, 10);');
                            break;
                    }

                    $('.modal-button').click(function() {
                        $('.regiestthirdparty-form .cellphone').val($('.input-value').val());
                    });
                }
            });

            //查看條款
            $('.query_iscarpolicy').click(function() {
                loginObj.queryiscarpolicy('personaldata_policy', 'registered');
            })

            $('.regiestthirdparty-left').click(function() {
                window.history.go(-2);
            });

            $('.registered').click(function() {

                $('.account-block').removeClass('lengthError');
                $('.nickname-block').removeClass('lengthError');
                $('.cellphone-block').removeClass('lengthError');
                $('.account-block').removeClass('formatError');
                $('.account-block').removeClass('spacesError');
                $('.nickname-block').removeClass('spacesError');
                $('.countrycode-block').removeClass('spacesError');
                $('.cellphone-block').removeClass('spacesError');
                $('.account-block').removeClass('nullError');
                $('.nickname-block').removeClass('nullError');
                $('.countrycode-block').removeClass('nullError');
                $('.cellphone-block').removeClass('nullError');
                $('.iscarpolicy_style').removeClass('nullError');

                var account = $('.regiestthirdparty-form .account').val();
                var nickname = $('.regiestthirdparty-form .nickname').val();
                var countrycode = $('.regiestthirdparty-form .countrycode').val();
                var cellphone = $('.regiestthirdparty-form .cellphone').val();
                $('.regiestthirdparty-form .ssodata').val(JSON.stringify(fb_data));

                var isError = false;

                var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i; //電子郵件格式限制
                if (account.search(emailRegEx) == -1 && account !== "") {
                    $('.account-block').addClass('formatError');
                    isError = true;
                }


                //長度判斷
                if (account.length > 64) {
                    $('.account-block').addClass('lengthError');
                    isError = true;
                }
                if (nickname.length > 50) {
                    $('.nickname-block').addClass('lengthError');
                    isError = true;
                }
                switch (countrycode) {
                    case '1':
                        if (cellphone.length != 10) {
                            $('.cellphone-block').addClass('lengthError');
                            isError = true;
                        }
                        break;
                    case '2':
                        if (cellphone.length != 11) {
                            $('.cellphone-block').addClass('lengthError');
                            isError = true;
                        }
                        break;
                }


                //空白字元判斷
                if (account.indexOf(" ") !== -1) {
                    $('.account-block').addClass('spacesError');
                    isError = true;
                }
                if (countrycode.indexOf(" ") !== -1) {
                    $('.countrycode-block').addClass('spacesError');
                    isError = true;
                }
                if (cellphone.indexOf(" ") !== -1) {
                    $('.cellphone-block').addClass('spacesError');
                    isError = true;
                }

                //填寫判斷
                if (account === "" || account.trim() === "") {
                    $('.account-block').addClass('nullError');
                    isError = true;
                }
                if (nickname === "" || nickname.trim() === "") {
                    $('.nickname-block').addClass('nullError');
                    isError = true;
                }
                if (countrycode === "") {
                    $('.countrycode-block').addClass('nullError');
                    isError = true;
                }
                if (cellphone === "" || cellphone.trim() === "") {
                    $('.cellphone-block').addClass('nullError');
                    isError = true;
                }
                if (!$('.iscarpolicy_style').find('input').prop('checked')) {
                    $('.iscarpolicy_style').addClass('nullError');
                    isError = true;
                }

                if (!isError) {
                    Cookies.set('countrycode', countrycode);
                    Cookies.set('cellphone', cellphone);
                    $('.regiestthirdparty-form').submit(); //送出註冊表單
                }

            });

        },
        //重新發送驗證碼
        resendVerifyCode: function() {
            myApp.showIndicator();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                contentType: "application/json; charset=utf-8",
                url: '/resendVerifyCode/verify',
                type: 'POST',
                data: JSON.stringify({
                    countrycode: Cookies.get('countrycode'),
                    cellphone: Cookies.get('cellphone')
                }),
                dataType: 'json',
                success: function(r) {
                    myApp.hideIndicator();
                    if (r.code === '0000') {

                        var shortly = new Date(); //倒數時間
                        shortly.setMinutes(shortly.getMinutes() + 10); //倒數分鐘數
                        //shortly.setSeconds(shortly.getSeconds() + 5); //倒數秒數
                        Cookies.set('shortly', shortly); //將倒數時間存進Cookies
                        $('.shortly').countdown('option', {
                            until: shortly
                        });
                        $('.not-send').css('display', 'block'); //顯示倒數鈕
                        $('.re-send').css('display', 'none'); //隱藏重新發送鈕

                    } else {
                        myApp.alert(r.message, stringObj.text.warn);
                    }
                },
                error: function(r) {
                    myApp.hideIndicator();
                    //console.log(JSON.stringify(r));
                    noNetwork();
                }
            });
        },
        //手機驗證碼
        regiestverifyInit: function(page) {
            //返回鈕
            $('.regiestverify-left').click(function() {
                window.history.back();
            });
            //確認鈕
            $('.confirm').click(function() {

                $('.verifycode-block').removeClass('lengthError');
                $('.verifycode-block').removeClass('spacesError');
                $('.verifycode-block').removeClass('nullError');
                var verifycode = $('.verify-form .verifycode').val();
                var isError = false;

                //長度判斷
                if (verifycode.length != 6) {
                    $('.verifycode-block').addClass('lengthError');
                    isError = true;
                }
                //空白字元判斷
                if (verifycode.indexOf(" ") !== -1) {
                    $('.verifycode-block').addClass('spacesError');
                    isError = true;
                }
                //填寫判斷
                if (verifycode === "") {
                    $('.verifycode-block').addClass('nullError');
                    isError = true;
                }

                if (!isError) {
                    $('.verify-form').submit(); //送出驗證碼表單
                }



            });

            var now_date = new Date(); //目前時間
            var shortly = new Date(); //倒數時間
            // if (Cookies.get('shortly') != undefined) {
            //     //若已設置倒數時間
            //     shortly = new Date(Cookies.get('shortly')); //將已存進Cookies之倒數時間設置為當前倒數時間
            //     if (((now_date - shortly) / (1000 * 60 * 60 * 24)) < 0) {
            //         //若倒數時間未結束
            //         $('.not-send').css('display', 'block'); //顯示倒數鈕
            //     } else {
            //         //若倒數時間已結束
            //         $('.re-send').css('display', 'block'); //顯示重新發送鈕
            //     }
            // } else {
            //     //若未設置倒數時間

            // }
            $('.not-send').css('display', 'block'); //顯示倒數鈕
            shortly.setMinutes(shortly.getMinutes() + 10); //倒數分鐘數
            Cookies.set('shortly', shortly); //將倒數時間存進Cookies

            if (((now_date - shortly) / (1000 * 60 * 60 * 24)) < 0) {
                //若倒數時間未結束
                $('.not-send').css('display', 'block'); //顯示倒數鈕
            } else {
                //若倒數時間已結束
                $('.re-send').css('display', 'block'); //顯示重新發送鈕
            }

            $('.re-send').click(function() {
                loginObj.resendVerifyCode();
            });

            $('.shortly').countdown({
                until: shortly, //時間設置
                onExpiry: liftOff, //計時歸0時執行function
                onTick: watchCountdown, //每秒執行function
                format: 'MS' //時間格式
            });

        },
        //忘記密碼
        forgetpwdInit: function(page) {
            $('.forgetpwd-left').click(function() {
                window.history.back();
            });
            //電子郵件
            // $$('.account-block').on('click', function() {
            //     myApp.alert('<input class="input-value" type="text" placeholder="' + stringObj.text.input_email + '">', stringObj.text.email);

            //     $('.input-value').val($('.forgetpwd-form .account').val());

            //     $('.input-value').focus();

            //     $('.modal-button').click(function() {
            //         $('.forgetpwd-form .account').val($('.input-value').val());
            //     });
            // });
            
            $('.send').click(function() {
                myApp.showIndicator();
                $('.account-block').removeClass('lengthError');
                $('.account-block').removeClass('formatError');
                $('.account-block').removeClass('spacesError');
                $('.account-block').removeClass('nullError');
                var account = $('.forgetpwd-form .account').val();
                var isError = false;
                var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i; //電子郵件格式限制
                if (account.search(emailRegEx) == -1 && account !== "") {
                    $('.account-block').addClass('formatError');
                    isError = true;
                }
                //長度判斷
                if (account.length > 64) {
                    $('.account-block').addClass('lengthError');
                    isError = true;
                }
                //空白字元判斷
                if (account.indexOf(" ") !== -1) {
                    $('.account-block').addClass('spacesError');
                    isError = true;
                }
                //填寫判斷
                if (account === "") {
                    $('.account-block').addClass('nullError');
                    isError = true;
                }


                if (!isError) {
                    $('.forgetpwd-form').submit(); //送出註冊表單
                } else {
                    myApp.hideIndicator();
                }


            });
        },
        //註冊完成
        regiestsuccessInit: function(page) {
            var q = page.query;

            Cookies.remove('countrycode'); //將Cookies地區資料清除
            Cookies.remove('cellphone'); //將Cookies電話資料清除

            $('.regiestsuccess-left').click(function() {
                window.history.back();
            });

            //執行倒數
            showTime(q.redirect_uri);

            //立即跳轉
            $('.goto').click(function() {
                //跳轉倒數初始化
                clearTimeout(timeout);
                t = 3;
                //toWeb(q.redirect_uri);
                window.location = q.redirect_uri;
            });

        },
        //忘記密碼發送完成
        forgetpwdsuccessInit: function(page) {
            var q = page.query;

            $('.forgetpwdsuccess-left').click(function() {
                window.history.back();
            });

            //執行倒數
            showTime(q.redirect_uri);

            //立即跳轉
            $('.goto').click(function() {
                //跳轉倒數初始化
                clearTimeout(timeout);
                t = 3;
                //toWeb(q.redirect_uri);
                window.location = q.redirect_uri;
            });

        },
        //密碼重置完成
        resetpwdsuccessInit: function(page) {
            var q = page.query;

            $('.resetpwdsuccess-left').click(function() {
                window.history.back();
            });

            //執行倒數
            showTime(q.redirect_uri);

            //立即跳轉
            $('.goto').click(function() {
                //跳轉倒數初始化
                clearTimeout(timeout);
                t = 3;
                toWeb(q.redirect_uri);
            });
        },
        //密碼重置
        resetpwdInit: function(page) {
            $('.resetpwd-left').click(function() {
                window.history.back();
            });

            $('.send').click(function() {

                $('.verifycode-block').removeClass('lengthError');
                $('.verifycode-block').removeClass('spacesError');
                $('.password-block').removeClass('spacesError');
                $('.passwordconfirm-block').removeClass('spacesError');
                $('.verifycode-block').removeClass('nullError');
                $('.password-block').removeClass('nullError');
                $('.passwordconfirm-block').removeClass('nullError');
                var verifycode = $('.verifycode').val();
                var password = $('.password').val();
                var passwordconfirm = $('.passwordconfirm').val();
                var isError = false;

                //長度判斷
                if (verifycode.length != 6) {
                    $('.verifycode-block').addClass('lengthError');
                    isError = true;
                }

                //空白字元判斷
                if (verifycode.indexOf(" ") !== -1) {
                    $('.verifycode-block').addClass('spacesError');
                    isError = true;
                }
                if (password.indexOf(" ") !== -1) {
                    $('.password-block').addClass('spacesError');
                    isError = true;
                }
                if (passwordconfirm.indexOf(" ") !== -1) {
                    $('.passwordconfirm-block').addClass('spacesError');
                    isError = true;
                }

                //填寫判斷
                if (verifycode === "") {
                    $('.verifycode-block').addClass('nullError');
                    isError = true;
                }
                if (password === "") {
                    $('.password-block').addClass('nullError');
                    isError = true;
                }
                if (passwordconfirm === "") {
                    $('.passwordconfirm-block').addClass('nullError');
                    isError = true;
                }

                if (password != '' && passwordconfirm != '') {
                    //密碼確認判斷
                    if (password === passwordconfirm) {
                        //強度判斷
                        //利用match函數去比較密碼是否符合指定條件：最少一個數字，最少一個小階英文，長度限制為8。
                        var chkPwdStength = password.match(/((?=.*\d)(?=.*[a-z]).{8})/);

                        //若match回傳的值為null，跳出警告並阻止表單送出。
                        if (chkPwdStength == null) {
                            myApp.alert('密碼強度不足，需包含英文字母、數字、大於八位數。', stringObj.text.warn);
                            isError = true;
                        } else {
                            if (!isError) {
                                $('.resetpwd-form').submit(); //送出密碼重置表單
                            }
                        }
                    } else {
                        myApp.alert(stringObj.text.pass_check_error, stringObj.text.warn);
                    }
                }

            });
        },
        //登入成功頁面初始化
        loginsuccessInit: function(page) {
            var q = page.query;

            $('.loginsuccess-left').click(function() {
                window.history.back();
            });

            //執行倒數
            showTime(q.redirect_uri + '?sat=' + q.sat);

            //修改密碼
            // $('.edit_pwd').click(function() {
            // mainView.router.load({
            //     url: 'changepwd?redirect_uri=' + encodeURIComponent(q.redirect_uri) + '&sat=' + q.sat + '&mur=' + q.mur
            // });
            // window.location.replace('changepwd?redirect_uri=' + encodeURIComponent(q.redirect_uri) + '&sat=' + q.sat + '&mur=' + q.mur)
            // });

            //立即跳轉
            $('.goto').click(function() {
                window.location = q.redirect_uri + '?sat=' + q.sat;
            });

        },
        //密碼修改頁面初始化
        changepwdInit: function(page) {
            var q = page.query;

            $('.changepwd-left').click(function() {
                window.history.back();
            });

            $('.send').click(function() {

                $('.passwordold-block').removeClass('spacesError');
                $('.passwordnew-block').removeClass('spacesError');
                $('.passwordnewconfirm-block').removeClass('spacesError');
                $('.passwordold-block').removeClass('nullError');
                $('.passwordnew-block').removeClass('nullError');
                $('.passwordnewconfirm-block').removeClass('nullError');
                var passwordold = $('.passwordold').val();
                var passwordnew = $('.passwordnew').val();
                var passwordnewconfirm = $('.passwordnewconfirm').val();
                var isError = false;

                //空白字元判斷
                if (passwordold.indexOf(" ") !== -1) {
                    $('.passwordold-block').addClass('spacesError');
                    isError = true;
                }
                if (passwordnew.indexOf(" ") !== -1) {
                    $('.passwordnew-block').addClass('spacesError');
                    isError = true;
                }
                if (passwordnewconfirm.indexOf(" ") !== -1) {
                    $('.passwordnewconfirm-block').addClass('spacesError');
                    isError = true;
                }

                //填寫判斷
                if (passwordold === "") {
                    $('.passwordold-block').addClass('nullError');
                    isError = true;
                }
                if (passwordnew === "") {
                    $('.passwordnew-block').addClass('nullError');
                    isError = true;
                }
                if (passwordnewconfirm === "") {
                    $('.passwordnewconfirm-block').addClass('nullError');
                    isError = true;
                }

                if (passwordnew != '' && passwordnewconfirm != '') {
                    //密碼確認判斷
                    if (passwordnew === passwordnewconfirm) {
                        //強度判斷
                        //利用match函數去比較密碼是否符合指定條件：最少一個數字，最少一個小階英文，長度限制為8。
                        var chkPwdStength = passwordnew.match(/((?=.*\d)(?=.*[a-z]).{8})/);

                        //若match回傳的值為null，跳出警告並阻止表單送出。
                        if (chkPwdStength == null) {
                            myApp.alert('密碼強度不足，需包含英文字母、數字、大於八位數。', stringObj.text.warn);
                            isError = true;
                        } else {
                            if (!isError) {
                                $('.changepwd-form').submit(); //送出密碼重置表單
                            }
                        }
                    } else {
                        myApp.alert(stringObj.text.pass_check_error, stringObj.text.warn);
                    }
                }

            });


        },
        //密碼修改成功頁面初始化
        changepwdsuccessInit: function(page) {
            var q = page.query;

            $('.changepwdsuccess-left').click(function() {
                window.history.back();
            });

            //執行倒數
            showTime(q.redirect_uri);

            //立即跳轉
            $('.goto').click(function() {
                window.location = q.redirect_uri;
            });
        },
        /**
         * 取得第三方登入URL
         * @param  action 動作(login/regiest)
         * @param  account 社群類別(facebook/google/wechat)
         */
        getThirdPartyUrl: function(action, account) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                contentType: "application/json; charset=utf-8",
                url: '/' + action + '/geturl/' + account + '/' + Cookies.get('parameter'),
                type: 'POST',
                data: '',
                success: function(r) {
                    //console.log(r);
                    window.location = r;
                },
                error: function(r) {
                    //console.log(JSON.stringify(r));
                    noNetwork();
                }
            });
        },
        //取得會員第三方資訊
        loginThirdParty: function(action, account, access_token) {
            if (Cookies.get('action') === 'login') {
                myApp.showPreloader(stringObj.text.logining);
            } else if (Cookies.get('action') === 'regiest') {
                myApp.showPreloader(stringObj.text.processing);
            }
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                contentType: "application/json; charset=utf-8",
                url: '/' + action + '/callback/' + account + '/' + Cookies.get('parameter'),
                type: 'POST',
                data: '{"access_token":"' + access_token + '"}',
                dataType: 'json',
                success: function(r) {
                    myApp.hidePreloader();
                    //console.log(JSON.stringify(r));
                    if (Cookies.get('action') === 'login') {
                        if (r.code === '1000') {
                            mainView.router.load({
                                url: 'thirdpartyloginsuccess?redirect_uri=' + r.redirect_uri + '&sat=' + r.sat + '&mur=' + r.mur
                            });
                        } else {
                            stringObj.return_header(r.code);
                            if (_tip) {
                                myApp.alert(_tip + '( ' + r.code + ' )', stringObj.text.warn);
                                _tip = null;
                                $('.modal-button').click(function() {
                                    mainView.router.load({
                                        url: 'login/' + Cookies.get('parameter')
                                    });
                                });
                            }
                        }
                    } else if (Cookies.get('action') === 'regiest') {
                        if (r.code === '2000') {
                            r.ssodata.access_token = access_token;
                            r.ssodata.sso_photourl = 'http://graph.facebook.com/' + r.ssodata.id + '/picture?type=large';
                            Cookies.set('fb_data', JSON.stringify(r.ssodata));
                            mainView.router.load({
                                url: 'regiestthirdparty/' + Cookies.get('parameter')
                            });
                        } else {
                            stringObj.return_header(r.code);
                            if (_tip) {
                                myApp.alert(_tip + '( ' + r.code + ' )', stringObj.text.warn);
                                _tip = null;
                                $('.modal-button').click(function() {
                                    mainView.router.load({
                                        url: 'login/' + Cookies.get('parameter')
                                    });
                                });
                            }
                        }

                    }
                },
                error: function(r) {
                    myApp.hidePreloader();
                    //console.log(JSON.stringify(r));
                    noNetwork();
                }
            });
        }
    };


    // Initialize app
    var myApp = new Framework7({
        swipeBackPage: false,
        pushState: true,
        pushStateNoAnimation: true,
        swipePanel: 'left',
        swipePanelActiveArea: -1,
        imagesLazyLoadPlaceholder: 'app/image/imgDefault.png',
        imagesLazyLoadThreshold: 150,
        animatePages: false,
        materialRipple: false,
        modalButtonOk: stringObj.text.confirm,
        modalButtonCancel: stringObj.text.cancel
    });


    // If we need to use custom DOM library, let's save it to $$ variable:
    var $$ = Dom7;

    var mainView = myApp.addView('.view-main', {
        dynamicNavbar: true
    });



    var exSwiper = new Swiper('.explanation-block', {
        pagination: '.swiper-pagination',
        paginationClickable: true,
        centeredSlides: true,
        autoplay: 3500,
        autoplayDisableOnInteraction: false,
        loop: true,
        effect: 'fade'
    });


    var toast = myApp.toast('message', '<i class="fa fa-exclamation-triangle"></i>', {});



    //無網路時的彈出訊息
    noNetwork = function() {
        toast.show(stringObj.text.noNetwork);
        $('.toast-container').css('color', '#F26531');
        $('.toast-container').css('top', '50%');
        $('.toast-container').css('left', '45%');
        $('.toast-container').css('width', '40%');
        $('.toast-container').css('background-color', 'rgba(30,30,30,.85)');
    };


    //尚未註冊視窗
    unregistered = function() {
        myApp.modal({
            title: stringObj.text.warn,
            text: '此帳號尚未註冊',
            buttons: [{
                text: stringObj.text.cancel,
                onClick: function() {

                }
            }, {
                text: stringObj.text.now_registered,
                onClick: function() {
                    loginObj.queryiscarpolicy('personaldata_policy', 'fb_registered');
                }
            }]
        });
    };


    //輸入長度限制
    input_limit = function(element, max) {
        var max_chars = max;
        if (element.value.length > max_chars) {
            element.value = element.value.substr(0, max_chars);
        }
    };


    //顯示自動跳轉倒數秒數
    showTime = function(uri) {
        t -= 1;
        $('.show-time span').html(t);

        //每秒執行一次,showTime()
        timeout = setTimeout("showTime(\'" + uri + "\')", 1000);

        if (t == 0) {
            t = 3;
            clearTimeout(timeout);
            window.location = uri;
        }

    };


    //計時器倒數結束
    liftOff = function() {
        $('.re-send').css('display', 'block'); //顯示重新發送鈕
        $('.not-send').css('display', 'none'); //隱藏倒數鈕
    };

    //計時器每秒呼叫function
    watchCountdown = function(periods) {
        if (periods[5] < 10) {
            periods[5] = '0' + periods[5];
        }
        if (periods[6] < 10) {
            periods[6] = '0' + periods[6];
        }
        $('.not-send').text(periods[5] + ':' + periods[6] + ' 後可重新發送');
    };

    goTo = function(page) {
        mainView.router.load({
            url: page + '/' + Cookies.get('parameter')
        });
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
            /*var win = window.open(url, '_blank');
            win.focus();*/
            window.location = url;
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

    //視窗開啟背景模糊設置
    $$(document).on('open', '.modal', function(e) {
        $('.view').css('-webkit-filter', 'blur(5px)');
    });
    $$(document).on('close', '.modal', function(e) {
        $('.view').css('-webkit-filter', 'blur(0px)');
    });

    //document start
    $(document).ready(function() {
        var strUrl = window.location.href;
        var getPara, ParaVal;
        var aryPara = [];

        if (strUrl.indexOf("access_token") != -1) { //access_token

            var getSearch = strUrl.split("?");
            getPara = getSearch[1].split("&");
            for (i = 0; i < getPara.length; i++) {
                ParaVal = getPara[i].split("=");
                aryPara.push(ParaVal[1]);
            }

            switch (Cookies.get('account')) {
                case 'facebook':
                    loginObj.loginThirdParty(Cookies.get('action'), Cookies.get('account'), aryPara[0]);
                    break;
            }

            //loginObj.getFBData(decodeURIComponent(aryPara[0]));

        } else {
            mainView.router.load({
                url: 'login/' + Cookies.get('parameter')
            });
        }
    });

    loginThirdParty = function(t) {
        loginObj.loginThirdParty(Cookies.get('action'), Cookies.get('account'), t);
    };


    //page start
    $$(document).on('pageInit', function(e) {
        myApp.hidePreloader();
        if ($('.error').val() != undefined) {
            myApp.alert($('.error').val(), stringObj.text.warn);
        }
        var page = e.detail.page;
        nowPage = page.name; //當前頁面名稱
        switch (page.name) {
            case 'login':
                loginObj.loginInit(page);
                break;
            case 'login_b':
                loginObj.loginInit(page);
                break;
            case 'regiest':
                loginObj.regiestInit(page);
                break;
            case 'regiestiscar':
                loginObj.regiestiscarInit(page);
                break;
            case 'regiestthirdparty':
                loginObj.regiestthirdpartyInit(page);
                break;
            case 'regiestverify':
                loginObj.regiestverifyInit(page);
                break;
            case 'forgetpwd':
                loginObj.forgetpwdInit(page);
                break;
            case 'regiestsuccess':
                loginObj.regiestsuccessInit(page);
                break;
            case 'forgetpwdsuccess':
                loginObj.forgetpwdsuccessInit(page);
                break;
            case 'resetpwdsuccess':
                loginObj.resetpwdsuccessInit(page);
                break;
            case 'resetpwd':
                loginObj.resetpwdInit(page);
                break;
            case 'loginsuccess':
                loginObj.loginsuccessInit(page);
                break;
            case 'thirdpartyloginsuccess': //
                loginObj.loginsuccessInit(page);
                break;
            case 'changepwdsuccess':
                loginObj.changepwdsuccessInit(page);
                break;
                // case 'changepwd':
                //     loginObj.changepwdInit(page);
                //     break;
            case 'logout':
                if ($('.logout_error').val() != undefined) {
                    myApp.alert($('.logout_error').val(), stringObj.text.warn);
                }
                $('.modal-button').click(function() {
                    window.history.back();
                });
                break;

        }
    });
})(window);