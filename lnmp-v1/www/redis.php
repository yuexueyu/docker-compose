<?php

$redis = new Redis();
$redis->connect('redis',6379);
$redis->set('test','hello world to redis');
echo $redis->get('test');

//$redis_host = "192.168.0.4";
//$redis_port = 6379;
//$user_pwd = "123456";
//$redis = new Redis();
//if ($redis->connect($redis_host, $redis_port) == false) {
//    die('111' . $redis->getLastError());
//}
//if ($redis->auth($user_pwd) == false) {
//    die('222' . $redis->getLastError());
//}
//if ($redis->set("welcome", "Hello Use Redis!") == false) {
//    die('333' . $redis->getLastError());
//}
//$value = $redis->get("welcome");
//echo $value;