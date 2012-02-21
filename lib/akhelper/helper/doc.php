<?php

class AKDoc {
	
	function execute ($func) 
	{
		$doc =& JFactory::getDocument();
		
        if (is_callable( array( $doc, $func ) ))
        {
            $temp = func_get_args();
            array_shift( $temp );
            $args = array();
            foreach ($temp as $k => $v) {
                $args[] = &$temp[$k];
            }
            return call_user_func_array( array( $doc, $func ), $args );
        }
        else
        {
            JError::raiseWarning( 0, $doc.'::'.$func.' not supported.' );
            return false;
        }
	}
	
	function addCustomTag() {
		return self::execute( 'addCustomTag' );
	}
	
	function addJs() {
		return self::execute( 'addScript' );
	}
	
	function addJsDecl() {
		return self::execute( 'addScriptDeclaration' );
	}
	
	function addCss() {
		return self::execute( 'addStyleSheet' );
	}
	
	function addCssDecl() {
		return self::execute( 'addStyleDeclaration' );
	}
	
	function getTitle() {
		return self::execute( 'getTitle' );
	}
	
	function setTitle($title) {
		return self::execute( 'setTitle' , $title );
	}
	
	function getDescription() {
		return self::execute( 'setDescription' );
	}
	
	function setDescription($desc) {
		return self::execute( 'setDescription' , $desc );
	}
	
	function getType() {
		return self::execute( 'getType' );
	}
	
	/*
	function () {
		return self::execute( '' );
	}
	*/
}

?>
