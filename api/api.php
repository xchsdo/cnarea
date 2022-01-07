<?php
header("Content-Type:text/json;charset=utf-8");
include_once './mysqli.php';
function getIsPostRequest() {
    return isset($_SERVER['REQUEST_METHOD']) && !strcasecmp($_SERVER['REQUEST_METHOD'], 'POST');
}
if (getIsPostRequest()) {
    $id = isset($_POST["id"]) ? $_POST["id"] : ""; // id = parent_code父级行政代码
    $type = isset($_POST["type"]) ? $_POST["type"] : ""; // type = level(层级) = 0省份, 1城市, 2区县, 3街道, 4村庄
} else {
    $id = isset($_GET["id"]) ? $_GET["id"] : ""; // id = parent_code父级行政代码
    $type = isset($_GET["type"]) ? $_GET["type"] : ""; // type = level(层级) = 0省份, 1城市, 2区县, 3街道, 4村庄
}
if ($id == "" || $type == "") {
    exit(json_encode(array("flag" => false, "msg" => "查询类型错误"), JSON_UNESCAPED_UNICODE));
} else {
    $sql = "select * from cnarea_2020 where parent_code=$id AND level=$type";
    $result = $mysqli->query($sql);
    if ($result->num_rows > 0) {
        $arr = [];
        while ($row = $result->fetch_assoc()) {
            $arr[$row["area_code"]]["id"] = $row["area_code"]; // area_code行政代码
            $arr[$row["area_code"]]["name"] = $row["name"]; // name名称
            $arr[$row["area_code"]]["shortName"] = $row["short_name"]; // short_name简称
            $arr[$row["area_code"]]["mergerName"] = $row["merger_name"]; // merger_name组合名称
            $arr[$row["area_code"]]["pinYin"] = $row["pinyin"]; // pinyin拼音
            $arr[$row["area_code"]]["cityCode"] = $row["city_code"]; // city_code区号
            $arr[$row["area_code"]]["zipCode"] = $row["zip_code"]; // zip_code邮政编码
            $arr[$row["area_code"]]["lng"] = $row["lng"]; // lng经度
            $arr[$row["area_code"]]["lat"] = $row["lat"]; // lat纬度
        }
    }
    $result->free();
    $mysqli->close();
    $provinces_json = json_encode($arr, JSON_UNESCAPED_UNICODE);
    exit($provinces_json);
}
?>