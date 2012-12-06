<?php

function insertHeader() {
	$doc = JFactory::getDocument();
	if( $doc->getType() !== 'html' ) return ;
	
	$es = AKEasyset::getInstance();

	$body = JResponse::getBody();

	$body = explode( '</head>' , $body );
	
	$body[0] .= "\n".$es->params->get( 'insertHeader' , '' )."\n" ;
	$body = implode( '</head>' , $body );
	JResponse::setBody($body);
	
	//$doc->addCustomTag( '<link rel="stylesheet" href="easyset/custom.css" type="text/css" />' );
}

