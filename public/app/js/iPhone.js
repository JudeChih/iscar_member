function connectWebViewJavascriptBridge(callback) {
    if (window.WebViewJavascriptBridge) {
        callback(WebViewJavascriptBridge)
    } else {
        document.addEventListener('WebViewJavascriptBridgeReady', function () {
            callback(WebViewJavascriptBridge)
        }, false)
    }
}

/* 連結 webView & Javascript */
connectWebViewJavascriptBridge(function (bridge) {
    var uniqueId = 1
    bridge.init(function (message, responseCallback) {
        // TODO: 暫時沒動作
        //_sipRegistrarState = ''
    })

});


var iPhone = {
    iphoneGetVersion: function () {
        var appName = 'iscar';
        var actionType = 'actionGetVersion';
        var actionParameters = {};
        var jsonString = (JSON.stringify(actionParameters));
        var escapedJsonParameters = encodeURI(jsonString);
        var url = appName + '://' + actionType + '#' + escapedJsonParameters;
        document.location = url;
    },
    fbLogin: function () {
        var appName = 'iscar';
        var actionType = 'actionFBLogin';
        var actionParameters = {};
        var jsonString = (JSON.stringify(actionParameters));
        var escapedJsonParameters = encodeURI(jsonString);
        var url = appName + '://' + actionType + '#' + escapedJsonParameters;
        document.location = url;
    },
    machineGetInfo: function () {
        var appName = 'iscar';
        var actionType = 'actionGetMachineInfo';
        var actionParameters = {};
        var jsonString = (JSON.stringify(actionParameters));
        var escapedJsonParameters = encodeURI(jsonString);
        var url = appName + '://' + actionType + '#' + escapedJsonParameters;
        document.location = url;
    },
    FBLogout: function () {
        var appName = 'iscar';
        var actionType = 'actionFBLogout';
        var actionParameters = {};
        var jsonString = (JSON.stringify(actionParameters));
        var escapedJsonParameters = encodeURI(jsonString);
        var url = appName + '://' + actionType + '#' + escapedJsonParameters;
        document.location = url;
    },
    FBShare: function (url) {
        var appName = 'iscar';
        var actionType = 'actionFBShare';
        var actionParameters = {
            'url': url
        };
        var jsonString = (JSON.stringify(actionParameters));
        var escapedJsonParameters = encodeURI(jsonString);
        var url = appName + '://' + actionType + '#' + escapedJsonParameters;
        document.location = url;
    },
    FBLike: function (url) {
        var appName = 'iscar';
        var actionType = 'actionFBLike';
        var actionParameters = {
            'url': url
        };
        var jsonString = (JSON.stringify(actionParameters));
        var escapedJsonParameters = encodeURI(jsonString);
        var url = appName + '://' + actionType + '#' + escapedJsonParameters;
        document.location = url;
    },
    FBComment: function (url, text) {
        var appName = 'iscar';
        var actionType = 'actionFBComment';
        //console.log(text);
        var actionParameters = {
            'url': url,
            'text': text
        };
        //console.log(encodeURI(text));
        var jsonString = (JSON.stringify(actionParameters));
        //console.log(jsonString);
        var escapedJsonParameters = encodeURI(jsonString);
        //console.log(escapedJsonParameters);
        var url = appName + '://' + actionType + '#' + escapedJsonParameters;
        document.location = url;
    },
    JSHTMLUpdate: function (version, url) {
        var appName = 'iscar';
        var actionType = 'actionJSHTMLUpdate';
        var actionParameters = {
            'version': version,
            'url': url
        };
        var jsonString = (JSON.stringify(actionParameters));
        var escapedJsonParameters = escape(jsonString);
        var url = appName + '://' + actionType + '#' + escapedJsonParameters;
        document.location = url;
    },
    ConfirmJSHTMLUpdate: function (version, url) {
        var appName = 'iscar';
        var actionType = 'actionConfirmJSHTMLUpdate';
        var actionParameters = {
            'version': version,
            'url': url
        };
        var jsonString = (JSON.stringify(actionParameters));
        var escapedJsonParameters = escape(jsonString);
        var url = appName + '://' + actionType + '#' + escapedJsonParameters;
        document.location = url;
    },
    StartQR: function () {
        var appName = 'iscar';
        var actionType = 'actionStartQR';
        var actionParameters = {};
        var jsonString = (JSON.stringify(actionParameters));
        var escapedJsonParameters = escape(jsonString);
        var url = appName + '://' + actionType + '#' + escapedJsonParameters;
        document.location = url;
    },
    getMdID: function (mdId) {
        var appName = 'iscar';
        var actionType = 'getMdID';
        var actionParameters = {
            'md_id': mdId
        };
        var jsonString = (JSON.stringify(actionParameters));
        var escapedJsonParameters = escape(jsonString);
        var url = appName + '://' + actionType + '#' + escapedJsonParameters;
        document.location = url;
    },
    nowScreenName: function (name) {
        var appName = 'iscar';
        var actionType = 'nowScreenName';
        var actionParameters = {
            'name': encodeURIComponent(name)
        };
        var jsonString = (JSON.stringify(actionParameters));
        var escapedJsonParameters = escape(jsonString);
        var url = appName + '://' + actionType + '#' + escapedJsonParameters;
        document.location = url;
    },
    nowEvent: function (category, action, mdId) {
        var appName = 'iscar';
        var actionType = 'nowEvent';
        var actionParameters = {
            'category': category,
            'action': encodeURIComponent(action),
            'mdId': mdId
        };
        var jsonString = (JSON.stringify(actionParameters));
        var escapedJsonParameters = escape(jsonString);
        var url = appName + '://' + actionType + '#' + escapedJsonParameters;
        document.location = url;
    },
    toWeb: function (url) {
        var appName = 'iscar';
        var actionType = 'toWeb';
        var actionParameters = {
            'url': encodeURIComponent(url)
        };
        var jsonString = (JSON.stringify(actionParameters));
        var escapedJsonParameters = escape(jsonString);
        var url = appName + '://' + actionType + '#' + escapedJsonParameters;
        document.location = url;
    },
    shareTo: function (url, id) {
        var appName = 'iscar';
        var actionType = 'shareTo';
        var actionParameters = {
            'newsID': encodeURIComponent(id),
            'url': encodeURIComponent(url)
        };
        var jsonString = (JSON.stringify(actionParameters));
        var escapedJsonParameters = escape(jsonString);
        var url = appName + '://' + actionType + '#' + escapedJsonParameters;
        document.location = url;
    },
    getVersionName: function () {
        var appName = 'iscar';
        var actionType = 'getVersionName';
        var actionParameters = {};
        var jsonString = (JSON.stringify(actionParameters));
        var escapedJsonParameters = escape(jsonString);
        var url = appName + '://' + actionType + '#' + escapedJsonParameters;
        document.location = url;
    },
    openPhoto: function () {
        var appName = 'iscar';
        var actionType = 'openPhoto';
        var actionParameters = {};
        var jsonString = (JSON.stringify(actionParameters));
        var escapedJsonParameters = escape(jsonString);
        var url = appName + '://' + actionType + '#' + escapedJsonParameters;
        document.location = url;
    },
    startEvent: function (category, action, mdId) {
        var appName = 'iscar';
        var actionType = 'startEvent';
        var actionParameters = {
            'category': category,
            'action': encodeURIComponent(action),
            'mdId': mdId
        };
        var jsonString = (JSON.stringify(actionParameters));
        var escapedJsonParameters = escape(jsonString);
        var url = appName + '://' + actionType + '#' + escapedJsonParameters;
        document.location = url;
    },
    exit: function () {
        var appName = 'iscar';
        var actionType = 'Exit';
        var actionParameters = {};
        var jsonString = (JSON.stringify(actionParameters));
        var escapedJsonParameters = escape(jsonString);
        var url = appName + '://' + actionType + '#' + escapedJsonParameters;
        document.location = url;
    },
    getLocation: function () {
        var appName = 'iscar';
        var actionType = 'getLocation';
        var actionParameters = {};
        var jsonString = (JSON.stringify(actionParameters));
        var escapedJsonParameters = escape(jsonString);
        var url = appName + '://' + actionType + '#' + escapedJsonParameters;
        document.location = url;
    },
    openCamera: function (cdm_id, cdd_qr, filename, FTP_HOST, FTP_PATH) {
        var appName = 'iscar';
        var actionType = 'openCamera';
        var actionParameters = {
            'cdm_id': encodeURIComponent(cdm_id),
            'cdd_qr': encodeURIComponent(cdd_qr),
            'filename': encodeURIComponent(filename),
            'FTP_HOST': encodeURIComponent(FTP_HOST),
            'FTP_PATH': FTP_PATH
        };
        var jsonString = (JSON.stringify(actionParameters));
        var escapedJsonParameters = escape(jsonString);
        var url = appName + '://' + actionType + '#' + escapedJsonParameters;
        document.location = url;
    },
    branchCamera: function (FTP_HOST, FTP_PATH, sd_id, subfolder, filename) {
        var appName = 'iscar';
        var actionType = 'branchCamera';
        var actionParameters = {
            'FTP_HOST': encodeURIComponent(FTP_HOST),
            'FTP_PATH': FTP_PATH,
            'sd_id': encodeURIComponent(sd_id),
            'subfolder': encodeURIComponent(subfolder),
            'filename': encodeURIComponent(filename)
        };
        var jsonString = (JSON.stringify(actionParameters));
        var escapedJsonParameters = escape(jsonString);
        var url = appName + '://' + actionType + '#' + escapedJsonParameters;
        document.location = url;
    },
    localImage: function (FTP_HOST, FTP_PATH, sd_id, subfolder, filename) {
        var appName = 'iscar';
        var actionType = 'localImage';
        var actionParameters = {
            'FTP_HOST': encodeURIComponent(FTP_HOST),
            'FTP_PATH': FTP_PATH,
            'sd_id': encodeURIComponent(sd_id),
            'subfolder': encodeURIComponent(subfolder),
            'filename': encodeURIComponent(filename)
        };
        var jsonString = (JSON.stringify(actionParameters));
        var escapedJsonParameters = escape(jsonString);
        var url = appName + '://' + actionType + '#' + escapedJsonParameters;
        document.location = url;
    },
    deleteFileFromFTP: function (FTP_HOST, FTP_PATH, filePath, filename) {
        var appName = 'iscar';
        var actionType = 'deleteFileFromFTP';
        var actionParameters = {
            'FTP_HOST': encodeURIComponent(FTP_HOST),
            'FTP_PATH': FTP_PATH,
            'filePath': filePath,
            'filename': filename
        };
        var jsonString = (JSON.stringify(actionParameters));
        var escapedJsonParameters = escape(jsonString);
        var url = appName + '://' + actionType + '#' + escapedJsonParameters;
        document.location = url;
    },
    renameFileToMove: function (FTP_HOST, FTP_PATH, FTP_TEMPPRARY_PATH, filePath, filename_r, filename_d) {
        var appName = 'iscar';
        var actionType = 'renameFileToMove';
        var actionParameters = {
            'FTP_HOST': encodeURIComponent(FTP_HOST),
            'FTP_PATH': FTP_PATH,
            'FTP_TEMPPRARY_PATH': FTP_TEMPPRARY_PATH,
            'filePath': filePath,
            'filename_r': filename_r,
            'filename_d': filename_d
        };
        var jsonString = (JSON.stringify(actionParameters));
        var escapedJsonParameters = escape(jsonString);
        var url = appName + '://' + actionType + '#' + escapedJsonParameters;
        document.location = url;
    },
    setAlarm: function (year,month,date,hour,minute,cdd_qr,msg) {
        var appName = 'iscar';
        var actionType = 'setAlarm';
        var actionParameters = {
            'year': encodeURIComponent(year),
            'month': encodeURIComponent(month),
            'date': encodeURIComponent(date),
            'hour': encodeURIComponent(hour),
            'minute': encodeURIComponent(minute),
            'id': encodeURIComponent(cdd_qr),
            'msg': encodeURIComponent(msg)
        };
        var jsonString = (JSON.stringify(actionParameters));
        var escapedJsonParameters = escape(jsonString);
        var url = appName + '://' + actionType + '#' + escapedJsonParameters;
        document.location = url;
    },
    closeAlarm: function (id) {
        var appName = 'iscar';
        var actionType = 'closeAlarm';
        var actionParameters = {
            'id': id
        };
        var jsonString = (JSON.stringify(actionParameters));
        var escapedJsonParameters = escape(jsonString);
        var url = appName + '://' + actionType + '#' + escapedJsonParameters;
        document.location = url;
    },
    goToAppStore: function () {
        var appName = 'iscar';
        var actionType = 'goToAppStore';
        var actionParameters = {};
        var jsonString = (JSON.stringify(actionParameters));
        var escapedJsonParameters = escape(jsonString);
        var url = appName + '://' + actionType + '#' + escapedJsonParameters;
        document.location = url;
    },
    loginPage: function () {
        var appName = 'iscar';
        var actionType = 'loginPage';
        var actionParameters = {};
        var jsonString = (JSON.stringify(actionParameters));
        var escapedJsonParameters = escape(jsonString);
        var url = appName + '://' + actionType + '#' + escapedJsonParameters;
        document.location = url;
    },
    userMenuPage: function () {
        var appName = 'iscar';
        var actionType = 'userMenuPage';
        var actionParameters = {};
        var jsonString = (JSON.stringify(actionParameters));
        var escapedJsonParameters = escape(jsonString);
        var url = appName + '://' + actionType + '#' + escapedJsonParameters;
        document.location = url;
    },       
    closePage: function () {
        var appName = 'iscar';
        var actionType = 'closePage';
        var actionParameters = {};
        var jsonString = (JSON.stringify(actionParameters));
        var escapedJsonParameters = escape(jsonString);
        var url = appName + '://' + actionType + '#' + escapedJsonParameters;
        document.location = url;
    },
    insertUserInfo: function (user_info) {
        var appName = 'iscar';
        var actionType = 'insertUserInfo';
        var actionParameters = {'user_info':user_info};
        var jsonString = (JSON.stringify(actionParameters));
        var escapedJsonParameters = escape(jsonString);
        var url = appName + '://' + actionType + '#' + escapedJsonParameters;
        document.location = url;
    },
    getUserInfo: function () {
        var appName = 'iscar';
        var actionType = 'getUserInfo';
        var actionParameters = {};
        var jsonString = (JSON.stringify(actionParameters));
        var escapedJsonParameters = escape(jsonString);
        var url = appName + '://' + actionType + '#' + escapedJsonParameters;
        document.location = url;
    },
    goTo: function (page_name) {
        var appName = 'iscar';
        var actionType = 'goTo';
        var actionParameters = {'page_name':page_name};
        var jsonString = (JSON.stringify(actionParameters));
        var escapedJsonParameters = escape(jsonString);
        var url = appName + '://' + actionType + '#' + escapedJsonParameters;
        document.location = url;
    },
    fbLogged: function () {
        var appName = 'iscar';
        var actionType = 'fbLogged';
        var actionParameters = {};
        var jsonString = (JSON.stringify(actionParameters));
        var escapedJsonParameters = escape(jsonString);
        var url = appName + '://' + actionType + '#' + escapedJsonParameters;
        document.location = url;
    }
};