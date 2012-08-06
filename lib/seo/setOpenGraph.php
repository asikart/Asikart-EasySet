<?php

function setOpenGraph ( $context , $article , $es) {
	
	$view = JRequest::getVar( 'view' );
	if( empty($article->id) ) return ;
	
	if( 'article' == $view ) :
		
		$img = AK::getArticleImages($article->id , 1);
		
		$cat = JTable::getInstance( 'category' );
		$cat->load( $article->catid ) ;
		$cat->params = new JRegistry( $cat->params ) ;
		
		$catimg = $cat->params->get( 'image' );
		
		if( isset($img->url) ) {
			$es->ogImage = $img->url ;
		}elseif( $catimg ){
			$es->ogImage = AK::pathAddHost($catimg) ;
			
		}else{
			if(!$es->params->get( 'ogDefaultImageOnlyFrontPage' , 1 ))
				$es->ogImage = AK::pathAddHost($es->params->get( 'ogDefaultImage' )) ;
		}
		
	elseif( 'category' == $view ):
		
		static $once = 1 ;
		
		if( $once ) {
			$cat = JTable::getInstance( 'category' );
			$cat->load( JRequest::getVar( 'id' ) ) ;
			$cat->params = new JRegistry( $cat->params ) ;
			
			$img = $cat->params->get( 'image' ) ;
			
			if( $img ){
				$es->ogImage = $img ;
			}elseif(!$es->params->get( 'ogDefaultImageOnlyFrontPage' , 1 )){
				$es->ogImage = $es->params->get( 'ogDefaultImage' ) ;
			}
			
			$es->ogImage = AK::pathAddHost( $es->ogImage );
		}
		
		$once = 0 ;
		
	endif ;
	
}


