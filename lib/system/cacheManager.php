<?php

function cacheManager()
{
	$es = AKEasyset::getInstance();
	$control_type 	= $es->params->get('cacheControlType', 'exclude') ;
	$cache_menus 	= $es->params->get('CacheMenus', array()) ;
	$cache_queries 	= $es->params->get('CacheQueries', array()) ;
	$config 		= JFactory::getConfig();
	$cache 			= null ;
	
	$menus 	= JMenu::getInstance('site');
	$uri 	= JFactory::getURI() ;
	$bool 	= array() ;
	
	/*
	// set Menu Itemid
	$route = str_replace( JURI::root(), '', $uri->toString());
	$route = str_replace( '.html', '', $route);
	if( $route == '' || $route == 'index.php' ){
		$actives = $menus->getItems( 'home' , 1 );
	}else{
		$actives = $menus->getItems( 'route' , $route );
	}
	
	// detect cache enabled?
	
	// menus control
	foreach( $actives as $active ):
		if( in_array($active->id, $cache_menus) ){
			$bool = true ;
			break ;
		}	
	endforeach;
	*/
	$itemid = JRequest::getVar('Itemid') ;
	if( in_array($itemid, $cache_menus) ){
		$bool[] = true ;
	}	
	
	// queries control
	$cache_queries = explode("\n", $cache_queries);
	
	//if( $bool == false ):
	
		foreach( $cache_queries as $q ):
			
			$q = explode( '&', $q );
			$r = false ;
			foreach( $q as $v ):
				$r = false ;
				
				if( strpos($v, '!=') ){
					$v = explode('!=', $v) ;
					if( JRequest::getVar(trim($v[0])) == trim($v[1]) ) break ;
				}else{
					$v = explode('=', $v) ;
					if( JRequest::getVar(trim($v[0])) != trim($v[1]) ) break ;
				}
				
				$r = true ;
			endforeach;
			
			$bool[] = $r ? true : false ;
		endforeach;
	
	//endif ;
	
	$tmp = false ;
	foreach( $bool as $v ):
		if($v){
			$tmp = true ;
			break ;
		}
	endforeach;
	$bool = $tmp ;
	
	// detach cache plugin
	$dispatcher = JDispatcher::getInstance();
	$observers	= $dispatcher->get('_observers');
	
	foreach( $observers as $observer ) {
		if( get_class($observer) == 'plgSystemCache' ) 
			$cache = $observer ;
	}
	
	if($control_type == 'include') {
		if($bool == true){
			$caching = 2 ;
		}else{
			$caching = 0 ;
			$dispatcher->detach( $cache );
		}
	}else{
		if($bool == true){
			$caching = 0 ;
			$dispatcher->detach( $cache );
		}else{
			$caching = 2 ;
		}
	}
	
	$config->set('caching' , $caching) ;
	return $caching ;
}