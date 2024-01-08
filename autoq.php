<?php

error_reporting(0);

header("Access-Control-Allow-Origin: *");

$c = $_REQUEST['c'] ?? $_REQUEST['id'] ?? null;

if(empty($c)) {
    http_response_code(400);
    exit("Mandatory Parameter Missing");
}

header("Location: app/webapi.php?action=direct_play&c={$c}&e=.m3u8", true, 307);
exit();

?>
