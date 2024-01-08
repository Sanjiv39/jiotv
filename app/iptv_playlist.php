<?php

session_start();

include("inc.configs.php");

$token = ""; $idata = "";

if(isset($_REQUEST['token']))
{
    $token = $_REQUEST['token'];
}

if(!empty($token))
{
    $idata = @json_decode(hidmaster("decrypt", $token), true);
}

if(empty($idata))
{
    http_response_code(401);
    exit();
}

$checkToken = sha1($idata['playlist_validity'].$idata['issue_time'].$idata['days'].$idata['type'].$APP_ENCRYPTION_KEY.$APP_STREAMING_KEY);

$expiry = $idata['issue_time'] + ($idata['playlist_validity'] * 3600);
if(time() > $expiry)
{
    http_response_code(410);
    exit();
}

if($checkToken !== $idata['hash'])
{
    http_response_code(403);
    exit();
}

$channels = jio_tv_channels();

if(empty($channels))
{
    http_response_code(400);
    exit("Channel List Not Found");
}

$playlistData = "";

$playlistData .= '#EXTM3U'."\n";
$v = 0;
foreach($channels as $mere)
{
    $v++;
    $playlistData .= '#EXTINF:-1 tvg-id="'.$mere['id'].'" tvg-name="'.$mere['title'].'" tvg-country="IN" tvg-logo="'.$mere['logo'].'" tvg-chno="'.$mere['id'].'" group-title="'.$mere['genre'].' - '.$mere['language'].'",'.$mere['title']."\n";
    if($_SERVER['SERVER_PORT'] !== "80" && $_SERVER['SERVER_PORT'] !== "443")
    {
        $playUrlBase = $streamenvproto."://".$plhoth.":".$_SERVER['SERVER_PORT'].str_replace(" ", "%20", str_replace("admin/", "streams/", str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF'])));
    }
    else
    {
        $playUrlBase = $streamenvproto."://".$plhoth.str_replace(" ", "%20", str_replace("admin/", "streams/", str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF'])));
    }
    if($idata['type'] == "direct_play")
    {
        $playurl = str_replace("app/", "", $playUrlBase)."autoq.php?c=".$mere['slug']."&e=.m3u8";
    }
    else
    {
        $playurl = $playUrlBase.generateStreamTokenForPlaylist($idata['days'])."/".$mere['id']."/".$mere['slug']."/index.m3u8";
    }
    $playurl = str_replace(" ", "%20", $playurl);
    $playlistData .= $playurl."\n";

}   

if(!empty($playlistData))
{
    $file = "JioTV_" . time() . "_usftoolsHub.m3u";
    header('Content-Disposition: attachment; filename="'.$file.'"');
    header("Content-Type: application/vnd.apple.mpegurl");
    exit($playlistData);
}
else
{
    http_response_code(404);
    exit();
}



?>