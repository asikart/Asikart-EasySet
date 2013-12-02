<?php

function insertContentTop() {
	
	$es = AKEasyset::getInstance() ;
	
	$fileContent 	= $es->params->get( 'insertContentTop' , '' );
	$fileName 		= JPATH_ROOT.DS.'tmp'.DS.'easyset'.DS.'code'.DS.md5(__FUNCTION__) ;
	$fileHash 		= md5( $fileContent );
	
	$tmpName	= $fileName ;
	$tmpContent = JFile::read( $tmpName );
	$tmpHash	= md5( $tmpContent ) ;
	
	if( $tmpHash !== $fileHash ) {
		JFile::write( $tmpName , $fileContent ) ;
	}
	
	ob_start();
	include $tmpName ;
	$output = str_replace( '$' , '\$' , ob_get_contents()); //fixed joomla bug 
	ob_end_clean();
	
	return $output ;
}

