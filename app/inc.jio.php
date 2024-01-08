<?php

function send_jio_otp($mobile)
{
    $zm_api = 'https://jiotvapi.media.jio.com/userservice/apis/v1/loginotp/send';
    $zm_headers = array('appname: RJIL_JioTV', 'os: android', 'devicetype: phone', 'content-type: application/json', 'user-agent: okhttp/3.14.9');
    $zm_payload = array('number' => base64_encode('+91'.$mobile));
    $process = curl_init($zm_api);
    curl_setopt($process, CURLOPT_POST, 1);
    curl_setopt($process, CURLOPT_POSTFIELDS, json_encode($zm_payload));
    curl_setopt($process, CURLOPT_HTTPHEADER, $zm_headers); 
    curl_setopt($process, CURLOPT_HEADER, 0);
    curl_setopt($process, CURLOPT_TIMEOUT, 10);
    curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
    $zm_resp = curl_exec($process);
    $zm_info = curl_getinfo($process);
    curl_close($process);
    $zm_data = @json_decode($zm_resp, true);
    if($zm_info['http_code'] == 204)
    {
        $respoz['status'] = "success";
        $respoz['message'] = "OTP Sent Successfully";
    }
    else
    {
        $respoz['status'] = "error";
        if(isset($zm_data['message']) && !empty($zm_data['message']))
        {
            $respoz['message'] = "Jio Error - ".$zm_data['message'];
        }
        else
        {
            $respoz['message'] = "Unknown Error Occured : Code ".$zm_info['http_code'];
        }
    }
    return $respoz;
}

function verify_jio_otp($mobile, $otp)
{
    global $APP_DATA_FOLDER;
    $zm_api = 'https://jiotvapi.media.jio.com/userservice/apis/v1/loginotp/verify';
    $zm_headers = array('appname: RJIL_JioTV', 'os: android', 'devicetype: phone', 'content-type: application/json', 'user-agent: okhttp/3.14.9');
    $zm_payload = '{"number":"'.base64_encode('+91'.$mobile).'","otp":"'.$otp.'","deviceInfo":{"consumptionDeviceName":"RMX1945","info":{"type":"android","platform":{"name":"RMX1945"},"androidId":"'.substr(sha1(time().rand(00, 99)), 0, 16).'"}}}';
    $process = curl_init($zm_api);
    curl_setopt($process, CURLOPT_POST, 1);
    curl_setopt($process, CURLOPT_POSTFIELDS, $zm_payload);
    curl_setopt($process, CURLOPT_HTTPHEADER, $zm_headers); 
    curl_setopt($process, CURLOPT_HEADER, 0);
    curl_setopt($process, CURLOPT_TIMEOUT, 10);
    curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
    $zm_resp = curl_exec($process);
    $zm_info = curl_getinfo($process);
    curl_close($process);
    $zm_data = @json_decode($zm_resp, true);
    if (isset($zm_data['ssoToken']) && !empty($zm_data['ssoToken']))
    {
        if(file_put_contents($APP_DATA_FOLDER."/usf_tplogin", $zm_resp))
        {
            $respoz['status'] = "success";
            $respoz['message'] = "Jio LoggedIn Successfully";
        }
        else
        {
            $respoz['status'] = "error";
            $respoz['message'] = "Logged In Successfully But Failed To Save Data";
        }
    }
    else
    {
        $respoz['status'] = "error";
        if(isset($zm_data['message']) && !empty($zm_data['message']))
        {
            $respoz['message'] = "Jio Error - ".$zm_data['message'];
        }
        elseif(isset($zm_data['errors'][1]['message']))
        {
            $respoz['message'] = "Jio Error - ".$zm_data['errors'][1]['message'];
        }
        elseif(isset($zm_data['errors'][0]['message']))
        {
            $respoz['message'] = "Jio Error - ".$zm_data['errors'][0]['message'];
        }
        else
        {
            $respoz['message'] = "Unknown Error Occured : Code ".$zm_info['http_code'];
        }
    }
    return $respoz;
}

function jio_sso_login($identifier, $password)
{
    global $APP_DATA_FOLDER;
    global $jioDataPath;
    $zm_api = 'https://api.jio.com/v3/dip/user/unpw/verify';
    $zm_headers = array('user-agent: okhttp/3.14.9', 'os: android', 'devicetype: phone', 'content-type: application/json', 'x-api-key: l7xx938b6684ee9e4bbe8831a9a682b8e19f');
    $zm_payload = array('identifier' => $identifier,
                        'password' => $password,
                        'rememberUser' => 'T',
                        'upgradeAuth' => 'Y',
                        'returnSessionDetails' => 'T',
                        'deviceInfo' => array('consumptionDeviceName' => 'samsung SM-G930F',
                                              'info' => array('type' => 'android',
                                                              'platform' => array('name' => 'SM-G930F',
                                                                                  'version' => '5.1.1'),
                                                               'androidId' => substr(sha1(time().rand(0,9)), 0, 16))));
    $process = curl_init($zm_api);
    curl_setopt($process, CURLOPT_POST, 1);
    curl_setopt($process, CURLOPT_POSTFIELDS, json_encode($zm_payload));
    curl_setopt($process, CURLOPT_HTTPHEADER, $zm_headers); 
    curl_setopt($process, CURLOPT_HEADER, 0);
    curl_setopt($process, CURLOPT_TIMEOUT, 10);
    curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
    $zm_resp = curl_exec($process);
    $zm_info = curl_getinfo($process);
    curl_close($process);
    $zm_data = @json_decode($zm_resp, true);
    if(isset($zm_data['ssoToken']) && !empty($zm_data['ssoToken']))
    {
        $zm_data['password'] = $password;
        if(file_put_contents($jioDataPath, json_encode($zm_data)))
        {
            $respoz['status'] = "success";
            $respoz['message'] = "Jio LoggedIn Successfully";
        }
        else
        {
            $respoz['status'] = "error";
            $respoz['message'] = "Logged In Successfully But Failed To Save Data";
        }
    }
    else
    {
        $respoz['status'] = "error";
        if(isset($zm_data['message']) && !empty($zm_data['message']))
        {
            $respoz['message'] = "Jio Error - ".$zm_data['message'];
        }
        elseif(isset($zm_data['errors'][1]['message']))
        {
            $respoz['message'] = "Jio Error - ".$zm_data['errors'][1]['message'];
        }
        elseif(isset($zm_data['errors'][0]['message']))
        {
            $respoz['message'] = "Jio Error - ".$zm_data['errors'][0]['message'];
        }
        else
        {
            $respoz['message'] = "Unknown Error Occured : Code ".$zm_info['http_code'];
        }
    }
    return $respoz;
}

function send_jionews_otp($mobile)
{
    $JNLoginAPI = "https://jionews.com/user/apis/v1.1/sendOTP";
    $JNLoginPost = '{"number":"'.base64_encode('+91'.$mobile).'"}';
    $JNLoginHeader = array("content-type: application/json",
                           "devicetype: mweb",
                           "origin: https://jionews.com",
                           "os: web",
                           "referer: https://jionews.com/liveTv/News18-India/231",
                           "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36");
    $process = curl_init($JNLoginAPI);
    curl_setopt($process, CURLOPT_POST, 1);
    curl_setopt($process, CURLOPT_POSTFIELDS, $JNLoginPost);
    curl_setopt($process, CURLOPT_HTTPHEADER, $JNLoginHeader); 
    curl_setopt($process, CURLOPT_HEADER, 0);
    curl_setopt($process, CURLOPT_TIMEOUT, 10);
    curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
    $zm_resp = curl_exec($process);
    $zm_info = curl_getinfo($process);
    curl_close($process);
    $zm_data = @json_decode($zm_resp, true);
    if($zm_info['http_code'] == 204)
    {
        $respoz['status'] = "success";
        $respoz['message'] = "OTP Sent Successfully";
    }
    else
    {
        $respoz['status'] = "error";
        if(isset($zm_data['result']['message']) && !empty($zm_data['result']['message']))
        {
            $respoz['message'] = "JioNews Error - ".$zm_data['result']['message'];
        }
        elseif(isset($zm_data['message']) && !empty($zm_data['message']))
        {
            $respoz['message'] = "JioNews Error - ".$zm_data['message'];
        }
        elseif(isset($zm_data['errors'][1]['message']))
        {
            $respoz['message'] = "JioNews Error - ".$zm_data['errors'][1]['message'];
        }
        elseif(isset($zm_data['errors'][0]['message']))
        {
            $respoz['message'] = "JioNews Error - ".$zm_data['errors'][0]['message'];
        }
        else
        {
            $respoz['message'] = "Unknown Error Occured : Code ".$zm_info['http_code'];
        }
    }
    return $respoz;
}

function verify_jionews_otp($mobile, $otp)
{
    global $APP_DATA_FOLDER;
    global $jionewsDataPath;
    $JNLoginAPI = "https://jionews.com/user/apis/v1.1/verifyOTP";
    $JNLoginPost = '{"otp":"'.$otp.'","number":"'.base64_encode('+91'.$mobile).'","deviceInfo":{"consumptionDeviceName":"Browser","info":{"type":"android","platform":{"name":"Chrome"},"deviceId":"'.mt_rand(100000000, 999999999).'"}},"langIds":[1,2]}';
    $JNLoginHeader = array("content-type: application/json",
                           "devicetype: mweb",
                           "origin: https://jionews.com",
                           "os: web",
                           "referer: https://jionews.com/liveTv/News18-India/231",
                           "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36");
    $process = curl_init($JNLoginAPI);
    curl_setopt($process, CURLOPT_POST, 1);
    curl_setopt($process, CURLOPT_POSTFIELDS, $JNLoginPost);
    curl_setopt($process, CURLOPT_HTTPHEADER, $JNLoginHeader); 
    curl_setopt($process, CURLOPT_HEADER, 0);
    curl_setopt($process, CURLOPT_TIMEOUT, 10);
    curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
    $zm_resp = curl_exec($process);
    $zm_info = curl_getinfo($process);
    curl_close($process);
    $zm_data = @json_decode($zm_resp, true);
    if(isset($zm_data['code']) && $zm_data['code'] == 200)
    {
        if(file_put_contents($jionewsDataPath, json_encode($zm_data['result'])))
        {
            $respoz['status'] = "success";
            $respoz['message'] = "JioNews LoggedIn Successfully";
        }
        else
        {
            $respoz['status'] = "error";
            $respoz['message'] = "JioNews Logged In But Failed To Save Data";
        }
    }
    else
    {
        $respoz['status'] = "error";
        if(isset($zm_data['result']['message']) && !empty($zm_data['result']['message']))
        {
            $respoz['message'] = "JioNews Error - ".$zm_data['result']['message'];
        }
        elseif(isset($zm_data['message']) && !empty($zm_data['message']))
        {
            $respoz['message'] = "JioNews Error - ".$zm_data['message'];
        }
        elseif(isset($zm_data['errors'][1]['message']))
        {
            $respoz['message'] = "JioNews Error - ".$zm_data['errors'][1]['message'];
        }
        elseif(isset($zm_data['errors'][0]['message']))
        {
            $respoz['message'] = "JioNews Error - ".$zm_data['errors'][0]['message'];
        }
        else
        {
            $respoz['message'] = "Unknown Error Occured : Code ".$zm_info['http_code'];
        }
    }
    return $respoz;
}

function voot_login($identifier, $password)
{
    global $APP_DATA_FOLDER;
    global $vootDataPath;
    if(is_numeric($identifier))
    {
        $login_type = "mobile";
        $loginPost = '{"type":"mobile","deviceId":"'.voot_device_id().'","deviceBrand":"PC/MAC","data":{"mobile":"'.$identifier.'","countryCode":"+91","password":"'.$password.'"}}';
    }
    else
    {
        $identifier = trim(strtolower($identifier));
        $login_type = 'traditional';
        $loginPost = '{"type":"traditional","deviceId":"' . voot_device_id() . '","deviceBrand":"PC/MAC","data":{"email":"' . $identifier . '","password":"' . $password . '"}}';
    }
        
    $loginApi = "https://userauth.voot.com/usersV3/v3/login";
    $loginHeader = array("Accept: application/json",
                         "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36",
                         "Content-Type: application/json;charset=UTF-8",
                         "Referer: https://www.voot.com/",
                         "Origin: https://www.voot.com");
    $process = curl_init($loginApi);
    curl_setopt($process, CURLOPT_POST, 1);
    curl_setopt($process, CURLOPT_POSTFIELDS, $loginPost);
    curl_setopt($process, CURLOPT_HTTPHEADER, $loginHeader);
    curl_setopt($process, CURLOPT_HEADER, 0);
    curl_setopt($process, CURLOPT_TIMEOUT, 10);
    curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
    $logResp = curl_exec($process);
    curl_close($process);
    $loginData = @json_decode($logResp, true);
    if (isset($loginData['data']['authToken']['accessToken'])) 
    {
        if(file_put_contents($vootDataPath, $logResp))
        {
            $respoz['status'] = "success";
            $respoz['message'] = "Voot LoggedIn Successfully";
        }
        else
        {
            $respoz['status'] = "error";
            $respoz['message'] = "Voot LoggedIn But Failed To Save Data";
        }
    }
    else
    {
        $loginErr = "";
        if (isset($loginData['status']['message'])) {
            if (!empty($loginData['status']['message'])) {
                $loginErr = $loginData['status']['message'];
            }
        }
        $respoz['status'] = "error";
        $respoz['message'] = "Error: Failed To Login. " . $loginErr;
    }
    return $respoz;
}

//==================================================================================//
//          R E F R E S H       T O K E N       F U N C T I O N S
//==================================================================================//

function refresh_voot_token()
{
    $output = false;
    $VTUD = voot_login_data();
    $access_token = $refresh_token = "";
    if(!empty($VTUD))
    {
        $refreshTokenAPI = "https://userauth.voot.com/usersV3/v3/refresh-access-token?id=".time();
        $refreshTokenHeaders = array("user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36",
                                     "refreshaccesstoken: ".$VTUD['data']['authToken']['refreshToken'],
                                     "origin: https://www.voot.com",
                                     "referer: https://www.voot.com/",
                                     "deviceinfo: ".voot_device_id(),
                                     "buildnumber: ");
        $process = curl_init($refreshTokenAPI);
        curl_setopt($process, CURLOPT_HTTPHEADER, $refreshTokenHeaders); 
        curl_setopt($process, CURLOPT_HEADER, 0);
        curl_setopt($process, CURLOPT_TIMEOUT, 10); 
        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
        $return = curl_exec($process); 
        curl_close($process); 
        $vdata = @json_decode($return, true);
        if(isset($vdata['access_token']))
        {
            $access_token = $vdata['access_token'];
        }
        if(isset($vdata['refresh_token']))
        {
            $refresh_token = $vdata['refresh_token'];
        }
        
        if(!empty($access_token) && !empty($refresh_token))
        {
            save_application_logs("Voot - New Refreshed AuthToken Fetched Successfully");
            saveNewVootTokens($access_token, $refresh_token);
            $output = true;
        }
    }
    return $output;
}

function saveNewVootTokens($access_token, $refresh_token)
{
    global $vootDataPath;
    $output = false;
    if(file_exists($vootDataPath))
    {
        $readJioData = @file_get_contents($vootDataPath);
        if(!empty($readJioData))
        {
            $lgJioTV = @json_decode($readJioData, true);
            if(isset($lgJioTV['data']['authToken']['refreshToken']))
            {
                $lgJioTV['data']['authToken']['accessToken'] = $access_token;
                $lgJioTV['data']['authToken']['refreshToken'] = $refresh_token;
                if(file_put_contents($vootDataPath, json_encode($lgJioTV)))
                {
                    save_application_logs("Voot - New Refreshed AuthToken Saved Successfully");
                    $output = true;
                }
            }
        }
    }
    if($output !== true)
    {
        save_application_logs("Voot - Failed To Save New Refreshed AuthToken");
    }
    return $output;
}

function refresh_jionews_token()
{
    $output = false;
    $JNDUL = jionews_login_data();
    if(!empty($JNDUL))
    {
        $refreshTokenAPI = 'https://jionews.com/user/apis/v1.1/refreshtoken';
        $refreshTokenPost = '{"uuid":"'.$JNDUL['uuid'].'","mToken":"'.$JNDUL['mToken'].'"}';
        $refreshTokenHeaders = array("content-type: application/json",
                                     "origin: https://jionews.com",
                                     "referer: https://jionews.com/liveTv/News18-India/231",
                                     "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36");
        $process = curl_init($refreshTokenAPI); 
        curl_setopt($process, CURLOPT_POST, 1);
        curl_setopt($process, CURLOPT_POSTFIELDS, $refreshTokenPost);
        curl_setopt($process, CURLOPT_HTTPHEADER, $refreshTokenHeaders); 
        curl_setopt($process, CURLOPT_HEADER, 0);
        curl_setopt($process, CURLOPT_TIMEOUT, 10); 
        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
        $return = curl_exec($process); 
        curl_close($process); 
        $kdata = @json_decode($return, true);
        if(isset($kdata['result']['accessToken']) && !empty($kdata['result']['accessToken']))
        {
            save_application_logs("Jio News - New Refreshed AuthToken Fetched Successfully");
            $newAuthToken = $kdata['result']['accessToken'];
            saveNewJioNewsAuthToken($newAuthToken);
            $output = true;
        }
    }
    return $output;
}

function saveNewJioNewsAuthToken($authToken)
{
    global $jionewsDataPath;
    $output = false;
    if(file_exists($jionewsDataPath))
    {
        $readJioData = @file_get_contents($jionewsDataPath);
        if(!empty($readJioData))
        {
            $lgJioTV = @json_decode($readJioData, true);
            if(isset($lgJioTV['accessToken']))
            {
                $lgJioTV['accessToken'] = $authToken;
                if(file_put_contents($jionewsDataPath, json_encode($lgJioTV)))
                {
                    save_application_logs("Jio News - New Refreshed AuthToken Saved Successfully");
                    $output = true;
                }
            }
        }
    }
    if($output !== true)
    {
        save_application_logs("Jio News - Failed To Save New Refreshed AuthToken");
    }
    return $output;
}

function refresh_jiotv_token()
{
    $output = false;
    $ErrorMsg = "Unknown";
    $JIO_AUTH = jiotv_login_data();
    if(!empty($JIO_AUTH))
    {
        $mTokenApi = "https://auth.media.jio.com/tokenservice/apis/v1/refreshtoken?langId=6";
        $mTokenPost = '{"appName":"RJIL_JioTV","deviceId":"'.getJioTVDeviceId().'","refreshToken":"'.$JIO_AUTH['refreshToken'].'"}';
        $mTokenHeads = array("accesstoken: ".$JIO_AUTH['authToken'],
                             "uniqueId: ".$JIO_AUTH['sessionAttributes']['user']['unique'],
                             "devicetype: phone",
                             "versionCode: 290",
                             "os: android",
                             "Content-Type: application/json");
        $process = curl_init($mTokenApi); 
        curl_setopt($process, CURLOPT_POST, 1);
        curl_setopt($process, CURLOPT_POSTFIELDS, $mTokenPost);
        curl_setopt($process, CURLOPT_HTTPHEADER, $mTokenHeads); 
        curl_setopt($process, CURLOPT_HEADER, 0);
        curl_setopt($process, CURLOPT_TIMEOUT, 10); 
        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
        $return = curl_exec($process); 
        curl_close($process); 
        $kdata = @json_decode($return, true);
        if(isset($kdata['message']) && !empty($kdata['message']))
        {
            $ErrorMsg = $kdata['message'];
        }
        if(isset($kdata['authToken']) && !empty($kdata['authToken']))
        {
            save_application_logs("JioTV [OTP Login] - New Refreshed AuthToken Fetched Successfully");
            $newAuthToken = $kdata['authToken'];
            saveNewJioTVAuthToken($newAuthToken);
            $output = true;
        }
    }
    if($output !== true) {
        save_application_logs("JioTV [OTP Login] - AuthToken Refresh Failed. Error - ".$ErrorMsg);
    }
    return $output;
}

function saveNewJioTVAuthToken($authToken)
{
    global $jioDataPath;
    $output = false;
    if(file_exists($jioDataPath))
    {
        $readJioData = @file_get_contents($jioDataPath);
        if(!empty($readJioData))
        {
            $lgJioTV = @json_decode($readJioData, true);
            if(isset($lgJioTV['authToken']))
            {
                $lgJioTV['authToken'] = $authToken;
                if(file_put_contents($jioDataPath, json_encode($lgJioTV)))
                {
                    save_application_logs("JioTV [OTP Login] - New Refreshed AuthToken Saved Successfully");
                    $output = true;
                }
            }
        }
    }
    if($output !== true)
    {
        save_application_logs("JioTV [OTP Login] - Failed To Save New Refreshed AuthToken");
    }
    return $output;
}

//====================================================================================//
//      C   H   A   N   N   E   L   S       F   U   N   C   T   I   O   N   S
//====================================================================================//
function delete_jio_tv_channels()
{
    global $APP_DATA_FOLDER;
    $res = false;
    $cachedList = $APP_DATA_FOLDER."/usf_ChnList";
    if(file_exists($cachedList))
    {
        if(unlink($cachedList))
        {
            $res = true;
        }
    }
    else
    {
        $res = true;
    }
    if($res == true)
    {
        save_application_logs("JioTV Channels List Deleted Successfully");
    }
    return $res;
}

function jio_tv_channels()
{
    global $JIOTV_IMAGES_CDN;
    global $APP_DATA_FOLDER;
    $lived = array();
    $cachedList = $APP_DATA_FOLDER."/usf_ChnList";
    if(file_exists($cachedList))
    {
        $readList = @file_get_contents($cachedList);
        if(!empty($readList))
        {
            $renList = @json_decode($readList, true);
            if(isset($renList[0]['id']))
            {
                $lived = $renList;
            }
        }
    }
    
    if(empty($lived))
    {
        $jiotv_genre_mapping = array("5" => "Entertainment", "6" => "Movies", "7" => "Kids", "8" => "Sports", "9" => "Lifestyle", "10" => "Infotainment", "11" => "Religious", "12" => "News", "13" => "Music", "14" => "Regional", "15" => "Devotional", "16" => "Business News", "17" => "Educational", "18" => "Shopping", "19" => "Jio Darshan");
        $jiotv_lang_mapping = array("1" => "Hindi", "2" => "Marathi", "3" => "Punjabi", "4" => "Urdu", "5" => "Bengali", "6" => "English", "7" => "Malayalam", "8" => "Tamil", "9" => "Gujarati", "10" => "Odia", "11" => "Telugu", "12" => "Bhojpuri", "13" => "Kannada", "14" => "Assamese", "15" => "Nepali", "16" => "French", "17" => "", "18" => "", "19" => "");
        $channelAPI = "http://jiotv.data.cdn.jio.com/apis/v1.3/getMobileChannelList/get/?langId=6&os=android&devicetype=phone&usergroup=tvYR7NSNn7rymo3F&version=6.0.9&langId=6";
        $headers = array("User-Agent: okhttp/4.9.0");
        $process = curl_init($channelAPI); 
        curl_setopt($process, CURLOPT_HTTPHEADER, $headers); 
        curl_setopt($process, CURLOPT_HEADER, 0);
        curl_setopt($process, CURLOPT_ENCODING, '');
        curl_setopt($process, CURLOPT_TIMEOUT, 10); 
        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1); 
        $return = curl_exec($process);
        $iofo = curl_getinfo($process);
        $ioerr = curl_error($process);
        curl_close($process); 
        $fdata = @json_decode($return, true);
        if(isset($fdata['code']) && $fdata['code'] == 200 && isset($fdata['result'][0]))
        {
            foreach($fdata['result'] as $tvc)
            {
                $lived[] = array("id" => $tvc['channel_id'],
                                "title" => $tvc['channel_name'],
                                "logo" => $JIOTV_IMAGES_CDN.$tvc['logoUrl'],
                                "genre" => $jiotv_genre_mapping[$tvc['channelCategoryId']],
                                "language" => $jiotv_lang_mapping[$tvc['channelLanguageId']],
                                "HD" => $tvc['isHD'],
                                "slug" => str_replace(".png", "", $tvc['logoUrl']),
                                "catchup" => $tvc['stbCatchup']);
            }
        }
        if(isset($lived[0]['id']))
        {
            save_application_logs("JioTV Channels List Updated Successfully");
            @file_put_contents($cachedList, json_encode($lived));
        }
        else
        {
            save_application_logs("JioTV Channels List Failed To Update");
        }
    }
    return $lived;
}

//====================================================================================//
//    L   O   G   I   N       D   A   T   A       F   U   N   C   T   I   O   N   S   //
//====================================================================================//

function voot_device_id()
{
    global $APP_DATA_FOLDER;
    $output = "";
    $devIDpath = $APP_DATA_FOLDER . "/vootDeviceID";
    if (file_exists($devIDpath))
    {
        $fDeviceId = @file_get_contents($devIDpath);
        if (!empty($fDeviceId)) { $output = $fDeviceId; }
    }
    if (empty($output))
    {
        $deviceIDheads = array("User-Agent: usftoolshttp/1.0");
        $process = curl_init(base64_decode(base64_decode("YUhSMGNITTZMeTlrWlhacFkyVnBaQzUxYzJaMGIyOXNjMmgxWWkxalpHNHVkMjl5YTJWeWN5NWtaWFl2")));
        curl_setopt($process, CURLOPT_HTTPHEADER, $deviceIDheads);
        curl_setopt($process, CURLOPT_TIMEOUT, 10);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
        $deviceRT = curl_exec($process);
        curl_close($process);
        $deviceD = @json_decode($deviceRT, true);
        if (isset($deviceD['device_id']) && !empty($deviceD['device_id'])) {
            @file_put_contents($devIDpath, $deviceD['device_id']);
            $output = $deviceD['device_id'];
        }
    }
    return $output;
}

function voot_login_data()
{
    global $vootDataPath;
    $output = array();
    $Voot_Login_Path = $vootDataPath;
    if(file_exists($Voot_Login_Path))
    {
        $read_VTUD = @file_get_contents($Voot_Login_Path);
        if(!empty($read_VTUD))
        {
            $data_VTUD = @json_decode($read_VTUD, true);
            if(isset($data_VTUD['data']['authToken']['accessToken'])) 
            {
                $output = $data_VTUD;
            }
        }
    }
    return $output;
}

function jiotv_login_data()
{
    global $jioDataPath;
    $output = array();
    $JioTV_login_path = $jioDataPath;
    if(file_exists($JioTV_login_path))
    {
        $read_tplgda = @file_get_contents($JioTV_login_path);
        if(!empty($read_tplgda))
        {
            $data_tplgda = @json_decode($read_tplgda, true);
            if(isset($data_tplgda['ssoToken']) && !empty($data_tplgda['ssoToken']))
            {
                $output = $data_tplgda;
            }
        }
    }
    if(!empty($output))
    {
        if(isset($output['password']) && !empty($output['password']))
        {
            $output['JTVloginMethod'] = "NON-OTP";
        }
        else
        {
            $output['JTVloginMethod'] = "OTP";
        }
    }
    return $output;
}

function jionews_login_data()
{
    global $jionewsDataPath;
    $output = array();
    $JioNews_Login_Path = $jionewsDataPath;
    if(file_exists($JioNews_Login_Path))
    {
        $read_JNLD = @file_get_contents($JioNews_Login_Path);
        if(!empty($read_JNLD))
        {
            $data_JNLD = @json_decode($read_JNLD, true);
            if(!empty($data_JNLD))
            {
                $output = $data_JNLD;
            }
        }
    }
    return $output;
}

//====================================================================================//
//          J I O N E W S       S T R E A M I N G       L I N K S                     //
//====================================================================================//

function jio_news_streamlink($id, $slug)
{
    global $JIONEWS_STREAM_HEADERS;
    $playurl = "";
    $token = jio_news_streamtoken();

    $voipath = voot_jionews_nonotpapi_links($id);
    if(!empty($voipath))
    {
        $playurl = "https://jionewslive.cdn.jio.com".$voipath."?".$token;
    }
    else
    {
        $type1 = "https://jionewslive.cdn.jio.com/bpk-tv/".$slug."_MOB/Fallback/index.m3u8"."?".$token;
        $type2 = "https://jionewslive.cdn.jio.com/packagerx_mpd3/".$slug."_HLS/".$slug.".m3u8"."?".$token;
        $type3 = "https://jionewslive.cdn.jio.com/".$slug."/".$slug.".m3u8"."?".$token;
        $type4 = "https://jionewslive.cdn.jio.com/packagerx_mpd2/".$slug."_HLS/".$slug.".m3u8"."?".$token;

        $ezmoS = pingStreamRespo($type1, $JIONEWS_STREAM_HEADERS);
        if(empty($ezmoS['data'])) {
            $ezmoS = pingStreamRespo($type2, $JIONEWS_STREAM_HEADERS);
            if(empty($ezmoS['data'])) {
                $ezmoS = pingStreamRespo($type3, $JIONEWS_STREAM_HEADERS);
                if(empty($ezmoS['data'])) {
                    $ezmoS = pingStreamRespo($type4, $JIONEWS_STREAM_HEADERS);
                }
            }
        }

        if(isset($ezmoS['url']) && !empty($ezmoS['url'])) {
            $playurl = $ezmoS['url'];
        }
    }

    if(empty($playurl)) {
        save_application_logs("JioNews Error : Failed To Fetch Channel Stream Link | ".$slug." (".$id.")");
    }
    return $playurl;
}

function jio_news_streamtoken()
{
    global $APP_DATA_FOLDER;
    $JNToken = "";
    $JN_UData = jionews_login_data();
    $JN_Token_Cache = $APP_DATA_FOLDER."/usf_JNToken";
    if(file_exists($JN_Token_Cache))
    {
        $JN_Token_CXTime = ""; $JN_Token_CXToken = "";
        $JN_Token_CacheData = @file_get_contents($JN_Token_Cache);
        if(stripos($JN_Token_CacheData, "|||") !== false)
        {
            $JN_Token_CXDT = explode("|||", $JN_Token_CacheData);
            if(isset($JN_Token_CXDT[0]) && !empty($JN_Token_CXDT[0]))
            {
                $JN_Token_CXTime = trim($JN_Token_CXDT[0]);
            }
            if(isset($JN_Token_CXDT[1]) && !empty($JN_Token_CXDT[1]))
            {
                $JN_Token_CXToken = trim($JN_Token_CXDT[1]);
            }
        }
        if(!empty($JN_Token_CXTime) && !empty($JN_Token_CXToken))
        {
            if(time() < $JN_Token_CXTime)
            {
                $JNToken = $JN_Token_CXToken;
            }
        }
    }
    if(!empty($JN_UData) && empty($JNToken))
    {
        $JNTokenata = jio_news_live_token();
        if(empty($JNTokenata))
        {
            $JNTokenata = jio_news_live_token();
            if(empty($JNTokenata))
            {
                if(refresh_jionews_token() == true)
                {
                    $JNTokenata = jio_news_live_token();
                }
            }
        }
        
        if(!empty($JNTokenata))
        {
            $JNToken = $JNTokenata['token'];
            if(file_put_contents($JN_Token_Cache, $JNTokenata['time']."|||".$JNTokenata['token'])){}
        }
    }
    return $JNToken;
}

function jio_news_live_token()
{
    $JNToken = ""; $JNTokenTime = "";
    $JN_UData = jionews_login_data();
    if(!empty($JN_UData))
    {
        $JN_Stream_Api = "https://jionews.com/livetv/apis/v1.1/stream";
        $JN_Stream_Post = '{"channelId":"231","streamType":"live","uuid":"'.$JN_UData['uuid'].'"}';
        $JN_Stream_Headers = array("user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36",
                                   "os: web",
                                   "referer: https://jionews.com/liveTv/News18-India/231",
                                   "origin: https://jionews.com",
                                   "devicetype: pc",
                                   "content-type: application/json",
                                   "buildversion: 4.7.5",
                                   "accesstoken: ".$JN_UData['accessToken']);
        $process = curl_init($JN_Stream_Api); 
        curl_setopt($process, CURLOPT_POST, 1);
        curl_setopt($process, CURLOPT_POSTFIELDS, $JN_Stream_Post);
        curl_setopt($process, CURLOPT_HTTPHEADER, $JN_Stream_Headers); 
        curl_setopt($process, CURLOPT_HEADER, 0);
        curl_setopt($process, CURLOPT_TIMEOUT, 10); 
        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);  
        $return = curl_exec($process); 
        curl_close($process);
        $codata = @json_decode($return, true);
        if(isset($codata['result']['metadata']['jct']) && isset($codata['result']['metadata']['pxe']))
        {
            save_application_logs("JioNews Stream Token Fetch Successful");
            $JNTokenTime = $codata['result']['metadata']['pxe'];
            $JNToken = "jct=".$codata['result']['metadata']['jct']."&pxe=".$codata['result']['metadata']['pxe']."&st=".$codata['result']['metadata']['st']."&secversion=".$codata['result']['metadata']['secversion'];
        }
        else
        {
            save_application_logs("JioNews Stream Token Fetch Failed");
        }
    }
    if(!empty($JNToken) && !empty($JNTokenTime))
    {
        return array("token" => $JNToken, "time" => $JNTokenTime);
    }
    else
    {
        return array();
    }
}

//====================================================================================//
//          V O O T       S T R E A M I N G       L I N K S                           //
//====================================================================================//

function voot_streamlink($id, $slug)
{
    $VOOT_STREAM_HEADERS = array("User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36",
                                 "Origin: https://www.voot.com",
                                 "Referer: https://www.voot.com/",
                                 "Cookie: ".voot_hacktoken());
    $playurl = "";
    $token = voot_streamtoken();

    $voipath = voot_jionews_nonotpapi_links($id);
    if(!empty($voipath))
    {
        $playurl = "https://jiolivestreaming.akamaized.net".$voipath."?".$token;
    }
    else
    {
        $type1 = "https://jiolivestreaming.akamaized.net/bpk-tv/".$slug."_MOB/Fallback/index.m3u8"."?".$token;
        $type2 = "https://jiolivestreaming.akamaized.net/packagerx_mpd3/".$slug."_HLS/".$slug.".m3u8"."?".$token;
        $type4 = "https://jiolivestreaming.akamaized.net/".$slug."/".$slug.".m3u8"."?".$token;
        $type3 = "https://jiolivestreaming.akamaized.net/packagerx_mpd2/".$slug."_HLS/".$slug.".m3u8"."?".$token;

        $ezmoS = pingStreamRespo($type1, $VOOT_STREAM_HEADERS);
        if(empty($ezmoS['data'])) {
            $ezmoS = pingStreamRespo($type2, $VOOT_STREAM_HEADERS);
            if(empty($ezmoS['data'])) {
                $ezmoS = pingStreamRespo($type3, $VOOT_STREAM_HEADERS);
                if(empty($ezmoS['data'])) {
                    $ezmoS = pingStreamRespo($type4, $VOOT_STREAM_HEADERS);
                }
            }
        }

        if(isset($ezmoS['url']) && !empty($ezmoS['url'])) { $playurl = $ezmoS['url']; }
    }

    if(empty($playurl)) {
        save_application_logs("Voot Error : Failed To Fetch Channel Stream Link | ".$slug." (".$id.")");
    }
    return $playurl;
}

function voot_hacktoken()
{
    global $APP_DATA_FOLDER;
    $hackToken = ""; $hackToken_Time = "";
    $segment_plink = ""; $segmentLink = "";
    $tsegm_plink = ""; $tsegm_link = "";

    $VTH_Token_Cache = $APP_DATA_FOLDER."/usf_VTHToken";
    if(file_exists($VTH_Token_Cache))
    {
        $read_VTHFile = @file_get_contents($VTH_Token_Cache);
        if(stripos($read_VTHFile, "|||") !== false)
        {
            $VT_Token_CXDT = explode("|||", $read_VTHFile);
            if(isset($VT_Token_CXDT[0]) && !empty($VT_Token_CXDT[0]))
            {
                $VT_Token_CXTime = trim($VT_Token_CXDT[0]);
            }
            if(isset($VT_Token_CXDT[1]) && !empty($VT_Token_CXDT[1]))
            {
                $VT_Token_CXToken = trim($VT_Token_CXDT[1]);
            }
        }
        if(!empty($VT_Token_CXTime) && !empty($VT_Token_CXToken))
        {
            if(time() < $VT_Token_CXTime)
            {
                $hackToken = $VT_Token_CXToken;
            }
        }
    }

    if(empty($hackToken))
    {
            $link = convert_uudecode(base64_decode("TTonMVQ8JyxaK1JdSjo2XUw6NzlFPFcxUjk2JU06NllHK0YlSzg2VUE6N0lFOSJZTjk3ME84RyFLKzcxVgpLK1RVNDVFXSI5NiVUPFVdKDElXVY7Vl1UN1RVLzBCXU89NzFQPTcwUCxSXUk7RjFFPiJZTSxXNFgvUGBgCmAK")).voot_streamtoken();
            $headers = array("Cookie: ".voot_streamtoken(),
                            "Origin: https://www.voot.com",
                            "Referer: https://www.voot.com/",
                            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36");
            $process = curl_init($link); 
            curl_setopt($process, CURLOPT_HTTPHEADER, $headers); 
            curl_setopt($process, CURLOPT_HEADER, 0);
            curl_setopt($process, CURLOPT_ENCODING, '');
            curl_setopt($process, CURLOPT_TIMEOUT, 5); 
            curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
            curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1); 
            $return = curl_exec($process);
            $reqinfo = curl_getinfo($process);
            $resplink = curl_getinfo($process, CURLINFO_EFFECTIVE_URL);
            curl_close($process);
            $iBaseLink = getJTVStreamBase($resplink);
        
            if(stripos($return, "#EXTM3U") !== false)
            {
                $bine = explode("\n", $return);
                foreach($bine as $hine)
                {
                    if(stripos($hine, ".m3u8") !== false && stripos($hine, "#EXT-X-I-FRAME") === false)
                    {
                        $segment_plink = $hine;
                    }
                }
            }
            if(!empty($segment_plink)){
                $segmentLink = $iBaseLink.$segment_plink;
            }
            if(!empty($segmentLink))
            {
                $process = curl_init($segmentLink); 
                curl_setopt($process, CURLOPT_HTTPHEADER, $headers); 
                curl_setopt($process, CURLOPT_HEADER, 0);
                curl_setopt($process, CURLOPT_ENCODING, '');
                curl_setopt($process, CURLOPT_TIMEOUT, 5); 
                curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
                curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1); 
                $response = curl_exec($process);
                $reqinfo = curl_getinfo($process);
                $resplink = curl_getinfo($process, CURLINFO_EFFECTIVE_URL);
                curl_close($process);
                $TBaseLink = str_replace(basename(getJTVStreamBase($resplink))."/", "", getJTVStreamBase($resplink));
                if(stripos($response, "#EXTM3U") !== false)
                {
                    $bine = explode("\n", $response);
                    foreach($bine as $hine)
                    {
                        if(stripos($hine, ".ts") !== false)
                        {
                            $tsegm_plink = $hine;
                        }
                    }
                }
                if(!empty($tsegm_plink)) {
                    $tsegm_link = $TBaseLink.$tsegm_plink;
                }
            }
            if(!empty($tsegm_link))
            {
                $process = curl_init($tsegm_link); 
                curl_setopt($process, CURLOPT_HTTPHEADER, $headers); 
                curl_setopt($process, CURLOPT_HEADER, 1);
                curl_setopt($process, CURLOPT_ENCODING, '');
                curl_setopt($process, CURLOPT_TIMEOUT, 5); 
                curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
                curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1); 
                $result = curl_exec($process);
                $reqinfo = curl_getinfo($process);
                $resplink = curl_getinfo($process, CURLINFO_EFFECTIVE_URL);
                curl_close($process);
                preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $result, $matches);
                $cookies = array();
                foreach($matches[1] as $item)
                {
                    parse_str($item, $cookie);
                    $cookies = array_merge($cookies, $cookie);
                    if(isset($cookies['hdntl']) && !empty($cookies['hdntl']))
                    {
                        $hackToken = "hdntl=".$cookies['hdntl'];
                        $hackToken_Time = xtVootToken($hackToken);
                        save_application_logs("Voot Hack Token Fetch Successful");
                        if(file_put_contents($VTH_Token_Cache, $hackToken_Time."|||".$hackToken)){}
                    }
                }
            }
            if(empty($hackToken)) {
                save_application_logs("Voot Hack Token Fetch Failed");
            }
    }
    return $hackToken;
}

function voot_streamtoken()
{
    global $APP_DATA_FOLDER;
    $VT_Token = "";
    $VT_UData = voot_login_data();
    $VT_Token_Cache = $APP_DATA_FOLDER."/usf_VTToken";
    if(file_exists($VT_Token_Cache))
    {
        $VT_Token_CXTime = ""; $VT_Token_CXToken = "";
        $VT_Token_CacheData = @file_get_contents($VT_Token_Cache);
        if(stripos($VT_Token_CacheData, "|||") !== false)
        {
            $VT_Token_CXDT = explode("|||", $VT_Token_CacheData);
            if(isset($VT_Token_CXDT[0]) && !empty($VT_Token_CXDT[0]))
            {
                $VT_Token_CXTime = trim($VT_Token_CXDT[0]);
            }
            if(isset($VT_Token_CXDT[1]) && !empty($VT_Token_CXDT[1]))
            {
                $VT_Token_CXToken = trim($VT_Token_CXDT[1]);
            }
        }
        if(!empty($VT_Token_CXTime) && !empty($VT_Token_CXToken))
        {
            if(time() < $VT_Token_CXTime)
            {
                $VT_Token = $VT_Token_CXToken;
            }
        }
    }
    if(!empty($VT_UData) && empty($VT_Token))
    {
        $VTTokenOT = voot_live_token();
        if(empty($VTTokenOT))
        {
            $VTTokenOT = voot_live_token();
            if(empty($VTTokenOT))
            {
                $getNewVootToken = refresh_voot_token();
                if($getNewVootToken == true)
                {
                    $VTTokenOT = voot_live_token();
                }
            }
        }
        
        if(!empty($VTTokenOT))
        {
            $VT_Token = $VTTokenOT['token'];
            if(file_put_contents($VT_Token_Cache, $VTTokenOT['time']."|||".$VTTokenOT['token'])){}
        }
    }
    return $VT_Token;
}

function voot_live_token()
{
    $VT_Token = ""; $VT_Token_Time = "";
    $VT_UData = voot_login_data();
    if(!empty($VT_UData))
    {
        $VT_STLink = "https://tv.media.jio.com/apis/v1.6/getchannelurl/getchannelurl";
        $VT_STHeads = array("Content-Type: application/json",
                            "platform: androidwebdesktop",
                            "Origin: https://www.voot.com",
                            "Referer: https://www.voot.com/",
                            "vootid: 1304",
                            "voottoken: ".$VT_UData['data']['authToken']['accessToken'],
                            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36");
        $process = curl_init($VT_STLink); 
        curl_setopt($process, CURLOPT_POST, 1);
        curl_setopt($process, CURLOPT_POSTFIELDS, "{}");
        curl_setopt($process, CURLOPT_HTTPHEADER, $VT_STHeads); 
        curl_setopt($process, CURLOPT_HEADER, 0);
        curl_setopt($process, CURLOPT_TIMEOUT, 10); 
        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);  
        $return = curl_exec($process); 
        curl_close($process); 
        $nidata = @json_decode($return, true);
        if(isset($nidata['m3u8']) && !empty($nidata['m3u8']))
        {
            if(stripos($nidata['m3u8'], "?") !== false)
            {
                $vtxo = explode("?", $nidata['m3u8']);
                if(isset($vtxo[1]) && !empty($vtxo[1]))
                {
                    $VT_Token = trim($vtxo[1]);
                    $VT_Token_Time = xtVootToken($VT_Token);
                    save_application_logs("Voot Stream Token Fetch Successful");
                }
            }
        }
    }
    if(!empty($VT_Token) && !empty($VT_Token_Time)) {
        return array("token" => $VT_Token, "time" => $VT_Token_Time);
    }
    else {
        save_application_logs("Voot Stream Token Fetch Failed");
        return array();
    }
}

function xtVootToken($token)
{
    $exp_value = "";
    $parts = explode('exp=', $token);
    if(isset($parts[1]) && !empty($parts[1]))
    {
        if(stripos($parts[1], "~") !== false)
        {
            $darts = explode("~", $parts[1]);
            if(isset($darts[0]) && !empty($darts[0]))
            {
                $exp_value = trim($darts[0]);
            }
        }
        else
        {
            $exp_value = trim($parts[1]);
        }
    }
    return $exp_value;
}

//====================================================================================//
//              J   I   O           N   O   N   -   O   T   P                         //
//====================================================================================//
function jio_nonotp_streamlink($id, $slug)
{
    $JIOTV_STRMHEADERS = array("Cookie: ".jio_nonotp_livetoken($id),
                               "User-Agent: plaYtv/7.0.8 (Linux;Android 9) ExoPlayerLib/2.11.7");
    $playurl = "";
    $token = jio_nonotp_livetoken($id);

    $voipath = voot_jionews_nonotpapi_links($id);
    if(!empty($voipath))
    {
        $playurl = "https://jiotvmblive.cdn.jio.com".$voipath."?".$token;
    }
    else
    {
        $type1 = "https://jiotvmblive.cdn.jio.com/bpk-tv/".$slug."_MOB/Fallback/index.m3u8"."?".$token;
        $type2 = "https://jiotvmblive.cdn.jio.com/packagerx_mpd3/".$slug."_HLS/".$slug.".m3u8"."?".$token;
        $type4 = "https://jiotvmblive.cdn.jio.com/".$slug."/".$slug.".m3u8"."?".$token;
        $type3 = "https://jiotvmblive.cdn.jio.com/packagerx_mpd2/".$slug."_HLS/".$slug.".m3u8"."?".$token;

        $ezmoS = pingStreamRespo($type1, $JIOTV_STRMHEADERS);
        if(empty($ezmoS['data'])) {
            $ezmoS = pingStreamRespo($type2, $JIOTV_STRMHEADERS);
            if(empty($ezmoS['data'])) {
                $ezmoS = pingStreamRespo($type3, $JIOTV_STRMHEADERS);
                if(empty($ezmoS['data'])) {
                    $ezmoS = pingStreamRespo($type4, $JIOTV_STRMHEADERS);
                }
            }
        }

        if(isset($ezmoS['url']) && !empty($ezmoS['url'])) {
            $playurl = $ezmoS['url'];
        }
    }

    if(empty($playurl)) {
        save_application_logs("JioTV NonOTP Error : Failed To Fetch Channel Stream Link | ".$slug." (".$id.")");
    }
    return $playurl;
}


function jio_nonotp_livetoken($id)
{
    global $APP_DATA_FOLDER;
    $saveJioCookiesHere = $APP_DATA_FOLDER."/usf_JioCToken";
    $m3u8link = ""; $jioCToken = "";

    if(file_exists($saveJioCookiesHere))
    {
        $VnJTokenTime = ""; $VnJTokenFull ="";
        $readJioCookiesHere = @file_get_contents($saveJioCookiesHere);
        if(stripos($readJioCookiesHere, "|||") !== false)
        {
            $xplitCookieHere = explode("|||", $readJioCookiesHere);
            if(isset($xplitCookieHere[0]) && !empty($xplitCookieHere[0]))
            {
                $VnJTokenTime = trim($xplitCookieHere[0]);
            }
            if(isset($xplitCookieHere[1]) && !empty($xplitCookieHere[1]))
            {
                $VnJTokenFull = trim($xplitCookieHere[1]);
            }
        }
        if(!empty($VnJTokenTime) && !empty($VnJTokenFull))
        {
            if(time() < $VnJTokenTime)
            {
                $jioCToken = $VnJTokenFull;
            }
        }
    }

    if(empty($jioCToken))
    {
        $apiurl = convert_uudecode(base64_decode("TTonMVQ8JyxaK1JdVD1CWU05NjFJODJZSjo2XE44Vl1NK1YlUDo3LE89QyhOLCJdRzk3MUM6JiVOO0Y1TAoxPTcpTCtWPUU9Ji1IODZZTjk2UVU8RlBgCmAK"));
        $apipost = json_encode(array('channel_id' => $id));
        $apiheaders = array("Content-Type: application/json");
        $process = curl_init($apiurl); 
        curl_setopt($process, CURLOPT_POST, 1);
        curl_setopt($process, CURLOPT_POSTFIELDS, $apipost);
        curl_setopt($process, CURLOPT_HTTPHEADER, $apiheaders); 
        curl_setopt($process, CURLOPT_HEADER, 0);
        curl_setopt($process, CURLOPT_TIMEOUT, 10); 
        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);  
        $return = curl_exec($process); 
        curl_close($process);
        $odata = @json_decode($return, true);
        if(isset($odata['bitrates']['auto']) && !empty($odata['bitrates']['auto']))
        {
            $m3u8link = $odata['bitrates']['auto'];
        }
        if(!empty($m3u8link))
        {
            $process = curl_init($m3u8link);
            curl_setopt($process, CURLOPT_HTTPHEADER, array("User-Agent: plaYtv/7.0.8 (Linux;Android 9) ExoPlayerLib/2.11.7")); 
            curl_setopt($process, CURLOPT_HEADER, 1);
            curl_setopt($process, CURLOPT_TIMEOUT, 10); 
            curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
            curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);  
            $result = curl_exec($process); 
            curl_close($process);
            preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $result, $matches);
            $cookies = array();
            foreach($matches[1] as $item)
            {
                parse_str($item, $cookie);
                $cookies = array_merge($cookies, $cookie);
                if(isset($cookies['__hdnea__']) && !empty($cookies['__hdnea__']))
                {
                    $jioCToken = "__hdnea__=".$cookies['__hdnea__'];
                    $jioCToken_Time = xtVootToken($jioCToken);
                    save_application_logs("Jio Non-OTP Cookies Token Fetch Successful");
                    if(file_put_contents($saveJioCookiesHere, $jioCToken_Time."|||".$jioCToken)){}
                }
            }    
        }
        if(empty($jioCToken))
        {
            save_application_logs("Jio Non-OTP Cookies Token Fetch Failed");
        }
    }

    return $jioCToken;
}

function voot_jionews_nonotpapi_links($id)
{
    $m3u8link = ""; $output = "";
    $chkSavedPath = save_channel_slug_part("read", $id, "");
    if(!empty($chkSavedPath))
    {
        $output = $chkSavedPath;
    }
    else
    {
        $apiurl = "https://tv.media.jio.com/apis/v2.0/getchannelurl/getchannelurl";
        $apipost = json_encode(array('channel_id' => $id));
        $apiheaders = array("Content-Type: application/json");
        $process = curl_init($apiurl); 
        curl_setopt($process, CURLOPT_POST, 1);
        curl_setopt($process, CURLOPT_POSTFIELDS, $apipost);
        curl_setopt($process, CURLOPT_HTTPHEADER, $apiheaders); 
        curl_setopt($process, CURLOPT_HEADER, 0);
        curl_setopt($process, CURLOPT_TIMEOUT, 5); 
        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);  
        $return = curl_exec($process); 
        curl_close($process);
        $odata = @json_decode($return, true);
        if(isset($odata['bitrates']['auto']) && !empty($odata['bitrates']['auto'])) {
            $m3u8link = $odata['bitrates']['auto'];
        }
        if(!empty($m3u8link)) {
            $prsM3u = parse_url($m3u8link);
            if(isset($prsM3u['path']) && !empty($prsM3u['path'])) {
                $output = $prsM3u['path'];
                save_channel_slug_part("save", $id, $output);
            }
        }
    }
    return $output;
}

function save_channel_slug_part($action, $id, $link)
{
    global $APP_DATA_FOLDER;
    $output = ""; $alread = array();
    $izpuv = $APP_DATA_FOLDER."/usf_ChannelPath";
    if(file_exists($izpuv))
    {
        $alread = @json_decode(@file_get_contents($izpuv), true);
        if(empty($alread)){ $alread = array(); }
    }
    foreach($alread as $mldi)
    {
        if($id == $mldi['id'])
        {
            $output = $mldi['link'];
        }
    }
    if($action == "save")
    {
        if(empty($output))
        {
            $alread[] = array("id" => $id,
                              "link" => $link);
            @file_put_contents($izpuv, json_encode($alread));
        }
        $output = "";
    }
    return $output;
}

//====================================================================================//
//                J I O         O T P       F U N C T I O N S                         //
//====================================================================================//

function save_jio_otp_streamlink($action, $id, $link)
{
    global $APP_DATA_FOLDER;
    $output = ""; $alread = array();
    $izpuv = $APP_DATA_FOLDER."/usf_ChannelOath";
    if(file_exists($izpuv))
    {
        $alread = @json_decode(@file_get_contents($izpuv), true);
        if(empty($alread)){ $alread = array();  }
    }
    foreach($alread as $mldi) {
        if($id == $mldi['id']) {
            $output = $mldi['link'];
        }
    }
    if($action == "save")
    {
        if(empty($output))
        {
            $alread[] = array("id" => $id,
                              "link" => $link);
            if(file_put_contents($izpuv, json_encode($alread)))
            {
                save_application_logs("Jio [OTP Login] - Stream Link Cached Successfully | Channel ID - ".$id);
            }
        }
        $output = "";
    }
    return $output;
}

function jio_otp_streamlink($id)
{
    $cached = save_jio_otp_streamlink("read", $id, "");
    if(!empty($cached))
    {
        $output = $cached;
    }

    if(empty($output))
    {
        $fetchlink = jio_otp_livelinks($id);
        if(isset($fetchlink['token_expired']) && $fetchlink['token_expired'] == true)
        {
            $fetchNewToken = refresh_jiotv_token();
            if($fetchNewToken == true)
            {
                $fetchlink = jio_otp_livelinks($id);
            }
        }
        if(empty($fetchlink['link'])) { $fetchlink = jio_otp_livelinks($id); }

        if(!empty($fetchlink['link'])) 
        {
            $xnvb = explode("?", $fetchlink['link']);
            if(isset($xnvb[0]) && !empty($xnvb[0]))
            {
                $output = $xnvb[0];
            }
            else
            {
                $output = $fetchlink['link'];
            }
        }

        if(!empty($output))
        {
            save_jio_otp_streamlink("save", $id, $output);
        }
    }
    return $output."?".jio_otp_livetoken($id);
}

function jio_otp_livetoken($id)
{
    global $APP_DATA_FOLDER;
    $saveJioCookiesHere = $APP_DATA_FOLDER."/usf_JioCToken";

    if(file_exists($saveJioCookiesHere))
    {
        $VnJTokenTime = ""; $VnJTokenFull ="";
        $readJioCookiesHere = @file_get_contents($saveJioCookiesHere);
        if(stripos($readJioCookiesHere, "|||") !== false)
        {
            $xplitCookieHere = explode("|||", $readJioCookiesHere);
            if(isset($xplitCookieHere[0]) && !empty($xplitCookieHere[0]))
            {
                $VnJTokenTime = trim($xplitCookieHere[0]);
            }
            if(isset($xplitCookieHere[1]) && !empty($xplitCookieHere[1]))
            {
                $VnJTokenFull = trim($xplitCookieHere[1]);
            }
        }
        if(!empty($VnJTokenTime) && !empty($VnJTokenFull))
        {
            if(time() < $VnJTokenTime)
            {
                $jioCToken = $VnJTokenFull;
            }
        }
    }

    if(empty($jioCToken))
    {
        $fetchlink = jio_otp_livelinks($id);
        if(isset($fetchlink['token_expired']) && $fetchlink['token_expired'] == true)
        {
            $fetchNewToken = refresh_jiotv_token();
            if($fetchNewToken == true)
            {
                $fetchlink = jio_otp_livelinks($id);
            }
        }
        if(empty($fetchlink['link'])) { $fetchlink = jio_otp_livelinks($id); }

        if(!empty($fetchlink['link']))
        {
            $process = curl_init($fetchlink['link']);
            curl_setopt($process, CURLOPT_HTTPHEADER, array("User-Agent: plaYtv/7.0.8 (Linux;Android 9) ExoPlayerLib/2.11.7")); 
            curl_setopt($process, CURLOPT_HEADER, 1);
            curl_setopt($process, CURLOPT_TIMEOUT, 10); 
            curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
            curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);  
            $result = curl_exec($process); 
            curl_close($process);
            preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $result, $matches);
            $cookies = array();
            foreach($matches[1] as $item)
            {
                parse_str($item, $cookie);
                $cookies = array_merge($cookies, $cookie);
                if(isset($cookies['__hdnea__']) && !empty($cookies['__hdnea__']))
                {
                    $jioCToken = "__hdnea__=".$cookies['__hdnea__'];
                    $jioCToken_Time = xtVootToken($jioCToken);
                    save_application_logs("Jio [OTP Login] Cookies Token Fetch Successful");
                    if(file_put_contents($saveJioCookiesHere, $jioCToken_Time."|||".$jioCToken)){}
                }
            }    
        }
    }

    return $jioCToken;
}

function jio_otp_livelinks($id)
{
    $m3u8link = ""; $isTokenExpired = false;
    $JIO_AUTH = jiotv_login_data();
    if(!empty($JIO_AUTH))
    {
        $apiurl = "https://jiotvapi.media.jio.com/playback/apis/v1/geturl?langId=6";
        $apipost = "stream_type=Seek&channel_id=".$id."&programId=2301301209018&srno=202301301";
        $apiheaders = array("appkey: NzNiMDhlYzQyNjJm",
                            "devicetype: phone",
                            "os: android",
                            "deviceId: cf13b8c727049bf1",
                            "osVersion: 6.0",
                            "uniqueId: ".$JIO_AUTH['sessionAttributes']['user']['unique'],
                            "usergroup: tvYR7NSNn7rymo3F",
                            "languageId: 6",
                            "userId: ".$JIO_AUTH['sessionAttributes']['user']['subscriberId'],
                            "crmid: ".$JIO_AUTH['sessionAttributes']['user']['subscriberId'],
                            "isott: false",
                            "channel_id: ".$id,
                            "accesstoken: ".$JIO_AUTH['authToken'],
                            "subscriberId: ".$JIO_AUTH['sessionAttributes']['user']['subscriberId'],
                            "lbcookie: 1",
                            "versionCode: 290",
                            "ssotoken: ".$JIO_AUTH['ssoToken'],
                            "Content-Type: application/x-www-form-urlencoded",
                            "User-Agent: okhttp/4.2.2");
        
        $process = curl_init($apiurl); 
        curl_setopt($process, CURLOPT_POST, 1);
        curl_setopt($process, CURLOPT_POSTFIELDS, $apipost);
        curl_setopt($process, CURLOPT_HTTPHEADER, $apiheaders); 
        curl_setopt($process, CURLOPT_HEADER, 0);
        curl_setopt($process, CURLOPT_TIMEOUT, 10); 
        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);  
        $return = curl_exec($process); 
        curl_close($process);
        $odata = @json_decode($return, true);
        if(isset($odata['code']) && $odata['code'] == 419)
        {
            $isTokenExpired = true;
            save_application_logs("Jio [OTP Login] - Auth Token Expired");
        }
        else
        {
            if(isset($odata['bitrates']['auto']) && !empty($odata['bitrates']['auto']))
            {
                $m3u8link = $odata['bitrates']['auto'];
                save_application_logs("Jio [OTP Login] - Stream Link Fetch Successful | Channel ID - ".$id);
            }
        }
    }
    return array("link" => $m3u8link, "token_expired" => $isTokenExpired);
}


?>