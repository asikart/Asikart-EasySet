<?php

function customCode( $position , $prepareContent = false , $article = null ) {
	
	$es = AKEasyset::getInstance() ;
	
	// generate code output
	$fileContent 	= $es->params->get( $position , '' );
	$fileName 		= JPATH_ROOT.DS.'tmp'.DS.'inputcode'.DS.'code'.DS.md5($position) ;
	$fileHash 		= md5( $fileContent );
	
	if( !file_exists($fileName) )
		JFile::write( $fileName , $fileContent );
	
	$tmpName	= $fileName ;
	$tmpContent = JFile::read( $tmpName ) ;
	$tmpHash	= md5( $tmpContent ) ;
	
	if( $tmpHash !== $fileHash ) {
		JFile::write( $tmpName , $fileContent ) ;
	}
	
	ob_start();
	
	include $tmpName ;
	$output = str_replace( '$' , '\$' , ob_get_contents()); //fixed joomla bug 
	
	ob_end_clean();
	
	// Inset the code to article
	if( $prepareContent ):
		if(!is_object($article)) return;
		
		switch( $position ){
			case 'insertArticleTop' :
				if(JRequest::getVar( 'view' , 'article' ) == 'article')
					echo  $output ;
			break;
			
			case 'insertContentTop' :
				$article->text = $output.$article->text ;
			break;
			
			default:
				return ;
			break;
		}
		
	else:
		return $output ;
	endif;
}

