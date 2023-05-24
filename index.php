<?php
header('Access-Control-Allow-Origin:*');error_reporting("E_ALL");ini_set("display_errors", 1);

$d = isset($_GET['d']) ? $_GET['d'] : "";
$url = isset($_GET['url']) ? $_GET['url'] : "";
$pwd = isset($_GET['pwd']) ? $_GET['pwd'] : "";
$type = isset($_GET['type']) ? $_GET['type'] : "";
include('D123pan.clsss.php');
if(!empty($d) && empty($url)){$arr = explode('_',$d);$url=$arr[0];$pwd=$arr[1];$type='down';}
$dp = new d123pan;
$res=$dp->getUrl($url,$pwd);
if($res['status']==0)ajaxReturn($res);
switch($type){
    case "down":
    $urlarr = explode('?',$res['info']);parse_str($urlarr[1],$param_arr);
    $url = base64_decode($param_arr['params']);
    header("Location:".$url);die;
    break;
    default:
    header('Content-Type:application/json; charset=utf-8');
    ajaxReturn($res);
    break;
}

function ajaxReturn($array){
    $content=json_encode($array,JSON_UNESCAPED_UNICODE);
    if(empty($_GET['callback'])){
        echo $content;exit;
    }else{
        echo $_GET['callback']."(".$content.")";exit;
    }
}
?>