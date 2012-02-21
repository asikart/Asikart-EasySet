<?php

// set extension type path
switch ( $akconfig->get('extension_type') ) 
{
	case 1 :
		$extension_type 		= 'com' ;
		$extension_path 		= 'components' ;
		$extension_path_admin 	= 'administrator'.DS.'components' ;
	break ;
	
	case 2 :
		$extension_type		= 'mod' ;
		$extension_path		= $akconfig->get( 'module_position' ) ? 'modules' : 'administrator'.DS.'modules' ;
	break ;
	
	case 3 :
		$extension_type		= 'plg' ;
		$extension_path		= 'plugins'.DS.$akconfig->get( 'plugin_group' ) ;
	break ;
	
	default :
		jexit( 'Please set extension type in akhelper.config.php' ) ;
	break ;
}

$extension_name = $extension_type.'_'.$akconfig->get('extension_name' , $option ) ;
$extension_name = strtoupper($extension_name) ;


//Define constants for all pages
define( $extension_name.'_DIR' 			, $com_dir 															);
define( $extension_name.'_PATH'			, JPATH_ROOT.DS.$com_dir 											);
define( $extension_name.'_ADMIN_PATH'	, JPATH_ROOT.DS.'administrator'.DS.$com_dir 						);
define( $extension_name.'_HELPER_PATH'	, JPATH_ROOT.DS.'administrator'.DS.$com_dir.DS.'helper'				);
define( $extension_name.'_URL'			, JURI::root().str_replace( DS, '/', $com_dir )						);
define( $extension_name.'_ADMIN_URL'	, JURI::root().str_replace( DS, '/', 'administrator'.DS.$com_dir )	);


//Define AKHelper Paths
define( 'AK_HELPER_PATH'				, $akhelper_path ) ;


