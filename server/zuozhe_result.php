<?php

if(
	isset($_POST) 
	&& isset($_POST['list']) && !empty($_POST['list']) 
	//&& strpos($_SERVER['HTTP_REFERER'],'/tool/jiliang/zuozhe.php') !== false
){
	$download = isset($_POST['download']) && !empty($_POST['download']) ? $_POST['download'] : false;
	if(!$download){
		header("Content-type: text/html; charset=utf-8");
	}else{
		header("Content-Type:".(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ? 'application/octetstream' : 'application/octet-stream')."; charset=UTF-8");
		header("Content-Disposition:".(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ? 'inline' : 'attachment')."; filename=documents_meta_data_zuozhe.txt");
	}

	$list_string = trim($_POST['list']);

	// 第一作者统计
	$zuozhes = explode("\n",$list_string);
	foreach($zuozhes as $key => $zuozhe){
		if($zuozhe == '作者'){
			unset($zuozhes[0]);
			continue;
		}
		if(trim($zuozhe) == ''){
			unset($zuozhes[$key]);
		}else{
			$zuozhe = str_replace('，',',',$zuozhe);
			$single_1 = strpos($zuozhe,';');
			$single_2 = strpos($zuozhe,',');
			if($single_1 !== false && $single_2 !== false){
				$single = min($single_1,$single_2);
			}elseif($single_1 !== false){
				$single = $single_1;
			}elseif($single_2 !== false){
				$single = $single_2;
			}else{
				$single = false;
			}
			if($single !== false){
				$zuozhes[$key] = trim(substr($zuozhe,0,$single));
			}else{
				$zuozhes[$key] = trim($zuozhe);
			}
		}
	}
	
	if(!$download)echo "<pre>";
	$zuozhes = array_filter($zuozhes);
	$zuozhe_result = array_count_values($zuozhes);
	//echo "<pre class=\"first-author\">\n";
	echo "第一作者：\n";
	echo "作者\t篇数\n";
	foreach($zuozhe_result as $zuozhe => $count){
		echo "{$zuozhe}\t{$count}\n";
	}

	// 所有作者统计
	$list_string = str_replace('作者','',$list_string);
	$list_string = str_replace(",\n",',',$list_string);
	$list_string = str_replace("\n",';',$list_string);
	$list_string = preg_replace("/\s/",'',$list_string);
	$list_string = str_replace(';',',',$list_string);
	$list_string = str_replace('，',',',$list_string);
	$authors = explode(',',$list_string);
	$authors = array_filter($authors);
	$authors = array_count_values($authors);
	//echo "<pre class=\"all-author\">\n";
	echo "\n所有作者：\n";
	echo "作者\t篇数\n";
	foreach($authors as $name => $count){
		echo "{$name}\t{$count}\n";
	}
	if(!$download)echo "</pre>";

	// 结束
	exit;
}