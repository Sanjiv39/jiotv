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

$search_query = "";
if(isset($_REQUEST['search'])) {
   $search_query = trim(strip_tags($_REQUEST['search']));
}

$category = "";
if(isset($_REQUEST['category'])) {
   $category = trim(strip_tags($_REQUEST['category']));
}

if(empty($category)) {
    $pageName = "Home ";
} else {
    $pageName = $category." Channels ";
}

?>
<!-- Source Code Created By UsefulToolsHub, Find us Telegram @usefultools_hub -->
<!DOCTYPE html>
<html>
<head>
    <title><?php print($pageName); ?>- JioTV Web | Software By UsefulToolsHub</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@5.1.3/dist/simplex/bootstrap.min.css"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css2/darkmode.min.css" />
    <link rel="stylesheet" href="assets/css2/main.css" />
    <link rel="stylesheet" href="assets/css2/util.css" />
    <link rel="stylesheet" href="assets/css2/usftoolshub.css?b=2213" />
    <link rel="shortcut icon" href="https://i.ibb.co/37fVLxB/f4027915ec9335046755d489a14472f2.png"/>
</head>
<body>
  <div class="usf_logoheads mt-4">
    <img class="img-fluid" src="assets/images/app_logo.png?v=1" width="450" height="" />
  </div>

  <div class="px-5 mt-5">
    <form method="get" action="index2.php">
        <input type="text" autocomplete="off" class="form-control" value="<?php print($search_query); ?>" placeholder="Type Channel Name and Press Enter" id="searchBar" name="search"/>
    </form>
  </div>
  
  <div class="px-5 mt-2" id="tvCategoryHere"></div>

    <div class="py-2 mt-5">
      <div class="container">
        <div class="row" id="channelCards">
          
        </div>

        <div align="center" class="mt-3">
        <button class="btn btn-danger loadmoretvv"> Load More </button>
        </div>

      </div>

   </div>
</div>

<script>let tv_search="<?php print($search_query); ?>"; let tv_cat="<?php print($category); ?>";</script>
<script src="assets/js/jquery.js"></script>
<script src="assets/js/crypto.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lazysizes@5.3.2/lazysizes.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@iconify/iconify@2.1.2/dist/iconify.min.js"></script>
<script src="assets/js/jioUI2.js?v=a1"></script>

</body>
<script>
$(document).ready(function(){
    start_app();
});
</script>
</body>
</html>
<!-- Source Code Created By UsefulToolsHub, Find us Telegram @usefultoolshub -->