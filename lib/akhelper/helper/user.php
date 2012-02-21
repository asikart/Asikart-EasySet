<?php

class AKUser {
	public static function execute ($func) 
	{
		$object = JFactory::getUser();
		
        if (is_callable( array( $object, $func ) ))
        {
            $temp = func_get_args();
            array_shift( $temp );
            
			//Need to load Object
			$object->load( $temp[0] );
			array_shift( $temp );

            $args = array();
            foreach ($temp as $k => $v) {
                $args[] = &$temp[$k];
            }
            return call_user_func_array( array( $object, $func ), $args );
        }
        else
        {
            JError::raiseWarning( 0, $object.'::'.$func.' not supported.' );
            return false;
        }
	}
	
	public static function getName($uid) {
		return self::execute( 'get' , $uid , 'name' );
	}
}


