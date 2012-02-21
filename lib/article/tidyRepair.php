<?php

function tidyRepair(&$article , $easyset) {
	
	$context = explode( '.' , $context );
	if( 'com_content' !== $context[0] ) return ;
	if( !$article->introtext && !$article->fulltext ) return ;
	
	if(function_exists('tidy_repair_string')):
		//Tidy Repair Text
        $TidyConfig = array('indent' 			=> TRUE,
                			'output-xhtml' 		=> true,
                			'show-body-only' 	=> true,
                			'wrap'				=> false
                			);
        $article->introtext = tidy_repair_string($article->introtext,$TidyConfig,'utf8');
        $article->fulltext  = tidy_repair_string($article->fulltext, $TidyConfig,'utf8');
    else :
    	require_once ( 'closeTags.php' );
    	$article->introtext = closetags($article->introtext);
    	$article->fulltext  = closetags($article->fulltext);
    endif;
}

?>