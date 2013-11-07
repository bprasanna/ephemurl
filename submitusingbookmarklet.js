function handleurl() {
var i=document.createElement('iframe');
i.setAttribute('name', 'ipb295319');
i.setAttribute('id', 'ipb295319');
i.setAttribute('allowtransparency', 'true');
i.setAttribute('style', 'border: 0; width: 1px; height: 1px; position: absolute; left: 0; top: 0;');
document.body.appendChild(i);
var titleIn = encodeURIComponent(document.title);
var urlIn = encodeURIComponent(window.location.host+window.location.pathname);
titleIn = encodeURIComponent(titleIn).replace(/'/g, '%27');
urlIn = encodeURIComponent(urlIn).replace(/'/g, '%27');
window.frames['ipb295319'].document.write(
'<!DOCTYPE html><html><head><meta charset="UTF-8"></head><body style="background-color: transparent;">' +
'<form action="http://ephemurl.herokuapp.com/handledata.php" method="get" id="urlform" accept-charset="utf-8">' +
'<input type="hidden" name="titleIn" id="titleIn" value=""/>' +
'<input type="hidden" name="urlIn" id="urlIn" value=""/>' +
'<input type="hidden" name="id" value="ipb295319"/>' +

'</form>' +
"<script>var e=encodeURIComponent,w=window,d=document,f=d.getElementById('urlform');" +
"d.getElementById('titleIn').value=decodeURIComponent('" + titleIn + "');d.getElementById('urlIn').value=decodeURIComponent('" + urlIn + "');" +
"f.submit();" +
"</script></body></html>"
);
}

handleurl();

