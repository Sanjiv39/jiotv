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

?>
<!DOCTYPE html> 
<html>
<head>
    <meta charset="UTF-8">
    <meta name="referrer" content="no-referrer">
    <title>JioTV Live On Web | Powered By UsefulToolsHuB</title>
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin=""/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,700" media="all">
    <script src="assets/js/webfontloader.min.js"></script> 
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
    <meta name="mobile-web-app-capable" content="yes"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="theme-color" content="#000000"/>
    <link media="all" href="assets/css/webzit.css" rel="stylesheet"/>
    <script src="assets/js/invisible.js"></script>
    <link rel="stylesheet" href="assets/css/niceba.css?v=1"/>
    <link rel="shortcut icon" href="favicon.ico"/>
    <style>
      .box {
         padding-left: 10px !important;
         padding-right: 10px !important;
         padding-bottom: 0px !important;
      }
    </style>
</head>
<body class="home blog">
   <div id="dt_contenedor">
   
   <header id="header" class="main">
   <div class="hbox">
        <div class="fix-hidden">
            <div class="logo">
                <a href="index.php">
                    <img class="lazyload" src="assets/images/app_logo.png?v=<?php print(md5(time())); ?>" data-src="assets/images/app_logo.png?v=<?php print(md5(time())); ?>" />
                </a>
            </div>
            <div class="head-main-nav">
                <div class="menu-new-container">
                    <ul id="main_header" class="main-header">

                    <li id="menu-item-487" class="genres menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-2413">
                        <a href="?_=ilive">Live Channels</a> 
                        <ul class="sub-menu" id="tv_groups_mhead" style="margin-left: -10%;"></ul>
                    </li>

                    </ul>
                </div>
            </div>
            <div class="headitems register_active">
                <div id="advc-menu" class="search" style="margin-right: 10px;">
                        <form method="get" id="searchform" action="">
                           <input type="text" placeholder="Search..." name="search" id="s" value="<?php print($search_query); ?>" autocomplete="off">
                           <button class="search-button" type="submit"><span class="fas fa-search"></span></button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="live-search ltr"></div>
            </div>
         </header>
         <div class="fixheadresp">
            <header class="responsive">
               <div class="nav"><a class="aresp nav-resp"></a></div>
               <div class="search"><a class="aresp search-resp"></a></div>
               <div class="logo"> <a href=""> <img class="lazyload" src="assets/images/app_logo.png?v=<?php print(md5(time())); ?>" data-src="assets/images/app_logo.png?v=<?php print(md5(time())); ?>" /> </a> </div>
            </header>
            <div class="search_responsive">
               <form method="get" id="form-search-resp" class="form-resp-ab" action=""> <input type="text" placeholder="Search..." name="search" id="ms" value="<?php print($search_query); ?>" autocomplete="off"> <button type="submit" class="search-button"><span class="fas fa-search"></span></button></form>
               <div class="live-search"></div>
            </div>
            <div id="arch-menu" class="menuresp">
               <div class="menu">
                  <div class="user"> </div>
                  <div class="menu-new-container">
                     <ul id="main_header" class="resp">

                     <li id="menu-item-487" class="genres menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-2413">
                           <a href="?_=ilive">Live Channels</a> 
                           <ul class="sub-menu" id="menu_items_tvn"></ul>
                        </li>

                     </ul>
                  </div>
               </div>
        </div>
    </div>

      <div id="contenedor">
         <div class="login_box">
            <div class="box">
                <a id="c_loginbox"><i class="fas fa-times" onclick="suspendPlayer()"></i></a><h3 class="majoro"> Loading ... </h3>
                
                <div id="vplayer" style="height: auto; text-align: center; margin-top: 40px !important;"></div>
            </div>
         </div>
        
         
         <div id="contenedor">
            <div class="login_box">
               <div class="box">
                <a id="c_loginbox"><i class="fas fa-times" onclick="suspendPlayer()"></i></a><h3 class="majoro"> Loading ... </h3>
                
                <div id="vplayer" style="height: auto; text-align: center; margin-top: 40px !important;"></div>
               </div>
            </div>
            <div class="module">
               <div class="content right full">
                  <h1 class="heading-archive"><?php if(!empty($search_query)){ print('Search - <span id="fauji">'.$search_query.'</span>&nbsp;|&nbsp;'); }else { if(!empty($category)){ print($category.'&nbsp;|&nbsp;'); }} ?>Live TV</h1>
                  <div class="desc_category"></div>
                  <header>
                     <h2>Live TV</h2>
                     <span><a class="see-all" id="princimi"></a></span> 
                  </header>
                  <div class="items full" id="xn_nv_tv">
                  
                  </div>
                  <button class="loadmoretvv">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="assets/icons/rolling-swhite.svg" width='21' height='21'/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>
                  
               </div>
               <div class="sidebar right scrolling">
                  <div class="fixed-sidebar-blank">
                     <div class="dt_mainmeta">
                        <nav class="releases">
                        <h2>TV Categories</h2>
                           <ul class="releases falsescroll" id="right_nav_livex"></ul>
                        </nav>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         
   <footer class="main">
   <div class="fbox">
      <div class="fcmpbox">
         <div class="primary">
            <div class="columenu">
            <div class="item footer_creds_buymtree_holder">
               <h3>Useful Links</h3>
                  <div class="menu-footer-pro-1-container">
                     <ul id="menu-footer-pro-1" class="menu">
                        <li id="menu-item-24907" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-24907"><a target="_blank" rel="noopener" href="" style="font-weight:bold;" class="footer_creds_buymtree_link"></a></li>
                     </ul>
                  </div>
               </div>
            </div>
            <div class="fotlogo">
               <div class="logo"> <img class=" ls-is-cached lazyloaded" src="assets/images/app_logo.png?v=<?php print(time()); ?>" data-src="assets/images/iptv_white.png?v=1668972251" alt=""></div>
                  <div class="text">
                     <p>Best IPTV Service Provider in the world. We provide streaming solution for you with full stable streams 99.9% guaranteed.</p>
                  </div>
            </div>
         </div>
         <div class="copy">Powered by UsefulToolsHub</div>
         <span class="top-page"><a id="top-page"><i class="fas fa-angle-up"></i></a></span> 
      </div>
   </div>
</footer>
</div>

<script>let tv_search="<?php print($search_query); ?>"; let tv_cat="<?php print($category); ?>";</script>
<script src="assets/js/jquery.js"></script>
<script src="assets/js/crypto.js"></script>
<script src="assets/js/jioUI1.js?v=a1"></script>

<script>
var dtGonza = [];
window.lazySizesConfig=window.lazySizesConfig||{};window.lazySizesConfig.loadMode=1;
jQuery(document).ready(function($)
{
    start_app();
    $(".reset").click(function(event){ if (!confirm( dtGonza.reset_all )) { event.preventDefault() } });
    $(".addcontent").click(function(event){ if(!confirm(dtGonza.manually_content)){ event.preventDefault() } });
});
</script> 
<script async data-noptimize="1" src="assets/js/lazysizes.min.js"></script> 
<div id="oscuridad"></div>
<script src="assets/js/de9f950debfefa.js"></script>
</body>