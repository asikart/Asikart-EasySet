<?php

$submenu[] = 'tags' ;
$submenu[] = 'orphan' ;


foreach( $submenu as $val ):
	if( $val ) :
		$view = $multictrl ? $controller_query_key : 'view' ;
		JSubMenuHelper::addEntry( JText::_($val) , "index.php?option={$option}&{$view}={$val}" );
	endif;
endforeach;

