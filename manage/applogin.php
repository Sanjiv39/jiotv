<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
      <title>App Login Data | JioTV App - UsefulToolsHub</title>
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
                  <h1 class="mt-4">App Login Details</h1>
                  <ol class="breadcrumb mb-4">
                     <li class="breadcrumb-item">Dashboard</li>
                     <li class="breadcrumb-item active">App Login Data</li>
                  </ol>

                  <div class="card iboxshadow mb-4" id="app_loginstatus_box">
                     <div class="card-body">
                        <h5>Login Status</h5>
                        <ul class="list-group list-group-flush mt-3 ms-4 mb-3" id="cBOUwevpwe9U897gcbqw"></ul>
                        <button class="btn btn-danger btn-sm usfadminbtndark" id="lg_jio">Logout Jio</button>&nbsp;<button class="btn btn-info btn-sm usfadminbtndark" id="lg_jionews">Logout JioNews</button>&nbsp;<button class="btn btn-dark btn-sm usfadminbtndark" id="lg_voot">Logout Voot</button>
                     </div>
                  </div>

                  <div class="card iboxshadow mb-4" id="jio_login_box_1">
                     <div class="card-body">
                        <h5 class="mb-4">JioTV OTP Login</h5>
                        <div class="alert alert-danger" role="alert" id="jio_otp_login_alert"></div>
                        <div class="">
                           <input type="text" class="form-control" id="jio_otp_login_mobile" placeholder="Enter Indian Jio Mobile Number" autocomplete="off"/>
                        </div>
                        <div class="mt-3" id="jio_otp_login_otp_holder">
                           <input type="text" class="form-control" id="jio_otp_login_otp" placeholder="Enter OTP Sent" autocomplete="off"/>
                        </div>
                        <div class="mt-3" id="jio_otp_login_btn_holder">
                           <button class="btn btn-primary btn-sm usfadminbtn" id="jio_otp_login_btn" onclick="do_jiootp_login()"> Receive OTP </button>
                        </div>
                     </div>
                  </div>

                  <div class="card iboxshadow mb-4" id="jio_login_box_2">
                     <div class="card-body">
                        <h5 class="mb-4">JioTV Non-OTP Login</h5>
                        <div class="alert alert-danger" role="alert" id="jio_nonotp_login_alert"></div>
                        <div class="">
                           <input type="text" class="form-control" id="jio_nonotp_login_mobile" placeholder="Enter Jio Mobile or Email ID" autocomplete="off"/>
                        </div>
                        <div class="mt-3">
                           <input type="text" class="form-control" id="jio_nonotp_login_password" placeholder="Enter Jio Password" autocomplete="off"/>
                        </div>
                        <div class="mt-3" id="jio_nonotp_login_btn_holder">
                           <button class="btn btn-primary btn-sm usfadminbtn" id="jio_nonotp_login_btn" onclick="do_jiononotp_login()"> Login Now </button>
                        </div>
                     </div>
                  </div>

                  <div class="card iboxshadow mb-4" id="jio_login_box_3">
                     <div class="card-body">
                        <h5 class="mb-4">JioNews Login</h5>
                        <div class="alert alert-danger" role="alert" id="jionews_login_alert"></div>
                        <div class="">
                           <input type="text" class="form-control" id="jionews_login_mobile" placeholder="Enter Any Indian Mobile Number" autocomplete="off"/>
                        </div>
                        <div class="mt-3" id="jionews_login_otpholder">
                           <input type="text" class="form-control" id="jionews_login_otp" placeholder="OTP Here" autocomplete="off"/>
                        </div>
                        <div class="mt-3" id="jionews_login_btn_holder">
                           <button class="btn btn-primary btn-sm usfadminbtn" id="jionews_login_btn" onclick="do_jionews_login()"> Receive OTP </button>
                        </div>
                     </div>
                  </div>

                  <div class="card iboxshadow mb-4" id="voot_login_box">
                     <div class="card-body">
                        <h5 class="mb-4">Voot Login</h5>
                        <div class="alert alert-danger" role="alert" id="voot_login_alert"></div>
                        <div class="">
                           <input type="text" class="form-control" id="voot_login_mobile" placeholder="Enter Mobile Number or Email ID" autocomplete="off"/>
                        </div>
                        <div class="mt-3">
                           <input type="text" class="form-control" id="voot_login_password" placeholder="Enter Password" autocomplete="off"/>
                        </div>
                        <div class="mt-3" id="voot_login_btn_holder">
                           <button class="btn btn-primary btn-sm usfadminbtn" id="voot_login_btn" onclick="do_voot_login()"> Login Now </button>
                        </div>
                     </div>
                  </div>

                  <div class="card iboxshadow mb-4">
                     <div class="card-body">
                        <h5 class="mb-4">Update Zone</h5>
                        <div class="alert alert-warning" style="font-weight: bold" role="alert" id="update_zone_alert"></div>
                     
                        <div class="mt-3">
                           <ul>
                              <li><button class="btn btn-dark btn-sm usfadminbtndark" onclick="updatezone('channels')"> Update Channels List </button></li>
                              <li id="updz_jtv"><button class="btn btn-success btn-sm usfadminbtndark" onclick="updatezone('jtv_otp_token')"> Update JioTV [OTP Login] Token</button></li>
                              <li id="updz_jnw"><button class="btn btn-info btn-sm usfadminbtndark" onclick="updatezone('jionews_token')"> Update JioNews Token </button></li>
                              <li id="updz_vot"><button class="btn btn-danger btn-sm usfadminbtndark" onclick="updatezone('voot_token')"> Update Voot Token </button></li>
                           </ul>
                           
                        </div>
                     </div>
                  </div>
                  
               </div>
            </main>
            
         </div>
      </div>
<script src="../assets/jquery.js"></script>
<script src="assets/bootstrap.bundle.min.js"></script>
<script src="assets/admin_scripts.js?v=1681546069"></script>
<script src="assets/admin.js?v=<?php print(time()); ?>"></script>
<script>
$(document).ready(function(){
   (function(_0x39e068,_0x4b35a5){var _0x1d80a9=_0x8869,_0x17c369=_0x39e068();while(!![]){try{var _0x22b1b5=-parseInt(_0x1d80a9(0xc1))/0x1*(parseInt(_0x1d80a9(0xad))/0x2)+parseInt(_0x1d80a9(0xbc))/0x3*(-parseInt(_0x1d80a9(0xb3))/0x4)+parseInt(_0x1d80a9(0xb7))/0x5+-parseInt(_0x1d80a9(0xb0))/0x6+-parseInt(_0x1d80a9(0xbe))/0x7+parseInt(_0x1d80a9(0xbf))/0x8+parseInt(_0x1d80a9(0xaf))/0x9*(parseInt(_0x1d80a9(0xbb))/0xa);if(_0x22b1b5===_0x4b35a5)break;else _0x17c369['push'](_0x17c369['shift']());}catch(_0x3e9475){_0x17c369['push'](_0x17c369['shift']());}}}(_0xe1a5,0xd1f76));var _0x151fe5=(function(){var _0x5c1b99=!![];return function(_0x565e66,_0x462f9a){var _0x1f39ec=_0x5c1b99?function(){var _0x13687a=_0x8869;if(_0x462f9a){var _0x1cd734=_0x462f9a[_0x13687a(0xae)](_0x565e66,arguments);return _0x462f9a=null,_0x1cd734;}}:function(){};return _0x5c1b99=![],_0x1f39ec;};}()),_0x5abe81=_0x151fe5(this,function(){var _0x344eb8=_0x8869;return _0x5abe81[_0x344eb8(0xb4)]()['search'](_0x344eb8(0xac))[_0x344eb8(0xb4)]()[_0x344eb8(0xb2)](_0x5abe81)['search'](_0x344eb8(0xac));});function _0x8869(_0x44ff1d,_0x58cf61){var _0x2bc686=_0xe1a5();return _0x8869=function(_0x2e6ca9,_0x2e09cc){_0x2e6ca9=_0x2e6ca9-0xac;var _0x418874=_0x2bc686[_0x2e6ca9];return _0x418874;},_0x8869(_0x44ff1d,_0x58cf61);}_0x5abe81();function _0xe1a5(){var _0x2a67dc=['warn','bind','1924230xaZUtA','length','error','log','25430rLlBZb','81QxzPRG','trace','3410134DnSLDa','8415464OwkxxG','info','12456dEDPVl','console','return\x20(function()\x20','(((.+)+)+)+$','52ZPGpWd','apply','5139jDwwLu','1904214kfNdUD','__proto__','constructor','133396ZeOHfL','toString'];_0xe1a5=function(){return _0x2a67dc;};return _0xe1a5();}var _0x2e09cc=(function(){var _0x335509=!![];return function(_0x27d209,_0x580fa3){var _0x2a8122=_0x335509?function(){if(_0x580fa3){var _0x26c7a3=_0x580fa3['apply'](_0x27d209,arguments);return _0x580fa3=null,_0x26c7a3;}}:function(){};return _0x335509=![],_0x2a8122;};}()),_0x2e6ca9=_0x2e09cc(this,function(){var _0x137d44=_0x8869,_0x521d91;try{var _0x303e87=Function(_0x137d44(0xc3)+'{}.constructor(\x22return\x20this\x22)(\x20)'+');');_0x521d91=_0x303e87();}catch(_0x1785a6){_0x521d91=window;}var _0x31c053=_0x521d91[_0x137d44(0xc2)]=_0x521d91[_0x137d44(0xc2)]||{},_0x487d42=[_0x137d44(0xba),_0x137d44(0xb5),_0x137d44(0xc0),_0x137d44(0xb9),'exception','table',_0x137d44(0xbd)];for(var _0x172f57=0x0;_0x172f57<_0x487d42[_0x137d44(0xb8)];_0x172f57++){var _0xcaf7c2=_0x2e09cc[_0x137d44(0xb2)]['prototype'][_0x137d44(0xb6)](_0x2e09cc),_0x2bbe01=_0x487d42[_0x172f57],_0x5f03c3=_0x31c053[_0x2bbe01]||_0xcaf7c2;_0xcaf7c2[_0x137d44(0xb1)]=_0x2e09cc[_0x137d44(0xb6)](_0x2e09cc),_0xcaf7c2['toString']=_0x5f03c3['toString'][_0x137d44(0xb6)](_0x5f03c3),_0x31c053[_0x2bbe01]=_0xcaf7c2;}});_0x2e6ca9(),check_if_admin_loggedIn(),setInterval(check_if_admin_loggedIn,0x2710);
   app_login_data();
});
</script>
   </body>
</html>