var _tip;
var stringObj = {

    API_IP: server_type + _region + '-member.iscarmg.com', //'ga.iscarmg.com',
    WEB_URL: server_type + _region + '-member.iscarmg.com',
    NEWS_URL: server_type + _region + '-app.iscarmg.com',
    SHOP_URL: server_type + _region + '-pm.iscarmg.com',
    CP_URL: server_type + _region + '-cp.iscarmg.com',
    BOX_URL: server_type + _region + '-box.iscarmg.com',
    return_header: function(msg) {
        switch (msg) {
            case '000000002':
                _tip = '該條碼機帳號已被停用，請聯絡註冊碼提供者重新申請';
                break;
            case '000000004':
                _tip = '該條碼機帳號未放行，請聯絡註冊碼提供者進行放行';
                break;
            case '000000005':
                _tip = '註冊程序有誤，請聯絡iscar進行協助';
                break;

            case '999999982':
                _tip = '查無伺服端功能模組連線憑證記錄';
                break;
            case '999999983':
                _tip = '伺服端功能模組連線憑證逾期失效';
                break;
            case '999999984':
                _tip = '伺服端功能模組連線憑證簽章有誤';
                break;
            case '999999985':
                _tip = '伺服端功能模組連線憑證無法解譯';
                break;

            case '999999990':
                _tip = '條碼機所屬分店不符合記錄，請聯絡管理者進行確認';
                break;
            case '999999991':
                _tip = '條碼機編號不符合記錄，請重新登入APP取得正確編號';
                break;
            case '999999992':
                if (window.location.href.match('webend_admin')) {

                    myApp.modal({
                        title: stringObj.text.warn,
                        text: '無效的伺服端連線憑證，請重新登入取用',
                        buttons: [{
                            text: stringObj.text.confirm,
                            onClick: function() {
                                indexObj._dataStorage(indexObj._storage.main, null);
                                window.location = 'http://app.iscarmg.com/Shop/www/webend_admin/login/login.html';
                            }
                        }]
                    });

                } else {

                    myApp.modal({
                        title: stringObj.text.warn,
                        text: '無效的伺服端連線憑證，請重新登入取用',
                        buttons: [{
                            text: stringObj.text.cancel,
                            onClick: function() {
                                indexObj._userLogout();
                            }
                        }, {
                            text: stringObj.text.reLogin,
                            onClick: function() {
                                indexObj._userLogout();
                                webview.loginPage();

                            }
                        }]
                    });

                }

                break;
            case '999999993':
                myApp.modal({
                    title: stringObj.text.warn,
                    text: '伺服端連線憑證逾期，請重新登入取用',
                    buttons: [{
                        text: stringObj.text.cancel,
                        onClick: function() {
                            indexObj._userLogout();
                        }
                    }, {
                        text: stringObj.text.reLogin,
                        onClick: function() {
                            indexObj._userLogout();
                            webview.loginPage();
                        }
                    }]
                });
                break;
            case '999999994':
                _tip = '服務呼叫者身份無法驗證，請重新安裝APP';
                break;
            case '999999995':
                _tip = '輸入之json內容無法解析，請重新呼叫';
                break;
            case '999999996':
                myApp.modal({
                    title: stringObj.text.warn,
                    text: '查無使用者記錄，請重新登錄',
                    buttons: [{
                        text: stringObj.text.cancel,
                        onClick: function() {
                            indexObj._userLogout();
                        }
                    }, {
                        text: stringObj.text.reLogin,
                        onClick: function() {
                            indexObj._userLogout();
                            webview.loginPage();
                        }
                    }]
                });
                break;
            case '999999997':
                _tip = '記錄已存在，無需再次更新';
                break;
            case '999999998':
                _tip = '尚未更新完成，請接續更新';
                break;
            case '999999999':
                _tip = '未知錯誤失敗，請稍候再試';
                break;
            case '010101001':
                myApp.modal({
                    title: stringObj.text.warn,
                    text: '呼叫者登入訊息驗證失敗，請重新登入',
                    buttons: [{
                        text: stringObj.text.cancel,
                        onClick: function() {
                            indexObj._userLogout();
                        }
                    }, {
                        text: stringObj.text.reLogin,
                        onClick: function() {
                            indexObj._userLogout();
                            webview.loginPage();
                        }
                    }]
                });
                break;
            case '010101002':
                _tip = '行動裝置無法辨識，請重新安裝APP';
                break;
            case '010101003':
                _tip = '行動裝置記錄無效，請重新安裝APP';
                break;
            case '010101004':
                _tip = 'APP類型辨識錯誤，請更新APP版本';
                break;

            case '010102000':
                _tip = '綁定完成，後續請改用FB登入，感謝您的使用';
                break;
            case '010102001':
                _tip = '查無使用者帳號記錄，請確認是否已完成註冊';
                break;
            case '010102002':
                _tip = '使用者帳號記錄大於一筆，請聯絡isCar進行處理';
                break;
            case '010102003':
                _tip = '該帳號非單機用戶，無法進行綁定';
                break;
            case '010102004':
                _tip = '本帳號已綁定完成，無需重新綁定，請改用FB登入';
                break;
            case '010102005':
                _tip = '該FB帳號使用，請選用其他帳號進行綁定';
                break;

            case '010103001':
                _tip = '公鑰格式有誤，請重新呼叫';
                break;
            case '010104000':
                _tip = '金鑰驗證成功更新作業完成';
                break;
            case '010107002':
                _tip = '此更新操作已重覆執行，請先進行同步作業';
                break;
            case '010107003':
                _tip = '書籤更新物件不存在，請確認後再發送';
                break;
            case '010107004':
                _tip = '書籤更新操作無效，請確認後再發送';
                break;
            case '010301001':
                _tip = '無更多商品，請參考瀏覽其他類別商品';
                break;
            case '010301002':
                _tip = '該選單編號無商品項目，請重新選擇';
                break;
            case '010301003':
                _tip = '該選單編號無效，請重新確認';
                break;
            case '010302001':
                _tip = '無法查看，請先換購瀏覽權限';
                break;
            case '010302002':
                _tip = '查無商品項目，請確認後重發';
                break;
            case '010302003':
                _tip = '該活動已截止，請選用其他活動券';
                break;
            case '010302004':
                myApp.modal({
                    title: stringObj.text.warn,
                    text: '該活動券已領畢，請選用其他活動券',
                    buttons: [{
                        text: stringObj.text.confirm,
                        bold: true,
                        onClick: function() {
                            mainView.router.load({
                                url: 'coupon-main.html',
                                reload: true
                            });
                        }
                    }]
                });
                break;
            case '010302005':
                _tip = '該活動尚未開始，請選用其他活動券';
                break;
            case '010303001':
                _tip = '查無系統記錄，請重新安裝APP';
                break;
            case '010303002':
                _tip = '該活動券已索取完畢，無法再索取';
                break;
            case '010303003':
                _tip = '該活動已截止，請選用其他活動券';
                break;
            case '010303004':
                _tip = '代幣不足，無法索取';
                break;
            case '010303005':
                _tip = '該活動必須指定使用開市，請重新索取';
                break;
            case '010303006':
                _tip = '該活動一人限索取一次，無法重覆索取';
                break;
            case '010303007':
                _tip = '預約之時段已額滿，請重新選擇';
                break;
            case '010303008':
                _tip = '未設定預約時段，請重新選擇';
                break;
            case '010303009':
                _tip = '無法索取，請先換購索取權限';
                break;
            case '010303010':
                _tip = '活動使用門市指定錯誤，請重新索取';
                break;
            case '010303011':
                _tip = '該活動券未用畢前不可再次索取';
                break;
            case '010304000':
                _tip = '棄用完成，歡迎選用其他活動券';
                break;
            case '010304001':
                _tip = '查無記錄，請重新確認';
                break;
            case '010304002':
                _tip = '該券已用畢，請重啟APP以更新活動券持有記錄';
                break;
            case '010304003':
                _tip = '該券已棄用，請重啟APP以更新活動券持有記錄';
                break;
            case '010305001':
                _tip = '查無記錄，請確認是否已使用';
                break;
            case '010305002':
                _tip = '你已對該活動券評分，請勿重新評分';
                break;
            case '010306001':
                _tip = '查無上傳記錄 請重新傳送';
                break;
            case '010306002':
                _tip = '上傳記錄大於100組，重新傳送';
                break;
            case '010307000':
                _tip = '預約取消完成';
                break;
            case '010307001':
                _tip = '查無索取記錄，請重新發送';
                break;
            case '010307002':
                _tip = '查無預約記錄，請重新發送';
                break;
            case '010307003':
                _tip = '該索取記錄，已取消預約';
                break;
            case '010307004':
                _tip = '該預約記錄已逾期，無法取消';
                break;
            case '010308001':
                _tip = '查無該筆商品資料，請重新發送';
                break;
            case '010308002':
                _tip = '傳入值有誤，請重新發送';
                break;
            case '010309001':
                _tip = '無法索取，請先換購索取權限';
                break;
            case '010309002':
                _tip = '該活動需設定使用門市';
                break;
            case '010309003':
                _tip = '該活動需設定預約時間與門市';
                break;
            case '010402001':
                _tip = '查詢資料錯誤，請重新發送';
                break;
            case '010501000':
                _tip = '交易記錄驗證成功，代幣已存入';
                break;
            case '010501001':
                _tip = '交易記錄無法驗證，請向平台商確認交易完成度';
                break;
            case '010501002':
                _tip = '電子發票時間異常，請重新發送';
                break;
            case '010501003':
                _tip = '交易商品不符合電子發票內容，請確認後再發送';
                break;
            case '010502000':
                _tip = '權限購買成功';
                break;
            case '010502001':
                _tip = '代幣餘額不足，請先購入代幣';
                break;
            case '010502002':
                _tip = '紅利餘額不足，歡迎使用者更積極參與iscar互動活動，贏得更多紅利';
                break;
            case '010602001':
                _tip = '查無該項活動，請重新呼叫';
                break;
            case '010602002':
                _tip = '該項活動已取消，請參閱其他活動';
                break;
            case '010602003':
                _tip = '該項活動已逾期，請參閱其他活動';
                break;
            case '010603000':
                _tip = '活動預約完成，請按時前往參加';
                break;
            case '010603001':
                _tip = '查無該項活動記錄，請重新選擇預約項目';
                break;
            case '010603002':
                _tip = '該項活動無法預約，請重新選擇預約項目';
                break;
            case '010603003':
                _tip = '該項活動已預約額滿，請選擇其他項目';
                break;
            case '010603004':
                _tip = '使用者已預約該項活動，請勿重複預約';
                break;
            case '010605000':
                _tip = '該問卷可用，歡迎填寫問卷';
                break;
            case '010605001':
                _tip = '查無該項問卷記錄，請重新選擇問卷';
                break;
            case '010605002':
                _tip = '該問卷已停用，請重新選擇問卷';
                break;
            case '010605003':
                _tip = '該問卷已發放完畢，請重新選擇問卷';
                break;
            case '010605004':
                _tip = '該問卷有效期限已過，請重新選擇問卷';
                break;
            case '010605005':
                _tip = '您已填過該問卷，請選擇其他問卷';
                break;
            case '010606000':
                _tip = '感謝您的填寫，使至活動會場掃描Qrcode對換獎品';
                break;
            case '010606001':
                _tip = '該問卷回收額滿，請選用其他問卷';
                break;
            case '010606002':
                _tip = '該問卷已停用，請重新選擇問卷';
                break;
            case '010606003':
                _tip = '該問卷有效期限已過，請重新選擇問卷';
                break;
            case '010606004':
                _tip = '您已填過該問卷，請選擇其他問卷';
                break;
            case '010701001':
                _tip = '記錄寫入失敗，請稍後重新發送';
                break;
            case '010702001':
                _tip = '查無分享項目編號，請重新發送';
                break;
            case '010702002':
                _tip = '該項目已過有效期限，無法分享，請重新選擇其他項目';
                break;
            case '010702003':
                _tip = '該項目已停用，無法分享，請重新選擇其他項目';
                break;
            case '010703001':
                _tip = '查無該評論項目，請重新操作';
                break;
            case '010801001':
                _tip = '無此選單編號，請重新輸入';
                break;
            case '010801002':
                _tip = '無此新聞編號，請重新輸入';
                break;
            case '010801003':
                _tip = '選單編號輸入格式錯誤，請重新輸入';
                break;
            case '010801004':
                _tip = '起始新聞編號輸入格式錯誤，請重新輸入';
                break;
            case '010801005':
                _tip = '索取數輸入格式錯誤，請重新輸入';
                break;
            case '010801006':
                _tip = '傳入值格式內容格式有誤，請重新輸入';
                break;
            case '010802001':
                _tip = '無此新聞ID，請重新輸入';
                break;
            case '010802002':
                _tip = '新聞ID輸入格式錯誤，請重新輸入';
                break;
            case '010802003':
                _tip = '呼叫內容有誤，無法辨識';
                break;
            case '010803001':
                _tip = '無此新聞ID，請重新輸入';
                break;
            case '010803002':
                _tip = '新聞ID輸入格式錯誤，請重新輸入';
                break;
            case '010803003':
                _tip = '呼叫內容有誤，無法辨識';
                break;
            case '010803004':
                _tip = '該新聞項目，尚無評論記錄';
                break;

            case '020203002':
                _tip = '行動裝置無法辨識，請重新安裝APP';
                break;
            case '020203003':
                _tip = '行動裝置記錄無效，請重新安裝APP';
                break;
            case '020203004':
                _tip = '該分店已停用，請與註冊碼提供者確認相關訊息';
                break;
            case '020203005':
                _tip = '該廠商已停用，請與註冊碼提供者確認相關訊息';
                break;
            case '020203006':
                _tip = '使用者註冊記錄大於一筆，無法辨識身份，請聯絡系統管理者進行確認';
                break;
            case '020203007':
                _tip = '無此帳號，請重新登入';
                break;
            case '020203008':
                _tip = '密碼錯誤超過五次，登入鎖定，請聯絡通路管理者重置';
                break;
            case '020203009':
                _tip = '帳號已停用，請聯絡通路管理者確認';
                break;
            case '020203010':
                _tip = '密碼錯誤，請重新登入';
                break;
            case '020203011':
                _tip = '帳號未放行，請聯絡管理者確認';
                break;

            case '020301002':
                _tip = '查無該券編號，請提醒消費者重新索取';
                break;
            case '020301003':
                _tip = '該券活動已逾期，不可再使用';
                break;
            case '020301004':
                _tip = '該券於索取時已指定使用門市，請提醒使用者至指定門市使用';
                break;
            case '020301005':
                _tip = '該券已使用完畢，請提醒消費者進行狀態更新';
                break;
            case '020301006':
                _tip = '該券已放棄使用，請提醒消費者進行狀態更新';
                break;
            case '020301007':
                _tip = '該券預約使用時間不符，請確認是否使用';
                break;
            case '020301008':
                _tip = '該活動券預約使用分店不符，請確認是否使用';
                break;
            case '020301009':
                _tip = '該券為預約客戶，可正常使用';
                break;
            case '020301010':
                _tip = '該券取用記錄有誤，請告知客戶重新取用活動券';
                break;

            case '020303002':
                _tip = '錯誤的作業內容，請重新選擇';
                break;
            case '020303004':
                _tip = '該券服務內容已執行中，請注意勿重複作業';
                break;

            case '020403001':
                _tip = '尚無預約記錄';
                break;


            case '010303012':
                _tip = '紅利點數不足，無法取用';
                break;
            case '010304000':
                _tip = '棄用完成，歡迎選用其他活動券';
                break;
            case '010304001':
                _tip = '查無記錄，請重新確認';
                break;
            case '010304002':
                _tip = '該券已用畢，請重啟APP以更新活動券持有記錄';
                break;
            case '010304003':
                _tip = '該券已棄用，請重啟APP以更新活動券持有記錄';
                break;

            case '010901001':
                _tip = '無此商家類別編號，請重新輸入';
                break;
            case '010901002':
                _tip = '無此商家編號，請重新輸入';
                break;
            case '010901003':
                _tip = '選單編號輸入格式錯誤，請重新輸入';
                break;
            case '010901004':
                _tip = '起始商家編號輸入格式錯誤，請重新輸入';
                break;
            case '010901005':
                _tip = '索取數輸入格式錯誤，請重新輸入';
                break;
            case '010901006':
                _tip = '傳入值格式內容格式有誤，請重新輸入';
                break;
            case '010901007':
                _tip = '輸入之商家名稱，查詢無結果';
                break;
            case '010901008':
                _tip = '輸入之地區，查詢無結果';
                break;

            case '010902001':
                _tip = '該商家記錄未有有效管理者，請確認管理效期是否仍有效';
                break;
            case '010902002':
                _tip = '會員非本商家管理者，請確認後再試';
                break;
            case '010902003':
                _tip = '會員之管理權限已失效，請確認後再試';
                break;
            case '010902004':
                _tip = '商家地址無法轉換有效經緯度坐標，請確認後重新輸入';
                break;


            case '020303001':
                _tip = '記錄完成，客戶服務程序開始';
                break;
            case '020303002':
                _tip = '錯誤的作業內容，請重新選擇';
                break;
            case '020303003':
                _tip = '該券服務內容已執行完畢，請勿重複作業';
                break;
            case '020303004':
                _tip = '該券服務內容已執行中，請注意勿重複作業';
                break;
            case '020303005':
                _tip = '該券設有攝影記錄需求，核準前請先行攝影記錄';
                break;
            case '020304001':
                _tip = '查無該券編號，請確認後重新發送';
                break;
            case '020304002':
                _tip = '該券非使用完畢狀態，無法新增相片';
                break;

            case '030101002':
                _tip = '使用者註冊記錄大於一筆，無法辨識身份，請聯絡系統管理者進行確認';
                break;
            case '030101003':
                _tip = '無此帳號，請重新登入';
                break;
            case '030101004':
                _tip = '密碼錯誤超過五次，登入鎖定，請聯絡通路管理者重置密碼';
                break;
            case '030101005':
                _tip = '帳號已停用，請聯絡通路管理者確認';
                break;
                /*case '030101006':
                    _tip = '密碼錯誤，請重新登入';
                    break;*/
            case '030101007':
                _tip = '帳號未放行，請聯絡管理者確認';
                break;
            case '030101008':
                _tip = '行動裝置無法辨識，請重新安裝APP';
                break;
            case '030101009':
                _tip = '行動裝置記錄無效，請重新安裝APP';
                break;

            case '030102001':
                _tip = '商家代號有誤，請確認後重發';
                break;
            case '030102002':
                _tip = '商家代號對應記錄大於一筆，請聯絡系統管理員進行處理';
                break;
            case '030102003':
                _tip = '會員代號有誤，請確認後重發';
                break;
            case '030102004':
                _tip = '會員代號對應記錄大於一筆，請聯絡系統管理員進行處理';
                break;
            case '030102005':
                _tip = '儲值點消費項目有誤，請確認後重發';
                break;
            case '030102006':
                _tip = '儲值點消費項目對應記錄大於一筆，請聯絡系統管理員進行處理';
                break;

            case '010901002':
                _tip = '無此商家編號，請重新輸入';
                break;
            case '010901005':
                _tip = '索取數輸入格式錯誤，請重新輸入';
                break;
            case '010901006':
                _tip = '傳入值格式內容格式有誤，請重新輸入';
                break;
            case '010901008':
                _tip = '輸入之地區，查詢無結果';
                break;

            case '010904001':
                _tip = '查無商品項目，請確認後重發';
                break;
            case '010904002':
                _tip = '該活動尚未開始，請選用其他活動券';
                break;
            case '010904003':
                _tip = '該活動已截止，請選用其他活動券';
                break;
            case '010904004':
                _tip = '該活動已停刊，請選用其他活動券或稍後再試';
                break;

            case '010905001':
                _tip = '為避免無效取用，請會員先完成FB登入綁定';
                break;
            case '010905002':
                _tip = '優惠券索取完成，請先預約後使用';
                break;
            case '010905003':
                _tip = '該券預約額滿，已排入候補，請隨時關注可預約時間';
                break;
            case '010905004':
                _tip = '該券設有索取數限制，無法再索取';
                break;

            case '010906001':
                _tip = '查無索取記錄，請重新索取優惠券';
                break;
            case '010906002':
                _tip = '所選預約時段已被預約，請重新選取';
                break;
            case '010906003':
                _tip = '原有預約時段即將到期，無法變更預約時間';
                break;

            case '010909001':
                _tip = '查無記錄，請重新確認';
                break;
            case '010909002':
                _tip = '所選預約時段已被預約，請重新選取';
                break;
            case '010909003':
                _tip = '該券已棄用或逾期，請重啟APP以更新活動券持有記錄';
                break;


            case '010907003':
                _tip = '會員未用畢前，無法更新活動券內容，可先停刊本券';
                break;
            case '010907004':
                _tip = '操作項目無法辨視，請重新發送';
                break;
            case '010907005':
                _tip = '欄位未填,無法作業';
                break;
            case '010907006':
                _tip = '該活動券非預約類型，無法更新預約時間';
                break;
            case '010907007':
                _tip = '超出活動最大有效日期，無法更新';
                break;

            case '010910001':
                _tip = '查無該券編號，請提醒消費者重新索取';
                break;
            case '010910002':
                _tip = '該券活動已逾期，不可再使用';
                break;
            case '010910004':
                _tip = '該券已使用完畢，請提醒消費者進行狀態更新';
                break;
            case '010910005':
                _tip = '該券已放棄使用，請提醒消費者進行狀態更新';
                break;
            case '010910007':
                _tip = '該券為預約客戶，可正常使用';
                break;
            case '010910008':
                _tip = '該券取用記錄有誤，請告知客戶重新取用活動券';
                break;
            case '010909009':
                _tip = '該券非貴司發行，請告知客戶前往正確商家使用';
                break;

            case '010911000':
                _tip = '服務完成，請提示客戶該券已用畢';
                break;
            case '010911000':
                _tip = '錯誤的作業內容，請重新選擇';
                break;

            case '011001001':
                _tip = '輸入之活動類別有誤，請確認後重發';
                break;
            case '011001002':
                _tip = '查無取用記錄，請確認後重發';
                break;
            case '011001003':
                _tip = '查無使用完畢記錄，請確認後重發';
                break;
            case '011002001':
                _tip = '您已完成該問卷，感謝你的填寫';
                break;


            case '010912001':
                _tip = '查無欲回覆項目，請更新留言記錄';
                break;
            case '010912002':
                _tip = '記錄有誤，無法留言回覆，請聯絡isCar人員進行處理';
                break;
            case '010912003':
                _tip = '該項目已回覆，無法再添加回覆，請改用修改功能';
                break;
            case '010912004':
                _tip = '該項目未有回覆，無法更新，請改用新增功能';
                break;

            case '010913001':
                _tip = '服務編號有誤，請確認後重發';
                break;

            case '010914001':
                _tip = '該商家已暫停排隊服務，請隨時關注該商家近況';
                break;
            case '010914002':
                _tip = '該服務項目已停止提供，請隨時關注該商家近況';
                break;
            case '010914003':
                _tip = '非服務時間，請於商家服務時間，點選排隊';
                break;
            case '010914004':
                _tip = '當前隊列人數已滿，請稍候再試';
                break;

            case '010911001':
                _tip = '錯誤的作業內容，請重新選擇';
                break;

            case '010915001':
                _tip = '已有記錄，請使用修改功能進行更新';
                break;
            case '010915002':
                _tip = '無啟用記錄，請使用新增功能先行新增';
                break;

            case '010916001':
                _tip = '憑證號碼無效，請重新輸入';
                break;
            case '010916002':
                _tip = '非貴司所發行之服務，請通知用戶前往正確商家使用';
                break;
            case '010916003':
                _tip = '用戶已棄用，由商家自行決定是否提供服務';
                break;
            case '010916004':
                _tip = '用戶未到號，請用戶稍候';
                break;
            case '010916005':
                _tip = '用戶已棄用，由商家自行決定是否提供服務';
                break;
            case '010916006':
                _tip = '用戶已服務完成，無法再次使用';
                break;
            case '010916007':
                _tip = '用戶已過號，由於商家自行決定是否提供服務';
                break;


            case '010917002':
                _tip = '正式服務時間尚未開始，停止自動叫號';
                break;
            case '010917003':
                _tip = '當前用戶為過號用戶，暫停呼叫次一號用戶';
                break;
            case '010917004':
                _tip = '暫無次一隊列用戶，請等候新用戶選用服務';
                break;

            case '010918001':
                _tip = '無效的排隊編號，請確認後重發';
                break;
            case '010918002':
                _tip = '非貴司所發行之服務，無法操作過號設置';
                break;
            case '010918003':
                _tip = '該用戶已服務完畢，無法設置過號';
                break;
            case '010918004':
                _tip = '非排隊狀態，無法設置過號';
                break;
            case '010918005':
                _tip = '叫號未達兩次以上，無法設置過號';
                break;
            case '010918006':
                _tip = '叫號後未達10分鐘以上，無法設置過號';
                break;

            case '010919001':
                _tip = '目前無排隊中用戶，請等候新用戶加入';
                break;
            case '010919002':
                _tip = '非貴司所發行之服務，無法操作叫號';
                break;
            case '010919003':
                _tip = '該用戶已服務完畢，無法設置叫號';
                break;
            case '010919004':
                _tip = '非排隊狀態，無法設置叫號';
                break;
            case '010919005':
                _tip = '該用戶已棄用，系統將自動呼叫次一服務號';
                break;
            case '010919006':
                _tip = '距前次叫號未達五分鐘，請稍候再叫號';
                break;

            case '010920001':
                _tip = '服務狀態已變更，無需重新操作，請更新狀態';
                break;
            case '010920002':
                _tip = '排隊服務已設置暫停，若需繼續叫號請先啟動服務';
                break;
            case '010920003':
                _tip = '今日排隊服務已終止，次日請先啟動服務';
                break;
            case '010920004':
                _tip = '今日排隊服務已終止，次日啟動服務後將自動叫號';
                break;

            case '010921001':
                _tip = '非過號隊列用戶無法進行呼叫';
                break;

            case '010922001':
                _tip = '查無記錄，請重新確認';
                break;
            case '010922002':
                _tip = '該記錄已用畢，請重啟APP以更新服務排隊狀態';
                break;
            case '010922003':
                _tip = '該記錄已棄用或失約，請重啟APP以更新活動券持有記錄';
                break;

            case '010923001':
                _tip = '已通知商家即將前往，請注意安全';
                break;
            case '010923002':
                _tip = '已通知商家無法前往，並設置為過號';
                break;
            case '010923003':
                _tip = '無效的操作，請確認後重發';
                break;

            case '011101001':
                _tip = '查詢條件有誤，請重新輸入';
                break;
            case '011101002':
                _tip = '無效的操作動作，請重新輸入';
                break;
            case '011101003':
                _tip = '新增時，主索引不可賦值';
                break;
            case '011101004':
                _tip = '無法辨視索引值';
                break;


            case '999999989':
                _tip = '無效的操作動作，請重新輸入';
                break;
            case '011105001':
                _tip = '查無約看記錄，請確認後重發';
                break;
            case '011106001':
                _tip = '敲定之約看時間，不符合買家提出之項目';
                break;
            case '011106002':
                _tip = '記錄編號查無資料，請確認後重發';
                break;


            case '011103001':
                _tip = '刊登項目有誤，無法完成作業，請確認後重發';
                break;
            case '011103002':
                _tip = '餘額不足，請先完成儲值動作';
                break;

            case '011104001':
                _tip = '查無該車輛，請確認後重發';
                break;
            case '011104002':
                _tip = '車輛記錄大於一筆，請聯絡isCar管理員進行處理';
                break;
            case '011104003':
                _tip = '約看詢問，發送失敗，請稍後再試';
                break;


            case '011202001':
                _tip = '用戶未加入車團，請先加入車團';
                break;


            case '011203001':
                _tip = '用戶已加入車團，無法重複加入不同車團';
                break;
            case '011203002':
                _tip = '查無對應車團資訊，請確認後重送';
                break;
            case '011203003':
                _tip = '所選車團已解散，無法加入';
                break;
            case '011203004':
                _tip = '所選車團為非公開車團，無法申請加入';
                break;
            case '011203005':
                _tip = '所選車團已滿額，無法申請加入';
                break;
            case '011203006':
                _tip = '用戶已申請加入該社團.記錄失效前,無法再申請';
                break;
            case '011203007':
                _tip = '申請發送失敗，請稍後再試';
                break;


            case '011204001':
                _tip = '邀請人所屬車團不符，請確認後重發';
                break;
            case '011204002':
                _tip = '查無會員資料，請確認後重發';
                break;
            case '011204003':
                _tip = '未完成FB綁定程序，無法加入車團';
                break;
            case '011204004':
                _tip = '邀請對象已加入其他社團，無法重複加入';
                break;
            case '011204005':
                _tip = '邀請失敗，請稍後再試';
                break;


            case '011205001':
                _tip = '用戶已有車團成員身份，無法成立車團';
                break;
            case '011205002':
                _tip = '用戶等級不足，無法成立車團';
                break;
            case '011205003':
                _tip = '用戶所選車團名稱重複，無法成立車團';
                break;

            case '011206001':
                _tip = '查無邀請記錄，請確認後重發';
                break;
            case '011206002':
                _tip = '被邀請人記錄不符，請確認後重發';
                break;
            case '011206003':
                _tip = '邀請記錄已逾期失效';
                break;
            case '011206004':
                _tip = '該邀請記錄已回覆，無法再使用';
                break;
            case '011206005':
                _tip = '邀請記錄拒絕作業已完成';
                break;

            case '999999988':
                _tip = '資料庫存取異常,請稍候再試';
                break;

            case '011207001':
                _tip = '查無車團參與記錄，請確認後重發';
                break;
            case '011207002':
                _tip = '車團成員等級不足，無法執行作業';
                break;

            case '011209001':
                _tip = '當前無可用之申請加入記錄，請稍候再試';
                break;

            case '011208001':
                _tip = '查無記錄，請確認後重發';
                break;
            case '011208002':
                _tip = '申請記錄之車團編號與管理者不同，無法操作';
                break;
            case '011208003':
                _tip = '該申請記錄已被審核，無法操作';
                break;
            case '011208004':
                _tip = '該申請記錄已失效，無法操作';
                break;
            case '011208005':
                _tip = '申請人已加入車團，無法操作';
                break;
            case '011208006':
                _tip = '車團人數已滿，無法加入新成員';
                break;

            case '011210001':
                _tip = '指派對象不存在，請確認後重發';
                break;
            case '011210002':
                _tip = '指派對象非車團成員，請確認後重發';
                break;
            case '011210003':
                _tip = '指派對象權級相同，無法重複指派';
                break;
            case '011210004':
                _tip = '無法經此方式指派團員為團長';
                break;
            case '011210005':
                _tip = '副團長人數已達車團等級限制，無法指派更多副團長';
                break;

            case '011202002':
                _tip = '加入記錄大於一筆，無法使用，請聯繫系統管理員進行處理';
                break;

            case '011211001':
                _tip = '查無對應表決記錄，請確認後重發';
                break;
            case '011211002':
                _tip = '查無應表決人對應記錄，請確認後重發';
                break;
            case '011211003':
                _tip = '非所屬車團表決案，無法執行作業';
                break;
            case '011211004':
                _tip = '您已表決完成，無法法執行作業';
                break;
            case '011211005':
                _tip = '應表決人數異常，管理員正在處理中，請稍候';
                break;
            case '011211006':
                _tip = '該表案已完成，無法執行作業';
                break;
            case '011211007':
                _tip = '該表決案可投票時間已過，無法執行作業，請等候系統結算';
                break;

            case '011212001':
                _tip = '無效的留言編號，請確認後重發';
                break;
            case '011212002':
                _tip = '該留言編號非所屬車團，無法進行操作';
                break;
            case '011212003':
                _tip = '留言狀態已符合設置選項，無法進行操作';
                break;
            case '011212004':
                _tip = '置頂設置已完成，請注意置頂目僅顯示最新三筆記錄';
                break;

            case '011213001':
                _tip = '未指派接任人選，無法退出車團';
                break;
            case '011213002':
                _tip = '車團尚有成員，請先指派繼任團長';
                break;
            case '011213003':
                _tip = '無法指派他團成員為繼任者，請確認後重發';
                break;
            case '011213004':
                _tip = '退團完成，無繼任人選車團同步解散';
                break;

            case '999999986':
                _tip = '記錄編號重複，系統修正中，請稍候再試';
                break;

            case '011214001':
                _tip = '未設置副團長，無法進行表決';
                break;

            case '011300001':
                _tip = '會員名下無此車輛記錄，是否已刪除';
                break;
            case '011300002':
                _tip = '該車輛記錄已被刪除';
                break;
            case '011300003':
                _tip = '查無所選車款對應記錄，請確認後重發';
                break;

            case '011301001':
                _tip = '車輛持有狀態有誤，請確認後重發';
                break;
            case '011301002':
                _tip = '用戶個資未授權使用，系統無法登載車輛資料';
                break;
            case '011301003':
                _tip = '上傳圖片數不符限制，請確認後重發';
                break;
            case '011301004':
                _tip = '封面圖片數不符限制，請確認後重發';
                break;

            case '011302001':
                _tip = '車輛狀態指定有誤，請重新輸入';
                break;

            case '040100001':
                _tip = '傳輸碼編號無效，請重新取用';
                break;
            case '000000008':
                _tip = '密碼無法辨識，請重新登入';
                break;

            case '040101001':
                _tip = '交易驗證碼不符，請重新輸入';
                break;

            case '999999972':
                _tip = '代幣餘額不足，請先購入代幣';
                break;
            case '999999973':
                _tip = '查無對應特約商服務費用項目，請重新輸入';
                break;
            case '999999975':
                _tip = '交易驗證碼不符，請重新輸入';
                break;
            case '999999976':
                _tip = '傳輸碼編號無效，請重新取用';
                break;
            case '999999977':
                _tip = '索引值比對不符，請重新輸入';
                break;

            case '1001':
                _tip = '登入失敗';
                break;
            case '1002':
                _tip = '尚未成為會員';
                break;
            case '2001':
                _tip = '註冊失敗';
                break;
            case '2002':
                _tip = '該帳號已註冊為會員';
                break;

        }
    },
    text: {
        appInit: '初始化...',
        appDataUpdate: '資料更新...',
        appUpdateTitle: '更新公告',
        appUpdateContext_And: '請至Google Play更新至最新版本',
        appUpdateContext_iOS: '請至App Store更新至最新版本',
        iosWebUpdateContext: '偵測到新版本，將進行更新動作',
        offlineTitle: '連線失敗',
        offlineContext: '未能與伺服器連線,請檢查連線狀態',
        version_check: '檢查版本更新...',
        warn: '提醒',
        login: '登入',
        reLogin: '重登',
        loginFB: 'Facebook 登入',
        logining: '登入中',
        notLogin: '尚未登入',
        notLoginFB: '尚未登入Facebook，<br><span style="color:red;">此登入非綁定FB</span>',
        mailDeleteAll: '<span>還有<span style="color:red;">未讀</span>信件，是否全部移除</span>',
        newsDeleteAll: '<span>是否移除所有<span style="color:red;">新聞書籤</span></span>',
        couponDeleteAll: '<span>是否移除所有<span style="color:red;">活動書籤</span></span>',
        branchDeleteAll: '<span>是否移除所有<span style="color:red;">店家書籤</span></span>',
        shopcouponDeleteAll: '<span>是否移除所有<span style="color:red;">商家活動書籤</span></span>',
        deleteAll: '是否全部移除',
        cancel: '取消',
        confirm: '確認',
        error: '錯誤',
        reLogin: '請重新登入',
        fbCancelTitle: '登入取消',
        fbCancelContext: '請登入Facebook才能做之後的操作',
        fbFailTitle: '登入失敗',
        fbFailContext: '請重新登入Facebook才能做之後的操作',
        bugReportCheck: '<span>確定要送出?</span>',
        processing: '處理中',
        result: '結果',
        bugReportResult: '發送完成',
        coin: '禮點',
        coinProcessing: '禮點處理中',
        nowCoin: '目前禮點數為',
        shareFinish: '分享完成',
        fbLikeSuccess: '對此文章按讚成功',
        fbLikeAlready: '已經對此文章按讚',
        pleaseWait: '請稍後再試',
        noNetwork: '未能與伺服器連線,請稍後再試',
        textMessage: '文字訊息',
        systemMessage: '系統訊息',
        goToWebCheck: '是否前往該頁面',
        required: '必填',
        emailFormatError: '信箱格式錯誤',
        telFormatError: '不屬於電話格式',
        textFormatError: '請輸入中英文或數字',
        password: '27270625',
        inputPassword: '請輸入密碼',
        wrongPassword: '密碼錯誤',
        catName: '類別',
        createDate: '日期',
        createdBy: '作者',
        like: '讚',
        share: '分享',
        relatedNews: '相關新聞',
        comment_btn: '發佈評論',
        comment: '評論',
        fbComment: '是否同步發佈到臉書動態牆',
        comment_submit_btn: '發佈評論',
        commentCheck: '發佈評論為社群討論功能，需經FB帳號授權使用，請先綁定FB帳號',
        comment_finish: '評論完成',
        noNews: '暫無新聞資訊',
        choseShare: '請選擇分享工具',
        lineShare: 'LINE 分享',
        bookmarks: '書籤',
        releasing: '發佈中',
        marksRemoveCheck: '是否移除收藏',
        appShareContext: 'isCar！最新好車新聞，跟我一起挑戰下一個彎道吧！  http://tw.iscarmg.com/appshare.html',
        user_name: '姓名',
        input_name: '姓名',
        input_name_placeholder: '名稱',
        input_Email_placeholder: '電子郵件',
        input_question: '問題描述',
        input_question_placeholder: '請填入問題內容',
        send: '發送訊息',
        reservationTime: '預約時間',
        choseBranch: '請先選擇指定店家',
        noBranch: '未選擇店家',
        welcomeTitle: '歡迎使用 isCar',
        welcomeContext: '註冊完成後可於會員選單中進行FB綁定',
        fbLoginContext: '此功能僅供註冊新用戶或已綁定FB之用戶登入使用，<br><span style="color:red;">未綁定FB用戶無法經此綁定</span>',
        shopLoginContext: '此功能僅供註冊完成之<span style="color:red;">特約商用戶</span>登入使用，普通用戶或未完成註冊特約商無法經此註冊',
        fbBindingTitle: '請注意!',
        fbBindingContext: '每個FB帳號僅可作<span style="color:red;">一次綁定</span>，完成後請改用Facebook登入isCar',
        binding: '綁定',
        binding_success: '綁定完成',
        registered: '註冊',
        member_registered: '會員註冊',
        register_login: '註冊/登入',
        registeredBtn1: '快速註冊',
        registeredBtn2: '手機登入',
        fbLoginBtn: 'Facebook 登入',
        aboutUs: '關於我們',
        privacy: '隱私權',
        selectArea: '選擇區域',
        noSelectArea: '縣市地區未填寫完整',
        edit: '修改',
        editFinish: '修改完成',
        special_offer: '折扣特價',
        prize: '獎品贈送',
        cash: '現金抵用',
        remainder_times: '剩餘次數',
        qr_title: '優惠條碼',
        qr_subtitle: '使用方式',
        qr_context: '憑本券至指定商家，出示此QR碼給商家掃描確認後，即可享有優惠!',
        branch: '通路商',
        tel: '聯絡電話',
        address: '地址',
        success_get: '索取成功',
        monthNames: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
        abandon_reason: ["沒有原因", "地址不便", "時間太短", "暫無需求"],
        service_abandon_reason: ["無需求", "距離太遠", "不符需求", "等候過久", "超過服務時間", "沒有原因"],
        coupon_explanation: '活動說明',
        available_times: '可用次數',
        coupon_end: '截止日',
        coupon_content: '活動內容',
        coupon_limit: '活動限制',
        coupon_img_path: 'http://' + _region + '-media.iscarmg.com/images/coupon/active_banner/',
        branch_img_path: 'http://' + _region + '-media.iscarmg.com/shopdata/',
        usedcar_img_path: 'http://' + _region + '-media.iscarmg.com/usedcar/',
        mycar_img_path: 'http://' + _region + '-media.iscarmg.com/membercars/',
        branch_img_temporary_path: 'http://' + _region + '-media.iscarmg.com/shopdata/temporary/',
        car_club_img_path: 'http://' + _region + '-media.iscarmg.com/car_club/',
        car_club_img_temporary_path: 'http://' + _region + '-media.iscarmg.com/car_club/temporary/',
        couponGetCheck: '為確保活動券發行有效性，取用前需完成身份驗證，請先執行FB綁定功能',
        upload_success: '上傳完成',
        upload_fail: '上傳失敗，請重新上傳',
        delete_success: '刪除完成',
        delete_fail: '刪除失敗',
        check_delete: '確定要刪除?',
        settle_accounts: '結帳',
        set_alarm_time: '設定提醒時間',
        hour_ago: '一小時前',
        date_ago: '一天前',
        no_use: '未使用',
        completed: '已完成',
        cancelled: '已取消',
        overdue: '逾期未至',
        no_input_name_or_pass: '未輸入帳號密碼',
        no_input_name: '未輸入帳號',
        no_input_pass: '未輸入密碼',
        pay: '結帳',
        serving: '服務',
        not_input_complete: '未輸入完整',
        pass_check: '密碼確認',
        old_pass_error: '舊密碼錯誤',
        new_pass_check_error: '新密碼確認錯誤',
        pass_check_error: '密碼與密碼確認不符',
        finished: '完成',
        week_array: ["星期一", "星期二", "星期三", "星期四", "星期五", "星期六", "星期日"],
        code39_title: '銷帳條碼',
        no_code39: '此活動未設置銷帳條碼',
        name_or_pass_error: '帳號或密碼錯誤',
        reLoginTimes: '可再嚐試登入次數：',
        bindingType: '請選擇綁定類別',
        noBinding: '尚未綁定社群帳號',
        front_cover: '封面',
        pic: '圖',
        edit_success: '更改完成',
        serving_success: '服務完成',
        add: '新增',
        add_success: '新增完成',
        not_reservation_time: '該券預約使用時間不符，請確認是否使用',
        use_finish: '使用完畢',
        renounce_use: '放棄使用',
        no_come: '失約未至',
        sqna_message: '留言',
        data_not_complete: '資料未填寫完整',
        input_limit70: '請輸入評論(限70字以內)',
        reply_success: '回覆完成',
        search: '查詢',
        no_comment: '暫無評論',
        close: '關閉',
        start_use: '啟用',
        stop_use: '停用',
        shopcoupon_edit_check: '<div class="alert-text">請先將活動停刊，再進行修改</div>',
        shopservice_edit_check: '<div class="alert-text">請先將服務停用，再進行修改</div>',
        service_queue: '服務排隊',
        queuing: '排隊中',
        servicing: '已服務',
        abandoned: '棄用',
        missed: '失約',
        missed_serviced: '失約仍到訪服務',
        abandoned_serviced: '棄用後仍到訪服務',
        passed: '過號',
        passed_serviced: '過號已服務',
        queue_success: '排隊完成',
        call_success: '叫號完成，請等候用戶前往',
        passed_success: '過號完成，已自動呼叫下一號',
        service_qr_title: '服務條碼',
        service_qr_context: '憑本券至指定商家，出示此ＱＲ碼給商家掃描確認後，即可接受服務',
        _010916003: '用戶已棄用，由商家自行決定是否提供服務',
        _010916005: '用戶已設置失約，由商家自行決定是否提供服務',
        _010916007: '用戶已過號，由於商家自行決定是否提供服務',
        _010919007: '<div class="alert-text">叫號完成，目前叫號已達兩次，可於十分鐘後設置過號</div>',
        will_go: '即將抵達',
        not_go: '無法前往',
        reply_branch: '已通知商家',
        spacesError: '*勿含空白字元',
        lengthError50: '*勿超過50字',
        lengthError15: '*勿超過15字',
        lengthError10: '*勿超過10字',
        branch_name: '店名',
        tel: '電話',
        type: '類別',
        branch_region: '郵遞區號',
        pay_list: '方案項目',
        now_coin: '現有點數',
        sd_type: '商家類別',
        sd_type_array: ['汽車美容', '汽車維修', '汽車輪胎', '汽車百貨'],
        application_data_record: '前次紀錄',
        _delete: '刪除',
        not_finish: '未完成',
        _continue: '繼續',
        application_success: '申請成功,請重新登入',
        publish_success: '刊登完成',
        shop_type: '商家類別',
        shop_type_array: ['一般商家', '二手車商'],
        dcil_list: [{
            "dcil_id": "4974f2deccc5486caeba3f3971c7e2ed",
            "dcil_category": 2,
            "dcil_itemname": "贈送試用期90天",
            "dcil_depositamount": 0,
            "dcil_availabledays": 90,
            "dcil_iconpath": null,
            "dcil_itemdescript": null
        }, {
            "dcil_id": "6600a61948924d82a959b03d2236f47b",
            "dcil_category": 1,
            "dcil_itemname": "商家首頁刊登90天",
            "dcil_depositamount": 0,
            "dcil_availabledays": 90,
            "dcil_iconpath": null,
            "dcil_itemdescript": "付費於isCar合作社內刊登商家資訊,並使用對應功能"
        }, {
            "dcil_id": "78e112898a334b02bd8ecf601c864b1b",
            "dcil_category": 1,
            "dcil_itemname": "車輛販售廣告刊登90天",
            "dcil_depositamount": 0,
            "dcil_availabledays": 90,
            "dcil_iconpath": null,
            "dcil_itemdescript": "付費於isCar車賣場內刊登您的車輛訊息90天"
        }, {
            "dcil_id": "8195e11ca2d14b06985c7873bfcb77a8",
            "dcil_category": 2,
            "dcil_itemname": "贈送試用期30天",
            "dcil_depositamount": 0,
            "dcil_availabledays": 30,
            "dcil_iconpath": null,
            "dcil_itemdescript": null
        }, {
            "dcil_id": "9ae7609723c34efb9bad7f248e07f5f8",
            "dcil_category": 1,
            "dcil_itemname": "列表項目背色強化顯示",
            "dcil_depositamount": 0,
            "dcil_availabledays": 90,
            "dcil_iconpath": null,
            "dcil_itemdescript": "列表搜尋結果顯示背色為紅,強化顯示項目"
        }, {
            "dcil_id": "f1756980ce694a9a8d6a68a2f3a4d29b",
            "dcil_category": 2,
            "dcil_itemname": "無贈送試用期",
            "dcil_depositamount": 0,
            "dcil_availabledays": 0,
            "dcil_iconpath": null,
            "dcil_itemdescript": null
        }],
        no_dcil_id_or_type: '尚未選擇方案或類別',
        no_shop: '尚未選擇店家',
        no_input_name: '尚未輸入名稱',
        no_reason: '尚未填寫原因',
        _010917001: '服務完成後將超過今日服務時間，將停止自動叫號，請問是否結束今日排隊服務',
        _010917004: '暫無次一隊列用戶，請等候新用戶選用服務',
        max_5: '最多設定5組',
        fbBindingCheck: '此功能需經FB帳號授權使用，請先綁定FB帳號',
        already_send: '已送出',
        yes: '是',
        no: '否',
        context: '內容',
        no_introduce: '否,介紹相似車款',
        set_remind_two_hr: '是否設置前兩小時提醒',
        setting_success: '已設置提醒',
        apply_join: '申請加入',
        establish: '創立',
        establish_club: '創立車團',
        club_name: '車團名稱',
        isPublic: '是否對外開放',
        input_name: '請輸入名稱',
        invitation_to_join: '邀請加入',
        join_check: '入團申請審核',
        club_info_edit: '車團資料變更',
        club_vote_event: '車團表決事件申請',
        message_management: '留言板管理',
        exit_culd: '退出車團',
        announcement_context: '公告內容',
        description_context: '簡介內容',
        vote_item: '表決項目',
        reason: '原因',
        establish_success: '創立完成',
        apply_success: '申請完成',
        agreed: '已同意',
        refused: '已拒絕',
        chose_level: '請選擇職等',
        club_levels: ['副團長', '高級團員', '一般團員'],
        assign_success: '指派完成',
        quit_club_success: '退團完成',
        quit_club_check: '確定要退出車團？',
        assign_commander: '請指派接任團長',
        agree: '同意',
        oppose: '反對',
        refuse: '拒絕',
        vote_success: '投票完成，請等候系統結算',
        cmsr_operationtype_1: '被允許加入',
        cmsr_operationtype_2: '同意邀請後加入',
        cmsr_operationtype_3: '遭驅逐出團',
        cmsr_operationtype_4: '退出社團',
        cmsr_operationtype_5: '權級調整',
        invite_success: '邀請完成',
        agree_invite: '已同意邀請，請重新登入',
        refuse_invite: '已拒絕邀請',
        club_invite: '車團邀請',
        dismiss_club: '解散車團',
        _011209001: '當前無可用之申請加入記錄，請稍候再試',
        today: '今天',
        no_member: '無團員',
        scan_msg_error: '條碼格式有誤',
        no_club: '暫無車團',
        no_message: '暫無留言',
        no_log: '暫無紀錄',
        no_data: '暫無資訊',
        no_shopcoupon: '暫無優惠活動',
        no_service: '暫無服務',
        _011213004: '退團完成，無繼任人選車團同步解散',
        club_search: '車團查詢',
        input_club_name: '請輸入車團名稱',
        re_select: '重選',
        remark: '備註',
        logoutCheck: '確定要登出?',
        gender: '性別',
        gender_array: ['男', '女'],
        age: '年齡',
        male: '男',
        female: '女',
        region: '地區',
        input_gender: '請選擇性別',
        input_age: '請選擇年齡',
        input_region: '請選擇地區',
        search_result: '查詢結果',
        any_condition: '至少填寫任一條件',
        search_null: '查無資料',
        select_img: '選擇圖片',
        no_select: '尚未選擇對象',
        now_registered: '立即註冊',
        member_login: '會員登入',
        member_password: '密碼',
        forget_password: '忘記密碼',
        other_login: '其他方式登入',
        re_send: '重新發送',
        mobile_check: '手機驗證',
        verification_code: '驗證碼',
        mobile: '手機',
        nickname: '暱稱',
        input_nickname: '請輸入暱稱',
        select_region: '先選擇地區',
        mobile_set: '手機設定',
        original_email: '請輸入原註冊之電子信箱',
        email: '電子信箱',
        input_email: '請輸入電子信箱',
        input_cellphone: '請輸入手機號碼'

    },
    used_car: {
        cbi_carbrand: '廠牌',
        cbi_carbodytype: '車種',
        cbi_saleprice: '售價',
        cbi_brandmodel: '車系',
        cbi_modelstyle: '車款',
        cbi_carsource: '來源',
        cbi_carlocation: '所在地',
        cbi_carbodycolor: '外觀色',
        cbi_carinteriorcolor: '內裝色',
        cbi_displacement: '排氣量',
        cbi_fueltype: '引擎燃料',
        cbi_transmissionsystem: '變速系統',
        cbi_drivemode: '驅動方式',
        cbi_carseats: '乘客數',
        cbi_cardoors: '車門數',
        cbi_manufactoryyear: '出廠年份',
        cbi_manufactorymonth: '出廠月份',
        cbi_caryearstyle: '年式',
        cbi_licensestatus: '牌照狀態',
        cbi_licensingyear: '領牌年份',
        cbi_licensingmonth: '領牌月份',
        cbi_everrepair: '是否維修',
        cbi_carbrand_json: [{
            "cbl_id": 1,
            "cbl_fullname": "BENZ",
            "cbl_nickname": "賓士",
            "cbl_hotitemtag": 0,
            "cbl_shortname": "BENZ",
            "cbl_listorder": null,
            "cbl_iconpath": null
        }, {
            "cbl_id": 2,
            "cbl_fullname": "BMW",
            "cbl_nickname": "BMW",
            "cbl_hotitemtag": 0,
            "cbl_shortname": "BMW",
            "cbl_listorder": null,
            "cbl_iconpath": null
        }, {
            "cbl_id": 3,
            "cbl_fullname": "Ford",
            "cbl_nickname": "Ford",
            "cbl_hotitemtag": 0,
            "cbl_shortname": "Ford",
            "cbl_listorder": null,
            "cbl_iconpath": null
        }, {
            "cbl_id": 4,
            "cbl_fullname": "Honda",
            "cbl_nickname": "Honda",
            "cbl_hotitemtag": 0,
            "cbl_shortname": "Honda",
            "cbl_listorder": null,
            "cbl_iconpath": null
        }, {
            "cbl_id": 5,
            "cbl_fullname": "Toyota",
            "cbl_nickname": "Toyota",
            "cbl_hotitemtag": 0,
            "cbl_shortname": "Toyota",
            "cbl_listorder": null,
            "cbl_iconpath": null
        }],
        cbi_brandmodel_json: [{
            "cbm_id": 1,
            "cbl_id": 1,
            "cbm_fullname": "A-CLASS",
            "cbm_nickname": "A-CLASS",
            "cbm_hotitemtag": 0,
            "cbm_shortname": "A-CLASS",
            "cbm_listorder": null,
            "cbm_iconpath": null
        }, {
            "cbm_id": 2,
            "cbl_id": 1,
            "cbm_fullname": "B-Class",
            "cbm_nickname": "B-Class",
            "cbm_hotitemtag": 0,
            "cbm_shortname": "B-Class",
            "cbm_listorder": null,
            "cbm_iconpath": null
        }, {
            "cbm_id": 3,
            "cbl_id": 1,
            "cbm_fullname": "C-Class",
            "cbm_nickname": "C-Class",
            "cbm_hotitemtag": 0,
            "cbm_shortname": "C-Class",
            "cbm_listorder": null,
            "cbm_iconpath": null
        }, {
            "cbm_id": 6,
            "cbl_id": 2,
            "cbm_fullname": "1-Series",
            "cbm_nickname": "1-Series",
            "cbm_hotitemtag": 0,
            "cbm_shortname": "1-Series",
            "cbm_listorder": null,
            "cbm_iconpath": null
        }, {
            "cbm_id": 7,
            "cbl_id": 2,
            "cbm_fullname": "3-Series Sedan",
            "cbm_nickname": "3-Series Sedan",
            "cbm_hotitemtag": 0,
            "cbm_shortname": "3-Series Sedan",
            "cbm_listorder": null,
            "cbm_iconpath": null
        }, {
            "cbm_id": 8,
            "cbl_id": 2,
            "cbm_fullname": "5-Series",
            "cbm_nickname": "5-Series",
            "cbm_hotitemtag": 0,
            "cbm_shortname": "5-Series",
            "cbm_listorder": null,
            "cbm_iconpath": null
        }, {
            "cbm_id": 9,
            "cbl_id": 3,
            "cbm_fullname": "EcoSport",
            "cbm_nickname": "EcoSport",
            "cbm_hotitemtag": 0,
            "cbm_shortname": "EcoSport",
            "cbm_listorder": null,
            "cbm_iconpath": null
        }, {
            "cbm_id": 10,
            "cbl_id": 3,
            "cbm_fullname": "Kuga",
            "cbm_nickname": "Kuga",
            "cbm_hotitemtag": 0,
            "cbm_shortname": "Kuga",
            "cbm_listorder": null,
            "cbm_iconpath": null
        }, {
            "cbm_id": 11,
            "cbl_id": 3,
            "cbm_fullname": "Escape",
            "cbm_nickname": "Escape",
            "cbm_hotitemtag": 0,
            "cbm_shortname": "Escape",
            "cbm_listorder": null,
            "cbm_iconpath": null
        }, {
            "cbm_id": 12,
            "cbl_id": 4,
            "cbm_fullname": "Accord",
            "cbm_nickname": "Accord",
            "cbm_hotitemtag": 0,
            "cbm_shortname": "Accord",
            "cbm_listorder": null,
            "cbm_iconpath": null
        }, {
            "cbm_id": 13,
            "cbl_id": 4,
            "cbm_fullname": "CR-V",
            "cbm_nickname": "CR-V",
            "cbm_hotitemtag": 0,
            "cbm_shortname": "CR-V",
            "cbm_listorder": null,
            "cbm_iconpath": null
        }, {
            "cbm_id": 14,
            "cbl_id": 4,
            "cbm_fullname": "City",
            "cbm_nickname": "City",
            "cbm_hotitemtag": 0,
            "cbm_shortname": "City",
            "cbm_listorder": null,
            "cbm_iconpath": null
        }, {
            "cbm_id": 15,
            "cbl_id": 5,
            "cbm_fullname": "86",
            "cbm_nickname": "86",
            "cbm_hotitemtag": 0,
            "cbm_shortname": "86",
            "cbm_listorder": null,
            "cbm_iconpath": null
        }, {
            "cbm_id": 16,
            "cbl_id": 5,
            "cbm_fullname": "Alphard",
            "cbm_nickname": "Alphard",
            "cbm_hotitemtag": 0,
            "cbm_shortname": "Alphard",
            "cbm_listorder": null,
            "cbm_iconpath": null
        }, {
            "cbm_id": 17,
            "cbl_id": 5,
            "cbm_fullname": "COROLLA ALTIS X",
            "cbm_nickname": "COROLLA ALTIS X",
            "cbm_hotitemtag": 0,
            "cbm_shortname": "COROLLA ALTIS X",
            "cbm_listorder": null,
            "cbm_iconpath": null
        }],
        cbi_modelstyle_json: [{
            "cms_id": 1,
            "cbl_id": 1,
            "cbm_id": 1,
            "cms_fullname": "190 E",
            "cms_nickname": "190 E",
            "cms_hotitemtag": 0,
            "cms_shortname": "190 E",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 2,
            "cbl_id": 1,
            "cbm_id": 1,
            "cms_fullname": "A160",
            "cms_nickname": "A160",
            "cms_hotitemtag": 0,
            "cms_shortname": "A160",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 3,
            "cbl_id": 1,
            "cbm_id": 2,
            "cms_fullname": "B180",
            "cms_nickname": "B180",
            "cms_hotitemtag": 0,
            "cms_shortname": "B180",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 4,
            "cbl_id": 1,
            "cbm_id": 2,
            "cms_fullname": "B200",
            "cms_nickname": "B200",
            "cms_hotitemtag": 0,
            "cms_shortname": "B200",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 5,
            "cbl_id": 1,
            "cbm_id": 3,
            "cms_fullname": "C200K Classic",
            "cms_nickname": "C200K Classic",
            "cms_hotitemtag": 0,
            "cms_shortname": "C200K Classic",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 6,
            "cbl_id": 1,
            "cbm_id": 3,
            "cms_fullname": "C200K T",
            "cms_nickname": "C200K T",
            "cms_hotitemtag": 0,
            "cms_shortname": "C200K T",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 7,
            "cbl_id": 2,
            "cbm_id": 6,
            "cms_fullname": "118i",
            "cms_nickname": "118i",
            "cms_hotitemtag": 0,
            "cms_shortname": "118i",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 8,
            "cbl_id": 2,
            "cbm_id": 6,
            "cms_fullname": "120i",
            "cms_nickname": "120i",
            "cms_hotitemtag": 0,
            "cms_shortname": "120i",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 9,
            "cbl_id": 2,
            "cbm_id": 7,
            "cms_fullname": "220i M Sport",
            "cms_nickname": "220i M Sport",
            "cms_hotitemtag": 0,
            "cms_shortname": "220i M Sport",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 10,
            "cbl_id": 2,
            "cbm_id": 7,
            "cms_fullname": "220i Sport Line",
            "cms_nickname": "220i Sport Line",
            "cms_hotitemtag": 0,
            "cms_shortname": "220i Sport Line",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 11,
            "cbl_id": 2,
            "cbm_id": 8,
            "cms_fullname": "528i M Sport Package",
            "cms_nickname": "528i M Sport Package",
            "cms_hotitemtag": 0,
            "cms_shortname": "528i M Sport Package",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 12,
            "cbl_id": 2,
            "cbm_id": 8,
            "cms_fullname": "520i",
            "cms_nickname": "520i",
            "cms_hotitemtag": 0,
            "cms_shortname": "520i",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 13,
            "cbl_id": 3,
            "cbm_id": 9,
            "cms_fullname": "1.5L",
            "cms_nickname": "1.5L",
            "cms_hotitemtag": 0,
            "cms_shortname": "1.5L",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 14,
            "cbl_id": 3,
            "cbm_id": 10,
            "cms_fullname": "1.5L",
            "cms_nickname": "1.5L",
            "cms_hotitemtag": 0,
            "cms_shortname": "1.5L",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 15,
            "cbl_id": 2,
            "cbm_id": 10,
            "cms_fullname": "2.0L d",
            "cms_nickname": "2.0L d",
            "cms_hotitemtag": 0,
            "cms_shortname": "2.0L d",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 16,
            "cbl_id": 3,
            "cbm_id": 11,
            "cms_fullname": "2.3 2WD XLT",
            "cms_nickname": "2.3 2WD XLT",
            "cms_hotitemtag": 0,
            "cms_shortname": "2.3 2WD XLT",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 17,
            "cbl_id": 2,
            "cbm_id": 11,
            "cms_fullname": "2.3 4WD",
            "cms_nickname": "2.3 4WD",
            "cms_hotitemtag": 0,
            "cms_shortname": "2.3 4WD",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 18,
            "cbl_id": 4,
            "cbm_id": 12,
            "cms_fullname": "2.4 VTi-S Exclusive",
            "cms_nickname": "2.4 VTi-S Exclusive",
            "cms_hotitemtag": 0,
            "cms_shortname": "2.4 VTi-S Exclusive",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 19,
            "cbl_id": 2,
            "cbm_id": 12,
            "cms_fullname": "2.4 VTi Luxury",
            "cms_nickname": "2.4 VTi Luxury",
            "cms_hotitemtag": 0,
            "cms_shortname": "2.4 VTi Luxury",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 20,
            "cbl_id": 4,
            "cbm_id": 13,
            "cms_fullname": "2.0 VTi",
            "cms_nickname": "2.0 VTi",
            "cms_hotitemtag": 0,
            "cms_shortname": "2.0 VTi",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 21,
            "cbl_id": 2,
            "cbm_id": 13,
            "cms_fullname": "2.4 VTi-S",
            "cms_nickname": "2.4 VTi-S",
            "cms_hotitemtag": 0,
            "cms_shortname": "2.4 VTi-S",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 22,
            "cbl_id": 4,
            "cbm_id": 14,
            "cms_fullname": "1.5 VTi",
            "cms_nickname": "1.5 VTi",
            "cms_hotitemtag": 0,
            "cms_shortname": "1.5 VTi",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 23,
            "cbl_id": 5,
            "cbm_id": 15,
            "cms_fullname": "86 Aero",
            "cms_nickname": "86 Aero",
            "cms_hotitemtag": 0,
            "cms_shortname": "86 Aero",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 24,
            "cbl_id": 5,
            "cbm_id": 15,
            "cms_fullname": "86 Limited 6AT",
            "cms_nickname": "86 Limited 6AT",
            "cms_hotitemtag": 0,
            "cms_shortname": "86 Limited 6AT",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 25,
            "cbl_id": 5,
            "cbm_id": 16,
            "cms_fullname": "2.4",
            "cms_nickname": "2.4",
            "cms_hotitemtag": 0,
            "cms_shortname": "2.4",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 26,
            "cbl_id": 5,
            "cbm_id": 16,
            "cms_fullname": "3.5",
            "cms_nickname": "3.5",
            "cms_hotitemtag": 0,
            "cms_shortname": "3.5",
            "cms_listorder": null,
            "cms_iconpath": null
        }],
        cbi_carbodytype_json: [{
            "cbt_id": 1,
            "cbt_fullname": "四門轎車",
            "cbt_nickname": "四門轎車",
            "cbt_hotitemtag": 0,
            "cbt_shortname": "四門轎車",
            "cbt_listorder": null,
            "cbt_iconpath": null
        }, {
            "cbt_id": 2,
            "cbt_fullname": "掀背車",
            "cbt_nickname": "掀背車",
            "cbt_hotitemtag": 0,
            "cbt_shortname": "掀背車",
            "cbt_listorder": null,
            "cbt_iconpath": null
        }, {
            "cbt_id": 3,
            "cbt_fullname": "雙門跑車",
            "cbt_nickname": "雙門跑車",
            "cbt_hotitemtag": 0,
            "cbt_shortname": "雙門跑車",
            "cbt_listorder": null,
            "cbt_iconpath": null
        }, {
            "cbt_id": 4,
            "cbt_fullname": "敞篷車",
            "cbt_nickname": "敞篷車",
            "cbt_hotitemtag": 0,
            "cbt_shortname": "敞篷車",
            "cbt_listorder": null,
            "cbt_iconpath": null
        }, {
            "cbt_id": 5,
            "cbt_fullname": "休旅車",
            "cbt_nickname": "休旅車",
            "cbt_hotitemtag": 0,
            "cbt_shortname": "休旅車",
            "cbt_listorder": null,
            "cbt_iconpath": null
        }, {
            "cbt_id": 6,
            "cbt_fullname": "轎式休旅車",
            "cbt_nickname": "轎式休旅車",
            "cbt_hotitemtag": 0,
            "cbt_shortname": "轎式休旅車",
            "cbt_listorder": null,
            "cbt_iconpath": null
        }, {
            "cbt_id": 7,
            "cbt_fullname": "高頂休旅車",
            "cbt_nickname": "高頂休旅車",
            "cbt_hotitemtag": 0,
            "cbt_shortname": "高頂休旅車",
            "cbt_listorder": null,
            "cbt_iconpath": null
        }, {
            "cbt_id": 8,
            "cbt_fullname": "五門旅行車",
            "cbt_nickname": "五門旅行車",
            "cbt_hotitemtag": 0,
            "cbt_shortname": "五門旅行車",
            "cbt_listorder": null,
            "cbt_iconpath": null
        }, {
            "cbt_id": 9,
            "cbt_fullname": "貨卡車",
            "cbt_nickname": "貨卡車",
            "cbt_hotitemtag": 0,
            "cbt_shortname": "貨卡車",
            "cbt_listorder": null,
            "cbt_iconpath": null
        }],
        cbi_carlocation_json: [{
            "cln_id": 1,
            "cln_cityname": "台北市",
            "cln_districtname": null,
            "cln_listorder": null
        }, {
            "cln_id": 2,
            "cln_cityname": "基隆市",
            "cln_districtname": null,
            "cln_listorder": null
        }, {
            "cln_id": 3,
            "cln_cityname": "新北市",
            "cln_districtname": null,
            "cln_listorder": null
        }, {
            "cln_id": 4,
            "cln_cityname": "連江縣",
            "cln_districtname": null,
            "cln_listorder": null
        }, {
            "cln_id": 5,
            "cln_cityname": "宜蘭縣",
            "cln_districtname": null,
            "cln_listorder": null
        }, {
            "cln_id": 6,
            "cln_cityname": "新竹市",
            "cln_districtname": null,
            "cln_listorder": null
        }, {
            "cln_id": 7,
            "cln_cityname": "新竹縣",
            "cln_districtname": null,
            "cln_listorder": null
        }, {
            "cln_id": 8,
            "cln_cityname": "桃園縣",
            "cln_districtname": null,
            "cln_listorder": null
        }, {
            "cln_id": 9,
            "cln_cityname": "苗栗縣",
            "cln_districtname": null,
            "cln_listorder": null
        }, {
            "cln_id": 10,
            "cln_cityname": "彰化縣",
            "cln_districtname": null,
            "cln_listorder": null
        }, {
            "cln_id": 11,
            "cln_cityname": "南投縣",
            "cln_districtname": null,
            "cln_listorder": null
        }, {
            "cln_id": 12,
            "cln_cityname": "嘉義市",
            "cln_districtname": null,
            "cln_listorder": null
        }, {
            "cln_id": 13,
            "cln_cityname": "嘉義縣",
            "cln_districtname": null,
            "cln_listorder": null
        }, {
            "cln_id": 14,
            "cln_cityname": "雲林縣",
            "cln_districtname": null,
            "cln_listorder": null
        }, {
            "cln_id": 15,
            "cln_cityname": "台南市",
            "cln_districtname": null,
            "cln_listorder": null
        }, {
            "cln_id": 16,
            "cln_cityname": "高雄市",
            "cln_districtname": null,
            "cln_listorder": null
        }, {
            "cln_id": 17,
            "cln_cityname": "澎湖縣",
            "cln_districtname": null,
            "cln_listorder": null
        }, {
            "cln_id": 18,
            "cln_cityname": "金門縣",
            "cln_districtname": null,
            "cln_listorder": null
        }, {
            "cln_id": 19,
            "cln_cityname": "屏東縣",
            "cln_districtname": null,
            "cln_listorder": null
        }, {
            "cln_id": 20,
            "cln_cityname": "台東縣",
            "cln_districtname": null,
            "cln_listorder": null
        }, {
            "cln_id": 21,
            "cln_cityname": "花蓮縣",
            "cln_districtname": null,
            "cln_listorder": null
        }],
        cbi_carbodycolor_json: [{
            "cbc_id": 1,
            "cbc_colorname": "白"
        }, {
            "cbc_id": 2,
            "cbc_colorname": "黑"
        }, {
            "cbc_id": 3,
            "cbc_colorname": "紅"
        }, {
            "cbc_id": 4,
            "cbc_colorname": "藍"
        }],
        cbi_carinteriorcolor_json: [{
            "cic_id": 1,
            "cic_colorname": "白"
        }, {
            "cic_id": 2,
            "cic_colorname": "黑"
        }, {
            "cic_id": 3,
            "cic_colorname": "銀灰"
        }, {
            "cic_id": 4,
            "cic_colorname": "藍"
        }],
        cbi_carsource_json: [{
            "cse_id": 1,
            "cse_sourcename": "國產車",
            "cse_listorder": null
        }, {
            "cse_id": 2,
            "cse_sourcename": "歐洲車",
            "cse_listorder": null
        }, {
            "cse_id": 3,
            "cse_sourcename": "美國車",
            "cse_listorder": null
        }, {
            "cse_id": 4,
            "cse_sourcename": "日本車/亞裔美規車",
            "cse_listorder": null
        }, {
            "cse_id": 5,
            "cse_sourcename": "韓國車",
            "cse_listorder": null
        }, {
            "cse_id": 6,
            "cse_sourcename": "中國車",
            "cse_listorder": null
        }],
        cbi_transmissionsystem_array: ['手自排', '自手排', '自排', '手排'],
        cbi_fueltype_array: ['汽油車', '柴油車', 'HyBrid混合動力車', '瓦斯車', '電動車'],
        cbi_drivemode_array: ['前輪驅動', '後輪驅動', '四輪驅動'],
        cbi_carseats_array: [2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
        cbi_cardoors_array: [2, 3, 4, 5],
        cbi_carequiptments_json: [{
            "ces_id": 1,
            "ces_category": 0,
            "ces_ietmesname": "HID氙氣頭燈",
            "ces_listorder": null
        }, {
            "ces_id": 2,
            "ces_category": 0,
            "ces_ietmesname": "安全氣囊",
            "ces_listorder": null
        }, {
            "ces_id": 3,
            "ces_category": 1,
            "ces_ietmesname": "天窗",
            "ces_listorder": null
        }, {
            "ces_id": 4,
            "ces_category": 1,
            "ces_ietmesname": "全景天窗",
            "ces_listorder": null
        }, {
            "ces_id": 5,
            "ces_category": 2,
            "ces_ietmesname": "電折後視鏡",
            "ces_listorder": null
        }, {
            "ces_id": 6,
            "ces_category": 2,
            "ces_ietmesname": "備胎架",
            "ces_listorder": null
        }, {
            "ces_id": 7,
            "ces_category": 3,
            "ces_ietmesname": "渦輪增壓",
            "ces_listorder": null
        }, {
            "ces_id": 8,
            "ces_category": 3,
            "ces_ietmesname": "機械增壓",
            "ces_listorder": null
        }],
        cbi_guaranteeitems_json: [{
            "cgi_id": 1,
            "cgi_itemname": "里程保證",
            "cgi_listorder": null
        }, {
            "cgi_id": 2,
            "cgi_itemname": "實車",
            "cgi_listorder": null
        }, {
            "cgi_id": 3,
            "cgi_itemname": "鑑定書",
            "cgi_listorder": null
        }, {
            "cgi_id": 4,
            "cgi_itemname": "非泡水車",
            "cgi_listorder": null
        }, {
            "cgi_id": 5,
            "cgi_itemname": "非營業車",
            "cgi_listorder": null
        }, {
            "cgi_id": 6,
            "cgi_itemname": "非贓車",
            "cgi_listorder": null
        }, {
            "cgi_id": 7,
            "cgi_itemname": "非失竊尋回車",
            "cgi_listorder": null
        }],
        cbi_licensestatus_array: ['已領牌', '未領牌', '停用/註銷', '全新車'],
        cbi_everrepair_array: ['是', '否'],
        cbi_salestatus_array: ['出售中', '已收訂', '賀成交'],
        dcil_list_json0: [{
                "dcil_id": "6600a61948924d82a959b03d2236f47b",
                "dcil_category": 0,
                "dcil_itemname": "商家首頁刊登90天",
                "dcil_depositamount": 0,
                "dcil_availabledays": 90,
                "dcil_iconpath": '',
                "dcil_itemdescript": "付費於isCar合作社內刊登商家資訊,並使用對應功能"
            }, {
                "dcil_id": "6600a61948924d82a959b03d2236f47b",
                "dcil_category": 0,
                "dcil_itemname": "商家首頁刊登90天",
                "dcil_depositamount": 0,
                "dcil_availabledays": 90,
                "dcil_iconpath": '',
                "dcil_itemdescript": "付費於isCar合作社內刊登商家資訊,並使用對應功能"
            }, {
                "dcil_id": "6600a61948924d82a959b03d2236f47b",
                "dcil_category": 0,
                "dcil_itemname": "商家首頁刊登90天",
                "dcil_depositamount": 0,
                "dcil_availabledays": 90,
                "dcil_iconpath": '',
                "dcil_itemdescript": "付費於isCar合作社內刊登商家資訊,並使用對應功能"
            }, {
                "dcil_id": "6600a61948924d82a959b03d2236f47b",
                "dcil_category": 0,
                "dcil_itemname": "商家首頁刊登90天",
                "dcil_depositamount": 0,
                "dcil_availabledays": 90,
                "dcil_iconpath": '',
                "dcil_itemdescript": "付費於isCar合作社內刊登商家資訊,並使用對應功能"
            }

        ],
        dcil_list_json1: [{
                "dcil_id": "78e112898a334b02bd8ecf601c864b1b",
                "dcil_category": 1,
                "dcil_itemname": "車輛販售廣告刊登90天",
                "dcil_depositamount": 0,
                "dcil_availabledays": 90,
                "dcil_iconpath": '',
                "dcil_itemdescript": "付費於isCar車賣場內刊登您的車輛訊息90天"
            }, {
                "dcil_id": "78e112898a334b02bd8ecf601c864b1b",
                "dcil_category": 1,
                "dcil_itemname": "車輛販售廣告刊登90天",
                "dcil_depositamount": 0,
                "dcil_availabledays": 90,
                "dcil_iconpath": '',
                "dcil_itemdescript": "付費於isCar車賣場內刊登您的車輛訊息90天"
            }, {
                "dcil_id": "78e112898a334b02bd8ecf601c864b1b",
                "dcil_category": 1,
                "dcil_itemname": "車輛販售廣告刊登90天",
                "dcil_depositamount": 0,
                "dcil_availabledays": 90,
                "dcil_iconpath": '',
                "dcil_itemdescript": "付費於isCar車賣場內刊登您的車輛訊息90天"
            }

        ]
    },
    my_car: {
        input_brand: '請選擇廠牌',
        input_brand_f: '請先選擇廠牌',
        input_car_modal: '請選擇車系',
        input_car_modal_f: '請先選擇車系',
        input_car_style: '請選擇車款',
        select_car: '挑選車款',
        car_brand: '廠牌',
        car_modal: '車系',
        car_style: '車款',
        car_yearstyle: '年式',
        check_yearstyle: '確認年式',

        moc_purchasedate: '購買年月',
        moc_carbodycolor: '車色',
        moc_ownstatus: '持有狀態',
        moc_enginenumber: '引擎編號',
        moc_vin: '車身號碼',
        input_carbodycolor: '請輸入車色',
        input_enginenumber: '請輸入引擎編號',
        input_vin: '請輸入車身號碼',
        moc_cartypecode_json: [{
            id: '01',
            type: '重型機器腳踏車'
        }, {
            id: '02',
            type: '輕型機器腳踏車'
        }, {
            id: '03',
            type: '自用小客車'
        }, {
            id: '04',
            type: '自用小貨車'
        }, {
            id: '05',
            type: '自用大客車'
        }, {
            id: '06',
            type: '自用大貨車'
        }, {
            id: '07',
            type: '營業小客車'
        }, {
            id: '08',
            type: '營業小貨車'
        }, {
            id: '09',
            type: '營業大客車'
        }, {
            id: '10',
            type: '營業大貨車'
        }, {
            id: '11',
            type: '自用小型特種車'
        }, {
            id: '12',
            type: '自用大型特種車'
        }, {
            id: '13',
            type: '營業一般貨運曳引車'
        }, {
            id: '14',
            type: '租賃小客車'
        }, {
            id: '15',
            type: '個人計程車'
        }, {
            id: '16',
            type: '營業小型特種車'
        }, {
            id: '17',
            type: '營業大型特種車'
        }, {
            id: '18',
            type: '自用一般貨運曳引車'
        }, {
            id: '19',
            type: '公司行號自用小貨車'
        }, {
            id: '20',
            type: '公司行號自用大貨車'
        }, {
            id: '21',
            type: '長期租賃小客車'
        }, {
            id: '22',
            type: '客貨兩用車'
        }, {
            id: '23',
            type: '租賃大客車'
        }, {
            id: '24',
            type: '長期租賃大客車'
        }, {
            id: '25',
            type: '軍用行政車輛'
        }, {
            id: '26',
            type: '軍用戰鬥車輛'
        }, {
            id: '27',
            type: '動力機械'
        }, {
            id: '28',
            type: '臨時牌照車輛'
        }, {
            id: '29',
            type: '試車牌照車輛'
        }, {
            id: '30',
            type: '營業貨櫃貨運曳引車'
        }, {
            id: '31',
            type: '自用貨櫃貨運曳引車'
        }, {
            id: '32',
            type: '超重型機器腳踏車'
        }, {
            id: '33',
            type: '農耕機械車'
        }, {
            id: '34',
            type: '電動車'
        }],
        moc_ownstatus_array: ['已持有', '已售出', '已報銷']
    },
    menu: {
        mailbox: '收件夾',
        myBookmarks: '我的書籤',
        memberInfo: '會員資料',
        gallery: '圖庫',
        bugReport: '問題回報',
        logout: '登出',
        appShare: 'App分享',
        testBlcok: '業務專區',
        fbBinding: '綁定Facebook',
        bonusInquire: 'isCar錢包',
        isCarNews: 'isCar新聞',
        reservationRecord: '預約紀錄',
        scanRecord: '掃描記錄',
        coupon_main: '旗艦館',
        coupon_record: '使用紀錄',
        modify_password: '修改密碼',
        server_index: '首頁',
        member_card: '會員名片',
        branch_cooperative: 'isCar合作社',
        branch_info_edit: '廠商資訊修改',
        used_car_market: '車賣場'
    },
    blogMenu: {
        index: '頭條精選',

        weekly_fun_facts: 'isCar週遊趣',
        legal_class: '鴻毅有辦法',
        good_choise: '不難要怎樣',
        david_power: '大衛行勢力',
        car_camp: '開車去露營',
        famous_vip: '藝名菁人',
        car_travel: '開車去旅行',

        top_news: '新聞我最快',
        new_car: '新車快訊',
        home_abroad: '國外新聞',
        taiwan_carnews: '國內新聞',
        upgrade: '周邊升級',
        strengthen: '性能強化',
        race: '賽事動態',
        intel: '車廠情報',

        best_buy: '購車我最行',
        roadtest: '試車報告',
        top_sales: '銷售資訊',
        used_car: '中古情報',

        love_life: '汽車玩很大',
        love_car: '汽車生活',
        love_travel: '旅遊樂活',
        love_fun: '新奇逗趣',
        love_knowledge: '玩車尚智',
        menschannel: '男人不要看',

        hottest: '今日我最夯',
        hot_brand: '最夯品牌',
        hot_people: '最夯人物',
        hot_talk: '最夯話題',
        hot_event: '最夯活動',

        autoshow: '2016世界新車大展',
        autoshow_info: '車展情報站',
        autoshow_brand: '參展品牌',
        eye_catching: '超吸睛推薦',
        autoshow_gallery: 'isCar圖輯隊',

        moduleaccount: 'iscarnews',
        modulepassword: '2wsx0okm'

    },
    shop: {
        index: '商家首頁',
        shop_list: '特約商列表',
        client_list: '會員列表',
        sell_management: '行銷管理',
        code_scan: '條碼掃描',
        bonus_management: '紅利管理',
        reservation_management: '預約管理',
        coupon_management: '折扣券管理',
        activity_preferential: '活動優惠',
        queue_reservation: '排隊預約',
        open_store: '我要開店',
        evaluation_management: '評價管理',
        shop_record: '服務紀錄',
        coupon_record: '消費紀錄',
        bonus_record: '紅利紀錄',
        change_branch: '商家切換',
        staff_management: '店員管理',
        message_push: '優惠推送',
        shop_bookmarks: '商家書籤',
        bonus_item: '紅利項目',
        item_name: '項目名稱',
        bonus_point: '紅利數額',
        bonus_status: '生效狀態',
        status_on: '啟用中',
        status_off: '停用中',
        expired: '已過期',
        illegal: '違規下架',
        status_off_edit: '<div class="alert-text">請先將活動停刊，再進行修改</div>',
        bonus_gift: '紅利贈送',
        cost: '消費金額',
        input_cost: '請輸入金額',
        input_bonus_point: '請輸入數額',
        moduleaccount: 'iscarshop',
        modulepassword: '4rfv5tgb',
        add_shop: '新增特約商書籤將同步成為該特約商會員接收最新優惠消息',
        gift_success: '贈送成功',
        duties: '職務',
        manager: '店長',
        employee: '店員',
        add_employee: '新增店員',
        isLeaving: '確定要將此店員設置離職？',
        point: '點數',
        select_member: '選擇推送對象',
        seach_member: '篩選推送對象',
        set_message: '訊息編輯',
        push_check: '推送確認',
        pushed_info: '推送內容',
        pushed_num: '推送數',
        pushed_date: '日期',
        now_coin: '當前購點： <span>0</span> 點',
        today_pushed: '今日推送： <span>0</span> 次',
        push_record: '推送紀錄',
        member: '專屬會員',
        not_member: '非會員',
        push_type: '推送類型',
        member_push: '專屬會員推送',
        not_member_push: '非會員推送',
        push_coin: '一則 <span>1</span> 點',
        total_member: '會員總數 <span>0</span> 人',
        select_all: '全選',
        push_num: '推送人數 <span></span> 人',
        set_push_num: '設定本次推送<input type="number"/>人',
        consumption_coin: '消費購點',
        readed_num: '已讀人數',
        push_success: '推送完成',
        no_member: '尚無會員',
        _push_check: '確定要推送？',
        no_push_num: '尚未設定人數',
        shopMessage: '特約商訊息',
        toShop: '前往商家',
        temple_scan: '祈福掃描'
    },
    branchMenu: {
        today_service: '今日服務',
        iscar_news: 'isCar新聞',
        index: '我的首頁',
        index_management: '首頁管理',
        shopcoupon_management: '活動管理',
        reservation_record: '預約紀錄',
        scan_record: '掃描記錄',
        service_queue: '服務排隊',
        evaluation_management: '評價管理',
        shop_record: '紀錄查看',
        fans_management: '粉絲管理',
        change_branch: '商家切換',
        publish_management: '刊登管理',

        branch_cooperative: 'isCar合作社',
        used_car: '二手車商',
        car_cosmetology: '汽車美容',
        car_repair: '汽車維修',
        car_tire: '汽車輪胎',
        car_department: '汽車百貨',
        reservation_record: '約看紀錄',
        blessing_block: '祈福專區'
    },
    counties: [
        '台北市', '基隆市', '新北市', '宜蘭縣', '新竹縣市', '桃園縣', '苗栗縣', '台中市',
        '彰化縣', '南投縣', '嘉義縣市', '雲林縣', '台南市', '高雄市', '澎湖縣',
        '屏東縣', '台東縣', '花蓮縣', '金門縣', '連江縣', '南海諸島', '釣魚台列嶼'
    ],
    region: {

        台北市: [
            ['中正區', '大同區', '中山區', '松山區', '大安區', '萬華區', '信義區', '士林區',
                '北投區', '內湖區', '南港區', '文山區'
            ],
            ['100', '103', '104', '105', '106', '108', '110', '111', '112', '114', '115', '116']
        ],
        基隆市: [
            ['仁愛區', '信義區', '中正區', '中山區', '安樂區', '暖暖區', '七堵區'],
            ['200', '201', '202', '203', '204', '205', '206']
        ],
        新北市: [
            ['萬里區', '金山區', '板橋區', '汐止區', '深坑區', '石碇區', '瑞芳區', '平溪區',
                '雙溪區', '貢寮區', '新店區', '坪林區', '烏來區', '永和區', '中和區', '土城區',
                '三峽鎮', '樹林區', '鶯歌鎮', '三重區', '新莊區', '泰山區', '林口區', '蘆洲區',
                '五股區', '八里區', '淡水鎮', '三芝區', '石門區'
            ],
            ['207', '208', '220', '221', '222', '223', '224', '226', '227', '228',
                '231', '232', '233', '234', '235', '236', '237', '238', '239', '241',
                '242', '243', '244', '247', '248', '249', '251', '252', '253'
            ]
        ],
        宜蘭縣: [
            ['宜蘭市', '頭城鎮', '礁溪鄉', '壯圍鄉', '員山鄉', '羅東鎮', '三星鄉', '大同鄉',
                '五結鄉', '冬山鄉', '蘇澳鎮', '南澳鄉'
            ],
            ['260', '261', '262', '263', '264', '265', '266', '267', '268', '269',
                '270', '272'
            ]
        ],
        新竹縣市: [
            ['新竹市', '竹北市', '湖口鄉', '新豐鄉', '新埔鎮', '關西鎮', '芎林鄉', '寶山鄉',
                '竹東鎮', '五峰鄉', '橫山鄉', '尖石鄉', '北埔鄉', '峨眉鄉'
            ],
            ['300', '302', '303', '304', '305', '306', '307', '308', '310', '311',
                '312', '313', '314', '315'
            ]
        ],
        桃園縣: [
            ['中壢市', '平鎮市', '龍潭鄉', '楊梅鎮', '新屋鄉', '觀音鄉', '桃園市', '龜山鄉',
                '八德市', '大溪鎮', '復興鄉', '大園鄉', '蘆竹鄉'
            ],
            ['320', '324', '325', '326', '327', '328', '330', '333', '334', '335',
                '336', '337', '338'
            ]
        ],
        苗栗縣: [
            ['竹南鎮', '頭份鎮', '三灣鄉', '南庄鄉', '獅潭鄉', '後龍鎮', '通霄鎮', '苑裡鎮',
                '苗栗市', '造橋鄉', '頭屋鄉', '公館鄉', '大湖鄉', '泰安鄉',
                '銅鑼鄉', '三義鄉', '西湖鄉', '卓蘭鎮'
            ],
            ['350', '351', '352', '353', '354', '356', '357', '358', '360', '361',
                '362', '363', '364', '365', '366', '367', '368', '369'
            ]
        ],
        台中市: [
            ['中區', '東區', '南區', '西區', '北區', '北屯區', '西屯區', '南屯區', '太平區',
                '大里區', '霧峰區', '烏日區', '豐原區', '后里區', '石岡區', '東勢區', '和平區',
                '新社區', '潭子區', '大雅區', '神岡區', '大肚區', '沙鹿區', '龍井區', '梧棲區',
                '清水區', '大甲區', '外埔區', '大安區'
            ],
            ['400', '401', '402', '403', '404', '406', '407', '408', '411', '412',
                '413', '414', '420', '421', '422', '423', '424', '426', '427', '428',
                '429', '432', '433', '434', '435', '436', '437', '438', '439'
            ]
        ],
        彰化縣: [
            ['彰化市', '芬園鄉', '花壇鄉', '秀水鄉', '鹿港鎮', '福興鄉', '線西鄉', '和美鎮',
                '伸港鄉', '員林鎮', '社頭鄉', '永靖鄉', '埔心鄉', '溪湖鎮', '大村鄉', '埔鹽鄉',
                '田中鎮', '北斗鎮', '田尾鄉', '埤頭鄉', '溪州鄉', '竹塘鄉', '二林鎮', '大城鄉',
                '芳苑鄉', '二水鄉'
            ],
            ['500', '502', '503', '504', '505', '506', '507', '508', '509', '510',
                '511', '512', '513', '514', '515', '516', '520', '521', '522', '523',
                '524', '525', '526', '527', '528', '530'
            ]
        ],
        南投縣: [
            ['南投市', '中寮鄉', '草屯鎮', '國姓鄉', '埔里鎮', '仁愛鄉', '名間鄉', '集集鎮',
                '水里鄉', '魚池鄉', '信義鄉', '竹山鎮', '鹿谷鄉'
            ],
            ['540', '541', '542', '544', '545', '546', '551', '552', '553', '555',
                '556', '557', '558'
            ]
        ],
        嘉義縣市: [
            ['嘉義市', '番路鄉', '梅山鄉', '竹崎鄉', '阿里山', '中埔鄉', '大埔鄉', '水上鄉',
                '鹿草鄉', '太保市', '朴子市', '東石鄉', '六腳鄉', '新港鄉', '民雄鄉', '大林鎮',
                '溪口鄉', '義竹鄉', '布袋鎮'
            ],
            ['600', '602', '603', '604', '605', '606', '607', '608', '611', '612',
                '613', '614', '615', '616', '621', '622', '623', '624', '625'
            ]
        ],
        雲林縣: [
            ['斗南鎮', '大埤鄉', '虎尾鎮', '土庫鎮', '褒忠鄉', '東勢鄉', '台西鄉', '崙背鄉',
                '麥寮鄉', '斗六市', '林內鄉', '古坑鄉', '莿桐鄉', '西螺鎮', '二崙鄉', '北港鎮',
                '水林鄉', '口湖鄉', '四湖鄉', '元長鄉'
            ],
            ['630', '631', '632', '633', '634', '635', '636', '637', '638', '640', '643',
                '646', '647', '648', '649', '651', '652', '653', '654', '655'
            ]
        ],
        台南市: [
            ['中西區', '東區', '南區', '北區', '安平區', '安南區', '永康區', '歸仁區', '新化區',
                '左鎮區', '玉井區', '楠西區', '南化區', '仁德區', '關廟區', '龍崎區', '官田區',
                '麻豆區', '佳里區', '西港區', '七股區', '將軍區', '學甲區', '北門區', '新營區',
                '後壁區', '白河區', '東山區', '六甲區', '下營區', '柳營區', '鹽水區', '善化區',
                '大內區', '山上區', '新市區', '安定區'
            ],
            ['700', '701', '702', '704', '708', '709', '710', '711', '712', '713', '714',
                '715', '716', '717', '718', '719', '720', '721', '722', '723', '724', '725',
                '726', '727', '730', '731', '732', '733', '734', '735', '736', '737', '741',
                '742', '743', '744', '745'
            ]
        ],
        高雄市: [
            ['新興區', '前金區', '苓雅區', '鹽埕區', '鼓山區', '旗津區', '前鎮區', '三民區',
                '楠梓區', '小港區', '左營區', '仁武區', '大社區', '岡山區', '路竹區', '阿蓮區',
                '田寮區', '燕巢區', '橋頭區', '梓官區', '彌陀區', '永安區', '湖內區', '鳳山市',
                '大寮區', '林園區', '鳥松區', '大樹區', '旗山區', '美濃區', '六龜區', '內門區',
                '杉林區', '甲仙區', '桃源區', '那瑪夏區', '茂林區', '茄萣區'
            ],
            ['800', '801', '802', '803', '804', '805', '806', '807', '811', '812', '813',
                '814', '815', '820', '821', '822', '823', '824', '825', '826', '827', '828',
                '829', '830', '831', '832', '833', '840', '842', '843', '844', '845', '846',
                '847', '848', '849', '851', '852'
            ]
        ],
        澎湖縣: [
            ['馬公市', '西嶼鄉', '望安鄉', '七美鄉', '白沙鄉', '湖西鄉'],
            ['880', '881', '882', '883', '884', '885']
        ],
        屏東縣: [
            ['屏東市', '三地門', '霧台鄉', '瑪家鄉', '九如鄉', '里港鄉', '高樹鄉', '鹽埔鄉',
                '長治鄉', '麟洛鄉', '竹田鄉', '內埔鄉', '萬丹鄉', '潮州鎮', '泰武鄉', '來義鄉',
                '萬巒鄉', '崁頂鄉', '新埤鄉', '南州鄉', '林邊鄉', '東港鎮', '琉球鄉', '佳冬鄉',
                '新園鄉', '枋寮鄉', '枋山鄉', '春日鄉', '獅子鄉', '車城鄉', '牡丹鄉', '恆春鎮',
                '滿州鄉'
            ],
            ['900', '901', '902', '903', '904', '905', '906', '907', '908', '909', '911',
                '912', '913', '920', '921', '922', '923', '924', '925', '926', '927', '928',
                '929', '931', '932', '940', '941', '942', '943', '944', '945', '946', '947'
            ]
        ],
        台東縣: [
            ['台東市', '綠島鄉', '蘭嶼鄉', '延平鄉', '卑南鄉', '鹿野鄉', '關山鎮', '海端鄉',
                '池上鄉', '東河鄉', '成功鎮', '長濱鄉', '太麻里', '金峰鄉', '大武鄉', '達仁鄉'
            ],
            ['950', '951', '952', '953', '954', '955', '956', '957', '958', '959', '961',
                '962', '963', '964', '965', '966'
            ]
        ],
        花蓮縣: [
            ['花蓮市', '新城鄉', '秀林鄉', '吉安鄉', '壽豐鄉', '鳳林鎮', '光復鄉', '豐濱鄉',
                '瑞穗鄉', '萬榮鄉', '玉里鎮', '卓溪鄉', '富里鄉'
            ],
            ['970', '971', '972', '973', '974', '975', '976', '977', '978', '979', '981',
                '982', '983'
            ]
        ],
        金門縣: [
            ['金沙鎮', '金湖鎮', '金寧鄉', '金城鎮', '烈嶼鄉', '烏坵鄉'],
            ['890', '891', '892', '893', '894', '896']
        ],
        連江縣: [
            ['南竿鄉', '北竿鄉', '莒光鄉', '東引鄉'],
            ['209', '210', '211', '212']
        ],
        南海諸島: [
            ['東沙', '南沙'],
            ['817', '819']
        ],
        釣魚台列嶼: [
            ['釣魚台列嶼'],
            ['290']
        ]
    },
    regulations_title:'會員入會告知暨同意書及服務條款',
    regulations_context: '<p>歡迎申請加入就是行會員，就是行會員服務，是由『就是行國際科技有限公司』（下稱本公司）所建置提供。為了保護您以及所有使用者的利益，並為服務提供依據，請您詳細閱讀下列各項服務辦法及條款。<br /> 當您完成就是行之會員註冊手續、或開始使用本服務時，即表示已閱讀、瞭解並同意接受本服務條款之所有內容，並完全接受本服務現有與未來衍生的服務項目及內容。本公司有權於任何時間修改或變更本服務條款之內容，修改後的服務條款內容將發送到您的會員收件夾，建議您隨時注意相關調整與修改。您於本服務任何修改或變更後繼續使用時，視為您已閱讀、瞭解並同意接受該等修改或變更。若不同意上述的服務條款修訂或更新方式，或不接受本服務條款的其他任一約定，您應立即停止使用本服務，並通知本公司。<br /> 如您未滿二十歲，請您確認已取得您的監護人/法定代理人的同意，方得註冊為會員、使用或繼續使用本服務。當您使用或繼續使用本服務時，即視為您的監護人/法定代理人已閱讀、瞭解並同意接受本同意書及服務條款之所有內容及其後修改變更。</p>' +
        '<p>一、會員資料之蒐集、處理及利用事項<br /> 為確保您之個人資料、隱私及消費者權益之保護，謹依個人資料保護法第8條規定告知以下事項：</p>' +
        '<p>1. &nbsp;蒐集之目的<br />a.022行銷<br />b.037客戶管理(包含但不限於會員管理、會員識別、會員權益通知、提供服務及履行契約與義務、贈獎、優惠等)<br />c.066會員管理</p>' +
        '<p>2. &nbsp;個人資料之類別：<br />a.C001辨識個人者。如姓名、地址、電話、手機、電子郵件、寄送地址及收件人資料等。<br />b.C002辨識財務者。如信用卡或簽帳卡之號碼、個人之其他號碼或帳戶等。<br />c.C011個人描述。如出生年月日。<br />d.C102約定或契約。如關於交易、商業、法律或其他契約等</p>' +
        '<p>3. &nbsp;利用期間<br />自您同意成為就是行會員之日起至任一方終止本服務之日止，但法令另有規定者不再此限。</p>' +
        '<p>4. &nbsp;利用地區、對象及方式<br />a.本同意書及服務條款所謂個人資料，本公司得於利用期間內，於台澎金馬地區，於前述個人資料蒐集目的範圍內，蒐集、處理及利用本人之個人資料(包括但不限於會員管理、客戶管理之檢索查詢等功能外，亦將利用於辨識身份、金流服務、物流服務、行銷廣宣等)。並於上述蒐集目的與履行契約義務的範圍內，將個人資料提供給與本公司有合作關係之第三人【包括但不限於: 因使用本服務所提供之網路交易或活動，可能須透過宅配或貨運業者始能完成貨品(或贈品等)】之配送或取回，因此，您同意並授權本公司得視該次活動之需求及目的，將由您所提供且為配送所必要之個人資料(如收件人姓名、配送地址、連絡電話等)，提供予宅配貨運業者及相關配合之廠商，以利完成該次貨品或贈品等之配送、取回)。<br />b. 本公司將於蒐集之特定目的範圍內處理並利用個人資料。</p>' +
        '<p>5. &nbsp;會員就其個人資料依法得向本公司以書面請求行使下列權利：<br />a.查詢或請求閱覽。<br />b.製作複製本。<br />c.補充或更正。<br />d.請求停止蒐集、處理或利用。<br />e.請求刪除。 <br /> <br />上述權利，若因會員不符合申請程序、法律規定、本公司依法負有保存義務或法律另有規定之情況者，不在此限。您可自由選擇提供個人資料，若其提供之資料不足或有誤時，本公司將無法提供適當之會員服務權利。</p>' +
        '<p>6. &nbsp;會員認知及接受條款<br /> 您同意提供包括但不限於姓名、性別、生日、手機號碼、市話、地址及電子郵件等基本資料進行註冊，於本網站/APP進行消費及接受本會員服務。必要時，您同意提供相關親屬、稱謂、性別、生日及地址給本公司。<br /> 您確認於註冊時提供個人資料均屬真實，正確、最新的資料，且不得以第三人之名義註冊為會員。如您個人資料誤填或有變更時，您將隨時更正或更新。本公司將依您所提供之最新資料予以更新或補充，並維護您個人資料之正確性；如您未及時更新，表示您同意本公司繼續依您原登錄資料提供各項服務，若因此所受相關責任，均由您自行承擔。<br /> 每位會員僅能註冊登錄一個帳號，不可重覆註冊登錄，請您務必詳實填寫使用者帳號及密碼。若您登錄不實資料，本公司將於發現後暫停或終止您的會員資格，並拒絕您使用會員服務。本公司若發現或合理懷疑您個人資料不正確、遺失或遭冒用時，將盡快通知您，並停止處理或利用您的個人資料，且暫停該帳號所產生交易之處理及後續利用；若您發現或合理懷疑您個人資料不正確、遺失或遭冒用時，請您應該立即通知本公司。本公司將採取必要的防範措施並視情況進行調查，以上內容通知不代表本公司對會員負有任何形式之賠償或補償的責任。<br /> 若您就會員註冊資料向本公司請求答覆查詢、提供閱覽或製作複製本時，本公司將酌收必要成本費用。前述的申請，應填寫申請文件，並由本人親自申請，本公司需向您請求提出可資確認身分的證明文件。若委託他人代為申請者，應出具委任書，並提供本人及代理人的身分證明文件。</p>' +
        '<p>7. &nbsp;會員帳號、密碼及安全<br /> 當您同意使用Facebook/Google/微信帳號登入使用本服務時，該Facebook/Google/微信帳號之密碼是由Facebook/Google/微信網站所處理，本公司並不會知悉、蒐集、處理或利用您的Facebook/Google/微信帳號之密碼，而且每一個Facebook/Google/微信帳號只能綁定一個會員帳號進行登入，第一次綁定後即不能再予修改。經由登入Facebook/Google/微信帳號及密碼而登入使用本服務時，該登入的帳號即代表您本人，您於使用本服務之任何行為，均視為您本人之行為，並需遵循就是行會員條款之各項約定。您必須妥善設定、維護及保管自己之Facebook/Google/微信帳號及密碼，包含但不限使用本服務結束時應適時登出本服務之網站並同時登出Facebook/Google/微信帳號。<br /> 您應妥善保管帳號及密碼，並於每次使用後確實登出，以防他人盜用，維持密碼及帳號之機密安全，是您的責任，請勿將您的帳號與密碼洩露或提供給第三人知悉，以避免因此遭人非法使用，若您未能保管好帳號及密碼，因此所受之損害，將由您自行承擔。任何依照規定方法輸入會員帳號及密碼與登入資料一致時，無論是否由本人親自輸入，均將視為您本人所使用，利用該密碼及帳號所進行的一切行動，您本人應負完全責任。<br /> 本站會員所發表的內容訊息本公司將不負任何責任，並保留刪除任何危害其他方著作權或專利內容的權利。若相關內容經本公司認定為有害、具攻擊性、或有侵犯本條款與違法之情事，本公司保有權利直接刪除。若經判定您的行為不符合相關服務條款的要求和精神時，本公司有中斷對其提供網路服務的權利，包括但不限於：當用戶端向外傳輸技術性資料時，應符合當地及國際有關法規。<br /> 您不得公佈或傳送任何誹謗、侮辱、具威脅性、攻擊性、不雅、猥褻、不實、違反公共秩序或善良風俗及其他不法之文字、圖片或任何形式的檔案。您不得侵害或毀損本公司網路或他人之名譽、隱私權、或私自透露營業機密、或利用本服務行侵犯任何與商標權、著作權、專利權、其他智慧財產權及其他權利。</p>' +
        '<p>8. &nbsp;本網站資料記錄<br /> 當您使用本網站時，在使用過程中所有的資料紀錄，包括交易資料、付款方式及資訊、寄送地址及收件人資料，本公司得保存於會員服務資料庫記錄，您同意本公司基於履行契約、客戶管理、會員管理、行銷、統計調查與分析等特定目的之範圍內，蒐集、利用或處理。如您登錄資料或您的行為與上述「會員資料」內容不符或有違反，您同意並瞭解本公司將依此約定條款揭示內容處理，且表示您將自行負擔所有不便及相關法律責任。<br /> 本公司得保留您在上網瀏覽或查詢時，伺服器自行產生的相關記錄，包括您使用連線設備的 IP 位址、使用時間、使用的瀏覽器、瀏覽及點選資料紀錄等。<br /> 本公司得使用 cookie。Cookies 是伺服端為了區別使用者的不同，經由瀏覽器寫入使用者硬碟的一些簡短資訊。只有原設置 cookie 的網站能讀取其內容。本網站使用 cookies 大多基於輔助作用，例如儲存您偏好的特定種類資料，或儲存相關密碼以方便您上網至本公司網站時不必每次再輸入密碼。Cookie 並不含任何資料足使他人透過電話、電子郵件與您聯絡。您可在您的網站瀏覽器上設定功能以獲知何時 cookies 被記錄或避免 cookies 的設置。</p>' +
        '<p>二、會員服務<br /> 對於您所登錄或留存之個人資料，本公司及委外與配合之相關廠商都將於公告之特定目的範圍內予以利用，並採取適當的安全措施予以保護。<br /> <br />1. &nbsp;智慧財產權<br /> 本公司所使用之軟體或程式、網站上所有內容，包括但不限於著作、圖片、檔案、資訊、資料、網站架構、網站畫面的安排、網頁設計，均由本公司或其他權利人依法擁有其智慧財產權；請務必於取得本公司及其他權利人書面同意後，才能使用前述資料。</p>' +
        '<p>2. &nbsp;服務暫停與中斷<br /> 本系統或功能可能因『例行性』之維護、改置、變動或本公司認為需要時，將發生服務暫停或中斷，且對因此所造成任何不便或損害，均不負任何賠償或補償之責任。</p>' +
        '<p>3. &nbsp;網站連結<br /> 為方便您自行蒐集或取得資訊，本公司將提供相關連結，但此連結並不代表本公司與此類連結網站管理者有任何合夥、僱傭或其他類似關係，其他業者經營的網站均由各該業者自行負責，本公司對任何的外部連結，不擔保其合適性、有效性、或即時性，且本公司不對連結網站所提供產品、服務或資訊提供擔保或其他責任，請您留意該連結網站之交易及資訊安全性。<br /> <br />三、其他條款<br /> 隨著市場環境的改變本公司將會不定期修訂網站/APP政策。當我們在使用個人資料的規定上作出大修改時，我們將發送到您的會員收件夾，通知相關事項。您於本服務任何修改或變更後繼續使用時，視為您已閱讀、瞭解並同意接受該等修改或變更。若不同意上述的服務條款修訂或更新方式，或不接受本服務條款的其他任一約定，您應立即停止使用本服務，並通知本公司。<br /> 本同意書及服務條款以中文為準，其解釋、補充及適用均以中華民國法令為準據法(但涉外民事法律適用法，不在適用之列)。會員約定條款中任何條款之全部或一部份無效時，不影響其他約定之效力。<br />因會員條款所發生之訴訟，以台灣台北地方法院為第一審管轄法院。</p>'

}