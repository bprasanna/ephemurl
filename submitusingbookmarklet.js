function ephemurlit(){
    var xmlHttp=null;
    if (window.XMLHttpRequest) {
        xmlHttp = new window.XMLHttpRequest;
    }
    else {
        try {
            xmlHttp =  new ActiveXObject("Microsoft.XMLHTTP");
        }
        catch(ex) {
            xmlHttp = null;
        }
    }
    var url = "http://ephemurl.herokuapp.com/handleData.php";
    
    var titleIn=encodeURIComponent(document.title);
    var urlIn=encodeURIComponent(window.location.host+window.location.pathname);
    

    if(titleIn===''){
        return false;       
    }
    
    if(urlIn===''){
        return false;
    }
    
    if(xmlHttp != null){
        xmlHttp.onreadystatechange=function(){
            if (xmlHttp.readyState == 4)
            { 
                if (xmlHttp.status == 200){
                    res = xmlHttp.responseText;
                    if (res!=null){
                        alert(res);
                    } else {
                        alert("Error while submitting url. Please try again.");
                    }
                } else if (xmlHttp.status == 404){
                    alert("Request URL does not exist");
                } else {
                    alert("Error: status code is (2):" + xmlHttp.status);   
                }
            }
        };
        xmlHttp.open("POST",url,true);
        xmlHttp.setRequestHeader("Content-type","application/x-www-form-urlencoded;charset=UTF-8");
        xmlHttp.send("titleIn="+titleIn+"&urlIn="+urlIn);
        return true;
    } else {
        alert('Your browser doesn\'t support AJAX');
        return false;     
    }
}

ephemurlit();
