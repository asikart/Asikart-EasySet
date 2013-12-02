<?php

function saveImages( $context , $article )
{
	if( 'com_content' != JRequest::getVar('com_content') );
	
	$db = JFactory::getDbo();
	
	// delete all images data first
	if( $article->id ):
		$sql = "DELETE FROM #__content_images WHERE contentid='{$article->id}' ;" ;
		$db->setQuery( $sql );
		$db->query();
	endif;
	
	JLoader::import( 'dom.simple_html_dom' , AK_ADMIN_LIB_PATH , null );

	$html = str_get_html($article->introtext.$article->fulltext);
	$imgs = $html->find('img');
	$all_img = array() ;
	
	if( $imgs ) :
		foreach( $imgs as $key => $img ) :
			
			$main = 0 ;
			$link = '' ;
			
			if( $img->parentNode()->tag == 'a' ) 
				$link = $img->parentNode()->href ;
			
			$class = $img->getAttribute ( 'class' ) ;
			$class = explode( ' ' , $class );
			if( in_array( 'main' , $class ) ) 
				$main = 1 ;
			
			$insert = new JObject();
			$insert->contentid 	= $article->id ;
			$insert->catid 		= $article->catid ;
			$insert->url 		= $img->src ;
			$insert->link 		= $link ;
			$insert->main 		= $main ;
			$insert->ordering 	= $key ;
			
			$db->insertObject( '#__content_images' , $insert ) ;
			
			unset( $link );
		endforeach;
		
	endif;
	
}
