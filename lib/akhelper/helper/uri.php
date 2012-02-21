<?php

class AKURI {
	public static function execute ($func) 
	{
		$object = JFactory::getURI();
		
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
	
	public static function pathAddHost ( $path ) {
		
		if( !$path ) return ;
		
		// build path
	    $uri = new JURI( $path );
	    
	    if( $uri->getHost() ) return $path ;
	    
	    $uri->parse( JURI::root() );
	    $uri->setPath( $uri->getPath().$path );
	    $uri->setScheme( 'http' );
	    $uri->setQuery(null);
	    
	    return $uri->toString();
	}
	
	public static function urlBase64( $action , $url ) {
		
		switch($action) {
			case 'encode' :
				$url = base64_encode( $url );
			break;
			
			case 'decode' :
				$url = str_replace( ' ' , '+' , $url );
				$url = base64_decode( $url );
			break;
		}
		return $url ;
	}
	
	public static function current( $hasQuery = false ) {
		if( $hasQuery )
			return JFactory::getURI()->toString();
		else
			return self::execute( 'current' );
	}
}


