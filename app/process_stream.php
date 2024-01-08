<?php

include("inc.configs.php");

header("Access-Control-Allow-Origin: *");

$sreq = getRequestData();
if(empty($sreq))
{
    exit("Bad Request");
}

validateStreamToken($sreq['token']);

$JIO_AUTH = jiotv_login_data();

$streamHeaders = array();
$streamMode = getJTVStreamMode($sreq['channel_id'], $sreq['channel_slug']);
if($streamMode == "JIONEWS")
{
    $streamHeaders = $JIONEWS_STREAM_HEADERS;
}
elseif($streamMode == "VOOT")
{
    $streamHeaders = array("User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36",
                           "Origin: https://www.voot.com",
                           "Referer: https://www.voot.com/",
                           "Cookie: ".voot_hacktoken());
}
elseif($streamMode == "JIO_NONOTP")
{
    $streamHeaders = array("Cookie: ".jio_nonotp_livetoken($sreq['channel_id']),
                           "User-Agent: plaYtv/7.0.8 (Linux;Android 9) ExoPlayerLib/2.11.7");
}
elseif($streamMode == "OTP")
{
    $streamHeaders = array("Cookie: ".jio_otp_livetoken($sreq['channel_id']),
                           "User-Agent: plaYtv/7.0.8 (Linux;Android 9) ExoPlayerLib/2.11.7");
}
else
{
    $streamHeaders = array("Cookie: ".jio_nonotp_livetoken($sreq['channel_id']),
                           "User-Agent: plaYtv/7.0.8 (Linux;Android 9) ExoPlayerLib/2.11.7");
}

//==================================================================//

if($sreq['resource'] == "index.m3u8")
{
    $link = getJTVStreamlinks($sreq['channel_id'], $sreq['channel_slug']);
    $process = curl_init($link); 
    curl_setopt($process, CURLOPT_HTTPHEADER, $streamHeaders); 
    curl_setopt($process, CURLOPT_HEADER, 0);
    curl_setopt($process, CURLOPT_ENCODING, '');
    curl_setopt($process, CURLOPT_TIMEOUT, 15); 
    curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1); 
    $return = curl_exec($process);
    $pgnfo = curl_getinfo($process);
    $finalurl = curl_getinfo($process, CURLINFO_EFFECTIVE_URL);
    curl_close($process);
    $iBaseLink = getJTVStreamBase($finalurl);
    if(stripos($return, "#EXTM3U") !== false)
    {
        $hline = "";
        $vline = explode("\n", $return);
        foreach ($vline as $iline)
        {
            if(stripos($iline, "#EXT-X-I-FRAME") !== false && stripos($iline, ".m3u8") !== false)
            {
                //Extract IFrame Link
                $xb = explode('URI="', $iline);
                $xt = explode('"', $xb[1]);
                $segment_link = $xt[0];

                //New IFrame Link
                $new_link = hidmaster("encrypt", $iBaseLink.$segment_link)."_playlist.m3u8";

                //Final Link
                $hline .= str_replace($segment_link, $new_link, $iline)."\n";
            }
            elseif(stripos($iline, "#EXT-X-MEDIA:TYPE=AUDIO") !== false && stripos($iline, ".m3u8") !== false)
            {
                //Extract IFrame Link
                $xb = explode('URI="', $iline);
                $xt = explode('"', $xb[1]);
                $segment_link = $xt[0];

                //New IFrame Link
                $new_link = hidmaster("encrypt", $iBaseLink.$segment_link)."_playlist.m3u8";

                //Final Link
                $hline .= str_replace($segment_link, $new_link, $iline)."\n";
            }
            elseif(stripos($iline, ".m3u8") !== false)
            {
                $hline .= hidmaster("encrypt", $iBaseLink.$iline)."_playlist.m3u8"."\n";
            }
            else
            {
                $hline .= $iline."\n";
            }
        }
        header("Content-Type: application/vnd.apple.mpegurl");
        exit(trim($hline));
    }
    else
    {
        save_application_logs("Stream Load Failed [Mode - ".$streamMode."] - ".$sreq['channel_slug']." (".$sreq['channel_id'].") - HTTP Error Code ".$pgnfo['http_code']);
        header("Location: ../../../../video/index.m3u8");
        exit();
    }
}
elseif(stripos($sreq['resource'], "_playlist") !== false)
{
    $streamlink = "";
    $xlink = str_replace("_playlist", "", str_replace(".m3u8", "", $sreq['resource']));
    $hdlink = hidmaster("decrypt", $xlink);
    if(filter_var($hdlink, FILTER_VALIDATE_URL)) {
        $streamlink = $hdlink;
    }
    if(empty($streamlink)){ exit("Bad Request"); }

    if($streamMode == "JIONEWS") {
        $streamlink = $streamlink."?".jio_news_streamtoken();
    }
    if($streamMode == "JIO_NONOTP") {
        $streamlink = $streamlink."?".jio_nonotp_livetoken($sreq['channel_id']);
    }
    if($streamMode == "JIO_OTP") {
        $streamlink = $streamlink."?".jio_otp_livetoken($sreq['channel_id']);
    }
    $process = curl_init($streamlink); 
    curl_setopt($process, CURLOPT_HTTPHEADER, $streamHeaders); 
    curl_setopt($process, CURLOPT_HEADER, 0);
    curl_setopt($process, CURLOPT_ENCODING, '');
    curl_setopt($process, CURLOPT_TIMEOUT, 10); 
    curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1); 
    $return = curl_exec($process);
    $pgnfo = curl_getinfo($process);
    $finalurl = curl_getinfo($process, CURLINFO_EFFECTIVE_URL);
    curl_close($process);
    $iBaseLink = getJTVStreamBase($finalurl);
    if(stripos($return, "#EXTM3U") !== false)
    {
        $hline = "";
        $vline = explode("\n", $return);
        foreach ($vline as $iline)
        {
            if(stripos($iline, "#EXT-X-KEY") !== false)
            {
                //Extract Key Link
                $xb = explode('URI="', $iline);
                $xt = explode('"', $xb[1]);
                $key_link = $xt[0];

                //New Key Link
                $new_link = hidmaster("encrypt", $key_link).".key";

                //Final Link
                $hline .= str_replace($key_link, $new_link, $iline)."\n";
            }
            elseif(stripos($iline, ".ts") !== false)
            {
                if(strtolower(get_WorldWideProxyStatus()) !== "on")
                {
                    if($streamMode == "VOOT") {
                        $iline = $iline."?".voot_streamtoken();
                    }
                    elseif($streamMode == "JIO_NONOTP") {
                        $iline = $iline."?".jio_nonotp_livetoken($sreq['channel_id']);
                    }
                    elseif($streamMode == "JIO_OTP") {
                        $iline = $iline."?".jio_otp_livetoken($sreq['channel_id']);
                    }
                    else
                    {
                        $iline = $iline."?".jio_nonotp_livetoken($sreq['channel_id']);
                    }
                    $hline .= $iBaseLink.$iline."\n";
                }
                else
                {
                    $hline .= hidmaster("encrypt", $iBaseLink.$iline).".ts"."\n";
                }
            }
            elseif(stripos($iline, ".aac") !== false)
            {
                if(strtolower(get_WorldWideProxyStatus()) !== "on")
                {
                    if($streamMode == "VOOT") {
                        $iline = $iline."?".voot_streamtoken();
                    }
                    if($streamMode == "JIO_NONOTP") {
                        $iline = $iline."?".jio_nonotp_livetoken($sreq['channel_id']);
                    }
                    if($streamMode == "JIO_OTP") {
                        $iline = $iline."?".jio_otp_livetoken($sreq['channel_id']);
                    }
                    $hline .= $iBaseLink.$iline."\n";
                }
                else
                {
                    $hline .= hidmaster("encrypt", $iBaseLink.$iline).".aac"."\n";
                }
            }
            else
            {
                $hline .= $iline."\n";
            }
        }
        header("Content-Type: application/vnd.apple.mpegurl");
        exit(trim($hline));
    }
    else
    {
        http_response_code(404);
        exit();
    }
}
elseif(stripos($sreq['resource'], ".ts") !== false)
{
//=======================================================================//
    $tsLink = "";
    $xlink = str_replace(".ts", "", $sreq['resource']);
    $hdlink = hidmaster("decrypt", $xlink);
    if(filter_var($hdlink, FILTER_VALIDATE_URL)) {
        $tsLink = $hdlink;
    }
    if(empty($tsLink)){ exit("Bad Request"); }

    if($streamMode == "JIONEWS") {
        $tsLink = $tsLink."?".jio_news_streamtoken();
    }
    elseif($streamMode == "JIO_NONOTP") {
        $tsLink = $tsLink."?".jio_nonotp_livetoken($sreq['channel_id']);
    }
    elseif($streamMode == "JIO_OTP") {
        $tsLink = $tsLink."?".jio_otp_livetoken($sreq['channel_id']);
    }
    else {
        $tsLink = $tsLink."?".jio_nonotp_livetoken($sreq['channel_id']);
    }

    $process = curl_init($tsLink);
    curl_setopt($process, CURLOPT_HTTPHEADER, $streamHeaders); 
    curl_setopt($process, CURLOPT_HEADER, 0);
    curl_setopt($process, CURLOPT_ENCODING, '');
    curl_setopt($process, CURLOPT_TIMEOUT, 10); 
    curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1); 
    $return = curl_exec($process);
    $pgnfo = curl_getinfo($process);
    $finalurl = curl_getinfo($process, CURLINFO_EFFECTIVE_URL);
    curl_close($process);
    if($pgnfo['http_code'] == 200 || $pgnfo['http_code'] == 206)
    {
        header("Content-Type: video/mp2t");
        exit($return);
    }
    else
    {
        http_response_code(410);
        exit("Segment Gone");
    }
//=======================================================================//
}
elseif(stripos($sreq['resource'], ".aac") !== false)
{
//=======================================================================//
    $tsLink = "";
    $xlink = str_replace(".aac", "", $sreq['resource']);
    $hdlink = hidmaster("decrypt", $xlink);
    if(filter_var($hdlink, FILTER_VALIDATE_URL)) {
        $tsLink = $hdlink;
    }
    if(empty($tsLink)){ exit("Bad Request"); }

    if($streamMode == "JIONEWS") {
        $tsLink = $tsLink."?".jio_news_streamtoken();
    }
    elseif($streamMode == "JIO_NONOTP") {
        $tsLink = $tsLink."?".jio_nonotp_livetoken($sreq['channel_id']);
    }
    elseif($streamMode == "JIO_OTP") {
        $tsLink = $tsLink."?".jio_otp_livetoken($sreq['channel_id']);
    }
    else {
        $tsLink = $tsLink."?".jio_nonotp_livetoken($sreq['channel_id']);
    }
   
    $process = curl_init($tsLink);
    curl_setopt($process, CURLOPT_HTTPHEADER, $streamHeaders); 
    curl_setopt($process, CURLOPT_HEADER, 0);
    curl_setopt($process, CURLOPT_ENCODING, '');
    curl_setopt($process, CURLOPT_TIMEOUT, 10); 
    curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1); 
    $return = curl_exec($process);
    $pgnfo = curl_getinfo($process);
    $finalurl = curl_getinfo($process, CURLINFO_EFFECTIVE_URL);
    curl_close($process);
    if($pgnfo['http_code'] == 200 || $pgnfo['http_code'] == 206)
    {
        header("Content-Type: audio/aac");
        exit($return);
    }
    else
    {
        http_response_code(410);
        exit("Segment Gone");
    }
//=======================================================================//
}
elseif(stripos($sreq['resource'], ".key") !== false)
{
//==========================================================================================//
    $keySrvLink = "";
    $xlink = str_replace(".key", "", $sreq['resource']);
    $hdlink = hidmaster("decrypt", $xlink);
    if(filter_var($hdlink, FILTER_VALIDATE_URL)) {
        $keySrvLink = $hdlink;
    }
    if(empty($keySrvLink)){ exit("Bad Request"); }

    $keyHeads = array();
    $deviceId = substr(md5($APP_ENCRYPTION_KEY), 0, 16);
    $serielno = date('y').date('m').date('d').$sreq['channel_id'].'000';

    if(isset($JIO_AUTH['ssoToken']))
    {
        $keyHeads[] = "os: android";
        $keyHeads[] = "subscriberId: ".$JIO_AUTH['sessionAttributes']['user']['subscriberId'];
        $keyHeads[] = "deviceId: ".$deviceId;
        $keyHeads[] = "userId: ".$JIO_AUTH['sessionAttributes']['user']['uid'];
        $keyHeads[] = "versionCode: 290";
        $keyHeads[] = "devicetype: phone";
        $keyHeads[] = "crmid: ".$JIO_AUTH['sessionAttributes']['user']['subscriberId'];
        $keyHeads[] = "osVersion: 9";
        $keyHeads[] = "srno: ".$serielno;
        $keyHeads[] = "usergroup: tvYR7NSNn7rymo3F";
        $keyHeads[] = "uniqueId: ".$JIO_AUTH['sessionAttributes']['user']['unique'];
        $keyHeads[] = "User-Agent: plaYtv/7.0.8 (Linux;Android 9) ExoPlayerLib/2.11.7";
        $keyHeads[] = "ssotoken: ".$JIO_AUTH['ssoToken'];
        $keyHeads[] = "channelid: " . $sreq['channel_id'];
    }

    if($streamMode == "JIONEWS")
    {
        $keyHeads[] = "referer: https://www.jionews.com/";
        $keyHeads[] = "origin: https://www.jionews.com";
        $keySrvLink = $keySrvLink."?".jio_news_streamtoken();
    }
    if($streamMode == "VOOT")
    {
        if(stripos($keySrvLink, "fallback/bpk-tv") !== false)
        {
            $keySrvLink = str_replace("tv.media.jio.com/fallback/bpk-tv", "jiolivestreaming.akamaized.net/bpk-tv", $keySrvLink);
            $keySrvLink = $keySrvLink."?".voot_streamtoken();
        }
        elseif(stripos($keySrvLink, "/streams_live/") !== false)
        {
            $keyHeads[] = "referer: https://www.jionews.com/";
            $keyHeads[] = "origin: https://www.jionews.com";
            $keySrvLink = $keySrvLink."?".jio_news_streamtoken();
        }
        else
        {
            $keySrvLink = str_replace("tv.media.jio.com", "jiotvmblive.cdn.jio.com", $keySrvLink);
        }
    }
    if($streamMode == "JIO_NONOTP")
    {
        $keyHeads[] = "Cookie: ".jio_nonotp_livetoken($sreq['channel_id']);
        $keySrvLink = $keySrvLink."?".jio_nonotp_livetoken($sreq['channel_id']);
    }
    if($streamMode == "JIO_OTP")
    {
        $keyHeads[] = "Cookie: ".jio_otp_livetoken($sreq['channel_id']);
        $keySrvLink = $keySrvLink."?".jio_otp_livetoken($sreq['channel_id']);
    }
    if($streamMode == "HACK_UNLOGGED")
    {
        $keySrvLink = str_replace("tv.media.jio.com", "jiotvmblive.cdn.jio.com", $keySrvLink);
        $keyHeads[] = "Cookie: ".jio_nonotp_livetoken($sreq['channel_id']);
        $keySrvLink = $keySrvLink."?".jio_nonotp_livetoken($sreq['channel_id']);
    }
    
    $process = curl_init($keySrvLink);
    curl_setopt($process, CURLOPT_HTTPHEADER, $keyHeads); 
    curl_setopt($process, CURLOPT_HEADER, 0);
    curl_setopt($process, CURLOPT_ENCODING, '');
    curl_setopt($process, CURLOPT_TIMEOUT, 10); 
    curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1); 
    $return = curl_exec($process);
    $pgnfo = curl_getinfo($process);
    $finalurl = curl_getinfo($process, CURLINFO_EFFECTIVE_URL);
    curl_close($process);
    if($pgnfo['http_code'] == 200 || $pgnfo['http_code'] == 206)
    {
        header("Content-Type: application/binary");
        exit($return);
    }
    else
    {
        save_application_logs("JioTV Key Error :: [Mode - ".$streamMode."] :: ".$sreq['channel_slug']." (".$sreq['channel_id'].")");
        http_response_code(410);
        exit("Key Gone");
    }

//==========================================================================================//

}
else
{
    http_response_code(410);
    exit("Resource Gone");
}




?>