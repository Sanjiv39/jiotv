<?php

session_start();

include("inc.configs.php");

$action = ""; $page = 1;
if(isset($_REQUEST['page'])) {
    $page = trim($_REQUEST['page']);
}
if(isset($_REQUEST['action'])) {
    $action = trim($_REQUEST['action']);
}
$search = ""; $group = "";
if(isset($_REQUEST['search'])) {
    $search = trim($_REQUEST['search']);
}
if(isset($_REQUEST['group'])) {
    $group = trim($_REQUEST['group']);
}

$webapiStatus = webIApp("status");

if($action == "get_channels")
{
    if($webapiStatus !== "on") { response("error", "Feature Disabled", ""); }

    $tv_data = array();
    $channels = jio_tv_channels();
    $items_per_page = 40;
    $offset = ($page - 1) * $items_per_page;
    foreach($channels as $allTVCh)
    {
        if(!empty($search))
        {
            if(stripos($allTVCh['title'], $search) !== false)
            {
                $tv_data[] = array('id' => $allTVCh['id'],
                                   'slug' => $allTVCh['slug'],
                                   'title' => $allTVCh['title'],
                                   'image' => $allTVCh['logo'],
                                   'group' => $allTVCh['language']." - ".$allTVCh['genre']);
            }
        }
        else
        {
            if(!empty($group))
            {
                if(stripos($allTVCh['genre'], $group) !== false)
                {
                    $tv_data[] = array('id' => $allTVCh['id'],
                                        'slug' => $allTVCh['slug'],
                                        'title' => $allTVCh['title'],
                                        'image' => $allTVCh['logo'],
                                        'group' => $allTVCh['language']." - ".$allTVCh['genre']);
                }
                if(stripos($allTVCh['language'], $group) !== false)
                {
                    $tv_data[] = array('id' => $allTVCh['id'],
                                        'slug' => $allTVCh['slug'],
                                        'title' => $allTVCh['title'],
                                        'image' => $allTVCh['logo'],
                                        'group' => $allTVCh['language']." - ".$allTVCh['genre']);
                }
            }
            else
            {
                $tv_data[] = array('id' => $allTVCh['id'],
                                   'slug' => $allTVCh['slug'],
                                   'title' => $allTVCh['title'],
                                   'image' => $allTVCh['logo'],
                                   'group' => $allTVCh['language']." - ".$allTVCh['genre']);
            }
        }
    }
    if(empty($search))
    {
        $tv_data = array_slice($tv_data, $offset, $items_per_page);
    }
    
    if(!empty($tv_data))
    {
        response("success", "Paginated Channels List", array('count' => count($tv_data), 'page' => $page, 'group' => $group, 'search' => $search, 'list' => $tv_data));
    }
    else
    {
        response("error", "No Channels To Show", "");
    }
}
elseif($action == "get_streams")
{
    if($webapiStatus !== "on") { response("error", "Feature Disabled", ""); }

    $id = ""; $slug = "";
    if(isset($_REQUEST['id'])) {
        $id = trim($_REQUEST['id']);
    }
    if(isset($_REQUEST['slug'])) {
        $slug = trim($_REQUEST['slug']);
    }
    if(empty($id) && empty($slug))
    {
        response("error", "Mandatory Parameters Missing", "");
    }
    if(empty($id) && !empty($slug))
    {
        $id = getIDBySlug($slug);
    }
    if(!empty($id) && empty($slug))
    {
        $slug = getSlugByID($id);
    }
    $title = getTitleByID($id);
    $logo = getLogoByID($id);

    if(empty($title))
    {
        response("error", "Channel Not Found", "");
    }

    $streamqId = generateStreamToken()."/".$id."/".$slug;
    if($_SERVER['SERVER_PORT'] !== "80" && $_SERVER['SERVER_PORT'] !== "443")
    {
        $playurl = $streamenvproto.'://'.$_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT'].str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']).$streamqId.'/index.m3u8';
    }
    else
    {
        $playurl = $streamenvproto.'://'.$_SERVER['HTTP_HOST'].str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']).$streamqId.'/index.m3u8';
    }

    $playurl = str_replace(" ", "%20", $playurl);

    $response = array("id" => $id, "slug" => $slug, "title" => $title, "logo" => $logo, "playurl" => encrypt_api_res($playurl));
    
    response("success", "OK", $response);
}
elseif($action == "get_channels_group")
{
    if($webapiStatus !== "on") { response("error", "Feature Disabled", ""); }
    
    $list = array("Entertainment", "Movies", "Kids", "Sports", "Lifestyle", "Infotainment", "Religious", "News", "Music", "Regional", "Devotional", "Business News", "Educational", "Shopping", "Jio Darshan", "Hindi", "Marathi", "Punjabi", "Urdu", "Bengali", "English", "Malayalam", "Tamil", "Gujarati", "Odia", "Telugu", "Bhojpuri", "Kannada", "Assamese", "Nepali", "French");
    response("success", "OK", array("count" => count($list), "list" => $list));
}
elseif($action == "direct_play")
{
    if(directPlayAPI("status") == "off")
    {
        http_response_code(403);
        response("error", "Access To This Feature Is Disabled By Administrator", "");   
    }

    $id = ""; $token = "";
    if(isset($_REQUEST['c'])){ $id = $_REQUEST['c']; }
    if(isset($_REQUEST['id'])){ $id = $_REQUEST['id']; }
    if(isset($_REQUEST['token'])){ $token = $_REQUEST['token']; }

    if(empty($id)) {
        response("error", "Mandatory Parameters Missing", "");
    }

    if(is_numeric($id))
    {
        $slug = getSlugByID($id);
    }
    else
    {
        $slug = $id;
        $id = getIDBySlug($id);
    }

    if(empty($id) || empty($slug))
    {
        response("error", "Invalid Parameters Supplied", "");
    }

    $streamqId = generateStreamToken()."/".$id."/".$slug;
    if($_SERVER['SERVER_PORT'] !== "80" && $_SERVER['SERVER_PORT'] !== "443")
    {
        $playurl = $streamenvproto.'://'.$_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT'].str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']).$streamqId.'/index.m3u8';
    }
    else
    {
        $playurl = $streamenvproto.'://'.$_SERVER['HTTP_HOST'].str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']).$streamqId.'/index.m3u8';
    }

    $playurl = str_replace(" ", "%20", $playurl);

    header("Access-Control-Allow-Origin: *");
    header("Location: ".$playurl, true, 307);
    exit();
}
else
{
    response("error", "Bad Request", "");
}

?>