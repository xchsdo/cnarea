<?php
include './common.inc.php';
$mysqli = new mysqli(cfg_dbhost, cfg_dbuser, cfg_dbpwd, cfg_dbname);
$mysqli->set_charset(cfg_db_language);
if ($mysqli->connect_error) {
    die('数据库连接失败: ' . $mysqli->connect_error);
}
?>