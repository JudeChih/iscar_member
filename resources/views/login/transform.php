<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui">

    <title>isCar就是行</title>

</head>

<body>


</body>
<script type="text/javascript" src="../app/js/config.js"></script>
<!-- JS-cookie -->
<script src="app/libs/js-cookie/src/js.cookie.js"></script>

<script type="text/javascript" src="../app/js/string.js"></script>

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
        localStorage.setItem('main', decodeURIComponent(aryPara[0]));
        Cookies.set('parameter', aryPara[1]);
        window.location = "http://" + stringObj.WEB_URL; //轉址路徑
    }
</script>

</html>