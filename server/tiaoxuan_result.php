<?php

if(
  isset($_POST) 
  && isset($_POST['list']) && !empty($_POST['list']) 
  //&& strpos($_SERVER['HTTP_REFERER'],'/tool/jiliang/tiaoxuan.php') !== false
){
  $download = isset($_POST['download']) && !empty($_POST['download']) ? $_POST['download'] : false;
  if(!$download){
    header("Content-type: text/html; charset=utf-8");
  }else{
    header("Content-Type:".(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ? 'application/octetstream' : 'application/octet-stream')."; charset=UTF-8");
    header("Content-Disposition:".(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ? 'inline' : 'attachment')."; filename=documents_meta_data.txt");
  }

  $keys = trim($_POST['keys']);
  $list_string = trim($_POST['list']);
  $relation = $_POST['relation'];
  
  // 处理要挑选的关键词
  if(strpos($keys,';')){
    $keys = array_unique(array_filter(explode(',',$keys)));
  }else{
    $keys = array($keys);
  }
  $lists = array_filter(explode("\n",$list_string));

  // 根据提交的规定的关系删除不包含关键词的条目
  foreach($lists as $num => $doc){
    if($num == 0)continue;
    $delete = false;
    foreach($keys as $key){
      if($relation == 'and' && strpos($doc,$key) === false){
        $delete = true;
        break;
      }elseif($relation == 'or'){
        if(strpos($doc,$key) !== false){
          $delete = false;
          break;
        }else{
          $delete = true;
        }
      }
    }
    if($delete){
      unset($lists[$num]);
    }
  }

  if(!$download)echo "<pre>";
  foreach($lists as $doc){
    echo "{$doc}\n";
  }
  if(!$download)echo "</pre>";

  // 结束
  exit;
}