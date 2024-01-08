<?php

session_start();

include("inc.configs.php");

$action = "";
if(isset($_REQUEST['action'])) {
    $action = trim($_REQUEST['action']);
}

if($action == "login")
{
    $username = "";
    $password = "";
    if(isset($_REQUEST['username']))
    {
        $username = trim($_REQUEST['username']);
    }
    if(isset($_REQUEST['password']))
    {
        $password = trim($_REQUEST['password']);
    }
    if(empty($username))
    {
        response("error", "Please Enter Username", "");
    }
    if(empty($password))
    {
        response("error", "Please Enter Password", "");
    }
    if(isset($ADMIN_PANEL_CREDS['USERNAME']) && isset($ADMIN_PANEL_CREDS['PASSWORD']))
    {
        if($username == $ADMIN_PANEL_CREDS['USERNAME'] && $password == $ADMIN_PANEL_CREDS['PASSWORD'])
        {
            $_SESSION['usf_admin'] = true;
            response("success", "Logged In Successfully", array("session_token" => sha1(session_id())));
        }
        else
        {
            response("error", "Invalid Credentials", "");
        }
    }
    else
    {
        response("error", "Fatal Error Occured", "");
    }
}
elseif($action == "login_session")
{
    if(isset($_SESSION['usf_admin']) && $_SESSION['usf_admin'] == true)
    {
        response("success", "Login Session Active", "");
    }
    response("error", "No Active Login Session", "");
}
elseif($action == "logout")
{
    session_destroy();
    response("error", "Logged Out Successfully", "");
}
else
{
//==========================================================================================//
//          A   D   M   I   N       P   R   O   T   E   C   T   I   O   N
//==========================================================================================//
    if(!isset($_SESSION['usf_admin']))
    {
        response("error", "You are trying to access login protected resource.", "");
    }
    if($action == "dashboard_data")
    {
        $arespo = array("login" => array("username" => $ADMIN_PANEL_CREDS['USERNAME'],
                                         "password" => $ADMIN_PANEL_CREDS['PASSWORD']),
                        "application_logs" => array(),
                        "web_app_status" => webIApp("status"));
        response("success", "Dashboard Data Loaded Successfully", $arespo);
    }
    if($action == "update_credentials")
    {
        $username = "";
        $password = "";
        if(isset($_REQUEST['username'])){ $username = trim($_REQUEST['username']); }
        if(isset($_REQUEST['password'])){ $password = trim($_REQUEST['password']); }
        if(empty($username)){ response("error", "Please Enter Username", ""); }
        if(empty($password)){ response("error", "Please Enter Password", ""); }
        $NEW_CREDENTIALS = array("USERNAME" => $username, "PASSWORD" => $password);
        if(file_put_contents($APP_DATA_FOLDER."/usf_credentials", json_encode($NEW_CREDENTIALS)))
        {
            save_application_logs("Admin Credentials Updated");
            response("success", "Admin Credentials Updated. Login Again.", "");
        }
        else
        {
            response("error", "Failed To Update Admin Credentials", $arespo);
        }
    }
    if($action == "app_login_data")
    {
        $app_login_status = false;
        $app_login_method = "otp";
        $voot_login_status = false;
        $jionews_login_status = false;
        $JioTV_Login_Check = jiotv_login_data();
        if(!empty($JioTV_Login_Check))
        {
            $app_login_status = true;
            if(isset($JioTV_Login_Check['password']) && !empty($JioTV_Login_Check['password']))
            {
                $app_login_method = "nonotp";
            }
        }
        $JioNews_Login_Check = jionews_login_data();
        if(!empty($JioNews_Login_Check))
        {
            $jionews_login_status = true;
        }
        $Voot_Login_Check = voot_login_data();
        if(!empty($Voot_Login_Check))
        {
            $voot_login_status = true;
        }
        response("success", "App Data Loaded Successfully", array("app_login_status" => $app_login_status,
                                                                  "app_login_method" => $app_login_method,
                                                                  "voot_login_status" => $voot_login_status,
                                                                  "jionews_login_status" => $jionews_login_status));
    }
    if($action == "stream_data")
    {
        $worldwide_proxy = get_WorldWideProxyStatus();
        $server_bypass_method = get_ServerBypassMethod();
        $lstream_data = getCmStreamRestrictions();
        $lstream_tokendata = getCmStreamTokenRestrictions();
        response("success", "Stream Data Loaded Successfully", array("worldwide_proxy" => $worldwide_proxy,
                                                                     "server_bypass_method" => $server_bypass_method,
                                                                     "useragent_check" => $lstream_data['ua'],
                                                                     "referer_check" => $lstream_data['referer'],
                                                                     "origin_check" => $lstream_data['origin'],
                                                                     "direct_play" => array("status" => directPlayAPI("status"),
                                                                                            "link" => directPlayAPI("link")),
                                                                     "current_stream_mode" => str_replace("HACK", "", str_replace("_", " ", getJTVStreamMode("", ""))),
                                                                     "stream_token" => array("status" =>  $lstream_tokendata['status'],
                                                                                             "ip_check" =>  $lstream_tokendata['ipcheck'],
                                                                                             "validity" =>  $lstream_tokendata['validity'])));
    }

    //=====================================================================================//

    if($action == "jio_otp_login")
    {
        $mobile = ""; $otp = "";
        if(isset($_REQUEST['mobile']))
        {
            $mobile = trim($_REQUEST['mobile']);
        }
        if(isset($_REQUEST['otp']))
        {
            $otp = trim($_REQUEST['otp']);
        }
        if(!preg_match('/^[0-9]{10}$/', $mobile)) 
        {
            response("error", "Please Enter Valid 10 Digit Jio Mobile Number", "");
        }
        if(empty($otp))
        {
            $resenz = send_jio_otp($mobile);
        }
        else
        {
            $resenz = verify_jio_otp($mobile, $otp);
        }
        $Logtxt = "JioTV OTP Login - ".ucwords($resenz['status'])." - ".ucwords(strtolower($resenz['message']));
        save_application_logs($Logtxt);
        response($resenz['status'], $resenz['message'], "");
    }

    if($action == "jio_nonotp_login")
    {
        $identifier = ""; $password = "";
        if(isset($_REQUEST['mobile']))
        {
            $identifier = trim($_REQUEST['mobile']);
        }
        if(isset($_REQUEST['password']))
        {
            $password = trim($_REQUEST['password']);
        }
        if(empty($identifier))
        {
            response("error", "Please Enter Valid Jio Mobile Number or Email ID", "");
        }
        if(empty($password))
        {
            response("error", "Please Enter Password", "");
        }
        if(is_numeric($identifier)) 
        {
            $identifier = "+91".$identifier;
        }
        $resenz = jio_sso_login($identifier, $password);
        $Logtxt = "JioTV Non-OTP Login - ".ucwords($resenz['status'])." - ".ucwords(strtolower($resenz['message']));
        save_application_logs($Logtxt);
        response($resenz['status'], $resenz['message'], "");
    }

    if($action == "jio_news_login")
    {
        $mobile = ""; $otp = "";
        if(isset($_REQUEST['mobile']))
        {
            $mobile = trim($_REQUEST['mobile']);
        }
        if(isset($_REQUEST['otp']))
        {
            $otp = trim($_REQUEST['otp']);
        }
        if(!preg_match('/^[0-9]{10}$/', $mobile)) 
        {
            response("error", "Please Enter Any Valid Indian Number", "");
        }
        if(empty($otp))
        {
            $resenz = send_jionews_otp($mobile);
        }
        else
        {
            $resenz = verify_jionews_otp($mobile, $otp);
        }
        $Logtxt = "JioNews Login - ".ucwords($resenz['status'])." - ".ucwords(strtolower($resenz['message']));
        save_application_logs($Logtxt);
        response($resenz['status'], $resenz['message'], "");
    }

    if($action == "voot_login")
    {
        $identifier = ""; $password = "";
        if(isset($_REQUEST['identifier'])) {
            $identifier = trim($_REQUEST['identifier']);
        }
        if(isset($_REQUEST['password'])) {
            $password = $_REQUEST['password'];
        }
        if(empty($identifier)) {
            response("error", "Please Enter Voot Email ID or Mobile Number", "");
        }
        if(empty($password)) {
            response("error", "Please Enter Voot Password", "");
        }
        $resenz = voot_login($identifier, $password);
        $Logtxt = "Voot Login - ".ucwords($resenz['status'])." - ".ucwords(strtolower($resenz['message']));
        save_application_logs($Logtxt);
        response($resenz['status'], $resenz['message'], "");
    }

    if($action == "logout_jio")
    {
        $logout_status = false;
        if(file_exists($jioDataPath)) {
            if(unlink($jioDataPath)){ $logout_status = true; }
        }
        else {
            $logout_status = true;
        }
        if($logout_status == true) {
            save_application_logs("Logged Out JioTV");
            response("success", "Logged Out Successfully", "");
        }
        response("error", "Failed To Logout", "");
    }

    if($action == "logout_jionews")
    {
        $logout_status = false;
        if(file_exists($jionewsDataPath)) {
            if(unlink($jionewsDataPath)){ $logout_status = true; }
        }
        else {
            $logout_status = true;
        }
        if($logout_status == true) {
            save_application_logs("Logged Out JioNews");
            response("success", "Logged Out Successfully", "");
        }
        response("error", "Failed To Logout", "");
    }

    if($action == "logout_voot")
    {
        $logout_status = false;
        if(file_exists($vootDataPath)) {
            if(unlink($vootDataPath)) { $logout_status = true; }
        }
        else {
            $logout_status = true;
        }
        if($logout_status == true) {
            save_application_logs("Logged Out Voot");
            response("success", "Logged Out Successfully", "");
        }
        response("error", "Failed To Logout", "");
    }

    //=====================================================================================//

    if($action == "update_direct_play_status")
    {
        $upd = directPlayAPI("change");
        {
            if($upd == true)
            {
                response("success", "Updated Successfully", "");
            }
        }
        response("error", "Failed To Update", "");
    }

    if($action == "update_server_bypass_method")
    {
        $value = "";
        if(isset($_REQUEST['value'])){ $value = trim($_REQUEST['value']); }
        $server_Bypass_file = $APP_DATA_FOLDER."/usf_srvbypass";
        if(empty($value)){ $value = ""; }
        if(file_put_contents($server_Bypass_file, $value)){}
        if(empty($value)){ $value = "NONE"; }
        save_application_logs("Server Bypass Method Updated To ".strtoupper($value));
        response("success", "Updated Successfully", "");
    }

    if($action == "update_wwd_proxy")
    {
        $value = "";
        if(isset($_REQUEST['value'])){ $value = trim($_REQUEST['value']); }
        $wwd_Proxy_file = $APP_DATA_FOLDER."/usf_wwdproxy";
        if(empty($value)){ $value = "on"; }
        if(file_put_contents($wwd_Proxy_file, $value))
        {
            response("success", "Updated Successfully", "");
        }
        else
        {
            response("error", "Failed To Update", "");
        }
    }

    if($action == "update_stream_restriction")
    {
        $ua = $origin = $referer = "";
        if(isset($_REQUEST['ua'])){ $ua = trim($_REQUEST['ua']); }
        if(isset($_REQUEST['origin'])){ $origin = trim($_REQUEST['origin']); }
        if(isset($_REQUEST['referer'])){ $referer = trim($_REQUEST['referer']); }
        saveCmStreamRestrictions($ua, $origin, $referer);
        response("success", "Updated Successfully", "");
    }

    if($action == "update_stream_token_restriction")
    {
        $stream_token_status = "on";
        $stream_token_ipaddress = "off";
        $server_token_validity = "0.5";
        if(isset($_REQUEST['status']))
        {
            $stream_token_status = $_REQUEST['status'];
        }
        if(isset($_REQUEST['ipres']))
        {
            $stream_token_ipaddress = $_REQUEST['ipres'];
        }
        if(isset($_REQUEST['validity']))
        {
            $server_token_validity = $_REQUEST['validity'];
        }
        if($stream_token_status !== "on" && $stream_token_status !== "off")
        {
            response("error", "Invalid Value For Stream Token Security Status", "");
        }
        if( $stream_token_ipaddress !== "on" &&  $stream_token_ipaddress !== "off")
        {
            response("error", "Invalid Value For Stream Token Security IPAddress Restriction", "");
        }
        if(!is_numeric($server_token_validity))
        {
            response("error", "Invalid Value For Stream Token Validity Hour", "");
        }
        saveCmStreamTokenRestrictions($stream_token_status, $stream_token_ipaddress, $server_token_validity);
        response("success", "Updated Successfully", "");
    }

    //=====================================================================================//

    if($action == "update_zone")
    {
        $type = "";
        if(isset($_REQUEST['type'])) {
            $type = $_REQUEST['type'];
        }

        if($type == "channels")
        {
            $del = delete_jio_tv_channels();
            if($del == true)
            {
                $upd = jio_tv_channels();
                if(!empty($upd))
                {
                    response("success", "Channels Updated Successfully", "");
                }
            }
            response("error", "Failed To Update Channel", "");
        }
        elseif($type == "jtv_otp_token")
        {
            $upd = refresh_jiotv_token();
            if($upd == true)
            {
                response("success", "Updated JioTV [OTP Login] AuthToken", "");
            }
            else
            {
                response("error", "Failed To Update JioTV AuthToken", "");
            }
        }
        elseif($type == "jionews_token")
        {
            $upd = refresh_jionews_token();
            if($upd == true)
            {
                response("success", "Updated JioNews AuthToken", "");
            }
            else
            {
                response("error", "Failed To Update JioNews AuthToken", "");
            }
        }
        elseif($type == "voot_token")
        {
            $upd = refresh_voot_token();
            if($upd == true)
            {
                response("success", "Updated Voot AuthToken", "");
            }
            else
            {
                response("error", "Failed To Update Voot AuthToken", "");
            }
        }
        else
        {
            response("error", "Invalid Update Type Supplied", "");
        }
    }

    if($action == "genIPTVPlaylist")
    {
        $type = ""; $validity = "";
        $playlist_validity = "";
        if(isset($_REQUEST['type']))
        {
            $type = $_REQUEST['type'];
        }
        if(isset($_REQUEST['validity']))
        {
            $validity = $_REQUEST['validity'];
        }
        if(isset($_REQUEST['playlist_validity']))
        {
            $playlist_validity = $_REQUEST['playlist_validity'];
        }
        if($type !== "direct_play" && $type !== "actual_link")
        {
            response("error", "Invalid Value For Link Type", "");
        }
        if(!is_numeric($validity))
        {
            response("error", "Stream Token Validity Invalid. Please Enter Numeric Value For Numbers of Hour Streaming Link Should Work. [type 999999999 as many 9 as you can to set unlimited numbers of hours]", "");
        }
        if(!is_numeric($playlist_validity))
        {
            response("error", "Playlist Link Validity Invalid. Please Enter Numeric Value For Numbers of Hour Playlist Link Should Work. [type 999999999 as many 9 as you can to set unlimited numbers of hours]", "");
        }

        $issuetime = time();
        $hidethis = array("playlist_validity" => $playlist_validity, "issue_time" => $issuetime, "type" => $type, "days" => $validity, "hash" => sha1($playlist_validity.$issuetime.$validity.$type.$APP_ENCRYPTION_KEY.$APP_STREAMING_KEY));
        $playlist_payload = hidmaster("encrypt", json_encode($hidethis));
    
        if($_SERVER['SERVER_PORT'] !== "80" && $_SERVER['SERVER_PORT'] !== "443")
        {
            $link = $streamenvproto.'://'.$_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT'].str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']);
        }
        else
        {
            $link = $streamenvproto.'://'.$_SERVER['HTTP_HOST'].str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']);
        }
        $link = str_replace(" ", "%20", $link);
        $link .= "iptv_playlist.php?token=".$playlist_payload;
        response("success", "OK", $link);
    }

    if($action == "change_Webapp_Status")
    {
        $upd = webIApp("change");
        if($upd == true)
        {
            save_application_logs("WebApp Status Changed To ".strtoupper(webIApp("status")));
            response("success", "Updated Successfully", array("new_status" => webIApp("status")));
        }
        save_application_logs("Failed To Change WebApp Status");
        response("error", "Failed To Update", "");
    }
}
?>