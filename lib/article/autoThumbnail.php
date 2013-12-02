<?php

function autoThumbnail( $context, $article, $params = null ){
	
	JHtml::_('behavior.modal');
	
	$minimal = 30 ;
	
	$es = AKEasyset::getInstance();
	$es->getFunction( 'dom.simple_html_dom' );
	
	$html = str_get_html($article->text);
	$imgs = $html->find( 'img' );
	
	foreach( $imgs as $img ):
		$classes 	= explode( ' ' , $img->class) ;
		$imgUrl 	= AK::_( 'uri.pathAddHost' , $img->src ) ;
		
		if( in_array( 'nothumb' , $classes ) ) 	continue ; // Has class nothumb, skip to next.
		if( $img->parent->tag == 'a' ) 			continue ; // If is anchor already, skip to next.
		if( !$img->width && !$img->height ) 	continue ; // If img tag has no width and height attrs, skip.
		if( !strpos( '-'.$imgUrl , JURI::root() ) 
			&& $es->params->get('onlyLocalhostThumb' , 1 ) ) continue ; // If not localhost image, skip.
		
		// get img path and size
		$imgPath= JPath::clean(str_replace( JURI::root() , JPATH_ROOT.DS , $imgUrl ) );
		$size 	= getimagesize( $imgPath ) ;
		
		// manul size
		$imgW 	= $img->width ;
		$imgH 	= $img->height ;
		
		// original size
		$oriW	= $size[0] ;
		$oriH	= $size[1] ;
		
		if( $oriW <= $minimal || $oriH <= $minimal ) 	continue ; // if too small, skip.
		if( $oriW <= $imgW || $oriW <= $imgW ) 			continue ; // If large ten origin, skip.

		$img->src = AK::_('thumb.resize',  $imgUrl , $imgW, $imgH,0 ) ; // get thumb url
		
		$imgtext = $img->outertext ;
		$imgtext = <<<ANCHOR
		<a class="modal" href="{$imgUrl}">{$imgtext}</a>
ANCHOR;
		$img->outertext = $imgtext ;
		
		$classes = null ;
	endforeach;
	
	$article->text = $html->save();
}

