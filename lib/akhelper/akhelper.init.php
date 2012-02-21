<?php
require_once dirname(__FILE__).DS.'akhelper.config.php' ;

//some setting
$option 		= JRequest::getVar( 'option' ) ;
$com_dir 		= 'components'.DS.$option ;

//get config
$akconfig		= new AKConfig();
$akhelper_path  = $akconfig->get('akhelper_path') ;

//set akhelper path
$akhelper_path 	= $akhelper_path ? $akhelper_path : dirname(__FILE__) ;

//Define 
require_once (dirname(__FILE__).DS.'akhelper.defines.php') ;

//Load AKHelper
require_once (AK_HELPER_PATH.DS.'akhelper.php') ;

//set component portal
//$multictrl = AK::_( 'app.isAdmin') ? $multictrl_admin : $multictrl_site ; 
//require_once AK_HELPER_PATH.DS.'component.portal.php' ;


