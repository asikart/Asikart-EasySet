<?php

function setScript() {
	
	$doc = JFactory::getDocument();
	$doc->addScript( AK_JS_URL.'/easyset.js' );
		
	$script = 
<<<SCRIPT
	<script type="text/javascript">
		var akopt = {	"colorTable" : true 
						
						} ;
		AsikartEasySet.init( akopt );
	</script>
SCRIPT;


	$doc->addCustomTag( $script ) ;
	
}

