<!DOCTYPE html> <html> <head> <meta charset="UTF-8">
    <title>Ephemurl - Ephemeral URLs - Short living interesting links</title>
    <link href="http://getbootstrap.com/dist/css/bootstrap.min.css" rel="stylesheet"></link>
    <script src='https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>
    <script src='http://getbootstrap.com/dist/js/bootstrap.min.js'></script>
    <script src='https://cdn.firebase.com/v0/firebase.js'></script>

    <style>
       #wrapper2 {
            background: #f8f8f8;
            width:1024px;
            margin: 0 auto;
            padding: 5px;
       } 

       body {
            background: #c0c0c0;
            margin: 0 auto;
            font-family: arial, ubuntu, helvetica, sans-serif;
       }

    </style>

  </head>
  <body>
    <div id="wrapper2">

    <div class="panel panel-default">
		     <div class="panel-heading">
			    <h3 class="panel-title"><span style="color:gray">EphemURL=</span>Ephemeral+URLs</h3>
			    <b style="font-weight:bold;font-size:14px">Consider adding a page you enjoyed reading recently</b>
		     </div>
		     <div class="panel-body">
			        <input type='text' class="form-control" id='titleInput' placeholder='Page Title' title="Enter page/article title" />
			        
			        <div class="input-group">
			            <input type='text' class="form-control" id='urlInput' placeholder='Page URL' title="Enter url of the page/article, then just hit Enter" />
      					<span class="input-group-btn">
					        <button class="btn btn-primary" type="button" onclick="addURL()">Add!</button>
				        </span>
				    </div>
			        
				    
			        <div id='notifications'></div>
		     </div>
		     <div class="panel-body">
    		      <div id='urlsDiv'></div>
  		     </div>
   </div>

    <script>
    
      function trim12(stri) {
        var str = stri.replace(/^\s\s*/, ''),
          ws = /\s/,
          i = stri.length;
        while (ws.test(str.charAt(--i)));
        return str.slice(0, i + 1);
      }
      
      
      function addURL(){
          var title = $('#titleInput').val();
          var url = $('#urlInput').val();
          if ((trim12(title)) !== '' && (trim12(url) !== '')){
            myDataRef.push({title: title, url: url});
            $('#titleInput').val('');
            $('#urlInput').val('');
          } else {
            displayError();
          }      
      }
      
      var myDataRef = new Firebase('https://ephemurl.firebaseio.com/');
      $('#urlInput').keypress(function (e) {
        if (e.keyCode == 13) {
          var title = $('#titleInput').val();
          var url = $('#urlInput').val();
          if ((trim12(title)) !== '' && (trim12(url) !== '')){
            myDataRef.push({title: title, url: url});
            $('#titleInput').val('');
            $('#urlInput').val('');
          } else {
            displayError();
          }
        }
      });

      myDataRef.on('child_added', function(snapshot) {
        var newurl = snapshot.val();
        var uid = snapshot.name();
        displayURLs(uid, newurl.title, newurl.url);
      });
      
      myDataRef.on('child_removed', function(oldChildSnapshot) {
        refreshURLs();
      });



      function displayURLs(uid, name, text) {
        if(text.indexOf("http") == -1){
            $('#urlsDiv').prepend("<div class='alert alert-info' id='"+uid+"'><a class='alert-link' href='http://"+text+"' target='_blank'>"+name+"</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class='alert-link' href='#' onclick='removeURL(this.parentNode.id)'>[x]</a></div>");
        } else {
            $('#urlsDiv').prepend("<div class='alert alert-info' id='"+uid+"'><a class='alert-link' href='"+text+"' target='_blank'>"+name+"</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class='alert-link' href='#' onclick='removeURL(this.parentNode.id)'>[x]</a></div>");
        }
        
        $('#urlsDiv')[0].scrollTop = $('#urlsDiv')[0].scrollHeight;        
      };


      function removeURL(uid) {
        var urlRef = new Firebase('https://ephemurl.firebaseio.com/'+uid);
        urlRef.remove();
      };


      function refreshURLs() {
        location.reload();
      };

      function displayError() {
        document.getElementById("notifications").innerHTML = '<div class="alert alert-warning alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Attention:</strong> Please provide all required inputs.</div>';

      };
      
      function checkForPassedValues() {
      	var prmstr = window.location.search.substr(1);

		var prmarr = prmstr.split ("&");
		var params = {};

		if (prmarr.length > 1) {

			var tmparr = prmarr[0].split("=");
		    params[0] = tmparr[1];		    
			var titleIn = decodeURIComponent(params[0]);

			
			tmparr = prmarr[1].split("=");
		    params[1] = tmparr[1];
			var urlIn = decodeURIComponent(params[1]);

			
			if ((trim12(titleIn)) !== '' && (trim12(urlIn) !== '')){
			   addEntryFromBookMarklet(titleIn,urlIn);
			}
			
		}		
      }
      
      function addEntryFromBookMarklet(titleIn, urlIn) {
       if ((trim12(titleIn)) !== '' && (trim12(urlIn) !== '')){
            myDataRef.push({title: titleIn, url: urlIn});
            setInterval("reloadPage()", 5000);
          } else {
            displayError();
          }
      }
      
      $( document ).ready(function() {
         checkForPassedValues();
      });
      
      function reloadPage() {
      	window.location.href = 'http://ephemurl.herokuapp.com/';
      }
    </script>
    </div>
  </body>
</html>
