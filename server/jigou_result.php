<?php

if(
	isset($_POST) 
	&& isset($_POST['list']) && !empty($_POST['list']) 
	//&& strpos($_SERVER['HTTP_REFERER'],'/tool/jiliang/jigou.php') !== false
){
	$download = isset($_POST['download']) && !empty($_POST['download']) ? $_POST['download'] : false;
	if(!$download){
		header("Content-type: text/html; charset=utf-8");
	}else{
		header("Content-Type:".(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ? 'application/octetstream' : 'application/octet-stream')."; charset=UTF-8");
		header("Content-Disposition:".(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ? 'inline' : 'attachment')."; filename=documents_meta_data_jigou.txt");
	}

	$list_string = trim($_POST['list']);

	$list_string = str_replace('机构','',$list_string);
	$list_string = str_replace(';',',',$list_string);
	$list_string = str_replace(",\n",',',$list_string);
	$list_string = str_replace("\n",',',$list_string);
	$list_string = preg_replace("/\s/",'',$list_string);
	$organs = explode(',',$list_string);
	$organs = array_filter($organs);
	foreach($organs as $key => $organ){
		$organ = trim($organ);
		$daxue = strpos($organ,'大学');
		$xueyuan = strpos($organ,'学院');
		$guan = strpos($organ,'馆');
		$ju = strpos($organ,'局');
		$suo = strpos($organ,'所');
		$wei = strpos($organ,'委');
		$shi = strpos($organ,'室');
		if($daxue !== false && $xueyuan !== false){
			$organs[$key] = substr($organ,0,$daxue).'大学';
			continue;		
		}
		if($daxue !== false && $guan !== false){
			$organs[$key] = substr($organ,0,$daxue).'大学';
			continue;		
		}
		if($ju !== false){
			$organs[$key] = substr($organ,0,$ju).'局';
			continue;			
		}
		if($guan !== false){
			$organs[$key] = substr($organ,0,$guan).'馆';
			continue;			
		}
		if($suo !== false){
			$organs[$key] = substr($organ,0,$suo).'所';
			continue;			
		}
		if($wei !== false){
			$organs[$key] = substr($organ,0,$wei).'委';
			continue;			
		}
		if($shi !== false){
			$organs[$key] = substr($organ,0,$shi).'室';
			continue;			
		}
		if($daxue !== false){
			$organs[$key] = substr($organ,0,$daxue).'大学';
			continue;
		}
		if($xueyuan !== false){
			$organs[$key] = substr($organ,0,$xueyuan).'学院';
			continue;			
		}
	}
	$organs = array_count_values($organs);
	if(!$download)echo "<pre>";
	echo "机构\t篇数\n";
	foreach($organs as $organ => $count){
		echo "{$organ}\t{$count}\n";
	}
	if(!$download)echo "</pre>";

	// 结束
	exit;
}