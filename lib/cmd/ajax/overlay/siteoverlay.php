<?php
header('Content-Type: text/html; charset=utf-8');

$config = JFactory::getConfig();
jimport( 'joomla.filesystem.path' );

$config->set( 'list_limit' , 100 ) ;
$config->set( 'gzip' , 1 ) ;
$config->set( 'offset' , 'Asia/Taipei' ) ;
$config->set( 'MetaDesc' , 'Asikart.com 飛鳥工作室網站建置' ) ;
$config->set( 'MetaKeys' , 'asika, asikart, seo, joomla' ) ;
$config->set( 'sef_rewrite' , 1 ) ;
$config->set( 'sef_suffix' , 1 ) ;
$config->set( 'unicodeslugs' , 1 ) ;
$config->set( 'feed_limit' , 100 ) ;
$config->set( 'log_path' , JPATH_ROOT.DS.'log' ) ;
$config->set( 'tmp_path' , JPATH_ROOT.DS.'tmp' ) ;
$config->set( 'lifetime' , 150 ) ;
$config->set( 'feed_email' , 100 ) ;
//$config->set( '' ,  ) ;

JFile::move( JPATH_ROOT.DS.'htaccess.txt' , JPATH_ROOT.DS.'.htaccess' );

$configFile = JPATH_ROOT.DS.'configuration.php' ;
JPath::setPermissions( $configFile , 644 );

$file = $config->toString('php' , array( 'class'=>'JConfig' ));
if(JFile::write( $configFile , $file ))
	echo '覆蓋完成';
else
	echo '覆蓋失敗，請檢查configuration.php權限' ;

