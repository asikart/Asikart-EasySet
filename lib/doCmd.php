<?php

function doCmd() {
	$app = JFactory::getApplication() ;
	if( $app->isAdmin() ) return ;
	
	$cmd = JRequest::getVar( 'cmd' );
	if( $cmd ):
		
		$cmd = str_replace( '.' , DS , $cmd );
		$file = AK_CMD_PATH.DS.$cmd.'.php' ;
		
		if( !file_exists($file) ) 
			$file = AK_ADMIN_CMD_PATH.DS.$cmd.'.php' ;
		
		if( file_exists($file) )
			include_once( $file );
		
		jexit();
		
	endif;
}

?>