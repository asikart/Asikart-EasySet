<?php

defined( '_JEXEC' ) or die;

function doAjax() {
	$app = JFactory::getApplication() ;
	if( $app->isAdmin() ) return ;
	
	$cmd = JRequest::getVar( 'cmd' );
	if( $cmd ):
		
		$file = AK_AJAX_PATH.DS.$cmd.'.php' ;
		if( file_exists($file) ) include_once( $file );
		
		jexit();
		
	endif;
}

