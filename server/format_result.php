<?php
$referer = parse_url($_SERVER['HTTP_REFERER']);
if(
  isset($_POST) && 
  isset($_POST['list']) && !empty($_POST['list'])
  //&& $referer['host'] != 'wenxian.danganxue.com'
  //&& $referer['path'] !== '/format.php'
){
  // 规定输出字符集，避免输出乱码
  $download = isset($_POST['download']) && !empty($_POST['download']) ? $_POST['download'] : false;
  if(!$download){
    header("Content-type: text/html; charset=utf-8");
  }else{
    header("Content-Type:".(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ? 'application/octetstream' : 'application/octet-stream')."; charset=UTF-8");
    header("Content-Disposition:".(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ? 'inline' : 'attachment')."; filename=documents_meta_data.txt");
  }

  $list = trim($_POST['list']);
  $number = 1;
  
  $xmlString = preg_replace("/\s\s|\r|\n|\r\n/",'',$list);//去除所有空格，不能去除单个空格，因为有些人用空格作为分隔符
  $xmlString = str_replace('<?xmlversion="1.0"?>','',$xmlString);
  $xmlString = str_replace('<?xmlversion="1.0"encoding="UTF-8"?>','',$xmlString);

  // 创建数组及对应的使用数据
  $documents = simplexml_load_string($xmlString);
  $documents = json_decode(json_encode($documents),true);
  $documents = $documents['DATA'];
  //$count = count($documents);
  //$begin = 1 + $count*((int)$number-1);

  // 开始挑选和打印
  // http://elib.cnki.net/grid2008/brief/detailj.aspx?app=CNKI%20E-Learning&dbname=cjfq2006&filename=shij200603012
  // &uid=WEEvREcwSlJHSldRa1FhaXNLb3h2Wm1aZUszbnJhKzd1TnhPSEFza054UUE2RTdvYU9tNnpDdDNXZEt4YVBzPQ==

  if(!$download)echo "<pre>";
  
  // 表头
  echo "序号\t题名\t作者\t年度\t发表时间\t来源\t刊物(会议、导师)名称\t机构\t关键词\t摘要\t详情\t下载";
  echo "\n";

  // 打印记录
  foreach($documents as $num => $doc){
    // 准备变量
    $doc_title = $doc['Title'] ? $doc['Title'] : '-';
    $doc_year = $doc['Year'] ? $doc['Year'] : '-';
    $doc_pub = $doc['PubTime'] ? $doc['PubTime'] : '-';
    $doc_db = $doc['SrcDatabase'] ? $doc['SrcDatabase'] : '-';
    $doc_period = $doc['Period'] ? ',('.$doc['Period'].')' : '';
    $doc_page = $doc['Page'] ? ':'.$doc['Page'] : '';
    // 对作者列表进行处理，作者用,分开
    $doc_author = $doc['Author'] ? $doc['Author'] : '-';
    $doc_author = str_replace('；',',',$doc_author);
    $doc_author = str_replace('，',',',$doc_author);
    $doc_author = str_replace(';',',',$doc_author);
    //$doc_author = str_replace("\s",';',$doc_author);
    //$doc_author = str_replace("\t",';',$doc_author);
    $doc_author = preg_replace("/\s|　|\t/",',',$doc_author);
    $doc_author = array_unique(array_filter(explode(',',$doc_author)));
    $doc_author = implode(',',$doc_author);
    // 刊物：期刊、会议、导师等，把参考文献也弄出来
    if($doc['DataType'] == 1){ // 期刊
      $doc_src = $doc['Source'] ? $doc['Source'] : '-';
      $doc_index = "{$doc_author}.{$doc_title}[J].{$doc_src},{$doc_year}{$doc_period}{$doc_page}.";
    }elseif($doc['DataType'] == 2){ // 硕博
      $doc_src = $doc['Teacher'] ? $doc['Teacher'] : '-';
      $doc_index = "{$doc_author}.{$doc_title}[D].{$doc_src},{$doc_year}.";
    }elseif($doc['DataType'] == 3){ // 会议
      $doc_src = $doc['Meeting'].'-'.$doc['City'];
    }elseif($doc['DataType'] == 4){ // 报纸
      $doc_src = $doc['Source'] ? $doc['Source'] : '-';
    }else{
      $doc_src = $doc['Source'] ? $doc['Source'] : '-';
    }
    // 机构处理
    $doc_organ = $doc['Organ'];
    $doc_organ = str_replace(';',',',$doc_organ);
    $doc_organ = str_replace('；',',',$doc_organ);
    $doc_organ = str_replace('，',',',$doc_organ);
    $doc_organ = preg_replace("/\s|　|\t/",',',$doc_organ);
    $doc_organ = array_unique(array_filter(explode(',',$doc_organ)));
    $doc_organ = implode(',',$doc_organ);
    // 关键词列表
    $keywords = $doc['Keyword'];
    $keywords = str_replace(';',',',$keywords);
    $keywords = str_replace('；',',',$keywords);
    $keywords = str_replace('，',',',$keywords);
    $keywords = preg_replace("/\s|　|\t/",',',$keywords);
    $keywords = array_unique(array_filter(explode(',',$keywords)));
    $doc_keywords = '';
    foreach($keywords as $keyword){
      $clean = strpos($keyword,':');
      if($clean !== false){
        $doc_keywords .= substr($keyword,0,$clean).',';
      }else{
        $doc_keywords .= $keyword.',';
      }
    }
    if(substr($doc_keywords,-1) == ',') {
      $doc_keywords = substr($doc_keywords,0,-1);
    }
    $doc_excerpt = $doc['Summary'] ? $doc['Summary'] : '-';
    // 下面对全文信息链接和下载链接进行处理
    $doc_query = str_replace('http://epub.cnki.net/kns/detail/detail.aspx?','',$doc['Link']);
    $doc_link = 'http://elib.cnki.net/grid2008/brief/detailj.aspx?app=CNKI%20E-Learning&'.$doc_query;
    $doc_query = strtolower($doc_query);
    $doc_query = str_replace('dbname','dbcode',$doc_query);
    $doc_query = substr($doc_query,0,(int)strpos($doc_query,'dbcode=')+11);
    $doc_download = 'http://epub.cnki.net/grid2008/docdown/docdownload.aspx?dflag=cajdown&'.$doc_query;

    // 开始打印
    echo ($num+1)."\t{$doc_title}\t{$doc_author}\t{$doc_year}\t{$doc_pub}\t{$doc_db}\t{$doc_src}\t{$doc_organ}\t{$doc_keywords}\t{$doc_excerpt}\t{$doc_link}\t{$doc_download}\t{$doc_index}";
    echo "\n";
  }
  
  if(!$download)echo "</pre>";

  // 结束
  exit;
}