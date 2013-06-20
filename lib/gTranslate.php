<?php

function gTranslate ($text,$SourceLan,$ResultLan) {
	
	$url = new JURI();
	
    // for APIv2
    $url->setHost( 'https://www.googleapis.com/' );
    $url->setPath( 'language/translate/v2' ) ;
    
    $query['key'] 		= 'AIzaSyC04nF4KXjfR2VQ0jsFm5vEd9LbyiXqbKw' ;
    $query['q'] 		= urlencode($text) ;
    $query['source'] 	= $SourceLan ;
    $query['target'] 	= $ResultLan ;
    
    if( !$text ) return ;
    
    $url->setQuery( $query );
    $url->toString() ;
    $response =  AKHelper::_( 'curl.getPage', $url->toString() );
	
    $json = new JRegistry($response);
    
    $r =  $json->get( 'data.translations' ) ;
    
	return $r[0]->translatedText ;
}

