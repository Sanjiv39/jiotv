<?php

//DEFAULT ADMIN CREDENTIALS
$ADMIN_PANEL_CREDS = array("USERNAME" => "admin", "PASSWORD" => "usftoolshub@31209");

$APP_DATA_FOLDER = "AppData";

date_default_timezone_set('Asia/Kolkata');

$JIOTV_IMAGES_CDN = "https://jiotvimages.cdn.jio.com/dare_images/images/";

$JIONEWS_STREAM_HEADERS = array("origin: https://jionews.com",
                                "referer: https://jionews.com/liveTv/News18-India/231",
                                "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36");


//==================================================================================//

if(!is_dir($APP_DATA_FOLDER)){ mkdir($APP_DATA_FOLDER); }
if(!file_exists($APP_DATA_FOLDER."/.htaccess")){ @file_put_contents($APP_DATA_FOLDER."/.htaccess", "deny from all"); }
if(!file_exists($APP_DATA_FOLDER."/usf_EKey")){ @file_put_contents($APP_DATA_FOLDER."/usf_EKey", sha1(time().rand(00000, 99999))); }
if(!file_exists($APP_DATA_FOLDER."/usf_SKey")){ @file_put_contents($APP_DATA_FOLDER."/usf_SKey", sha1(time().rand(00000, 99999))); }

if(file_exists($APP_DATA_FOLDER."/usf_credentials"))
{
    $veed = @file_get_contents($APP_DATA_FOLDER."/usf_credentials");
    if(!empty($veed))
    {
        $xeed = @json_decode($veed, true);
        if(isset($xeed['USERNAME']))
        {
            $ADMIN_PANEL_CREDS['USERNAME'] = $xeed['USERNAME'];
        }
        if(isset($xeed['PASSWORD']))
        {
            $ADMIN_PANEL_CREDS['PASSWORD'] = $xeed['PASSWORD'];
        }
    }
}

if(!file_exists($APP_DATA_FOLDER."/usf_wwdproxy"))
{
    @file_put_contents($APP_DATA_FOLDER."/usf_wwdproxy", "on");
}

$jioDataPath = $APP_DATA_FOLDER."/usf_tplogin";
$jionewsDataPath = $APP_DATA_FOLDER."/usf_JNlogin";
$vootDataPath = $APP_DATA_FOLDER."/usf_VTLogin";

$APP_ENCRYPTION_KEY = $APP_STREAMING_KEY = "";
if(file_exists($APP_DATA_FOLDER."/usf_EKey"))
{
    $APP_ENCRYPTION_KEY = @file_get_contents($APP_DATA_FOLDER."/usf_EKey");
}
if(file_exists($APP_DATA_FOLDER."/usf_SKey"))
{
    $APP_STREAMING_KEY = @file_get_contents($APP_DATA_FOLDER."/usf_SKey");
}
//==================================================================================//

$streamenvproto = "http";
if(isset($_SERVER['HTTPS'])){ if($_SERVER['HTTPS'] == "on"){ $streamenvproto = "https"; } }
if(isset($_SERVER['HTTP_X_FORWARDED_PROTO'])){ if($_SERVER['HTTP_X_FORWARDED_PROTO'] == "https"){ $streamenvproto = "https"; }}

if(stripos($_SERVER['HTTP_HOST'], ':') !== false)
{
    $warl = explode(':', $_SERVER['HTTP_HOST']);
    if(isset($warl[0]) && !empty($warl[0])){ $_SERVER['HTTP_HOST'] = trim($warl[0]); }
}
if(stripos($_SERVER['HTTP_HOST'], 'localhost') !== false){ $_SERVER['HTTP_HOST'] = str_replace('localhost', '127.0.0.1', $_SERVER['HTTP_HOST']); }
$local_ip = getHostByName(php_uname('n'));
if($_SERVER['SERVER_ADDR'] !== "127.0.0.1"){ $plhoth = $_SERVER['HTTP_HOST'];  }else{ $plhoth = $local_ip;  }
$plhoth = str_replace(" ", "%20", $plhoth);

//==================================================================================//

function response($status, $message, $data)
{
    header("Content-Type: application/json");
    header("Access-Control-Allow-Origin: *");
    if($status == "success" || $status == "error")
    {
        if($status == "error"){ $data = array(); }
        $outr = array("status" => $status, "message" => $message, "data" => $data);
    }
    else
    {
        $outr = array("status" => "error", "message" => "Unable To Process Your Request", "data" => array());
    }
    exit(json_encode($outr));
}

function save_application_logs($text)
{
    global $APP_DATA_FOLDER;
    $output = false;;
    $logs_path = $APP_DATA_FOLDER."/application_logs";
    $new_log = date('F d, Y h:i:s A')." - ".$text." - ".$_SERVER['REMOTE_ADDR']." - ".$_SERVER['HTTP_USER_AGENT']."\n";
    if(file_exists($logs_path))
    {
        $read_logs = @file_get_contents($logs_path);
        if(!empty($read_logs))
        {
            $new_log = $new_log.trim($read_logs);
        }
    }
    if(!empty($new_log))
    {
        if(file_put_contents($logs_path, $new_log))
        {
            $output = true;
        }
    }
    return $output;
}

function hidmaster($action, $data)
{
    global $APP_ENCRYPTION_KEY;
    $output = "";
    $ikeyz = substr(md5($APP_ENCRYPTION_KEY."usefultoolshub"), 0, 16);
    $ky = $iv = $ikeyz;
    if($action == "encrypt")
    {
        $encrypted = openssl_encrypt($data, "AES-128-CBC", $ky, OPENSSL_RAW_DATA, $iv);
        if(!empty($encrypted))
        {
            $output = bin2hex($encrypted);
        }
    }
    if($action == "decrypt")
    {
        $decrypted = openssl_decrypt(hex2bin($data), "AES-128-CBC", $ky, OPENSSL_RAW_DATA, $iv);
        if(!empty($decrypted))
        {
            $output = $decrypted;
        }
    }
    return $output;
}

function saveCmStreamRestrictions($ua, $origin, $referer)
{
    global $APP_DATA_FOLDER;
    $strm_res_path = $APP_DATA_FOLDER."/usf_streamRest";
    $saveStrmResData = array("ua" => $ua, "origin" => $origin, "referer" => $referer);
    if(file_put_contents($strm_res_path, json_encode($saveStrmResData))){
        return true;
    }else {
        return false;
    }
}


function getCmStreamRestrictions()
{
    global $APP_DATA_FOLDER;
    $output = array();
    $strm_res_path = $APP_DATA_FOLDER."/usf_streamRest";
    if(file_exists($strm_res_path))
    {
        $strm_res_data = @file_get_contents($strm_res_path);
        if(!empty($strm_res_data))
        {
            $strm_res_val = @json_decode($strm_res_data, true);
            if(isset($strm_res_val['ua']) && isset($strm_res_val['origin']) && isset($strm_res_val['referer']))
            {
                $output = $strm_res_val;
            }
        }
    }
    if(empty($output))
    {
        $saveStrmResData = array("ua" => "", "origin" => "", "referer" => "");
        if(file_put_contents($strm_res_path, json_encode($saveStrmResData))){}
        $output = $saveStrmResData;
    }
    return $output;
}


function getCmStreamTokenRestrictions()
{
    global $APP_DATA_FOLDER;
    $output = array();
    $strm_res_path = $APP_DATA_FOLDER."/usf_streamTokenRest";
    if(file_exists($strm_res_path))
    {
        $strm_res_data = @file_get_contents($strm_res_path);
        if(!empty($strm_res_data))
        {
            $strm_res_val = @json_decode($strm_res_data, true);
            if(isset($strm_res_val['status']) && isset($strm_res_val['ipcheck']) && isset($strm_res_val['validity']))
            {
                $output = $strm_res_val;
            }
        }
    }
    if(empty($output))
    {
        $saveStrmResData = array("status" => "", "ipcheck" => "", "validity" => "");
        if(file_put_contents($strm_res_path, json_encode($saveStrmResData))){}
        $output = $saveStrmResData;
    }
    return $output;
}

function saveCmStreamTokenRestrictions($status, $ipcheck, $validity)
{
    global $APP_DATA_FOLDER;
    $strm_res_path = $APP_DATA_FOLDER."/usf_streamTokenRest";
    $saveStrmResData = array("status" => $status, "ipcheck" => $ipcheck, "validity" => $validity);
    if(file_put_contents($strm_res_path, json_encode($saveStrmResData))){
        return true;
    }else {
        return false;
    }
}

function get_ServerBypassMethod()
{
    global $APP_DATA_FOLDER;
    $server_Bypass_file_data = "";
    $server_Bypass_file = $APP_DATA_FOLDER."/usf_srvbypass";
    if(file_exists($server_Bypass_file))
    {
        $server_Bypass_file_data = @file_get_contents($server_Bypass_file);
    }
    return $server_Bypass_file_data;
}

function get_WorldWideProxyStatus()
{
    global $APP_DATA_FOLDER;
    $worldwide_proxy = "off";
    if(file_exists($APP_DATA_FOLDER."/usf_wwdproxy"))
    {
        $read_WWDPxyStatus = @file_get_contents($APP_DATA_FOLDER."/usf_wwdproxy");
        if(!empty($read_WWDPxyStatus))
        {
            $worldwide_proxy = $read_WWDPxyStatus;
        }
    }
    return $worldwide_proxy;
}

function getRequestData()
{
    $payload = ""; $token = ""; $Resource = "";
    $channelID = ""; $channelSlug = ""; $response = array();
    if(isset($_REQUEST['p'])) {
        $payload = $_REQUEST['p'];
    }
    $xpload = explode("/", $payload);
    if(isset($xpload[0]) && !empty($xpload[0])) {
        $token = trim($xpload[0]);
    }
    if(isset($xpload[1]) && !empty($xpload[1])) {
        $channelID = trim($xpload[1]);
    }
    if(isset($xpload[2]) && !empty($xpload[2])) {
        $channelSlug = trim($xpload[2]);
    }
    if(isset($xpload[3]) && !empty($xpload[3])) {
        $Resource = trim($xpload[3]);
    }

    if(!empty($token) && !empty($channelID) && !empty($channelSlug) && !empty($Resource))
    {
        $response = array("token" => $token, "channel_id" => $channelID, "channel_slug" => $channelSlug, "resource" => $Resource);
    }
    return $response;
}

function pingStreamRespo($link, $headers)
{
    $finalurl = "";
    $process = curl_init($link); 
    curl_setopt($process, CURLOPT_HTTPHEADER, $headers); 
    curl_setopt($process, CURLOPT_HEADER, 1);
    curl_setopt($process, CURLOPT_ENCODING, '');
    curl_setopt($process, CURLOPT_TIMEOUT, 6); 
    curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1); 
    $return = curl_exec($process);
    $pgnfo = curl_getinfo($process);
    $finalurl = curl_getinfo($process, CURLINFO_EFFECTIVE_URL);
    curl_close($process);
    if(stripos($return, "#EXTM3U") === false){ $return = ""; $finalurl = ""; }
    return array("http_code" => $pgnfo['http_code'], "data" => $return, "url" => $finalurl);
}

include("inc.jio.php");

//=============================================================================//
//      S T R E A M     S P E C I F I C     F U N C T I O N S
//=============================================================================//

function getJTVStreamBase($url)
{
    if(stripos($url, "?") !== false)
    {
        $mxxe = explode("?", $url);
        if(isset($mxxe[0]) && !empty($mxxe[0]))
        {
            $url = trim($mxxe[0]);
        }
    }
    $baseurl = str_replace(basename($url), "", $url);
    return $baseurl;
}

function getJTVStreamlinks($id, $slug)
{
    $outme = ""; $jiotvLoginMethod = "";
    $bypassMethod = get_ServerBypassMethod();
    $jiotvLogindata = jiotv_login_data();
    if(isset($jiotvLogindata['JTVloginMethod'])) {
        $jiotvLoginMethod = $jiotvLogindata['JTVloginMethod'];
    }
        if($bypassMethod == "jionews")
        {
            $outme = jio_news_streamlink($id, $slug);
            if(empty($outme))
            {
                $outme = jio_news_streamlink($id, $slug);
            }
        }
        elseif($bypassMethod == "voot")
        {
            $outme = voot_streamlink($id, $slug);
            if(empty($outme))
            {
                $outme = voot_streamlink($id, $slug);
            }
        }
        elseif($jiotvLoginMethod == "OTP")
        {
            $outme =  jio_otp_streamlink($id);
        }
        elseif($jiotvLoginMethod == "NON-OTP")
        {
            $outme = jio_nonotp_streamlink($id, $slug);
            if(empty($outme))
            {
                $outme = jio_nonotp_streamlink($id, $slug);
            }
        }
        else
        {
            $outme = jio_nonotp_streamlink($id, $slug);
            if(empty($outme))
            {
                $outme = jio_nonotp_streamlink($id, $slug);
            }
        }
        return $outme;
}
    


//Get Streaming Mode for StreamV1.php Script
function getJTVStreamMode($id, $slug)
{
    $outme = ""; $jiotvLoginMethod = "";
    $bypassMethod = get_ServerBypassMethod();
    $jiotvLogindata = jiotv_login_data();

    if(isset($jiotvLogindata['JTVloginMethod'])) {
        $jiotvLoginMethod = $jiotvLogindata['JTVloginMethod'];
    }

    if($bypassMethod == "jionews")
    {
        $outme = "JIONEWS";
    }
    elseif($bypassMethod == "voot")
    {
        $outme = "VOOT";
    }
    elseif($jiotvLoginMethod == "OTP")
    {
        $outme = "JIO_OTP";
    }
    elseif($jiotvLoginMethod == "NON-OTP")
    {
        $outme = "JIO_NONOTP";
    }
    else
    {
        $outme = "HACK_UNLOGGED";
    }
    
    return $outme;
}

//Extracts DeviceID From JioTV "authToken"
function getJioTVDeviceId()
{
    $deviceID = "";
    $JIO_AUTH = jiotv_login_data();
    if(!empty($JIO_AUTH))
    {
        if(isset($JIO_AUTH['authToken']) && !empty($JIO_AUTH['authToken']))
        {
            $xtoken = explode(".", $JIO_AUTH['authToken']);
            if(isset($xtoken[1]) && !empty($xtoken[1]))
            {
                $seetoken = @json_decode(base64_decode($xtoken[1]), true);
                if(isset($seetoken['data']['deviceId']) && !empty($seetoken['data']['deviceId']))
                {
                    $deviceID = $seetoken['data']['deviceId'];
                }
            }
        }
    }
    return $deviceID;
}

function getSlugByID($id)
{
    $slug = "";
    $channels = jio_tv_channels();
    foreach($channels as $tvchnls)
    {
        if($tvchnls['id'] == $id)
        {
            $slug = $tvchnls['slug'];
        }
    }
    return $slug;
}

function getTitleByID($id)
{
    $slug = "";
    $channels = jio_tv_channels();
    foreach($channels as $tvchnls)
    {
        if($tvchnls['id'] == $id)
        {
            $slug = $tvchnls['title'];
        }
    }
    return $slug;
}
function getLogoByID($id)
{
    $slug = "";
    $channels = jio_tv_channels();
    foreach($channels as $tvchnls)
    {
        if($tvchnls['id'] == $id)
        {
            $slug = $tvchnls['logo'];
        }
    }
    return $slug;
}

function getIDBySlug($slug)
{
    $id = "";
    $channels = jio_tv_channels();
    foreach($channels as $tvchnls)
    {
        if($tvchnls['slug'] == $slug)
        {
            $id = $tvchnls['id'];
        }
    }
    return $id;
}

function generateStreamToken()
{
    global $APP_STREAMING_KEY;
    $token = "live";
    $settings = getCmStreamTokenRestrictions();
    if($settings['status'] == "on")
    {
        $expiry = time() + ($settings['validity'] * 3600);
        $ip = $_SERVER['REMOTE_ADDR'];
        $hash = sha1($expiry.$ip.$APP_STREAMING_KEY);
        $token = "exp=".$expiry."~ip=".$ip."~hmac=".$hash;
        $token = "live".hidmaster("encrypt", $token);
    }
    return $token;
}

function generateStreamTokenForPlaylist($validity)
{
    global $APP_STREAMING_KEY;
    $token = "live";
    
    $expiry = time() + ($validity * 3600);
    $ip = $_SERVER['REMOTE_ADDR'];
    $hash = sha1($expiry.$ip.$APP_STREAMING_KEY);
    $token = "exp=".$expiry."~ip=".$ip."~hmac=".$hash;
    $token = "live".hidmaster("encrypt", $token);
    
    return $token;
}

function validateStreamToken($token)
{
    global $APP_STREAMING_KEY;
    validateEnvStreamCheck();
    $expiry = $ipaddr = $hmac = "";
    $settings = getCmStreamTokenRestrictions();
    if($settings['status'] == "on")
    {
        $token = hidmaster("decrypt", str_replace("live", "", $token));
        $xplod = explode("~", $token);
        if(isset($xplod[0]))
        {
            $expiry = str_replace("exp=", "", $xplod[0]);
        }
        if(isset($xplod[1]))
        {
            $ipaddr = str_replace("ip=", "", $xplod[1]);
        }
        if(isset($xplod[2]))
        {
            $hmac = str_replace("hmac=", "", $xplod[2]);
        }
        if(!is_numeric($expiry))
        {
            header("x-usf-error: check 1");
            http_response_code(403);
            exit();
        }
        if(time() > $expiry)
        {
            header("x-usf-error: check 2");
            http_response_code(403);
            exit();
        }
        if($settings['ipcheck'] == "on" && $_SERVER['REMOTE_ADDR'] !== $ipaddr)
        {
            header("x-usf-error: check 3");
            http_response_code(403);
            exit();
        }
        $build_hmac = sha1($expiry.$ipaddr.$APP_STREAMING_KEY);
        if($build_hmac !== $hmac)
        {
            header("x-usf-error: check 4");
            http_response_code(403);
            exit();
        }
    }
}

function validateEnvStreamCheck()
{
    if(!isset($_SERVER['HTTP_ORIGIN'])){ $_SERVER['HTTP_ORIGIN'] = ""; }
    if(!isset($_SERVER['HTTP_REFERER'])){ $_SERVER['HTTP_REFERER'] = ""; }
    $restriction = getCmStreamRestrictions();
    if(isset($restriction['ua']) && !empty($restriction['ua']))
    {
        if(stripos($_SERVER['HTTP_USER_AGENT'], $restriction['ua']) === false)
        {
            header("x-usf-error: envRR1");
            http_response_code(403);
            exit();
        }
    }
    if(isset($restriction['referer']) && !empty($restriction['referer']))
    {
        if(stripos($_SERVER['HTTP_REFERER'], $restriction['referer']) === false)
        {
            header("x-usf-error: envRR2");
            http_response_code(403);
            exit();
        }
    }
    if(isset($restriction['origin']) && !empty($restriction['origin']))
    {
        if(stripos($_SERVER['HTTP_ORIGIN'], $restriction['origin']) === false)
        {
            header("x-usf-error: envRR3");
            http_response_code(403);
            exit();
        }
    }
}

function directPlayAPI($action)
{
    global $APP_DATA_FOLDER;
    global $streamenvproto;
    $status = "off";
    $dpapiPath = $APP_DATA_FOLDER."/usf_dpAPISet";
    if(file_exists($dpapiPath)) {
        $read_dpapiS = @file_get_contents($dpapiPath);
        if($read_dpapiS == "on") {
            $status = "on";
        }
    }
    if($action == "change") {
        $Newstatus = ($status == "on") ? "off" : "on";
        return file_put_contents($dpapiPath, $Newstatus) ? true : false;
    }
    elseif($action == "link")
    {
        $link = "";
        if($_SERVER['SERVER_PORT'] !== "80" && $_SERVER['SERVER_PORT'] !== "443")
        {
            $link = $streamenvproto.'://'.$_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT'].str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']);
        }
        else
        {
            $link = $streamenvproto.'://'.$_SERVER['HTTP_HOST'].str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']);
        }
        $link = str_replace("app/", "", $link);
        $link = str_replace(" ", "%20", $link);
        $link .= "autoq.php?c={YOUR_CHANNEL_ID_OR_SLUG_HERE}&e=.m3u8";
        return $link;
    }
    else {
        return $status;
    }
}

function webIApp($action)
{
    global $APP_DATA_FOLDER;
    global $streamenvproto;
    $status = "on";
    $dpapiPath = $APP_DATA_FOLDER."/usf_wbAppStatus";
    if(file_exists($dpapiPath)) {
        $read_dpapiS = @file_get_contents($dpapiPath);
        if($read_dpapiS == "off") {
            $status = "off";
        }
    }
    if($action == "change") {
        $Newstatus = ($status == "on") ? "off" : "on";
        return file_put_contents($dpapiPath, $Newstatus) ? true : false;
    }
    else {
        return $status;
    }
}

function vschash($p)
{
    $v = "";
    foreach(str_split($p) as $b)
    { 
        if(ctype_upper($b)) {  $v .= strtolower($b); }elseif(ctype_lower($b)){ $v .= strtoupper($b); }else{ $v .= $b; }
    }
    return $v;
}

function encrypt_api_res($data)
{
    $data = base64_encode($data);
    $data = base64_encode($data);
    $data = vschash($data);
    $data = base64_encode($data);
    $data = base64_encode($data);
    $data = vschash($data);
    $data = str_replace("=", "", $data);
    return $data;
}

?>