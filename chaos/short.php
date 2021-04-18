<?php
require 'config.php';
/*
*
Created for MayaData hackathon on 19/04/2021 by Divya Shiv Pandey(1805120-KIIT)
*
*/

//read data from url
$url = isset($_GET['url']) ? urldecode(trim($_GET['url'])) : '';
$expiry = $_GET['time'];

//exit if invalid url
if (!filter_var($url, FILTER_VALIDATE_URL)) {
    die('Enter a valid url');  
} 

//generate unique authkey
function generateAuthKey($n = 20) {
    //defined set of chars to create key
    $charec = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $authKey = '';
    //randomaly select chars
    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($charec) - 1);
        $authKey .= $charec[$index];
    }
    return $authKey;
}
//generate randomly created short code for url
 function generateRandomString($length = 12){
    $chars = "abcdfghjkmnpqrstvwxyz|ABCDFGHJKLMNPQRSTVWXYZ|0123456789";
    //divide them in set
    $sets = explode('|', $chars);
    $all = '';
    $randString = '';
    //split randomly
    foreach($sets as $set){
        $randString .= $set[array_rand(str_split($set))];
        $all .= $set;
    }
    $all = str_split($all);
    //generate random string by randomly selectign chars
    for($i = 0; $i < $length - count($sets); $i++){
        $randString .= $all[array_rand($all)];
    }
    //shuffle string
    $randString = str_shuffle($randString);
    return $randString;
}

//connect to database
$database = new mysqli($config_host, $config_user, $config_password, $config_db); 
$database->set_charset('utf8');

try{
    //genrate authKey and short url
        $shorturl = generateRandomString($url);
        $userAuth= generateAuthKey();
       //insert generate values in data 
        if ($database->query('INSERT INTO shortener (userauth,shorturl, originalurl, origindate, expiry, visit) VALUES ("'. $userAuth .'","' . $shorturl . '", "' . $url . '","'. date('Y-m-d H:i:s').'","'. $expiry .'", 0)')) {
            echo  " URL: ".$domainame.$shorturl;
            echo "\n AUTH KEY: ". $userAuth;
            echo "\n CODE: ". $shorturl;
            echo " \n Please do not lose this Auth Key";
        }
}catch(Exception $e){
    // Display error
    echo $e->getMessage();
}
?>