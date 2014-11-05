var $send = $('#send'),// form
  $key = $('#key'),// 关键词
  $list = $('#list'),// 文本框
  $keys = $key.val(),
  $default = $list.val(),
  $post = false;

// 当文本框获取焦点的时候
$list.focus(function(){
  if($list.val() == $default){
    $list.val('');
  }
  $list.css('color','#333');
}).focusout(function(){
  if($list.val() == ''){
    $list.val($default);
  }
  $list.css('color','#ccc');
});

// 当关键词列表框获取焦点的时候
$key.focus(function(){
  if($key.val() == $keys){
    $key.val('');
  }
  $key.css('color','#333');
}).focusout(function(){
  if($key.val() == ''){
    $key.val($keys);
  }
  $key.css('color','#ccc');
});

// 当表单提交的时候
$send.submit(function(){
  if(
    $list.val() == '' || $key.val() == '' 
    || (
      ($list.val() == $default || ($key.val() == $keys && $keys != 'not null')) 
      && !$post
    )
  ){
    alert('请填写具体的数据！');
    return false;
  }
  if(
    ($list.val() == $default || ($key.val() == $keys && $keys != 'not null'))
    && $post
  ){
    var warn = confirm('填写的内容没有变化，您是否继续使用它？');
    if(warn == false){
      return false;
    }
  }
  setTimeout(function(){
    $default = $list.val();
    $keys = $key.val();
    $post = true;
  },1000);
});