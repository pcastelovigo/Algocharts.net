<?php
$valid_passwords = array ("test" => "test");
$valid_users = array_keys($valid_passwords);

$user = $_SERVER['PHP_AUTH_USER'];
$pass = $_SERVER['PHP_AUTH_PW'];

$validated = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);

if (!$validated) {
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$redis->auth('redis-token');

//$max_calls_limit  = 200;
$time_period      = 86400;
$total_user_calls = 0;

if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $user_ip_address = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $user_ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $user_ip_address = $_SERVER['REMOTE_ADDR'];
}

if (!$redis->exists($user_ip_address.$endpoint)) {
    $redis->set($user_ip_address.$endpoint, 1);
    $redis->expire($user_ip_address.$endpoint, $time_period);
    $total_user_calls = 1;
} else {
    $redis->INCR($user_ip_address.$endpoint);
    $total_user_calls = $redis->get($user_ip_address.$endpoint);
    if ($total_user_calls > $max_calls_limit) {
    header('WWW-Authenticate: Basic realm="Algocharts"');
    response(401,"Too much requests",NULL);
    header("Retry-After: ".$redis->ttl($user_ip_address.$endpoint));
        exit();
    } else { header('uso-api: '.$total_user_calls); api(); }
}
} else { header('uso-api: '.$total_user_calls); api(); }

?>
