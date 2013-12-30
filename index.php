<!DOCTYPE html> <html> 
<head> <meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Ephemurl - Ephemeral URLs - Short living interesting links</title>
   <link rel="shortcut icon" type="image/png" href="glyphicons_050_link.png" />
   <link href="http://getbootstrap.com/dist/css/bootstrap.css" rel="stylesheet"></link>
   <link href='http://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'></link>
   <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
   <script src='http://getbootstrap.com/dist/js/bootstrap.min.js'></script>
   <script src='https://cdn.firebase.com/v0/firebase.js'></script>
   <style>
      .popover{
          width:320px;
       }
   </style>
</head>
<body style="font-family: 'Droid Sans',arial, ubuntu, helvetica, sans-serif;">
    <div class="container">
      <div class="header" style="border-bottom: 1px solid rgb(229, 229, 229);">
        <ul class="nav nav-pills pull-right">

          <li>
			<!-- Single button -->
			<div class="btn-group">
			  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
				Add <span class="caret"></span>
			  </button>
			  <ul class="dropdown-menu" role="menu">
				<li><a data-toggle="modal" href="#" data-target="#addurl">URL</a></li>
				<li><a data-toggle="modal" href="#" data-target="#addcategory">Category</a></li>
			  </ul>
			</div>             
          </li>
          <li>
              <button type="button" class="btn btn-normal" onclick="displayAboutApp()" title="" data-original-title="About" data-placement="bottom" id="aboutapp" data-toggle="popover" data-content="Ephemurl is a web application built using firebase to share links across browsers. The idea is to have only minimal number of interesting URLs at any point in time. When someone opens ephemurl's website newly added URLs are synched across all the sites using firebase's APIs. Old links are removed periodically to keep the data fresh. This is a proof of concept application only. Still lot more are there to explore.">About</button>
           </li>
        </ul>
        <h3 class="text-muted">EphemURL</h3>
      </div>  <!-- Header End -->


      <div class="row marketing">
         <div class="col-lg-6" style="width:20%">
            <br>
            <div id="filter">
                 <input type="text" placeholder="Search for category"  class="form-control"/>
            </div> <!-- Filter End -->
            <div id="categories">

               <ul class="list-group" id="categoriesList"></ul>

            </div> <!-- Categories End -->
         </div> <!-- Col 1 End -->
         <div class="col-lg-6" style="width:80%">
            <input type="hidden" id="currentCategory" value="" />
            <br>
            <div id='urlsDiv'></div>    
         </div> <!-- Col 2 End -->
      </div> <!-- Row End --> 

      <div class="footer" style="border-top: 1px solid rgb(229, 229, 229);">
           <span id="bmlet"></span>
      </div> <!-- Footer End -->

   
      <!-- Modal comes here -->
      <div class="modal fade" id="addurl" tabindex="-1" role="dialog" aria-labelledby="view_more" aria-hidden="true">
         <div class="modal-dialog" style="width:800px">
            <div class="modal-content">
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <h4 class="modal-title" id="myModalLabel">Add an URL you enjoyed reading recently</h4>
               </div>
               <div class="modal-body">
                  <input type='text' class="form-control" id='titleInput' placeholder='Page Title' title="Enter page/article title" />
                  <div class="input-group">                 
                     <input type='text' class="form-control" id='urlInput' placeholder='Page URL' title="Enter url of the page/article, then just hit Enter" />
                     <span class="input-group-btn">
		        <button class="btn btn-primary" type="button" onclick="addURL()">Add!</button>
		     </span>
                   </div>
                   <div id='notifications'></div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
               </div>
             </div><!-- /.modal-content -->
         </div><!-- /.modal-dialog -->
      </div><!-- /.modal -->
      <!-- Modal comes here -->
      <div class="modal fade" id="addcategory" tabindex="-1" role="dialog" aria-labelledby="view_more" aria-hidden="true">
         <div class="modal-dialog" style="width:800px">
            <div class="modal-content">
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <h4 class="modal-title" id="myModalLabel">Add a new category/group for URLs</h4>
               </div>
               <div class="modal-body">
                  <input type='text' class="form-control" id='categoryInput' placeholder='Category Name' title="Enter new category name" />
                  <div class="input-group">                 
                     <input type='text' class="form-control" id='categoryTTL' placeholder='Enter lifespan (in minutes)' title="Enter lifespan of URLs in this category (in mins)" />
                     <span class="input-group-btn">
		        <button class="btn btn-primary" type="button" onclick="addCategory()">Add!</button>
		     </span>
                   </div>
                   <div id='notifications'></div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
               </div>
             </div><!-- /.modal-content -->
         </div><!-- /.modal-dialog -->
      </div><!-- /.modal -->


   </div> <!-- Container end -->


   <!-- Script to do everything else -->
    <script>
	  var firstLoad = 0;
      function trim12(stri) {
        var str = stri.replace(/^\s\s*/, ''),
          ws = /\s/,
          i = stri.length;
        while (ws.test(str.charAt(--i)));
        return str.slice(0, i + 1);
      }
      


      var categoryDataRef = new Firebase('https://ephemurl.firebaseio.com/categories/');
      function addCategory(){
          var category = $('#categoryInput').val();
          var ttl = $('#categoryTTL').val();
          if ((trim12(category)) !== '' && (trim12(ttl) !== '')){
            categoryDataRef.push({category: category, ttl: ttl, urls:""});
            $('#categoryInput').val('');
            $('#categoryTTL').val('');
          } else {
            displayError();
          }      
      }

      $('#categoryTTL').keypress(function (e) {
        if (e.keyCode == 13) {
            addCategory();
        }
      });

      categoryDataRef.on('child_added', function(snapshot) {
        var newCategory = snapshot.val();
        var uid = snapshot.name();
        displayCategories(uid, newCategory.category, newCategory.ttl);
      });
      
      var currentCategory = document.getElementById("currentCategory").value;
      function displayCategories(uid, name, ttl) {
		if (currentCategory === "") {
           if(name==='All' && firstLoad == 0){
		      document.getElementById("currentCategory").value = uid;
		      firstLoad++;
		              $('#categoriesList').prepend("<li class='list-group-item' id='"+uid+"' style='background-color:#eeeeee;font-weight:bold'><a href='#' onclick=loadCategory('"+uid+"')>"+name+"</a> ("+ttl+" mins)</li>");
		   }			
		} else {
			if(uid === currentCategory) {
			    $('#categoriesList').prepend("<li class='list-group-item' id='"+uid+"' style='background-color:#eeeeee;font-weight:bold'><a href='#' onclick=loadCategory('"+uid+"')>"+name+"</a> ("+ttl+" mins)</li>");	
			} else {
				$('#categoriesList').prepend("<li class='list-group-item' id='"+uid+"'><a href='#' onclick=loadCategory('"+uid+"')>"+name+"</a> ("+ttl+" mins)</li>");	
			}		    
		}
		
		document.getElementById("bmlet").innerHTML = "Drag this bookmarklet link <a href=\"javascript:location.href='http://ephemurl.herokuapp.com/index.php?titleIn='+encodeURIComponent(document.title)+'&amp;urlIn='+encodeURIComponent(document.location.href)+'&amp;category='"+currentCategory+"\" target=\"_blank\">ephemurl</a> to bookmarks bar, to directly add a page to Ephemurl";
        
        $('#categoriesList')[0].scrollTop = $('#categoriesList')[0].scrollHeight;        
      };


      
      function addURL(){
          var title = $('#titleInput').val();
          var url = $('#urlInput').val();
          if ((trim12(title)) !== '' && (trim12(url) !== '')){
            urlDataRef.push({title: title, url: url});
            $('#titleInput').val('');
            $('#urlInput').val('');
          } else {
            displayError();
          }      
      }

      
      var urlDataRef = new Firebase('https://ephemurl.firebaseio.com/categories/'+currentCategory+'/urls');
      $('#urlInput').keypress(function (e) {
        if (e.keyCode == 13) {
            addURL();
        }
      });

      urlDataRef.on('child_added', function(snapshot) {
        var newurl = snapshot.val();
        var uid = snapshot.name();
        displayURLs(uid, newurl.title, newurl.url);
      });
      
      
      function loadCategory(uid) {
		  document.getElementById('currentCategory').value = uid;
		  refreshURLs();		  
	  }

      function displayURLs(uid, name, text) {
        if(text.indexOf("http") == -1){
            $('#urlsDiv').prepend("<div class='alert alert-info' id='"+uid+"'><a class='alert-link' href='http://"+text+"' target='_blank'>"+name+"</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class='alert-link' href='#' onclick='removeURL(this.parentNode.id)'>[x]</a></div>");
        } else {
            $('#urlsDiv').prepend("<div class='alert alert-info' id='"+uid+"'><a class='alert-link' href='"+text+"' target='_blank'>"+name+"</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class='alert-link' href='#' onclick='removeURL(this.parentNode.id)'>[x]</a></div>");
        }
        
        $('#urlsDiv')[0].scrollTop = $('#urlsDiv')[0].scrollHeight;        
      };


      function removeURL(uid) {
        var urlRef = new Firebase('https://ephemurl.firebaseio.com/categories/'+currentCategory+'/urls/'+uid);
        urlRef.remove();
        var diven = document.getElementById('urlsDiv');
        diven.removeChild(document.getElementById(uid));
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
			
			//Get title
			var tmparr = prmarr[0].split("=");
		    params[0] = tmparr[1];		    
			var titleIn = decodeURIComponent(params[0]);

			//Get URL
			tmparr = prmarr[1].split("=");
		    params[1] = tmparr[1];
			var urlIn = decodeURIComponent(params[1]);

			//Get category
			tmparr = prmarr[2].split("=");
		    params[2] = tmparr[1];
			var categoryIn = decodeURIComponent(params[2]);
			
			document.getElementById("currentCategory").value = categoryIn;
			
			if ((trim12(titleIn) !== '') && (trim12(urlIn) !== '') && (trim12(categoryIn) !== '')){
			   addEntryFromBookMarklet(titleIn,urlIn);
			}
			
		}		
      }
      
      function addEntryFromBookMarklet(titleIn, urlIn,categoryIn) {
		  var urlRef = new Firebase('https://ephemurl.firebaseio.com/categories/'+categoryIn+'/urls');
          urlRef.push({title: titleIn, url: urlIn});
          setInterval("reloadPage()", 5000);
      }
      
      $( document ).ready(function() {
         checkForPassedValues();
         //Check for current category
      });
      
      function reloadPage() {
      	window.location.href = 'http://ephemurl.herokuapp.com/';
      }


      var x=1;
      function displayAboutApp(){
          if(x===1){
              $('#aboutapp').popover('toggle');
              x=2;
          }
    
          if(x===2){
              document.getElementById("aboutapp").innerHTML = "Close";
              x = 3;
          } else if(x===3){
              document.getElementById("aboutapp").innerHTML = "About";
              x = 2;
          }
      }

    </script>
</body>
</html>
