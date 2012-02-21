<?php
// Require the base controller
require_once JPATH_ROOT.DS.'administrator'.DS.$com_dir.DS.'helpers'.DS.'helper.php';

$com_name = str_replace( 'com_' , '' , $option) ;

// Set submenu
if( AK::_( 'app.isAdmin' ) ) require_once AK_HELPER_PATH.DS.'component.submenu.php' ;

// Multi controllers or not
if($multictrl):
	$default_controller = $akconfig->get('default_controller') ? $akconfig->get('default_controller') : $com_name ;
	$controller_type	= JRequest::getVar( $controller_query_key );
	$controller_type	= $controller_type ? $controller_type : $default_controller ;
	$controller 		= ucfirst($com_name).'Controller'.ucfirst( $controller_type );
	$controller_file 	= JPATH_COMPONENT.DS.'controllers'.DS.$controller_type.'.php' ;
else:
	$controller 		= ucfirst($com_name).'Controller' ;
	$controller_file 	= JPATH_COMPONENT.DS.'controller.php' ;
endif;

// Require the base controller
require_once $controller_file ;
$controller = new $controller( );

// Perform the Request task
$controller->execute( JRequest::getCmd('task'));
$controller->redirect();

