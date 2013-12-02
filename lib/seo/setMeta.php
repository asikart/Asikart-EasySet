<?php

function setMeta( $article , $easyset ) {
	
	if($easyset->app->isAdmin()) return ;
	
	static $first_article = true ;
	if( !$first_article ) return ;
	
	$doc 	= JFactory::getDocument();
	$config = JFactory::getConfig();
	
	if( AKHelper::isHome() ):
		$easyset->_metaDesc 	= $config->get('MetaDesc');
	else:
		$metaDesc 			= $article->text;
		$metaDesc 			= strip_tags($metaDesc);
		$metaDesc 			= trim($metaDesc);
		$metaDesc 			= JString::substr($metaDesc,0,$easyset->params->get('maxMetaChar',250));
		$easyset->_metaDesc = $metaDesc ;
	endif;
	
	//if($easyset->params->get('getMeta')) $doc->setDescription('123123123');
	$first_article = false ;
}

