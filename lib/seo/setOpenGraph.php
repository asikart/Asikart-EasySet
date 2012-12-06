<?php

function setOpenGraph ( $context , $article , $es) {
	
	$view = JRequest::getVar( 'view' );
	if( empty($article->id) ) return ;
	
	if( 'article' == $view ) :
		$images = new JRegistry($article->images);
		$img = $images->get('image_fulltext', $images->get('image_intro'));
		if(!$img) {
			$es = AKEasyset::getInstance();
			$es->getFunction( 'dom.simple_html_dom' );
			
			// If first image = main image, delete this paragraph.
			$html = str_get_html ( $article->text ) ;
			$imgs = $html->find( 'img' );
			
			if(!empty($imgs[0])) {
				$img = $imgs[0]->src ;
			}
		}
		
		$cat = JTable::getInstance( 'category' );
		$cat->load( $article->catid ) ;
		$cat->params = new JRegistry( $cat->params ) ;
		
		$catimg = $cat->params->get( 'image' );
		
		if( isset($img) ) {
			$es->ogImage = $img ;
		}elseif( $catimg ){
			$es->ogImage = AK::_('uri.pathAddHost', $catimg) ;
			
		}else{
			if(!$es->params->get( 'ogDefaultImageOnlyFrontPage' , 1 ))
				$es->ogImage = AK::_('uri.pathAddHost', $es->params->get( 'ogDefaultImage' )) ;
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
			
			$es->ogImage = AK::_('uri.pathAddHost',  $es->ogImage );
		}
		
		$once = 0 ;
		
	endif ;
	
}


