<!DOCTYPE html>
<html>
<head>
<title>关键词替换</title>
<meta charset="utf-8">
</head>
<body>
<?php
if($_POST['action_replace'] == 'true'){
	$keywords_list = $_POST['keywords_list'];
	$keywords_go = $_POST['keywords_go'];

	$keywords_list = explode('/',$keywords_list);
	$keyword_catch = $keywords_list[0];
	array_shift($keywords_list);
	$keywords_replace = array();
	foreach($keywords_list as $key => $word){
		$keywords_list[$key] = "/([\n|;])$word([;|\r])/";
		$keywords_replace[$key] = "\${1}$keyword_catch\${2}";
	}

	$keywords_go = preg_replace($keywords_list,$keywords_replace,$keywords_go);
	$keywords_exist = ($_POST['keywords_exist'] ? $_POST['keywords_exist'].'/' : '').$keyword_catch;
}
if($_POST['action_remove'] == 'true'){
	$keywords_exist = $_POST['keywords_exist'];
	$keywords_go = $_POST['keywords_go'];

	$keywords_exist = explode('/',$keywords_exist);
	$keywords_go = explode("\n",$keywords_go);
	$results = array();
	foreach($keywords_go as $line_num => $line_content){
		$results[$line_num] = array();
		if(!empty($line_content)){
			$line_words = explode(';',$line_content);
			foreach($line_words as $key => $value){
				if(in_array($value,$keywords_exist)){
					$results[$line_num][] = $value;
				}
			}
		}
	}
	echo "<pre>关键词\n";
	foreach($results as $line){
		if(!empty($line))echo implode(';',$line)."\n";
		else echo "\n";
	}
	echo "</pre>";
	exit;
}
?>
<form method="post">
	<p>被替换的词列表：<input type="text" name="keywords_list" style="width:600px;" /></p>
	<p>已经存在的关键词：<input type="text" name="keywords_exist" value="<?php echo $keywords_exist; ?>" /></p>
	<p>所有关键词<br /><textarea name="keywords_go" style="width:100%;height:300px;"><?php echo $keywords_go; ?></textarea></p>
	<button type="submit" name="action_replace" value="true">提交</button>
	<button type="submit" name="action_remove" value="true">删除无关</button>
	<button type="reset">重置</button>
</form>
</body>
</html>