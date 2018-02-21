<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui">

    <title>isCar就是行</title>

    <style>
        body {
            height: 100%;
            width: 100%;
            background-image: url(app/image/carbon_bg.jpg);
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
            background-size: cover;
        }
    </style>
</head>

<body>

</body>
<script type="text/javascript" src="../app/js/config.js"></script>
<script type="text/javascript" src="app/js/string.js"></script>

<script>
    var strUrl = location.search;
    var getPara, ParaVal;
    var aryPara = [];

    if (strUrl.indexOf("?") != -1) {
        var getSearch = strUrl.split("?");
        getPara = getSearch[1].split("&");
        for (i = 0; i < getPara.length; i++) {
            ParaVal = getPara[i].split("=");
            aryPara.push(ParaVal[1]);
        }

        var mainSg = JSON.parse(localStorage.getItem('main')) || {};
        var from = localStorage.getItem('from') || '';
        mainSg.sat = aryPara[0];
        localStorage.setItem('main', JSON.stringify(mainSg));
        //根據來源轉址
        switch (from) {
        case 'News':
            window.location = "http://" + stringObj.NEWS_URL + "/News/transform?user_info=" + encodeURIComponent(JSON.stringify(mainSg));
            break;
        case 'Shop':
            window.location = "http://" + stringObj.SHOP_URL + "/Shop/transform?user_info=" + encodeURIComponent(JSON.stringify(mainSg));
            break;
        case 'Shop_b':
            window.location = "http://" + stringObj.SHOP_URL + "/Shop/webend_admin/transform?user_info=" + encodeURIComponent(JSON.stringify(mainSg));
            break;
        case 'CP':
            window.location = "http://" + stringObj.CP_URL + "/transform.html?user_info=" + encodeURIComponent(JSON.stringify(mainSg));
            break;
        case 'Box':
            window.location = "http://" + stringObj.BOX_URL + "/home/transform?user_info=" + encodeURIComponent(JSON.stringify(mainSg));
            break;
        default:
            window.location = "http://" + stringObj.NEWS_URL + "/News/transform?user_info=" + encodeURIComponent(JSON.stringify(mainSg));
            break;
        }

    }
</script>

</html>