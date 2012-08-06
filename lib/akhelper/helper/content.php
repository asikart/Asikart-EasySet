<?php

class AKContent {
	
	public static function getArticleLink( $slug , $catslug = null , $absolute = 0 ) {
		
		include_once JPATH_ROOT.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php' ;
		
		$path = JRoute::_(ContentHelperRoute::getArticleRoute($slug, $catslug));
		$host = str_replace( 'http://'.$_SERVER['HTTP_HOST'] , '' , JURI::root() );
		if( $host != '/' ) $path = str_replace( $host , '' , $path );
		
		if( $absolute ) {
			return AK::_( 'uri.pathAddHost' ,$path);
	    }else{
			return $path ;
		}
		
	}
	
	public static function getCategoryLink( $catid , $absolute = 0) {
		
		include_once JPATH_ROOT.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php' ;
		
		$path = JRoute::_(ContentHelperRoute::getCategoryRoute($catid));
		$host = str_replace( 'http://'.$_SERVER['HTTP_HOST'] , '' , JURI::root() );
		$path = str_replace( $host , '' , $path );
		
		if( $absolute ) {
			return AK::_( 'uri.pathAddHost' ,$path);
	    }else{
			return $path ;
		}
	}
	
	public static function getArticleImages( $id , $main = false ) {
		
		$db 	= JFactory::getDbo();
		$result = null ;
		
		if( $main ) :
			$where = " AND main='1' " ;
		
			$sql = "SELECT * FROM #__content_images WHERE contentid='{$id}' {$where} ORDER BY ordering" ;
			$db->setQuery( $sql );
			$result = $db->loadObject();
			
			if( !$result ) :
				$sql = "SELECT * FROM #__content_images WHERE contentid='{$id}' ORDER BY ordering LIMIT 1" ;
				$db->setQuery( $sql );
				$result = $db->loadObject();
			endif;
			
			if( !$result ){
				
				$result = new JObject();
				$result->url = false ;
				$result->link = false ;
				
				return $result ;
			}
			
			$result->url = AK::_( 'uri.pathAddHost' , $result->url );
			$result->link = AK::_( 'uri.pathAddHost' , $result->link );
			
			return $result ;
		else:
			$sql = "SELECT * FROM #__content_images WHERE contentid='{$id}' ORDER BY ordering" ;
			$db->setQuery( $sql );
			
			$imgs = $db->loadObjectList();
			
			foreach( $imgs as $k => $v ) {
				$imgs[$k]->url 	= AK::_( 'uri.pathAddHost' , $v->url);
				$imgs[$k]->link = AK::_( 'uri.pathAddHost' , $v->link);
			}
			
			return $imgs ;
		endif;
	}
	
	public static function htmlRepair($html , $use_tidy = true ) {
		
		if(function_exists('tidy_repair_string') && $use_tidy ):
			$TidyConfig = array('indent' 			=> true,
	                			'output-xhtml' 		=> true,
	                			'show-body-only' 	=> true,
	                			'wrap'				=> false
	                			);
	        return tidy_repair_string($html,$TidyConfig,'utf8');
        else:
        	$arr_single_tags = array('meta','img','br','link','area');
		    #put all opened tags into an array
		    preg_match_all ( "#<([a-z]+)( .*)?(?!/)>#iU", $html, $result );
		    $openedtags = $result[1];
		 
		    #put all closed tags into an array
		    preg_match_all ( "#</([a-z]+)>#iU", $html, $result );
		    $closedtags = $result[1];
		    $len_opened = count ( $openedtags );
		    # all tags are closed
		    if( count ( $closedtags ) == $len_opened )
		    {
		        return $html;
		    }
		    $openedtags = array_reverse ( $openedtags );
		    # close tags
		    for( $i = 0; $i < $len_opened; $i++ )      
		    {
		        if ( !in_array ( $openedtags[$i], $closedtags ) )
		        {
		            if(!in_array ( $openedtags[$i], $arr_single_tags )) $html .= "</" . $openedtags[$i] . ">";
		        }
		        else
		        {
		            unset ( $closedtags[array_search ( $openedtags[$i], $closedtags)] );
		        }
		    }
		    return $html;
        endif;
	}
}


