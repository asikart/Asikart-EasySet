<?php

function setScript() {
	
	$doc 	= JFactory::getDocument();
	$body 	= JResponse::getBody();
	$app 	= JFactory::getApplication() ;
	$es		= AKEasyset::getInstance();
	
	if( $doc->getType() !== 'html' ) return ;
	
	JHtml::_('behavior.framework', true);
	
	$doc->addScript( AK_ADMIN_JS_URL.'/easyset.js' );
	$doc->addScript( AK_JS_URL.'/easyset-custom.js' );
	
	$root = JURI::root() ;
	
	$smoothScroll = $es->params->get('smoothScroll', 0) ;
	$smoothScroll = $smoothScroll ? 'true' : 'false' ;
	
	$script = 
<<<SCRIPT
	<script type="text/javascript">
		var akoption = {
				"colorTable" 	: true ,
				"opacityEffect" : true ,
				"foldContent" 	: true ,
				"fixingElement" : true ,
				"smoothScroll"	: {$smoothScroll}
						} ;
		var akconfig = new Object();
		akconfig.root = '{$root}' ;
		akconfig.host = 'http://'+location.host+'/' ;
		
		AsikartEasySet.init( akoption , akconfig );
	</script>
SCRIPT;


	$doc->addCustomTag( $script ) ;
	
	//include jQuery
	if( $es->params->get('includejQuery', 0) && $app->isSite()){
		if(JDEBUG){
			$doc->addScript( AK_ADMIN_JS_URL.'/jquery/jquery-1.7.2.js' );
		}else{
			$doc->addScript( AK_ADMIN_JS_URL.'/jquery/jquery-1.7.2.min.js' );
		}
	}
	
	// inculd bootstrap
	if( $es->params->get('includeBootstrap', 0) && $app->isSite()){
		if(JDEBUG){
			$doc->addStyleSheet(AK_ADMIN_CSS_URL.'/bootstrap/css/bootstrap.css');
			$doc->addStyleSheet(AK_ADMIN_CSS_URL.'/bootstrap/css/bootstrap-responsive.css');
			$doc->addScript( AK_ADMIN_CSS_URL.'/bootstrap/js/bootstrap.js' );
		}else{
			$doc->addStyleSheet(AK_ADMIN_CSS_URL.'/bootstrap/css/bootstrap.min.css');
			$doc->addStyleSheet(AK_ADMIN_CSS_URL.'/bootstrap/css/bootstrap-responsive.min.css');
			$doc->addScript( AK_ADMIN_CSS_URL.'/bootstrap/js/bootstrap.min.js' );
		}
	}
}

