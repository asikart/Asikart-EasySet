<?php
header('Content-Type:text/html;charset=utf-8');//防止中文信息有亂碼
header('Cache-Control:no-cache');//防止瀏覽器緩存，導致按F5刷新不管用

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

$folders = JFolder::listFolderTree(JPATH_PLUGINS.DS.'system'.DS.'asikart_easy_set','.',10);
$files = JFolder::files(JPATH_PLUGINS.DS.'system'.DS.'asikart_easy_set','.',true,true);

foreach($files as $key=>$val):
	$files[$key] = str_replace('\\', '/', $val);
	$files[$key] = strstr($files[$key],'asikart_easy_set');
	$output = '<filename>'.$files[$key].'</filename>';
	echo htmlentities($output).'<br />';
endforeach;

?>