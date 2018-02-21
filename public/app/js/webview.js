/**
 * webview lib by Android or iPhone
 */
//取得瀏覽器類型與版本號
function BrowserCheck() {
    var N = navigator.appName,
        ua = navigator.userAgent,
        tem;
    var M = ua.match(/(opera|chrome|safari|firefox|msie|trident)\/?\s*(\.?\d+(\.\d+)*)/i);
    if (M && (tem = ua.match(/version\/([\.\d]+)/i)) != null) {
        M[2] = tem[1];
    }
    M = M ? [M[1], M[2]] : [N, navigator.appVersion, '-?'];
    return M;
}
var isMobile = {
    Android: function () {
        // alert('Android');
        return navigator.userAgent.match(/Android/i);
    },
    iOS: function () {
        // alert('iOS');
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    any: function () {
        return (isMobile.Android() || isMobile.iOS());
    }
};

if (isMobile.iOS()) {
    //oScript.src= 'assets/js/u3mobile-iPhone.js';
    //oHead.appendChild(oScript);
    //document.write('<script type="text/javascript" src="assets/js/iPhone.js"><\/script>');
}

var webview = {
    _fakeData: true,

    machineGetInfo: function () {
        var i = {};
        if (Cookies.get('app_version')) {

            if (isMobile.Android()) {
                i.uuid = Android.getuuid();
                i.gcmid = Android.GetGCMID() || 'fakegcm-gjalgjalgdshfsdgjhgakgha';
                i.sysInfo = Android.getsysteminfo();
                i.sysType = "0";
                return i;
            } else if (isMobile.iOS()) {
                //return true;
                //iphoneGetVersion();
                iPhone.machineGetInfo();
                return false;
            }

        } else {

            i.uuid = generateUUID();
            i.gcmid = "no_gcmid";
            i.sysInfo = JSON.stringify({
                appCodeName: navigator.appCodeName,
                appName: navigator.appName,
                appVersion: navigator.appVersion,
                cookieEnabled: navigator.cookieEnabled,
                language: navigator.language,
                onLine: navigator.onLine,
                platform: navigator.platform,
                userAgent: navigator.userAgent
            });
            i.sysType = "0";
            return i;

        }
        if (!webview._fakeData) {
            return false;
        }
    },

    fbLogin: function () {
        if (typeof Android !== 'undefined') {
            Android.fbLogin();
            return true;
        }
        if (isMobile.iOS()) {
            //iphoneGetVersion();
            iPhone.fbLogin();
            return true;
        }
        //fake
        /*if(!webview._fakeData){
            return false;
        }*/
        //fbSuccess();
    },

    fbLogout: function () {
        if (typeof Android !== 'undefined') {
            Android.FBLogout();
        }
        if (isMobile.iOS()) {
            iPhone.FBLogout();
        }
    },

    fbGetData: function () {
        if (typeof Android !== 'undefined') {
            return JSON.parse(Android.FBGetData());
        }
        //fake
        /*if(!webview._fakeData){
            return false;
        }*/

    },

    fbShare: function (url) {
        if (typeof Android !== 'undefined') {
            myApp.showIndicator();
            Android.FBShare(url);
            return true;
        }
        if (isMobile.iOS()) {
            myApp.showIndicator();
            iPhone.FBShare(url);
            return true;
        }
        //fake
        /*if(!webview._fakeData){
            return false;
        }*/
        fbShareSuccess('111_222');
    },

    fbLike: function (url) {
        if (typeof Android !== 'undefined') {
            myApp.showIndicator();
            Android.FBLike(url);
            return true;
        }
        if (isMobile.iOS()) {
            myApp.showIndicator();
            iPhone.FBLike(url);
            return true;
        }
    },

    fbComment: function (url, text) {
        if (typeof Android !== 'undefined') {
            Android.FBComment(url, text);
            return true;
        }
        if (isMobile.iOS()) {
            iPhone.FBComment(url, text);
            return true;
        }
    },

    fbCheck: function () {
        if (typeof Android !== 'undefined') {
            if (Android.isFBLogin() === 'yes') {
                return true;
            }
            return false;
        }
        //fake
        if (!webview._fakeData) {
            return false;
        }
        var fbSg = indexObj._dataStorage(indexObj._storage.fbInfo);
        if (fbSg) {
            return true;
        }
        return false;
    },

    nowScreenName: function (name) {
        if (typeof Android !== 'undefined') {
            Android.nowScreenName(name);
            return true;
        } else if (isMobile.iOS()) {
            iPhone.nowScreenName(name);
            return true;
        }
    },

    nowEvent: function (category, action, mdID) {
        if (typeof Android !== 'undefined') {
            Android.nowEvent(category, action, mdID);
            return true;
        } else if (isMobile.iOS()) {
            iPhone.nowEvent(category, action, mdID);
            return true;
        }
    },

    setMdId: function (md_id) {
        if (Cookies.get('app_version')) {
            if (typeof Android !== 'undefined') {
                Android.getMdID(md_id);
                return true;
            } else if (isMobile.iOS()) {
                iPhone.getMdID(md_id);
                return true;
            }
        } else {
            return true;
        }

    },

    startEvent: function (category, action, mdID) {
        if (isMobile.iOS()) {
            iPhone.startEvent(category, action, mdID);
            return true;
        }
    },

    exit: function () {
        if (typeof Android !== 'undefined') {
            Android.exit();
            return true;
        } else if (isMobile.iOS()) {
            iPhone.exit();
            return true;
        }
    },

    camera: function (cdm_id, cdd_qr, filename, FTP_HOST, FTP_PATH) {
        if (typeof Android !== 'undefined') {
            Android.openCamera(cdm_id, cdd_qr, filename, FTP_HOST, FTP_PATH);
            return true;
        } else if (isMobile.iOS()) {
            iPhone.openCamera(cdm_id, cdd_qr, filename, FTP_HOST, FTP_PATH);
            return true;
        }
    },

    branchCamera: function (FTP_HOST, FTP_PATH, sd_id, subfolder, filename) {
        if (typeof Android !== 'undefined') {
            Android.branchCamera(FTP_HOST, FTP_PATH, sd_id, subfolder, filename);
            return true;
        } else if (isMobile.iOS()) {
            iPhone.branchCamera(FTP_HOST, FTP_PATH, sd_id, subfolder, filename);
            return true;
        }
    },

    localImage: function (FTP_HOST, FTP_PATH, sd_id, subfolder, filename) {
        if (typeof Android !== 'undefined') {
            Android.localImage(FTP_HOST, FTP_PATH, sd_id, subfolder, filename);
            return true;
        } else if (isMobile.iOS()) {
            iPhone.localImage(FTP_HOST, FTP_PATH, sd_id, subfolder, filename);
            return true;
        }
    },

    deleteFileFromFTP: function (FTP_HOST, FTP_PATH, filePath) {
        if (typeof Android !== 'undefined') {
            Android.deleteFileFromFTP(FTP_HOST, FTP_PATH, filePath);
            return true;
        } else if (isMobile.iOS()) {
            var filenames = [];
            for (var i in filePath) {
                filenames.push(filePath[i].substring(filePath[i].length - 18, filePath[i].length));
            }
            iPhone.deleteFileFromFTP(FTP_HOST, FTP_PATH, filePath[0].substring(0, filePath[0].length - 19), filenames);
            return true;
        }
    },

    renameFileToMove: function (FTP_HOST, FTP_PATH, filePath, FTP_TEMPPRARY_PATH, delete_imgs) {
        if (typeof Android !== 'undefined') {
            Android.renameFileToMove(FTP_HOST, FTP_PATH, filePath, FTP_TEMPPRARY_PATH);
            return true;
        } else if (isMobile.iOS()) {
            var filename_r = [];
            for (var i in filePath) {
                filename_r.push(filePath[i].substring(filePath[i].length - 18, filePath[i].length));
            }
            var filename_d = [];
            for (var i in delete_imgs) {
                filename_d.push(delete_imgs[i].substring(delete_imgs[i].length - 18, delete_imgs[i].length));
            }
            iPhone.renameFileToMove(FTP_HOST, FTP_PATH, FTP_TEMPPRARY_PATH, filePath[0].substring(0, filePath[0].length - 19), filename_r, filename_d);
            return true;
        }
    },

    getLocation: function () {
        if (typeof Android !== 'undefined') {
            //Android.getLocation();
            return true;
        } else if (isMobile.iOS()) {
            iPhone.getLocation();
            return true;
        }
    },

    setAlarm: function (year, month, date, hour, minute, alarmIndex, cdd_qr, msg, checkAlarmTime) {
        if (isMobile.Android()) {
            Android.setAlarm(year, month, date, hour, minute, alarmIndex, cdd_qr, msg, checkAlarmTime);
            return true;
        } else if (isMobile.iOS()) {
            if (checkAlarmTime == '1') {
                hour = hour - 1;
            } else if (checkAlarmTime == '2') {
                date = date - 1;
            }
            iPhone.setAlarm(year, month, date, hour, minute, cdd_qr, msg);
            return true;
        }
    },

    closeAlarm: function (id, scg_id) {
        if (typeof Android !== 'undefined') {
            //console.log('ID: ' + parseInt(id));
            Android.closeAlarm(parseInt(id));
            return true;
        } else if (isMobile.iOS()) {
            iPhone.closeAlarm(scg_id);
            return true;
        }
    },
    //開啟登入頁面
    loginPage: function () {
        /*if (isMobile.Android()) {
            Android.loginPage();
            return true;
        } else if (isMobile.iOS()) {
            iPhone.loginPage();
            return true;
        }*/
        var mainSg = JSON.parse(localStorage.getItem('main')) || {};
        var from = localStorage.getItem('from') || '';
        window.location = 'http://' + stringObj.WEB_URL + '/Login/transform?user_info=' + encodeURIComponent(JSON.stringify(mainSg)) + '&from=' + from;
    },
    fbLogged: function () {
        if (isMobile.Android()) {
            Android.fbLogged();
            return true;
        } else if (isMobile.iOS()) {
            iPhone.fbLogged();
            return true;
        }
    },
    isCamera: function () {
        if (isMobile.Android()) {
            Android.isCamera();
            return true;
        } else if (isMobile.iOS()) {
            //iPhone.isCamera();
            return true;
        }
    },
    scan: function () {
        //掃描器
        if (isMobile.Android()) {
            Android.qrScan();
        } else if (isMobile.iOS()) {
            iPhone.StartQR();
        }
    }
};


//device call back for ios
iMachineGetInfo = function (data) {
    //console.log('iMachineGetInfo:', data);
    var i = {};
    i.uuid = data.uuid;
    i.gcmid = data.gcmid;
    i.sysInfo = JSON.stringify({
        appCodeName: navigator.appCodeName,
        appName: navigator.appName,
        appVersion: navigator.appVersion,
        cookieEnabled: navigator.cookieEnabled,
        language: navigator.language,
        onLine: navigator.onLine,
        platform: navigator.platform,
        userAgent: navigator.userAgent
    });
    i.sysType = "1";
    indexObj._machineInfoGet(i);
}


//產生唯一碼
function generateUUID() {
    var d = new Date().getTime();
    if (window.performance && typeof window.performance.now === "function") {
        d += performance.now();; //use high-precision timer if available
    }
    var uuid = 'xxxxxxxxxxxxxxxxyxxxxxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
        var r = (d + Math.random() * 16) % 16 | 0;
        d = Math.floor(d / 16);
        return (c == 'x' ? r : (r & 0x3 | 0x8)).toString(16);
    });
    return uuid;
};
