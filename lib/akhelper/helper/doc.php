<?php

class AKDoc {
	
	public static function execute ($func) 
	{
		$doc = JFactory::getDocument();
		
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
	
	public static function addCustomTag() {
		return self::execute( 'addCustomTag' );
	}
	
	public static function addJs() {
		return self::execute( 'addScript' );
	}
	
	public static function addJsDecl() {
		return self::execute( 'addScriptDeclaration' );
	}
	
	public static function addCss() {
		return self::execute( 'addStyleSheet' );
	}
	
	public static function addCssDecl() {
		return self::execute( 'addStyleDeclaration' );
	}
	
	public static function getTitle() {
		return self::execute( 'getTitle' );
	}
	
	public static function setTitle($title) {
		return self::execute( 'setTitle' , $title );
	}
	
	public static function getDescription() {
		return self::execute( 'setDescription' );
	}
	
	public static function setDescription($desc) {
		return self::execute( 'setDescription' , $desc );
	}
	
	public static function getType() {
		return self::execute( 'getType' );
	}
	
	/*
	public static function () {
		return self::execute( '' );
	}
	*/
}


