<?php
/**
 * 演示令牌加入与消耗
 */
require 'TokenBucket.php';
// redis连接设定
$config = array (
    'host' => '127.0.0.1',
    'port' => 6379,
    'index' => 0,
    'auth' => '',
    'timeout' => 1,
    'reserved' => NULL,
    'retry_interval' => 100,
);
// 令牌桶容器
$queue = 'mycontainer';
// 最大令牌数
$max = 5;
// 创建TrafficShaper对象
$tokenBucket = new TokenBucket($config, $queue, $max);
// 重设令牌桶，填满令牌
$tokenBucket->reset();
// 循环获取令牌，令牌桶内只有5个令牌，因此最后3次获取失败
for ($i = 0; $i < 8; $i++) {
    var_dump($tokenBucket->get());
}
// 加入10个令牌，最大令牌为5，因此只能加入5个
$add_num = $tokenBucket->add(10);
var_dump($add_num);
// 循环获取令牌，令牌桶内只有5个令牌，因此最后1次获取失败
for ($i = 0; $i < 6; $i++) {
    var_dump($tokenBucket->get());
}
?>