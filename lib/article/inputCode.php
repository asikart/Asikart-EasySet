<?php

function inputCode ( $article , $easyset ) {
	
	// expression to search for
    $regex = '/{(akcode)\s*(.*?)}/i';
    
    // find all instances of $regex (i.e. akcode) in an article and put them in $matches
    $matches = array();
    preg_match_all( $regex, $article->text, $matches, PREG_SET_ORDER );
    
    foreach ($matches as $dummy):
		$params = array();
   		$akcode = "";
   		
   		// get all params
   		preg_match_all('/\[.*?\]/', $dummy[2], $params);
   		
   		// remove []
		if ($params) :
   			
			foreach ($params as $i => $mm ) 
   				$akcode = preg_replace("/\[|]/", "", $mm);
   		
		endif;
   		
   		// get first param as file name
   		$file = trim( array_shift($akcode) );
   		
   		if ( $file ) {
      		
      		$base = $easyset->params->get( 'inputCodeBase' , '' ) ;
      		$base = str_replace( '/' , '.' , $base );
      		$base = trim( $base , '.' );
      		$file = "{$base}.{$file}" ;
      		$file = trim( $file , '.' );
      		
      		// get file content
			ob_start();
			JLoader::import( $file , JPATH_ROOT, null );
			$output = str_replace( '$' , '\$' , ob_get_contents()); //fixed joomla bug 
			ob_end_clean();
   			
   		}
   		$article->text = preg_replace($regex, $output, $article->text, 1);
   		
	endforeach;
	
}

