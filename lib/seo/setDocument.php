<?php

function setDocument( $easyset ) {
	
	if($easyset->app->isAdmin()) return ;
	
	$doc 		= JFactory::getDocument();
	if($doc->getType() != 'html' ) return ;
	
	$config 	= JFactory::getConfig();
	$siteName	=  $config->get('sitename');
	
	
	if($easyset->params->get('getMeta')) :
		
		if( AKHelper::isHome() ) {
			$doc->setDescription( $config->get('MetaDesc') );
		}else{
			$doc->setDescription( $easyset->_metaDesc );
		}
		
	endif;
	
	// SEO Title
	$easyset->getFunction( 'seo.setTitle' );
	
	if($easyset->params->get('titleFix') && $easyset->_siteTitle) 
		$doc->setTitle($easyset->_siteTitle);
	
	//set Generator
	if( $easyset->params->get('generator') ) 
		$doc->setGenerator( $easyset->params->get('generator') );
	
	// set Open Graph
	if( $easyset->params->get( 'openGraph' , 1 ) ) :
		
		$meta = array();
		
		// og:image
		if( AK::isHome() )
		{
			if ($easyset->params->get( 'ogDefaultImage' ))
			{
				$meta[] = '<meta property="og:image" content="'.AK::_('uri.pathAddHost', $easyset->params->get( 'ogDefaultImage' )).'"/>' ;
			}
		}
		elseif( $easyset->ogImage )
		{
			$meta[] = '<meta property="og:image" content="'.$easyset->ogImage.'"/>' ;
		}
		
		// others
		$url 		= $doc->getBase() ? $doc->getBase() : AK::_( 'uri.current' , true );
		$admin_id	= $easyset->params->get('ogAdminId') ;
		$page_id	= $easyset->params->get('ogPageId') ;
		$app_id		= $easyset->params->get('ogAppId') ;
		
		$meta[] = '<meta property="og:title" content="'.$doc->getTitle().'"/>' ;
		$meta[] = '<meta property="og:site_name" content="'.$siteName.'"/>' ;
		$meta[] = '<meta property="og:description" content="'.$easyset->_metaDesc.'"/>' ;
		$meta[] = '<meta property="og:url" content="'.$url.'"/>' ;
		
		// admin, page, user ids
		if( $admin_id )
			$meta[] = '<meta property="fb:admins" content="'.$admin_id.'"/>' ;
		
		if( $page_id )
			$meta[] = '<meta property="fb:page_id" content="'.$page_id.'"/>' ;
		
		if( $app_id )
			$meta[] = '<meta property="fb:app_id" content="'.$app_id.'"/>' ;
		
		foreach( $meta as $v )
			$doc->addCustomTag( $v ) ;
	endif;
	
	
	
}
