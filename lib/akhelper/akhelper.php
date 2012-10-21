<?php

class AKHelper
{
	public static function isHome() {
		$juri = JFactory::getURI();
		$current_url = $juri->toString();
		
		if( $juri->base()==$current_url || $juri->base().'index.php' == $current_url ) 
			return true;
		else 
			return false;
	}
	
	
	public static function show($data = null) {
		echo '<pre>'.print_r( $data , 1 ).'</pre>' ;
	}
	
	
	public static function thumb($url=null , $width=100, $height=100,$zc=0, $q=85 ){
		//echo $url 	= self::_('uri.pathAddHost', $url);
		$path 	= self::_('thumb.resize', $url , $width, $height,$zc, $q) ;
		return $path ;
	}
	
	public static function getArticleLink( $slug , $catslug , $absolute ) {
		return self::_( 'content.getArticleLink' , $slug , $catslug , $absolute );
	}
	
	public static function getArticleImages( $id , $main = false ) {
		return self::_( 'content.getArticleImages' , $id , $main ) ;
	}
	
	public static function pathAddHost ( $path ) {
		return self::_( 'uri.pathAddHost' , $path ) ;
	}
	
	public static function urlBase64( $action , $url ) {
		return self::_( 'uri.urlBase64' , $action , $url  ) ;
	}
	
	public static function getEasySet() {
		if( plgSystemAsikart_easyset::$instance ){
			return plgSystemAsikart_easyset::$instance;
		}
	}
	
	public static function requireOnce($path = null) {
		if( !$path ) return false ;
		
		if( file_exists( $path ) )
			require_once $path ;
	}
	
	public static function addIncludePath( $path='' )
    {
        static $paths;
 
        if (!isset($paths)) {
            $paths = array( AK_HELPER_PATH.DS.'helper' );
        }
 
        // force path to array
        settype($path, 'array');
 
        // loop through the path directories
        foreach ($path as $dir)
        {
            if (!empty($dir) && !in_array($dir, $paths)) {
                array_unshift($paths, JPath::clean( $dir ));
            }
        }
 
        return $paths;
    }
	
	public static function _( $type )
    {
        //Initialise variables
        $prefix = 'AK';
        $file   = '';
        $func   = $type;
 
        // Check to see if we need to load a helper file
        $parts = explode('.', $type);
 
        switch(count($parts))
        {
            case 3 :
            {
                $prefix        = preg_replace( '#[^A-Z0-9_]#i', '', $parts[0] );
                $file        = preg_replace( '#[^A-Z0-9_]#i', '', $parts[1] );
                $func        = preg_replace( '#[^A-Z0-9_]#i', '', $parts[2] );
            } break;
 
            case 2 :
            {
                $file        = preg_replace( '#[^A-Z0-9_]#i', '', $parts[0] );
                $func        = preg_replace( '#[^A-Z0-9_]#i', '', $parts[1] );
            } break;
        }
 
        $className    = $prefix.ucfirst($file);
 
        if (!class_exists( $className ))
        {
            jimport('joomla.filesystem.path');
            if ($path = JPath::find(self::addIncludePath(), strtolower($file).'.php'))
            {
                require_once $path;
 
                if (!class_exists( $className ))
                {
                    JError::raiseWarning( 0, $className.'::' .$func. ' not found in file.' );
                    return false;
                }
            }
            else
            {
                JError::raiseWarning( 0, $prefix.$file . ' not supported. File not found.' );
                return false;
            }
        }
 
        if (is_callable( array( $className, $func ) ))
        {
            $temp = func_get_args();
            array_shift( $temp );
            $args = array();
            foreach ($temp as $k => $v) {
                $args[] = &$temp[$k];
            }
            return call_user_func_array( array( $className, $func ), $args );
        }
        else
        {
            JError::raiseWarning( 0, $className.'::'.$func.' not supported.' );
            return false;
        }
    }
}

class AK extends AKHelper {
	
}
