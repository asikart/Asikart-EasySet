<?php

class AKApp {
	
	function execute ($func) 
	{
		$app =& JFactory::getApplication();
		
        if (is_callable( array( $app, $func ) ))
        {
            $temp = func_get_args();
            array_shift( $temp );
            $args = array();
            foreach ($temp as $k => $v) {
                $args[] = &$temp[$k];
            }
            return call_user_func_array( array( $app, $func ), $args );
        }
        else
        {
            JError::raiseWarning( 0, $app.'::'.$func.' not supported.' );
            return false;
        }
	}
	
	function isSite() {
		return self::execute( 'isSite' );
	}
	
	function isAdmin() {
		return self::execute( 'isAdmin' );
	}
	
	function getConfig( $key ) {
		return self::execute( 'getCfg' , $key );
	}
	
	function close() {
		self::execute( 'close' );
	}
	
	function triggerEvent( $event, $args = null ) {
		self::execute( 'triggerEvent' , $event, $args );
	}
}

?>