<?php
/**
 * php document by Asika
 */ 

defined('_JEXEC') or die;

class AKLanguageOrphan {
	
	public function __construct()
	{
		
	}
	
	public function __destruct()
	{
		// get some data
		$lang 	= JFactory::getLanguage();
		$orphans= $lang->getOrphans();
		$used 	= $lang->getUsed() ;
		$es		= AKEasyset::getInstance();
		
		// get file
		$path 	= JPATH_ROOT.DS.$es->params->get('languageOrphanPath', 'logs/language.ini'); //'logs'.DS.'language.ini' ;
		$path	= JPath::clean($path);
		$file	= '';
		
		if(JFile::exists($path))
			$file = JFile::read($path);
		
		// set ini into registry, then convert to object
		$old	= new JRegistry();
		$old->loadString($file,'INI',array('processSections'=>'true'));
		$old = $old->toObject();
		
		//AK::show($old);
		
		// remove translated key
		foreach( (array)$old as $k => $v ){
			foreach( (array)$v as $k2 => $v2 ){
				if( array_key_exists($k2,$used) ){
					unset($old->$k->$k2);
					if(!$old->$k){
						unset($old->$k);
					}
				}
			}
		}
		
		//AK::show($old);
		
		// get orphan keys
		$obj = new JObject();
		
		foreach( $orphans as $k => $v ) {
			$key = explode( '_' , $k );
			
			$context = array_slice($key,0 , 2);
			$context = implode( '_' , $context );
			
			$lang = array_slice($key,2);
			$lang = implode( ' ' , $lang );
			
			if(!$obj->get($context)){
				$obj->set($context , new JObject() );
			}
			
			$obj->$context->set( $k , $lang );
		}
		
		// merge ini and orphans
		$ini = $obj ;
		
		foreach( (array)$old as $k => $v ){
			if( isset($ini->$k) ){
				$ini->$k = (object)array_merge((array) $ini->$k, (array) $old->$k);
			}else{
				$ini->$k = $v ;
			}
			
			$ini->$k = (array) $ini->$k ;
			ksort( $ini->$k );
			$ini->$k = (object) $ini->$k ;
		}
		
		//AK::show($ini);
		
		// save to file
		$ini = new JRegistry($ini);
		$ini = $ini->toString('ini') ;
		$ini = str_replace( '_errors=""' , '' , $ini );
		$ini = str_replace( '_errors=' , '' , $ini );
		
		//jimport('joomla.filesystem.path');
		//JPath::setPermissions($path, 644, 755);
		//if(is_writable($path))
			@JFile::write( $path , $ini );
	}
	
}

function languageOrphan(){
	static $orphan ;
	if(!$orphan)
		$orphan = new AKLanguageOrphan();
}