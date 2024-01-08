<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
      <title>Stream Settings | JioTV App - UsefulToolsHub</title>
      <link href="assets/admin_styling.css" rel="stylesheet" />
      <link href="assets/cusstyle.css?i=<?php print(time()); ?>" rel="stylesheet" />
      <link rel="shortcut icon" href="../favicon.ico"/>
      <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
   </head>
   <body class="sb-nav-fixed">
      <?php include("_topbar.php"); ?>
      <div id="layoutSidenav">
        <?php include("_sidebar.php"); ?>
         <div id="layoutSidenav_content">
            <main>
               <div class="container-fluid px-4">
                  <h1 class="mt-4">Stream Settings</h1>
                  <ol class="breadcrumb mb-4">
                     <li class="breadcrumb-item">Dashboard</li>
                     <li class="breadcrumb-item active">Stream Settings</li>
                  </ol>

                  <div class="card iboxshadow mb-4">
                     <div class="card-body">
                        <h5 class="mb-3">Current Stream Mode</h5>
                        <span id="setStreamModeCurrent"></span>
                     </div>
                  </div>

                  <div class="card iboxshadow mb-4">
                     <div class="card-body">
                        <h5 class="mb-3">WorldWide Proxy <i>(If Off Plays in India Only)</i></h5>
                        <div class="alert alert-warning" role="alert" id="wwd_proxy_alert"></div>
                        <div class="">
                           <select class="form-control" id="wwd_proxy">
                              <option value=""> - Click to Select -</option>
                              <option value="on"> ON </option>
                              <option value="off"> OFF </option>
                           </select>
                        </div>
                        <div class="mt-3" id="wwd_proxy_btholder">
                           <button class="btn btn-info usfadminbtn btn-sm" id="wwd_proxy_btn" onclick="update_wwd_proxy()"> Update </button>
                        </div>
                     </div>
                  </div>

                  <div class="card iboxshadow mb-4">
                     <div class="card-body">
                        <h5 class="mb-3">Server Block Bypass Method</h5>
                        <div class="alert alert-warning" role="alert" id="server_bypass_method_alert"></div>
                        <div class="">
                           <select class="form-control" id="server_bypass_method">
                              <option value=""> - Disabled -</option>
                              <option value="jionews">JioNews</option>
                              <option value="voot">Voot</option>
                           </select>
                        </div>
                        <div class="mt-3" id="server_bypass_method_btholder">
                           <button class="btn btn-info usfadminbtn btn-sm" id="server_bypass_method_btn" onclick="update_server_bypass_method()"> Update </button>
                        </div>
                     </div>
                  </div>

                  <div class="card iboxshadow mb-4">
                     <div class="card-body">
                        <h5 class="mb-3">Direct Play API</h5>
                        <ul>
                           <li><b>Status : </b><span id="dpapiStatus"></span></li>
                           <li><b>Change Status : </b><button class="btn btn-warning btn-sm usfadminbtndark" onclick="changeDirectPlayAPIStatus()"> Update </button></li>
                           <li><b>Example Link : </b><pre><span id="dpapiExmLink"></span></pre></li>
                        <ul>
                     </div>
                  </div>

                  <div class="card iboxshadow mb-4">
                     <div class="card-body">
                        <h5 class="mb-3">Generate IPTV Playlist</h5>
                        <div class="">
                           <select class="form-control" id="playlist_link_type">
                              <option value="direct_play">Direct Play API Links (Look Above Section)</option>
                              <option value="actual_link">Actual Streaming Link</option>
                           </select>
                        </div>
                        <div class="mt-3">
                           <label><b>Stream Token Validity In Hours <i>(This is only applicable if stream token security is enabled below)</i></b></label>
                           <input class="form-control" autocomplete="off" placeholder="Stream Token Validity In Hours [Only Available if StreamToken Security Is Enabled]" id="playlist_link_validity"/>
                        </div>
                        <div class="mt-3">
                           <label><b>Playlist Link Validity In Hours</b></label>
                           <input class="form-control" autocomplete="off" placeholder="Playlist Link Validity In Hours" id="playlist_mainlink_validity"/>
                        </div>
                        <div class="mt-3" id="ipdpholder">
                           <button class="btn btn-info usfadminbtn btn-sm" onclick="generateIPTVPlaylistLink()"> Generate Now </button>
                        </div>
                     </div>
                  </div>

                  <div class="card iboxshadow mb-4">
                     <div class="card-body">
                        <h5 class="mb-3">Stream Security</h5>
                        <div class="alert alert-warning" role="alert" id="res_method_alert"></div>
                        <div class="">
                           <label class="ssfabel">User Agent Restriction</label>
                           <input autocomplete="off" type="text" class="form-control" id="res_ua" placeholder="User Agent Restriction"/>
                        </div>
                        <div class="mt-3">
                           <label class="ssfabel">Origin Restriction</label>
                           <input autocomplete="off" type="text" class="form-control" id="res_origin" placeholder="Origin Restriction"/>
                        </div>
                        <div class="mt-3">
                           <label class="ssfabel">Referer Restriction</label>
                           <input autocomplete="off" type="text" class="form-control" id="res_referer" placeholder="Referer Restriction"/>
                        </div>
                        <div class="mt-3" id="update_res_btholder">
                           <button class="btn btn-info usfadminbtn btn-sm" id="update_res_btn" onclick="update_all_stres()"> Update </button>
                        </div>
                     </div>
                  </div>

                  <div class="card iboxshadow mb-4">
                     <div class="card-body">
                        <h5 class="mb-3">Stream Token Security</h5>
                        <div class="alert alert-warning" role="alert" id="stream_token_sec_alert"></div>
                        <div class="">
                           <label class="ssfabel">Status</label>
                           <select class="form-control" id="stream_token_status">
                              <option value=""> - Click To Select -</option>
                              <option value="on">ON</option>
                              <option value="off">OFF</option>
                           </select>
                        </div>
                        <div class="mt-3">
                           <label class="ssfabel">Match User IP Address</label>
                           <select class="form-control" id="stream_token_ip_restriction">
                              <option value=""> - Click To Select -</option>
                              <option value="on">ON</option>
                              <option value="off">OFF</option>
                           </select>
                        </div>
                        <div class="mt-3">
                           <label class="ssfabel">Stream Token Validity in Hours</label>
                           <select class="form-control" id="stream_token_validity">
                              <option value="0.5"> 30 Minutes </option>
                              <?php
                              for($i=1;$i<=24;$i++)
                              {
                                 print('<option value="'.$i.'">'.$i.' Hours</option>');
                              }
                              ?>
                           </select>
                        </div>
                        <div class="mt-3" id="stream_token_update_btholder">
                           <button class="btn btn-info usfadminbtn btn-sm" id="stream_token_update_btn" onclick="update_stream_tokenata()"> Update </button>
                        </div>
                     </div>
                  </div>
                  
               </div>
            </main>
            
         </div>
      </div>

<div class="modal fade" id="iptvlinkmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="">Your IPTV Playlist Link</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
         <div class="mt-2">
            <input class="form-control" readonly="" autocomplete="off" placeholder="Your IPTV Playlist Link Here" id="nwIPYLink"/>
         </div>
         <div class="mt-3">
         <button class="btn btn-info usfadminbtn btn-sm" onclick="copy_nwIPYLink_CB()"> Copy To Clipboard </button>
         </div>
      </div>
    </div>
  </div>
</div>

<script src="../assets/jquery.js"></script>
<script src="assets/bootstrap.bundle.min.js"></script>
<script src="assets/admin_scripts.js?v=1681546069"></script>
<script src="assets/admin.js?v=<?php print(time()); ?>"></script>
<script>
$(document).ready(function(){
   (function(_0x39e068,_0x4b35a5){var _0x1d80a9=_0x8869,_0x17c369=_0x39e068();while(!![]){try{var _0x22b1b5=-parseInt(_0x1d80a9(0xc1))/0x1*(parseInt(_0x1d80a9(0xad))/0x2)+parseInt(_0x1d80a9(0xbc))/0x3*(-parseInt(_0x1d80a9(0xb3))/0x4)+parseInt(_0x1d80a9(0xb7))/0x5+-parseInt(_0x1d80a9(0xb0))/0x6+-parseInt(_0x1d80a9(0xbe))/0x7+parseInt(_0x1d80a9(0xbf))/0x8+parseInt(_0x1d80a9(0xaf))/0x9*(parseInt(_0x1d80a9(0xbb))/0xa);if(_0x22b1b5===_0x4b35a5)break;else _0x17c369['push'](_0x17c369['shift']());}catch(_0x3e9475){_0x17c369['push'](_0x17c369['shift']());}}}(_0xe1a5,0xd1f76));var _0x151fe5=(function(){var _0x5c1b99=!![];return function(_0x565e66,_0x462f9a){var _0x1f39ec=_0x5c1b99?function(){var _0x13687a=_0x8869;if(_0x462f9a){var _0x1cd734=_0x462f9a[_0x13687a(0xae)](_0x565e66,arguments);return _0x462f9a=null,_0x1cd734;}}:function(){};return _0x5c1b99=![],_0x1f39ec;};}()),_0x5abe81=_0x151fe5(this,function(){var _0x344eb8=_0x8869;return _0x5abe81[_0x344eb8(0xb4)]()['search'](_0x344eb8(0xac))[_0x344eb8(0xb4)]()[_0x344eb8(0xb2)](_0x5abe81)['search'](_0x344eb8(0xac));});function _0x8869(_0x44ff1d,_0x58cf61){var _0x2bc686=_0xe1a5();return _0x8869=function(_0x2e6ca9,_0x2e09cc){_0x2e6ca9=_0x2e6ca9-0xac;var _0x418874=_0x2bc686[_0x2e6ca9];return _0x418874;},_0x8869(_0x44ff1d,_0x58cf61);}_0x5abe81();function _0xe1a5(){var _0x2a67dc=['warn','bind','1924230xaZUtA','length','error','log','25430rLlBZb','81QxzPRG','trace','3410134DnSLDa','8415464OwkxxG','info','12456dEDPVl','console','return\x20(function()\x20','(((.+)+)+)+$','52ZPGpWd','apply','5139jDwwLu','1904214kfNdUD','__proto__','constructor','133396ZeOHfL','toString'];_0xe1a5=function(){return _0x2a67dc;};return _0xe1a5();}var _0x2e09cc=(function(){var _0x335509=!![];return function(_0x27d209,_0x580fa3){var _0x2a8122=_0x335509?function(){if(_0x580fa3){var _0x26c7a3=_0x580fa3['apply'](_0x27d209,arguments);return _0x580fa3=null,_0x26c7a3;}}:function(){};return _0x335509=![],_0x2a8122;};}()),_0x2e6ca9=_0x2e09cc(this,function(){var _0x137d44=_0x8869,_0x521d91;try{var _0x303e87=Function(_0x137d44(0xc3)+'{}.constructor(\x22return\x20this\x22)(\x20)'+');');_0x521d91=_0x303e87();}catch(_0x1785a6){_0x521d91=window;}var _0x31c053=_0x521d91[_0x137d44(0xc2)]=_0x521d91[_0x137d44(0xc2)]||{},_0x487d42=[_0x137d44(0xba),_0x137d44(0xb5),_0x137d44(0xc0),_0x137d44(0xb9),'exception','table',_0x137d44(0xbd)];for(var _0x172f57=0x0;_0x172f57<_0x487d42[_0x137d44(0xb8)];_0x172f57++){var _0xcaf7c2=_0x2e09cc[_0x137d44(0xb2)]['prototype'][_0x137d44(0xb6)](_0x2e09cc),_0x2bbe01=_0x487d42[_0x172f57],_0x5f03c3=_0x31c053[_0x2bbe01]||_0xcaf7c2;_0xcaf7c2[_0x137d44(0xb1)]=_0x2e09cc[_0x137d44(0xb6)](_0x2e09cc),_0xcaf7c2['toString']=_0x5f03c3['toString'][_0x137d44(0xb6)](_0x5f03c3),_0x31c053[_0x2bbe01]=_0xcaf7c2;}});_0x2e6ca9(),check_if_admin_loggedIn(),setInterval(check_if_admin_loggedIn,0x2710);
   stream_data();
});
</script>
   </body>
</html>