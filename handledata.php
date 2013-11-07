<?php
$titleVal = pg_escape_string($_POST["titleIn"]); 
$urlVal = pg_escape_string($_POST["urlIn"]); 

// Get cURL resource
//Referred: http://codular.com/curl-with-php
$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'https://ephemurl.firebaseio.com/urls.json',
    CURLOPT_USERAGENT => 'Posting data using cURL',
    CURLOPT_POST => 1,
    CURLOPT_POSTFIELDS => array(
        title => $titleVal,
        url => $urlVal
    )
));

// Send the request & save response to $resp
$resp = curl_exec($curl);

// Close request to clear up some resources
curl_close($curl);

?>
