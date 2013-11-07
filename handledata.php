<?php
$titleVal = pg_escape_string($_POST["titleIn"]); 
$urlVal = pg_escape_string($_POST["urlIn"]); 

// Get cURL resource
$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'https://ephemurl.firebaseio.com/messages.json',
    CURLOPT_USERAGENT => 'Post data to firebase using cURL',
    CURLOPT_POST => 1,
    CURLOPT_POSTFIELDS => array(
        titleIn => $titleVal,
        urlIn => $urlVal
    )
));
// Send the request & save response to $resp
$resp = curl_exec($curl);
// Close request to clear up some resources
curl_close($curl);
?>
