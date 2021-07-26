<?php 
require 'config.php';

/*
*
Created for MayaData hackathon on 19/04/2021 by Divya Shiv Pandey(1805120-KIIT)
*
*/

//site loads even after expiring due to its existance in cache memory. to check expiry of url open in new window only!!!
clearstatcache();
$exp = true;

//check for expiry of url
function isExpired($db,$escapedCode){
    //get url creation time and validity time from database
    $created =$db->query('SELECT origindate FROM shortener WHERE shorturl = "' . $escapedCode . '"');
    $timeleft =$db->query('SELECT expiry FROM shortener WHERE shorturl = "' . $escapedCode . '"');
    //convert to calculatble format
    $date = new DateTime($created->fetch_object()->origindate);
    //get current time(system)
    $now = new DateTime();
    //find difference in currebt and creation time and convert it in minutes
    $interval = $now->diff($date);
    $sincecreated = ((($interval->format("%a") * 24) + $interval->format("%h"))*60)+($interval->format("%i"));
 
    //check if validity time has passed its existance time
    if($timeleft->fetch_object()->expiry < $sincecreated){
        //it is expired
        return true;
    }else{
        //nope, it will still work
        return false;
    }
}
//check if code is present
if (isset($_GET['code'])) {
    //convert it to lower case
    $code = strip_tags(strtolower($_GET['code']));
    //connect to database
    $db = new MySQLi($config_host, $config_user, $config_password, $config_db);
    $db->set_charset('utf8');
    $escapedCode = $db->real_escape_string($code);
//get url to be redirected to
    $redirectResult = $db->query('SELECT originalurl FROM shortener WHERE shorturl = "' . $escapedCode . '"');

//check existance and increase visit to the url by 1 is
    if ($redirectResult && $redirectResult->num_rows > 0) {
        $db->query('UPDATE shortener SET visit = visit + 1 WHERE shorturl = "' . $escapedCode . '"');

    //check for expiry of url
        if(!isExpired($db,$escapedCode)){
           $url = $redirectResult->fetch_object()->originalurl;
           $exp = false;
        }else{
            die("Link Has Expired");
            $exp = true;
        }
//url not found since number of rows returened from database was 0
    }else {
        die('No such url found!');
    }
    $db->close();

    if(!$exp){
        header('Location: ' . $url, null, 301);
    }       
    exit;
}
?>