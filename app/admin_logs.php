<?php

session_start();

include("inc.configs.php");

if(!isset($_SESSION['usf_admin']))
{
    http_response_code(404);
    exit();
}

$readfile = $APP_DATA_FOLDER."/application_logs";
if(file_exists($readfile))
{
    $logs = @file_get_contents($readfile);
    if(!empty($logs))
    {
        exit("<pre>".nl2br($logs)."</pre>");
    }
}
exit("<pre>No Logs Found</pre>");

?>