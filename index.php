<?php

$url = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'].'public/';
/* Redirect browser */
header("Location: ".$url);
/* Make sure that code below does not get executed when we redirect. */
exit;

?>