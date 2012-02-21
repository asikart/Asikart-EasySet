<?php

class AKQuery {
	
	function publishDate ( $prefix = '' ) {
		$db =& JFactory::getDBO();
		$nowDate 	=& JFactory::getDate()->toMySQL();
        $nullDate	= $db->getNullDate();
        
        $date_where = " ( {$prefix}publish_up < '{$nowDate}' OR  {$prefix}publish_up = '{$nullDate}') AND ".
        			  "( {$prefix}publish_down > '{$nowDate}' OR  {$prefix}publish_down = '{$nullDate}') " ;
        			  
        return $date_where ;
	}
	
	function publishedContent () {
		return self::publishDate()." AND state >= '1' ";
	}
	
	function massId ( $ids = array() , $logic = 'AND' , $type = 'id' ) {
		if( !is_array($ids) ) $ids = explode( ',' , $ids );
		
		$temp = array();
		foreach ($ids as $id)
	        	$temp[] = " {$type} = '{$id}' ";
		
		$where = " ( ".implode( $logic ,$temp)." ) " ;
		return $where;
	}
}


?>
