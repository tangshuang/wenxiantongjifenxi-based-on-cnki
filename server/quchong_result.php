<?php

if(
	isset($_POST) 
	&& isset($_POST['list']) && !empty($_POST['list']) 
	//&& strpos($_SERVER['HTTP_REFERER'],'/tool/jiliang/quchong.php') !== false
){
	$download = isset($_POST['download']) && !empty($_POST['download']) ? $_POST['download'] : false;
	if(!$download){
		header("Content-type: text/html; charset=utf-8");
	}else{
		header("Content-Type:".(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ? 'application/octetstream' : 'application/octet-stream')."; charset=UTF-8");
		header("Content-Disposition:".(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ? 'inline' : 'attachment')."; filename=documents_meta_data.txt");
	}

	$list_string = trim($_POST['list']);

	$docs = explode("\n",$list_string);	
	$docs = array_filter($docs);
	$docs = array_unique($docs);
	$doc_link = array();
	$doc_data = array();
	foreach($docs as $key => $doc){
		$doc_meta = explode("\t",$doc);
		$doc_link[$key] = $doc_meta[10];
		$doc_data[$key] = $doc_meta;
		$doc_data[$key][12] = $doc;
	}
	$doc_link = array_unique($doc_link);

	if(!$download)echo "<pre>";
	foreach($doc_data as $doc_meta){
		$exists = array_search($doc_meta[10],$doc_link);
		if($exists !== false){
			echo $doc_meta[12];
			unset($doc_link[$exists]);
		}
	}
	if(!$download)echo "</pre>";

	// 结束
	exit;
}