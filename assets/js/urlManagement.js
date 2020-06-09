class Url{
    static getFull(){
        return window.location;
    }
    static getFullRaw(){
        return window.location.href;

    }

    static setNew(newUrl){
        history.pushState(null, null, newUrl);
    }

    static decoder(url){
        return decodeURIComponent(url.replace(/\+/g, ' '));
    }

    static clean(){
        this.setNew(window.location.pathname);
    }
    static getUrlDeleteOneParameter(url, paramName){
        var regExpre = new RegExp("&?" + paramName + "=([^&]$|[^&]*)", "i");
        var result = url.replace(regExpre, "");
        return result;

    }

    static getUrlGetParam(variable) {
        var query = window.location.search.substring(1);
        var vars = query.split("&");

        for (var i = 0; i < vars.length; i++) {
            var pair = vars[i].split("=");
            if (pair[0] == variable) {
                return pair[1];
            }
        }
        return false;

    }

    static addParam(url, param){
        var conector = '';
        if(url.indexOf("?") > 0){
            conector = '&';
        }
        else{
            conector = '?';
        }

        return url + conector + param;
    }

    static redirect(url){
        window.location.href = url;
    }
}
