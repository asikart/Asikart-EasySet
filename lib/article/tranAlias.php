<?php

function tranAlias ($easyset) {
	// set translate language
	$SourceLan = $easyset->params->get('originLan');
    $ResultLan = $easyset->params->get('tranLlan','en');
    
    // get query
    $post = JRequest::get( 'post' );
    
    if( !isset($post['jform'])  || !isset($post['jform']['alias']) ) return ;
    $alias = $post['jform']['alias'] ;
    $title = $post['jform']['title'] ;
    
    $titleTmp = explode( '::' , $post['jform']['title']);
    if( !empty($titleTmp[1]) ) :
		$title = $titleTmp[0];
		$alias = JFilterOutput::stringURLSafe($titleTmp[1]);
	endif;
	
	if( trim($alias) == '' ) :
		$alias = $easyset->getFunction( 'gTranslate' , $title, $SourceLan, $ResultLan);
		$alias = trim( $alias );
		$alias = JFilterOutput::stringURLSafe($alias);
		
		$replace = array(	'aquot' => '' ,
							'a39'	=> '' ,
							'--'	=> '-'
							);
		$alias = strtr( $alias , $replace );
		$alias = trim( $alias , '-' );
	endif;
	
	$post['jform']['alias'] = $alias ;
	$post['jform']['title'] = $title ;
	
	JRequest::setVar( 'jform' , $post['jform'] );
}
/*
function tranAlias ($article , $easyset) {
	$SourceLan = $easyset->params->get('originLan');
    $ResultLan = $easyset->params->get('tranLlan','en');
    
    $titleTmp = explode( '::' , $article->title);
    if( $titleTmp[1] ) :
		$article->title = $titleTmp[0];
		$article->alias = JFilterOutput::stringURLSafe($titleTmp[1]);
	endif;
    
	$alias2 = JFilterOutput::stringURLSafe($article->title);
	
    if(trim(str_replace('-','',$alias2)) == '') {
        $datenow = JFactory::getDate();
        $alias2 = $datenow->toFormat("%Y-%m-%d-%H-%M-%S");
    }
	
	if($article->alias == $alias2){
		$article->alias = $easyset->getFunction( 'gTranslate' , $article->title, $SourceLan, $ResultLan);
		
		$article->alias = trim( $article->alias );
		$article->alias = JFilterOutput::stringURLSafe($article->alias);
		
		$replace = array(	'aquot' => '' ,
							'a39'	=> '' ,
							'--'	=> '-'
							);
		$article->alias = strtr( $article->alias , $replace );
		$article->alias = trim( $article->alias , '-' );
		
    }
	
    return $article;
}
*/
