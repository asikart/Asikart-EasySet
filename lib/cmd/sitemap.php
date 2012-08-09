<?php
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
$sql = "SELECT * FROM #__menu 
		WHERE id != 1 AND published=1 AND access=1 ;" ;
$menus = AK::_( 'db.loadObjectList' , $sql );

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
$sql = "SELECT * FROM #__categories 
		WHERE id != 1 AND extension = 'com_content' AND published=1 AND access=1 ;" ;
$cats = AK::_( 'db.loadObjectList' , $sql );

foreach( $cats as $cat ):
	
	// get category link
	$link = AK::_( 'content.getCategoryLink' , $cat->id , true ) ;
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
$where = AK::_( 'query.publishedContent' );
$sql = "SELECT * FROM #__content 
		WHERE {$where} 
		ORDER BY id DESC;" ;
$contents = AK::_( 'db.loadObjectList' , $sql );

foreach( $contents as $content ):
	
	// get category link
	$link = AK::_( 'content.getArticleLink' , $content->id , $content->catid , true ) ;
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