<?php

error_reporting(0);

webapp_status_checker();
function webapp_status_checker()
{
  $statusFile = "app/AppData/usf_wbAppStatus";
  if(file_exists($statusFile)) {
    $readStatus = @file_get_contents($statusFile);
    if($readStatus == "off" || $readStatus == "OFF") {
      http_response_code(404);
      exit();
    }
  }
}

$id = ""; $slug = "";

if(isset($id)) {
    $id = trim(strip_tags($_REQUEST['id']));
}

if(isset($slug)) {
    $slug = trim(strip_tags($_REQUEST['slug']));
}

if(empty($id) && empty($slug))
{
    header("Location: index2.php");
    exit();
}

?>
<!-- Source Code Created By UsefulToolsHub, Find us Telegram @usefultoolshub -->
<html>

<head>
    <title>LiveTV Player 2 - JioTV Live On Web | Powered By UsefulToolsHuB</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" href="https://i.ibb.co/37fVLxB/f4027915ec9335046755d489a14472f2.png"/>
    <link rel="stylesheet" href="assets/css2/playerp.css"/>
    <meta name="referrer" content="no-referrer"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/plyr@3.6.2/dist/plyr.css" />
    <script src="https://cdn.jsdelivr.net/npm/plyr@3.6.12/dist/plyr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/hls.js@1.1.4/dist/hls.min.js"></script>
</head>

<body>
    <div id="loading" class="loading">
        <div class="loading-text">
        <span class="loading-text-words">U</span>
        <span class="loading-text-words">S</span>
        <span class="loading-text-words">F</span>
        <span class="loading-text-words">T</span>
        <span class="loading-text-words">O</span>
        <span class="loading-text-words">O</span>
        <span class="loading-text-words">L</span>
        <span class="loading-text-words">S</span>
        <span class="loading-text-words">H</span>
        <span class="loading-text-words">U</span>
        <span class="loading-text-words">B</span>
        </div>
    </div>
    <video autoplay controls crossorigin poster="" playsinline id="play_link_video">
        <source type="application/vnd.apple.mpegurl" src="" id="play_link_hls">
    </video>
</body>

<script src="assets/js/jquery.js"></script>
<script src="assets/js/crypto.js"></script>
<script src="assets/js/jioUI2.js?v=a1"></script>
<script>
setTimeout(videovisible, 3000)
function videovisible() { document.getElementById('loading').style.display = 'none'; }
$(document).ready(function(){
    player_precheck();
});
</script>
</body>

</html>
<!-- Source Code Created By UsefulToolsHub, Find us Telegram @usefultoolshub -->