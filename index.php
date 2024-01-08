<?php

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

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome To JioTV Web | Powered By UsefulToolsHub</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"/>
  <link rel="shortcut icon" href="https://i.ibb.co/37fVLxB/f4027915ec9335046755d489a14472f2.png"/>
  <style>@import url('https://fonts.googleapis.com/css2?family=Oswald&display=swap'); body { font-family: 'Oswald', sans-serif; color: #FFFFFF; background-color: #000000; font-weight: bold;} .container { margin-top: 10px !important; } .UiTabz { padding: 10px; } .IStBd { border: 2px solid #28EBA4; }</style>
</head>
<body>
  <div class="container">
    <h3>Select JioTV UI</h3>
    <div class="row">
      <div class="col-md-6 UiTabz">
        <a href="index1.php">
          <img src="assets/images/jioStyle1.png" class="img-responsive IStBd" alt="JioTV UserInterface I"/>
        </a>
      </div>
      <div class="col-md-6 UiTabz">
        <a href="index2.php">
          <img src="assets/images/jioStyle2.png" class="img-responsive IStBd" alt="JioTV UserInterface II"/>
        </a>
      </div>
    </div>
    <pre style="margin-top: 12px;">Powered By UsefulToolsHub</pre>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>
</html>
