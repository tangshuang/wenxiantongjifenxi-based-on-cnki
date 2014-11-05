<?php

if(
	isset($_POST) 
	&& isset($_POST['list']) && !empty($_POST['list']) 
	//&& strpos($_SERVER['HTTP_REFERER'],'/tool/jiliang/guanjianci.php') !== false
){

	$download = isset($_POST['download']) && !empty($_POST['download']) ? $_POST['download'] : false;
	if(!$download){
		header("Content-type: text/html; charset=utf-8");
	}else{
		header("Content-Type:".(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ? 'application/octetstream' : 'application/octet-stream')."; charset=UTF-8");
		header("Content-Disposition:".(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ? 'inline' : 'attachment')."; filename=documents_meta_data_guanjianci.txt");
	}

	$list_string = trim($_POST['list']);

	$list_string = str_replace('关键词','',$list_string);
	$list_string = str_replace(",\n",',',$list_string);
	$list_string = str_replace("\n",',',$list_string);
	$list_string = preg_replace("/\s/",'',$list_string);
	$list_string = str_replace("“",'',$list_string);
	$list_string = str_replace("”",'',$list_string);
	$list_string = str_replace("《",'',$list_string);
	$list_string = str_replace("》",'',$list_string);
	$list_string = str_replace("<",'',$list_string);
	$list_string = str_replace(">",'',$list_string);
	$list_string = str_replace("‘",'',$list_string);
	$list_string = str_replace("’",'',$list_string);
	$list_string = str_replace("*",'',$list_string);
	$list_string = str_replace("'",'',$list_string);
	$list_string = str_replace('"','',$list_string);
	$list_string = str_replace(';',',',$list_string);
	$keywords = explode(',',$list_string);
	$keywords = array_filter($keywords);
	$keywords = array_count_values($keywords);
	if(!$download)echo "<pre>";
	echo "关键词\t频次\n";
	foreach($keywords as $keyword => $count){
		echo "{$keyword}\t{$count}\n";
	}
	if(!$download)echo '</pre>';

	// 结束
	exit;
}