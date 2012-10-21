<?php
//
defined( '_JEXEC' ) or die( 'Restricted access' );

if(JVERSION >= 3.0){
	define('DS', DIRECTORY_SEPARATOR) ;
}

$akpath 	= JPATH_ROOT.DS.'easyset' ;
$akurl		= JURI::root().'easyset/' ;

$akadmin_path 	= JPATH_PLUGINS.DS.'system'.DS.'asikart_easyset' ;
$akadmin_url	= JURI::root().'plugins/system/asikart_easyset' ;
$mainfile 		= $akadmin_path.DS.'main.php';

define('AK_PATH' 		, $akpath );
define('AK_CMD_PATH'	, AK_PATH.DS.'cmd');
define('AK_URL'			, $akurl);
define('AK_JS_URL'		, AK_URL.'js');
define('AK_CSS_URL'		, AK_URL.'css');

define('AK_ADMIN_PATH' 		, $akadmin_path );
define('AK_ADMIN_LIB_PATH'	, AK_ADMIN_PATH.DS.'lib');
define('AK_ADMIN_CMD_PATH'	, AK_ADMIN_LIB_PATH.DS.'cmd');
define('AK_ADMIN_URL'		, $akadmin_url);
define('AK_ADMIN_JS_URL'	, AK_ADMIN_URL.'/lib/includes/js');
define('AK_ADMIN_CSS_URL'	, AK_ADMIN_URL.'/lib/includes/css');

define('AK_THUMB_URL'		, AK_ADMIN_URL.'/lib/timthumb.php');
define('AK_THUMB_PATH'		, JPATH_ROOT.DS.'cache'.DS.'thumbs');
define('AK_THUMBTEMP_PATH'	, AK_THUMB_PATH.DS.'temp');
define('AK_THUMBCACHE_PATH', AK_THUMB_PATH.DS.'cache');
define('AK_THUMBCACHE_URL'	, JURI::root().'cache/thumbs/cache');


// load AKHelper
if( !class_exists( 'AKHelper' ) ) include_once ( AK_ADMIN_LIB_PATH.DS.'akhelper'.DS.'akhelper.init.php' );

// include something
jimport( 'joomla.filesystem.file' );
jimport( 'joomla.filesystem.folder' );

// load Easyset
include_once ( $mainfile );
