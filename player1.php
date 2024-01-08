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
    header("Location: index.php");
    exit();
}

?>
<html>
<head>
<title>LiveTV Player - JioTV Live On Web | Powered By UsefulToolsHuB</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="referrer" content="no-referrer"/>
<link href="https://fonts.googleapis.com/css?family=Poppins|Quattrocento+Sans" rel="stylesheet" />
<script src='https://content.jwplatform.com/libraries/IDzF9Zmk.js'></script>
<link rel="shortcut icon" href="https://i.ibb.co/37fVLxB/f4027915ec9335046755d489a14472f2.png"/>
</head>
<style>
    html {
        margin: 0;
        padding: 0;
        background-color: #000000;
    }

    #mpdvidply
    {
        position:reletive;
        width:100%!important;
        height:100%!important
    }


    .loading {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #000;
        z-index: 9999;
    }

    .loading-text {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        margin: auto;
        text-align: center;
        width: 100%;
        height: 100px;
        line-height: 100px;
    }

    .loading-text span {
        display: inline-block;
        margin: 0 5px;
        color: #d9230f;
        font-family: 'Quattrocento Sans', sans-serif;
    }

    .loading-text span:nth-child(1) {
        filter: blur(0px);
        animation: blur-text 1.5s 0s infinite linear alternate;
    }

    .loading-text span:nth-child(2) {
        filter: blur(0px);
        animation: blur-text 1.5s 0.2s infinite linear alternate;
    }

    .loading-text span:nth-child(3) {
        filter: blur(0px);
        animation: blur-text 1.5s 0.4s infinite linear alternate;
    }

    .loading-text span:nth-child(4) {
        filter: blur(0px);
        animation: blur-text 1.5s 0.6s infinite linear alternate;
    }

    .loading-text span:nth-child(5) {
        filter: blur(0px);
        animation: blur-text 1.5s 0.8s infinite linear alternate;
    }

    .loading-text span:nth-child(6) {
        filter: blur(0px);
        animation: blur-text 1.5s 1s infinite linear alternate;
    }

    .loading-text span:nth-child(7) {
        filter: blur(0px);
        animation: blur-text 1.5s 1.2s infinite linear alternate;
    }

    .loading-text span:nth-child(8) {
        filter: blur(0px);
        animation: blur-text 1.5s 1.4s infinite linear alternate;
    }

    .loading-text span:nth-child(9) {
        filter: blur(0px);
        animation: blur-text 1.5s 1.6s infinite linear alternate;
    }

    @keyframes blur-text {
        0% {
            filter: blur(0px);
        }

        100% {
            filter: blur(4px);
        }
    }

</style>
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
<video autoplay id="mpdvidply"></video>
</video>

<script src="assets/js/jquery.js"></script>
<script src="assets/js/crypto.js"></script>
<script src="assets/js/jioUI1.js?v=a1"></script>
<script>
    $(document).ready(function(){
        render_player_1();
    });
</script>
</body>
</html>