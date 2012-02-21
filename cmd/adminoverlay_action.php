<?php
header('Content-Type:text/html;charset=utf-8');//防止中文信息有亂碼
header('Cache-Control:no-cache');//防止瀏覽器緩存，導致按F5刷新不管用

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');


function overlay($component){
	$result = JFolder::copy(JPATH_SITE.DS.'plugins'.DS.'system'.DS.'asikart_easy_set'.DS.'html'.DS.'admin'.DS.$component,
						JPATH_ADMINISTRATOR.DS.'components'.DS.$component,null,true);
	
	$component = str_replace('com_', '' , $component);
	if($result) return $component.':成功';
	return $component.':失敗';
}

$folders = JFolder::folders(JPATH_SITE.DS.'plugins'.DS.'system'.DS.'asikart_easy_set'.DS.'html'.DS.'admin');

$message = array();

foreach ($folders as $com) 
	$message[] = overlay($com);

$message = implode(' <br />',$message);

$response = <<<RE
動作完成，請看右方資訊
<div style="
position:absolute;
right:80px;
top:280px;
background-color:#fff;
border:1px solid #ccc;
padding:15px;
">
覆蓋狀況：<br />
$message
</div>
RE;

echo $response ;
?>