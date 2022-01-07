<?php
/**
* @param  $uid
* @return bool|int
* PHP基于Redis检测用户接口访问频率
*/
function api_frequency_visits ($uid) {
    $key = "user:{$uid}:api:frequency";
    $redis = new Redis();
    set_time_limit(0);
    $redis->connect('127.0.0.1', 6379);
    $data = $redis->hGetAll($key);
    // 需要删除的key
    $del_key = [];
    // 时间内访问的总次数
    $total = 0;
    // 时间内最大访问次数
    $max_frequency = 10;
    // 当前时间
    $now_time = time();
    // 限制时间
    $limit_time = 60;
    foreach ($data as $time=>$count) {
        if ($time < $now_time - $limit_time) {
            $del_key[] = $time;
        } else {
            $total += $count;
        }
    }
    // 存在需要删除的key
    if ($del_key) {
        $redis->hDel($key, ...$del_key);
    }
    if ($total >= $max_frequency) {
        return false;
    }
    return $redis->hIncrBy($key, $now_time, 1);
}
$uid = 1;
$result = api_frequency_visits($uid);
if (!$result) {
    echo json_encode(['code'=>0, 'msg'=>'操作过于频繁']);
    die;
}
?>