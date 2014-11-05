<?php
if(
	isset($_POST) 
	&& isset($_POST['usefulkeywords']) && !empty($_POST['usefulkeywords']) 
	&& isset($_POST['allkeywords']) && !empty($_POST['allkeywords'])
	//&& strpos($_SERVER['HTTP_REFERER'],'/tool/jiliang/juzhen.php') !== false
){
	$usefulwords = trim($_POST['usefulkeywords']);
	$allwords = trim($_POST['allkeywords']);

	$download = isset($_POST['download']) && !empty($_POST['download']) ? $_POST['download'] : false;
	if(!$download){
		header("Content-type: text/html; charset=utf-8");
	}else{
		header("Content-Type:".(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ? 'application/octetstream' : 'application/octet-stream')."; charset=UTF-8");
		header("Content-Disposition:".(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ? 'inline' : 'attachment')."; filename=documents_meta_data_juzhen.txt");
	}

	$usefulwords = explode(',',$usefulwords);
	$usefulwords = array_filter($usefulwords);
	$results = array();
	
	if(!$download)echo "<pre>";

	// 先打印出横向坐标
	echo "*\t";
	foreach($usefulwords as $use){
		echo $use;
		echo "\t";
		foreach($usefulwords as $i){
			$results[$use][$i] = 0;
		}
	}
	echo "\n";

	// 通过遍历把每一个关键词的共现次数记录在$results中
	$allwords = explode("\n",$allwords); // array(1)(;)
	foreach($allwords as $key => $words){
		if($key == 0){
			unset($allwords[0]);
			continue;
		}
		$words = explode(",",$words);
		$findwords = array();
		foreach($words as $word){
			$word = preg_replace("/\s/",'',$word);
			$word = str_replace("“",'',$word);
			$word = str_replace("”",'',$word);
			$word = str_replace("《",'',$word);
			$word = str_replace("》",'',$word);
			$word = str_replace("<",'',$word);
			$word = str_replace(">",'',$word);
			$word = str_replace("‘",'',$word);
			$word = str_replace("’",'',$word);
			$word = str_replace("*",'',$word);
			$word = str_replace("'",'',$word);
			$word = str_replace('"','',$word);
			$word = str_replace(';','',$word);
			if(in_array($word,$usefulwords))$findwords[] = $word;
		}
		foreach($findwords as $find){
			foreach($findwords as $i){
				if($find != $i)$results[$find][$i] += 1;
			}
		}
	}

	// 开始一条记录一条记录的打印
	foreach($results as $key => $result){
		echo $key;
		echo "\t";
		foreach($result as $i){
			echo $i;
			echo "\t";
		}
		echo  "\n";
	}
	
	if(!$download)echo "</pre>";

	// 结束
	exit;
}