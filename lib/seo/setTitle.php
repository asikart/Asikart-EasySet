<?php

function setTitle() {
	$easyset	= AK::getEasyset();
	$doc 		= JFactory::getDocument();
	$config 	= JFactory::getConfig();
	$siteName	=  $config->getValue('sitename');
		
	if( AKHelper::isHome() ):
		$easyset->_siteTitle 	= $config->getValue('sitename');
	else:
		$separator = trim($easyset->params->get('titleSeparator'));
		
		$replace['{%SITE%}'] 		= $siteName ;
		$replace['{%CATEGORY%}'] 	= $easyset->_catName ;
		$replace['{%TITLE%}'] 		= $doc->getTitle() ;
			
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

?>