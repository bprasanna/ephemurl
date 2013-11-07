<?php
$titleVal = pg_escape_string($_POST["titleIn"]); 
$urlVal = pg_escape_string($_POST["urlIn"]); 

echo $titleVal;

?>
