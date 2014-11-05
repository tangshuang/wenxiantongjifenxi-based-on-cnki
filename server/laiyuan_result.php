<?php

if(
	isset($_POST) 
	&& isset($_POST['list']) && !empty($_POST['list']) 
	//&& strpos($_SERVER['HTTP_REFERER'],'/tool/jiliang/laiyuan.php') !== false
){
	$download = isset($_POST['download']) && !empty($_POST['download']) ? $_POST['download'] : false;
	if(!$download){
		header("Content-type: text/html; charset=utf-8");
	}else{
		header("Content-Type:".(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ? 'application/octetstream' : 'application/octet-stream')."; charset=UTF-8");
		header("Content-Disposition:".(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ? 'inline' : 'attachment')."; filename=documents_meta_data_laiyuan.txt");
	}

	$type = array();
	$source = array();
	$list_string = trim($_POST['list']);
	$laiyuans = explode("\n",$list_string);
	foreach($laiyuans as $key => $laiyuan){
		if($key == 0)continue;
		if(trim($laiyuan) == ''){
			unset($laiyuans[$key]);
		}else{
			$laiyuan = explode("\t",$laiyuan);
			$type[] = trim($laiyuan[0]);
			$source[$laiyuan[0]][] = trim($laiyuan[1]);
		}
	}
	
	// 来源统计
	$type = array_filter($type);
	$type_result = array_count_values($type);
	//echo "<pre>\n";
	echo "来源统计：\n";
	foreach($type_result as $type => $count){
		echo "{$type}\t{$count}\n";
	}
	//echo '</pre>';

	// 来源分布
	foreach($source as $key => $src){
		$src = array_filter($src);
		$src_result = array_count_values($src);
		if($key == '硕士' || $key == '博士'){
			$key .= '导师';
		}
		if(!$download)echo "<pre>";
		echo "\n{$key}统计：\n";
		echo "{$key}\t篇数\n";
		foreach($src_result as $name => $count){
			$name = trim($name);
			echo "{$name}\t{$count}\n";
		}
		if(!$download)echo "</pre>";
	}

	// 结束
	exit;
}
