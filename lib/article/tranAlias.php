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
		$alias = AKHelper::_( 'lang.translate' , $title, $SourceLan, $ResultLan);
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
	
    $input = JFactory::getApplication()->input ;
    
	JRequest::setVar( 'jform' , $post['jform'] , 'method', true);
    $input->post->set( 'jform' , $post['jform'] );
    $input->request->set( 'jform' , $post['jform'] );
}
