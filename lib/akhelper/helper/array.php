<?php

class AKArray {
	
	public static function getItem ($data, $key=0, $separator=',') {
		if( is_array($key) ) return $data[$key] ;
		
		$data = explode( $separator , $data );
		
		if($data[$key]) return $data[$key] ;
		else return 'No data' ;
	}
	
}


