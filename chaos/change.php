<?php 
require 'config.php';
/*
*
Created for MayaData hackathon on 19/04/2021 by Divya Shiv Pandey(1805120-KIIT)
*
*/

//read data from url
$auth = $_GET['auth'];
$code = $_GET['code'];
$url = isset($_GET['url']) ? urldecode(trim($_GET['url'])) : '';
//check for validity of url
if (!filter_var($url, FILTER_VALIDATE_URL)) {
    die('Enter a valid url');  
} 
//change code to compatible case
$code = strip_tags(strtolower($_GET['code']));

//make connection to database
$db = new MySQLi($config_host, $config_user, $config_password, $config_db);
$db->set_charset('utf8');

//fetch original authKey stored in database
$escapedCode = $db->real_escape_string($code);
$validAuth = $db->query('SELECT userauth FROM shortener WHERE shorturl = "' . $escapedCode . '"');

//validate authkey and change url
if (strcmp($validAuth->fetch_object()->userauth, $auth) == 0 ) {
    //change url stored in database 
    $db->query('UPDATE shortener SET real_url ="' .$url. '" WHERE shorturl = "' . $escapedCode . '"');
    echo "successfully changed";
}else{
    //user not identified
    die('Unauthorised access, check authKey');
}
//close connection and exist
$db->close();
exit;
?>