<?php

function setTitle() {
	$easyset	= AKEasyset::getInstance();
	$doc 		= JFactory::getDocument();
	$config 	= JFactory::getConfig();
	$siteName	= $config->get('sitename');
	$view 		= JRequest::getVar( 'view' ) ;
	$title		= $doc->getTitle();
	
	// fix for YOOTheme
	$title = explode('|', $title);
	$title = $title[0] ;
		
	if( AKHelper::isHome() ):
		$easyset->_siteTitle 	= $config->get('sitename');
	else:
		$separator = trim($easyset->params->get('titleSeparator'));
		
		$replace['{%SITE%}'] 		= $siteName ;
		$replace['{%TITLE%}'] 		= $title ;
		
		if( 'category' == $view || 'categories' == $view )
			$replace['{%CATEGORY%}'] 	= '' ;
		else
			$replace['{%CATEGORY%}'] 	= $easyset->_catName ;
		
		$siteTitle = strtr( $easyset->params->get('titleFix') , $replace );
		$siteTitle = explode( '|' , $siteTitle );
		foreach( $siteTitle as $k => $v ) {
			if( !trim($v) ) :
				unset( $siteTitle[$k] ) ;
				continue;
			endif;
			$siteTitle[$k] = trim( $siteTitle[$k] );
		}
		
		$siteTitle = implode( " {$separator} " , $siteTitle );

		$easyset->_siteTitle = $siteTitle;
	endif;

}
