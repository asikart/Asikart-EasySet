<?php

function setScript() {
	
	$doc 	= JFactory::getDocument();
	$body 	= JResponse::getBody();
	
	if( $doc->getType() !== 'html' ) return ;
	
	$doc->addScript( AK_ADMIN_JS_URL.'/easyset.js' );
	$doc->addScript( AK_JS_URL.'/easyset-custom.js' );
	
	$root = JURI::root() ;
		
	$script = 
<<<SCRIPT
	<script type="text/javascript">
		var akoption = {
				"colorTable" 	: true ,
				"opacityEffect" : true ,
				"foldContent" 	: true ,
				"fixingElement" : true ,
				"smoothScroll"	: true
						} ;
		var akconfig = new Object();
		akconfig.root = '{$root}' ;
		akconfig.host = 'http://'+location.host+'/' ;
		
		AsikartEasySet.init( akoption , akconfig );
	</script>
SCRIPT;


	$doc->addCustomTag( $script ) ;
	
}

?>