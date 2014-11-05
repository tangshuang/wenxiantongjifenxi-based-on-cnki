<?php

if(
	isset($_POST) 
	&& isset($_POST['years']) && !empty($_POST['years']) 
	//&& strpos($_SERVER['HTTP_REFERER'],'/tool/jiliang/niandu.php') !== false
){
	$download = isset($_POST['download']) && !empty($_POST['download']) ? $_POST['download'] : false;
	if(!$download){
		header("Content-type: text/html; charset=utf-8");
	}else{
		header("Content-Type:".(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ? 'application/octetstream' : 'application/octet-stream')."; charset=UTF-8");
		header("Content-Disposition:".(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ? 'inline' : 'attachment')."; filename=documents_meta_data_niandu.txt");
	}

	$years_string = trim($_POST['years']);
	$years_string = str_replace('年度','',$years_string);
	$years = explode("\n",$years_string);
	$years = array_filter($years);
	$now_year = date('Y');
	$min_year = (int)$now_year;
	foreach($years as $key => $year){
		$year = trim($year);
		$years[$key] = $year;
		if(!is_numeric($year) || empty($year)){
			unset($years[$key]);
		}
		if(is_numeric($year) && $min_year > $year){
			$min_year = $year;
		}
	}
	if(!$download)echo "<pre>";
	echo "年度\t篇数\n";
	if($min_year && $min_year != $now_year)for($i = $min_year;$i <= $now_year;$i ++){
		if(!in_array($i,$years)){
			echo "{$i}\t0\n";
		}
	}
	$years_result = array_count_values($years);
	ksort($years_result);
	foreach($years_result as $year => $count){
		echo "{$year}\t{$count}\n";
	}
	if(!$download)echo "</pre>";
	exit;
}