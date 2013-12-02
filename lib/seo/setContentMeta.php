<?php

function setContentMeta ( $article , $easyset ) {
	
	$metaDesc = null ;
	
	// This function just need execute one time.
	static $first_article = true ;
	if( !$first_article ) return ;
	
	$doc 	= JFactory::getDocument();
	$config = JFactory::getConfig();
	
	// get menu meta, if nonexists, use article meta
	if( isset($article->params) && $article->params instanceof JRegistry && isset($article->metadesc) )
		$metaDesc = $article->params->get('menu-meta_description' , $article->metadesc ) ;
	
	
	// get meta
	if( AKHelper::isHome() ):
	
		$easyset->_metaDesc = $config->get('MetaDesc');
		
	elseif( $metaDesc ):
	
		$easyset->_metaDesc = $metaDesc ;
		$article->metadesc 	= '' ;
		
	else:
		
		// get meta from article content
		$metaDesc 	= $article->text;
		$metaDesc 	= strip_tags($metaDesc);
		$metaDesc  	= preg_replace('/\{.*\}/', '', $metaDesc); // filter plgin like:{rsform 1}
		
		// remove line
		$metaDesc 	= str_replace( "\r\n" , '' , $metaDesc);
		$metaDesc 	= str_replace( "&nbsp;" , '' , $metaDesc);
		$metaDesc 	= trim($metaDesc);
		$metaDesc 	= JString::substr($metaDesc,0,$easyset->params->get('maxMetaChar',250));
		
		// remove latest word
		$metaDesc 	= trim($metaDesc);
		$metaDesc	= explode( ' ' , $metaDesc );
		$latestWord = array_pop( $metaDesc );
		if( strlen( $latestWord ) > 10 ) 
			$metaDesc[] = $latestWord ;
		
		$metaDesc	= implode( ' ' , $metaDesc ); // rebuild paragraph
		$easyset->_metaDesc = $metaDesc;
		
	endif;
	
	// save article and category data to easyset
	$view 	= JRequest::getVar( 'view' ) ;
	$cat_table = JPath::clean(JPATH_ROOT.'/libraries/joomla/database/table/category.php');
	if(JFile::exists($cat_table)) include_once $cat_table ;
	$cat 	= JTable::getInstance( 'Category' );
	
	if( 'category' == $view || 'categories' == $view ):
		$cat->load( JRequest::getVar('id') ) ;
		$easyset->_catName = $cat->title ;
		
		// Set default metadesc if exists.
		if( $cat->metadesc ):
			$easyset->_metaDesc = $cat->metadesc ;
		endif;
		
	elseif( 'article' == $view ):
		$cat->load( $article->catid ) ;
		$easyset->_catName = $cat->title ;
	endif;
	
	$easyset->category = $cat;
	$easyset->article  = $article ;
	
	
	$first_article = false ;
}

