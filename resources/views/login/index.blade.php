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

    <title>isCar-login</title>

    {{-- <link rel="stylesheet" href="app/libs/font-awesome-4.7.0/css/font-awesome.min.css"> --}}
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

<body>

    <div class="statusbar-overlay"></div>


    <!-- Views -->
    <div class="views">
        <div class="view view-main">
            <div class="navbar">
                <div class="navbar-inner"></div>
            </div>
            <div class="pages navbar-fixed toolbar-fixed">
                <div data-page="index" class="page no-navbar">
                @if($errors->any())
                <input class="error" type="hidden" value="{{ $errors->first() }}">
                @endif

                </div>
            </div>

        </div>

    </div>




    <!-- About Popup -->
    <div class="popup popup-about">
        <div class="close-btn">
            <a href="#" class="close-popup">
                <i class="fa fa-times" aria-hidden="true"></i>
            </a>
        </div>
        <div class="content-block">
            <p style="text-align: justify;"><span style="font-size: 14pt; color: #F26531;"><strong><span class="company
" style="font-size: 15pt;">就是行國際科技有限公司</span></strong>
                </span><span style="color: #ff6600; font-size: 15pt;" data-mce-mark="1"></span></p>
            {{-- <p><span style="font-size: 14pt;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;賢記紙業集團為台灣供應涵蓋最廣之連鎖紙張供應商，在2004年正式創辦翔偉資安科技公司並投入資訊安全系統整合供應領域，致力為各端點提供專業資安技術服務，並於2007正式取得芬蘭國際知名系統安全軟體F-Secure芬安全大中華區總代理授權，在實力堅強的專業團隊帶領下，零時差為全球客戶提供專業技術服務，以全方位服務解決資安問題。翔偉科技有著強大的資安整合系統背景，近年來更投入專業EDI(電子數據交換)提供數據交換平台，並為資訊互換安全層層把關， 2011 年成為HL7-Taiwan(台灣健康資訊交換第七協定會)團體會員，聯手為各大醫療機構團體滴水不漏保護病人個資安全。</span></p> --}}
            <p style="text-align: justify;"><span style="color: #ff6600; font-size: 14pt;" data-mce-mark="1"></span></p>
            {{-- <p style="text-align: justify;"><span style="font-size: 15pt; color: #F26531;" data-mce-mark="1"><strong>isCar</strong></span><span style="font-size: 14pt;" data-mce-mark="1"><br /></span></p> --}}
            <p style="text-align: justify;"><span style="font-size: 14pt;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;就是行國際科技有限公司以數位科技的專業優勢背景，於2014年正式整併isCar汽車網成為旗下品牌，將”行”的文化用最貼近人心的多元化模式為消費者提供最完整的汽車生活服務，拉近人車與生活的關係。isCar，有來自不同領域的專業人才無時無刻為消費者提供最新的汽車生活資訊，從計畫買車的那一刻開始，isCar就跟消費者有著密不可分的關係。isCar擁有走在時代前端的靈敏嗅覺，再結合翔偉科技其專業領域之技術整合，成為isCar強而有力之後盾。isCar絕對能給您最貼心、最專業之服務。</span></p>
            <p style="text-align: justify;">&nbsp;</p>
            <p style="text-align: justify;"><span style="color: #F26531; font-size: 15pt;" data-mce-mark="1"><strong>聯絡我們</strong></span></p>
            <div id="company_informations">
                <table class="companyinfotable" style="width: 100%;" border="0" cellspacing="0" cellpadding="5">
                    <tbody>
                        <tr>
                            <td valign="middle" width="32">
                                <i class="fa fa-phone"></i>
                            </td>
                            <td valign="middle"><strong>&nbsp;Phone.</strong>
                                <br />&nbsp;(02)5599-6122</td>
                            <td valign="middle" width="32">
                                <i class="fa fa-fax"></i>
                            </td>
                            <td valign="middle"><strong>&nbsp;Fax.</strong>
                                <br />&nbsp;(02)8773-4663</td>

                        </tr>
                        <tr>
                            <td valign="middle" width="32">
                                <a href="mailto:event@iscarmg.com">
                                    <i class="fa fa-envelope"></i>
                                </a>
                            </td>
                            <td valign="middle"><strong>&nbsp;E-mail.</strong>&nbsp;
                                <br /><a href="mailto:event@iscarmg.com" class="external">&nbsp;event@iscarmg.com</a></td>
                            <td valign="middle" width="32">
                                <a href="http://tw.iscarmg.com">
                                    <i class="fa fa-globe"></i>
                                </a>
                            </td>
                            <td valign="middle"><strong>&nbsp;Web site.</strong>
                                <br />&nbsp;<a href="http://tw.iscarmg.com" class="external">&nbsp;http://tw.iscarmg.com</a></td>
                        </tr>
                        <tr>
                            <td valign="middle" width="32">
                                <i class="fa fa-building"></i>
                            </td>
                            <td colspan="3" valign="middle"><strong>&nbsp;Address.</strong>
                                <br />&nbsp;台北市中山區八德路二段260號2樓</td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>


    <!-- Privacy Popup -->
    <div class="popup popup-privacy">
        <div class="close-btn">
            <a href="#" class="close-popup">
                <i class="fa fa-times" aria-hidden="true"></i>
            </a>
        </div>

        <div class="content-block">
        <p>一、隱私權保護政策</p>
<p>&nbsp; &nbsp; &nbsp; 就是行國際科技有限公司(以下簡稱本公司)非常重視用戶的隱私權。因此，制訂了隱私權保護政策。由於我們會收集部分個人資訊，因此我們希望協助用戶瞭解我們在蒐集與使用這些資訊上的規範。本隱私權政策中將會說明我們蒐集了哪些資訊，怎麼使用它，同時也會說明，如何修改您的資訊。我們採取嚴密的預防措施保護個人資料，避免流失、誤用、擅自存取或揭露、更改或損毀。請您細讀以下有關隱私權保護政策的內容。</p>
<p>1、使用者資料之蒐集目的：<br />此資料的蒐集目的係為確認於本公司網站進行交易服務行為或取得資訊的使用者身份確認及提供有關服務之用，蒐集您個人資訊的最主要目標是為了提供給您更佳的資訊內容與服務。</p>
<p>2、隱私權保護政策適用範圍：<br />隱私權保護政策內容，包括本公司如何處理在用戶使用網站服務時收集到的身份識別資料， 也包括本公司如何處理在商業伙伴與本公司合作時分享的任何身份識別資料，隱私權保護政策不適用於本公司以外的公司。</p>
<p>3、資料收集以及使用方式：<br />本公司在您註冊帳號、使用的產品或服務、瀏覽網頁、參加宣傳活動或贈獎遊戲時，本公司會收集您及您所提供親友的個人資料。本公司也可以從商業夥伴處取得個人資料。當您在本公司註冊時，我們會請您提供您的姓名、聯絡電話、行動電話、聯絡地址、電子郵件地址等資料。本公司也自動接收並紀錄您行動裝置上的伺服器數值，包括Cache等。本公司會使用資料作以下用途：改進為您提供的廣告及網頁內容、完成您對某項產品的要求及通知您特別活動或新產品。</p>
<p>4、資料分享以及公開方式：<br />本公司不會向任何人出售或出借您及您所提供親友的個人資料。在以下的情況下，本公司會向其他人士或公司提供您及您所提供親友的個人資料：1.與其他人士或公司共用資料前取得您的同意；2.需要與其他人士或公司共用您的資料，才能夠提供您要求的產品或服務；3.向代表本公司提供服務或產品的公司提供資料，以便向您提供產品或服務 (若我們沒有事先通知您，這些公司均無權使用我們提供的個人資料，作提供產品或服務以外的其他用途)；4.遵守法令或政府機關的要求；5.如您在網站上的行為違反法律相關規定而有犯罪嫌疑，經司法機關偵查時，本網站將協助配合及提供您或其他使用者之相關資料。</p>
<p>5、Cache：<br />您的行動裝置通常都會具備Cache功能，Cache中會儲存在您的行動裝置中，並紀錄您造訪過我們的哪些網站。本公司會設定並取用 Cache。但Cache並不會透露您的任何隱私資料，除非您主動將這些資料提供給我們。本公司容許於我們合作的廣告廠商到您的行動裝置設定並取用Cache，並根據其自訂的隱私權保護政策使用。</p>
<p>6、修改個人帳號資料及偏好設定：<br />您可在任何時候修改個人帳號資料及偏好設定，包括接受本公司通知您特別活動或新產品的接收與否。</p>
<p>7、連結網頁的使用：<br />為服務使用者，本網站的網頁可能提供其他網站的網路連結，用戶可經由本網站所提供的連結，點選進入其他網站。但本網站並不保護您因點選該連結而進入其他網頁後所引致之風險(包括但不限隱私權保護、病毒危害等)。</p>
<p>&nbsp;</p>
<p>二、用戶註冊義務</p>
<p>1、您依本應用程式之指示完成註冊後，除有特別規定者外，將可享受本應用程式完整服務。進行本網站註冊程序時，可能會透過您於第三方之帳號、密碼登入，以減省您註冊所需填寫資料的時間。當您以第三方之帳號、密碼登入時，系統將自動將您於第三方註冊之資訊提供予您確認，您於確認後即同意以該等資料向本網站進行註冊程序。</p>
<p>2、註冊前，請先確認您已符合法律所規定之年齡限制及有完全行為能力，並遵守下列事項：（a）於帳號申請表中提供正確、現有且完整的個人資訊；（b）隨時更新個人資料以確保每項資料均能維持正確、現有且完整之要求。</p>
<p>3、若您提供錯誤、過時或缺漏之個人資料，或本公司認為您所提供之資料具上述瑕疵，本公司擁有暫停或終止您對本公司下轄所有服務之使用權。</p>
<p>4、您以註冊通過之帳號密碼登入本網站後所為之ㄧ切行為或活動，應自行負責。為維護自身權益，請您勿將帳號及密碼洩漏或提供第三人知悉、出借或轉讓與他人使用。</p>
<p>5、本公司保留變更全部及部分本網站服務之權利。本網站如另行公告服務條款或相關規定（包括但不限於反垃圾郵件政策）亦均併入屬於本條款之一部分。本網站服務亦可能因為第三方服務之終止或變更，而產生變動，您於同意採用本網站服務時亦同意本服務變更所造成之風險應自行承擔，您不得因此向本公司或其代理人、經理人、受僱人或合作廠商為任何請求。</p>
<p>&nbsp;</p>
<p>2017年08月07日修訂</p>
            {{-- <p style="text-align: justify;"><strong><span style="font-size: 15pt; color:#F26531;">一、隱私權保護政策</span></strong>
            </p>
            <p><span style="font-size: 14pt;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;翔偉資安科技有限公司(以下簡稱本公司)非常重視用戶的隱私權。由於我們會收集部分個人資訊，因此我們希望協助用戶瞭解我們在蒐集與使用這些資訊上的規範。本項宣言中我們將會說明我們蒐集了哪些資訊，又怎麼使用它，同時也會說明，如何修改您的資訊。我們採取嚴密的預防措施保護個人資料，避免流失、誤用、擅自存取或揭露、更改或損毀。因此，制訂了隱私權保護政策。請你細讀以下有關隱私權保護政策的內容。</span></p>
            <p><span style="font-size: 14pt;">1、使用者資料之蒐集目的：<br>

此資料的蒐集目的係為確認於本公司網站進行交易服務行為或取得資訊的使用者身份確認及提供有關服務之用，蒐集網友個人資訊的最主要目標是為了提供網友更佳的資訊內容與服務。</span></p>
            <p><span style="font-size: 14pt;">2、隱私權保護政策適用範圍：<br>

隱私權保護政策內容，包括本公司如何處理在用戶使用網站服務時收集到的身份識別資料， 也包括本公司如何處理在商業伙伴與本公司合作時分享的任何身份識別資料。 隱私權保護政策不適用於本公司以外的公司，也不適用於本公司所僱用或管理的人員。</span></p>
            <p><span style="font-size: 14pt;">3、資料收集以及使用方式：<br>

本公司在您註冊帳號、使用的產品或服務、瀏覽網頁、參加宣傳活動或贈獎遊戲時，本公司會收集您的個人識別資料。本公司也可以從商業夥伴處取得個人資料。當你在本公司註冊時，我們會問及您的姓名、聯絡電話、行動電話、聯絡地址、電子郵件地址等資料。本公司也自動接收並紀錄您行動裝置上的伺服器數值，包括Cache等。本公司會使用資料作以下用途：改進為您提供的廣告及網頁內容、完成您對某項產品的要求及通知您特別活動或新產品。</span></p>
            <p><span style="font-size: 14pt;">4、資料分享以及公開方式：<br>

本公司不會向任何人出售或出借你的個人識別資料。在以下的情況下，本公司會向其他人士或公司提供你的個人識別資料：與其他人士或公司共用資料前取得您的同意； 需要與其他人士或公司共用您的資料，才能夠提供您要求的產品或服務； 向代表本公司提供服務或產品的公司提供資料，以便向您提供產品或服務 (若我們沒有事先通知您，這些公司均無權使用我們提供的個人資料，作提供產品或服務以外的其他用途)； 應遵守法令或政府機關的要求；或我們發覺您在網站上的行為違反本公司服務條款 或產品、服務的特定使用指南。若使用者因犯罪嫌疑，經司法機關依法偵查時，本網站將協助配合，並提供使用者之相關資料。</span></p>
            <p><span style="font-size: 14pt;">5、Cache：<br>

您的行動裝置通常都會具備Cache功能，Cache中會儲存在您的行動裝置中，並紀錄您造訪過我們的哪些網站。本公司會設定並取用 Cache。但Cache並不會透露您的任何隱私資料，除非您主動將這些資料提供給我們。本公司容許在我們網頁上擺放廣告的廠商到您的行動裝置設定並取用Cache，其他公司將根據其自訂的隱私權保護政策，而並非本政策使用該Cache，其他廣告商或公司不能提取本公司的Cache。
</span></p>
            <p><span style="font-size: 14pt;">
6、修改個人帳號資料及偏好設定的權：<br>

本公司賦予您在任何時候修改個人本公司帳號資料及偏好設定的權力，包括接受本公司通知您特別活動或新產品的決定權。</span></p>
            <p><span style="font-size: 14pt;">7、連結網頁的使用：<br>

為服務使用者，本網站的網頁可能提供其他網站的網路連結，用戶可經由本網站所提供的連結，點選進入其他網站。但本網站並不保護用戶於該等連結網站中的隱私權。</span></p>
            <p style="text-align: justify;"><span style="color: #ff6600; font-size: 14pt;" data-mce-mark="1"></span></p>
            <p style="text-align: justify;"><span style="font-size: 15pt; color: #F26531;" data-mce-mark="1"><strong>二、用戶註冊義務</strong></span><span style="font-size: 14pt;" data-mce-mark="1"><br /></span></p>
            <p><span style="font-size: 14pt;">1、用戶依本應用程式之指示完成註冊後，除有特別規定者外，將可享受本應用程式完整服務。進行本網站註冊程序時，可能會透過您於第三方之帳號、密碼登入，以減省您註冊所需填寫資料的時間。當您以第三方之帳號、密碼登入時，系統將自動將您於第三方註冊之資訊提供予您確認，您於確認後即同意以該等資料向本網站進行註冊程序。</span></p>
            <p><span style="font-size: 14pt;">2、註冊前，請先確認您已符合法律所規定之年齡限制及有完全行為能力，並遵守下列事項：（a）於帳號申請表中提供正確、現有且完整的個人資訊；（b）隨時更新個人資料以確保每項資料均能維持正確、現有且完整之要求。</span></p>
            <p><span style="font-size: 14pt;">3、若您提供錯誤、過時或缺漏之個人資料，或本公司認為您所提供之資料具上述瑕疵，本公司擁有暫停或終止您對本公司下轄所有服務之使用權。</span></p>
            <p><span style="font-size: 14pt;">4、您以註冊通過之帳號密碼登入本網站後所為之ㄧ切行為或活動，應自行負責。為維護自身權益，請您勿將帳號及密碼洩漏或提供第三人知悉、出借或轉讓與他人使用。</span></p>
            <p><span style="font-size: 14pt;">5、本公司保留變更全部及部分本網站服務之權利。本網站如另行公告服務條款或相關規定（包括但不限於反垃圾郵件政策）亦均併入屬於本條款之一部分。本網站服務亦可能因為第三方服務之終止或變更，而產生變動，您同意前開服務變更都是您在同意採用本網站服務時，應自行承擔之風險，您不得因此向本公司或其代理人、經理人、受僱人或合作廠商為任何請求。</span></p>
            <p style="text-align: justify;">&nbsp;</p>
            <p style="text-align: justify;"><span style="font-size: 14pt;">
2016年01月07日修訂</span></p> --}}
        </div>
    </div>

    <script type="text/javascript" src="app/libs/jquery/dist/jquery-1.11.3.min.js"></script>
    <script src="app/libs/fontawesome-free-5.0.6/on-server/js/fontawesome-all.min.js"></script>
    <script src="app/libs/fontawesome-free-5.0.6/on-server/js/fa-v4-shim.min.js"></script>
    <script type="text/javascript" src="app/libs/Framework7/dist/js/framework7.min.js"></script>
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
    <script src="app/js/iPhone.js"></script>
    <script src="app/js/webview.js"></script>
    <script src="app/js/string.js"></script>
    <!-- 瀏覽器裝置機碼 -->
    <script type="text/javascript">
        document.write('<script type="text/javascript" src="http://' + server_type + _region + '-member.iscarmg.com/app/js/generate_murid.js"><\/script>');
    </script>
    <script src="app/js/Login/login.js"></script>

    <!-- 裝置console -->
    <!-- <script src="app/libs/vConsole-dev/dist/vconsole.min.js"></script> -->


</body>

</html>
