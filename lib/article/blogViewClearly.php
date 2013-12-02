<?php

function blogViewClearly( $context, $article, $params = null ){
	
	if( /*JRequest::getVar( 'option' ) != 'com_content' && */ JRequest::getVar( 'layout' ) != 'blog' &&
	   JRequest::getVar( 'view' ) != 'featured')
		return ;
	
	$es 		= AKEasyset::getInstance();	
	$imgW 		= $es->params->get( 'blogViewImgWidth' , 150 );
	$maxChar 	= $es->params->get( 'blogViewMaxChar' , 250 );
	$crop 		= $es->params->get( 'blogViewImgCrop' , 1 );
	$allowTags 	= $es->params->get( 'blogViewTagsAllow' );
	$doc		= JFactory::getDocument();
	$text		= $article->introtext ;
	$mainImg	= null ;
	
	if( $doc->getType() != 'html' ) return ;
	
	// Clean Tags
	if( $es->params->get('blogViewCleanTags' , 1 ) ) :
		
		$es->getFunction( 'dom.simple_html_dom' );
		
		// If first image = main image, delete this paragraph.
		$html = str_get_html ( $text ) ;
		$imgs = $html->find( 'img' );
		
		if($imgs) :
			$mainImg = $imgs[0]->src ;
		
			$p 	= $imgs[0]->parent(); // is img in p tag?
			if( $p->tag != 'p' ) $p = $p->parent(); // if image has anchor, get parent.
			
			$imgtext 		= $p->children[0]->outertext;
			$p->innertext 	= str_replace( $imgtext , '' , $p->innertext) ;
			
			if( !trim( $p->innertext ) ) :
				$p->outertext = '' ;
			endif;
			
			$text	= $html->save();
			$text	= strip_tags( $text , $allowTags ) ;
			
			if( !$allowTags )
				$text = JString::substr( $text , 0 , $maxChar );
			
		endif;
	endif;
	
	// Handle Image
	if( $crop ):
		$imageUrl 	= AK::_('thumb.resize',  $mainImg , $imgW , $imgW , $crop ) ;
	else:
		$imageUrl 	= AK::_('thumb.resize',  $mainImg , $imgW , 999 , 0 ) ; ;
	endif;
	
	// Article Link
	$link = AK::_('jcontent.getArticleLink',  $article->id , $article->catid , 0 );
	
	// Set layout
	$layout = <<<LAYOUT
		<div class="ak_blog_layout">
			<div class="ak_blog_img_wrap fltlft float-left">
				<a class="ak_blog_img_link" href="{$link}">
					<img class="ak_blog_img" src="{$imageUrl}" alt="{$article->title}" width="{$imgW}px" />
				</a>
			</div>
			<div class="ak_blog_intro">
				{$text}
			</div>
			<div class="clr clearfix"></div>
		</div>
LAYOUT;

	$article->introtext = $layout ;
}