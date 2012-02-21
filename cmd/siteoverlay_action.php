<?php
header('Content-Type:text/html;charset=utf-8');//防止中文信息有亂碼
header('Cache-Control:no-cache');//防止瀏覽器緩存，導致按F5刷新不管用

$db = JFactory::getDBO();
$config_now = JFactory::getConfig();

$config = new JObject();
$config->offline 		= '0';
$config->editor 		= 'jce';
$config->list_limit 	= '100';
$config->feed_limit 	= '100';
$config->feed_email 	= 'site';
$config->gzip 			= '1';
$config->xmlrpc_server 	= '1';
$config->live_site 		= '';
$config->offset 		= '8';
$config->MetaAuthor 	= '1';
$config->MetaTitle 		= '1';
$config->lifetime 		= '151';
$config->MetaDesc 		= 'Asikart.com 專業Joomla!架站工作室';
$config->MetaKeys 		= 'asikart,asika,joomla,website';

$config_now->loadObject($config);

$params['class']='JConfig';
$config_text = $config_now->toString('php',null,$params);

$fp = fopen('configuration.php','w+');

if(fwrite($fp,$config_text)) echo '覆蓋成功';
else echo '失敗';
?>