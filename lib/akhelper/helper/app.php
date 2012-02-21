<?php

class AKApp {
	
	public static function execute ($func) 
	{
		$app = JFactory::getApplication();
		
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
	
	public static function isSite() {
		return self::execute( 'isSite' );
	}
	
	public static function isAdmin() {
		return self::execute( 'isAdmin' );
	}
	
	public static function getConfig( $key ) {
		return self::execute( 'getCfg' , $key );
	}
	
	public static function close() {
		self::execute( 'close' );
	}
	
	public static function triggerEvent( $event, $args = null ) {
		self::execute( 'triggerEvent' , $event, $args );
	}
}

