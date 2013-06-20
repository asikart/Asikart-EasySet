<?php
defined( '_JEXEC' ) or die;

// set output format as XML
JRequest::setVar( 'format' , 'xml' , 'method' , true ) ;

// get some datas
$app = JFactory::getApplication();
$doc = JFactory::getDocument(); 
$exists_links  = array();
$date = JFactory::getDate( 'now' , JFactory::getConfig()->get('offset') ) ;

// get XML parser
$xml = simplexml_load_string( '<?xml version="1.0" encoding="utf-8"?'.'>
								<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" />' ) ;

// set frontpage
$url = $xml->addChild( 'url' );
$url->addChild( 'loc'		, JURI::root() 	) ;
$url->addChild( 'lastmod'	, $date->format('Y-m-d')) ;
$url->addChild( 'changefreq', 'daily' 		) ;
$url->addChild( 'priority'	, '0.9' 		) ;


// build menu map

$db = JFactory::getDbo();
$q = $db->getQuery(true) ;

$q->select("*")
	->from("#__menu")
	->where("id != 1")
	->where("published=1")
	->where("access=1")
	;

$db->setQuery($q);
$menus = $db->loadObjectList();

foreach( $menus as $menu ):
	if( !$menu->link ) continue;
	
	// fix URI bugs
	$uri  	= new JURI( $menu->link );
	$uri->setVar( 'Itemid' , $menu->id ) ;
	if( $app->getCfg('sef') ) $uri->setVar( 'layout' , null ) ;
	$link	= JRoute::_( $uri->toString() ) ;
	$host 	= str_replace( 'http://'.$_SERVER['HTTP_HOST'] , '' , JURI::root() );
	$link 	= str_replace( $host , '' , $link );
	$link 	= AK::_( 'uri.pathAddHost' , $link ) ;
	
	// set xml data
	$url = $xml->addChild( 'url' );
	$url->addChild( 'loc'		, $link 	) ;
	$url->addChild( 'lastmod'	, $date->format('Y-m-d')) ;
	$url->addChild( 'changefreq', 'weekly' 		) ;
	$url->addChild( 'priority'	, '0.8' 		) ;
	
	$exists_links[] = $link ;
endforeach;


// build category map
$q = $db->getQuery(true) ;

$q->select("*")
	->from("#__categories")
	->where("id != 1")
	->where("published=1")
	->where("access=1")
	->where("extension = 'com_content'")
	;

$db->setQuery($q);
$cats = $db->loadObjectList();


foreach( $cats as $cat ):
	
	// get category link
	$link = AK::_( 'jcontent.getCategoryLink' , $cat->id , true ) ;
	if( in_array( $link , $exists_links ) ) continue;
	
	// set some data
	$modified = ( $cat->modified_time != '0000-00-00 00:00:00' ) ? $cat->modified_time : $cat->created_time ;
	$modified = JFactory::getDate( $modified , JFactory::getConfig()->get('offset') ) ;
	$modified = $modified->format('Y-m-d');
	
	// set xml data
	$url = $xml->addChild( 'url' );
	$url->addChild( 'loc'		, $link 	) ;
	$url->addChild( 'lastmod'	, $modified ) ;
	$url->addChild( 'changefreq', 'weekly' 	) ;
	$url->addChild( 'priority'	, '0.7' 	) ;
	
	$exists_links[] = $link ;
endforeach;


// build content map
$where = AK::_( 'query.publishingItems' , '', 'state');

$q = $db->getQuery(true) ;

$q->select("*")
	->from("#__content")
	->where($where)
	->order('id DESC')
	;

$db->setQuery($q);
$contents = $db->loadObjectList();

foreach( $contents as $content ):
	
	// get category link
	$link = AK::_( 'jcontent.getArticleLink' , $content->id , $content->catid , true ) ;
	if( in_array( $link , $exists_links ) ) continue;
	
	// set some data
	$modified = ( $content->modified != '0000-00-00 00:00:00' ) ? $content->modified : $content->created ;
	$modified = JFactory::getDate( $modified , JFactory::getConfig()->get('offset') ) ;
	$modified = $modified->format('Y-m-d');
	
	// set xml data
	$url = $xml->addChild( 'url' );
	$url->addChild( 'loc'		, $link 	) ;
	$url->addChild( 'lastmod'	, $modified ) ;
	$url->addChild( 'changefreq', 'weekly' 	) ;
	$url->addChild( 'priority'	, '0.6' 	) ;
	
	$exists_links[] = $link ;
endforeach;



// Output
$doc->setBuffer( $xml->asXML() );

JResponse::setBody( $doc->render() );

echo $app ;