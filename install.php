<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
/**
 * Script file of HelloWorld component
 */
class plgSystemAsikart_easysetInstallerScript
{
	/**
	 * method to install the component
	 *
	 * @return void
	 */
	function install($parent) 
	{
		$msg = '';
		
		$db = JFactory::getDbo();
		$sql = "UPDATE #__extensions 
				SET enabled='1' 
				WHERE element='asikart_easyset' ;" ;
		$db->setQuery( $sql );
		$db->query();
		
		$this->_createTable();
		
		//JFolder::copy( JPATH_PLUGINS.DS.'system'.DS.'asikart_easyset' , JPATH_ROOT.DS.'easyset' , '' , true );
		JFolder::create( JPATH_ROOT.'/easyset' );
		JFolder::create( JPATH_ROOT.'/easyset/cmd' );
		
		/*
		$array = array();
		$array[] = 'onAfterInitialise';
		$array[] = 'onAfterRoute';
		$array[] = 'onAfterDispatch';
		$array[] = 'onAfterRender';
		$array[] = 'onContentPrepare';
		$array[] = 'onContentBeforeDisplay';
		$array[] = 'onContentAfterDisplay';
		$array[] = 'onContentBeforeTitle';
		$array[] = 'onContentBeforeSave';
		$array[] = 'onContentAfterSave';
		$array[] = 'onContentBeforeDelete';
		$array[] = 'onContentAfterDelete';
		$array[] = 'onContentChangeState';
		
		$content = "<"."?php \n\n\n?".">" ;
		
		$libpath = JPATH_ROOT.DS.'easyset'.DS.'lib'.DS.'events' ;
		JPath::setPermissions( $libpath , 755 );
		
		foreach( $array as $file ) {
			if( JFile::exists( $libpath.DS.$file.'.php' ) )
				JFile::write( $libpath.DS.$file.'.php' , $content );
		}*/
		
		$file_list = $this->_copyIncludeFiles();
		if( $file_list ) $msg .= "<h3>成功複製檔案</h3><ul>{$file_list}</ul><br /><br />" ;
		
		// direct link
		$sql = "SELECT * FROM #__extensions WHERE element='asikart_easyset' ;" ;
		$db->setQuery( $sql );
		$plugin = $db->loadObject() ;
		$link = 'index.php?option=com_plugins&task=plugin.edit&extension_id='.$plugin->extension_id ;
		
		$msg = "<p>Easy set 安裝成功。</p><br />
				<h3><a href=\"{$link}\">[進入外掛管理]</a></h3><br /><br />".$msg;
		echo $msg ;
	}
 
	/**
	 * method to uninstall the component
	 *
	 * @return void
	 */
	function uninstall($parent) 
	{
		// $parent is the class calling this method
		//echo '<p>' . JText::_('COM_HELLOWORLD_UNINSTALL_TEXT') . '</p>';
		
	}
 
	/**
	 * method to update the component
	 *
	 * @return void
	 */
	function update($parent) 
	{
		// $parent is the class calling this method
		$this->_createTable();
		$file_list = $this->_copyIncludeFiles();
		
		$msg = '';
		
		if( $file_list ) $msg .= "<h3>成功複製更新檔案</h3><ul>{$file_list}</ul><br /><br />" ;
		
		echo '<p>' . '更新成功'. '</p>'.$msg;
		//JFolder::copy( JPATH_PLUGINS.DS.'system'.DS.'asikart_easyset' , JPATH_ROOT.DS.'easyset' , '' , true );
	}
 
	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @return void
	 */
	function preflight($type, $parent) 
	{
		// $parent is the class calling this method
		// $type is the type of change (install, update or discover_install)
		//echo '<p>' . JText::_('COM_HELLOWORLD_PREFLIGHT_' . $type . '_TEXT') . '</p>';
	}
 
	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	function postflight($type, $parent) 
	{
		$db = JFactory::getDbo();
		
		
		// Get install manifest
		// ========================================================================
		$p_installer 	= $parent->getParent() ;
		$installer 		= new JInstaller();
		$manifest 		= $p_installer->manifest ;
		$path 			= $p_installer->getPath('source');
		$result			= array() ;
		
		$css =
<<<CSS
	<style type="text/css">
		#ak-install-img {
			
		}
		
		#ak-install-msg {
			
		}
	</style>
CSS;
		
		echo $css ;
		include_once $path.'/windwalker/admin/installscript.php' ;
	}
	
	function _copyIncludeFiles(){
		$file_list = '' ;
		
		// css
		$types['css']['from'] = 'lib/events' ;
		$types['css']['to'] = 'easyset/events' ;
		
		// js
		$types['js']['from'] = 'css' ;
		$types['js']['to'] = 'easyset/css' ;
		
		// events
		$types['events']['from'] = 'js' ;
		$types['events']['to'] = 'easyset/js' ;
		
		
		foreach( $types as $type ):
		
			$include_path 	= JPath::clean(JPATH_ROOT.'/plugins/system/asikart_easyset/'.$type['from']) ;
			$include_path_to= JPath::clean(JPATH_ROOT.'/'.$type['to']) ;
			$include_files	= JFolder::files( $include_path , '.' , true , true ) ;
			
			if( !JFolder::exists($include_path_to) ) JFolder::create( $include_path_to ) ;
			
			foreach( $include_files as $include ){
				$include = JPath::clean($include) ;
				$file = str_replace( $include_path , $include_path_to , $include ) ;
				
				if( !JFile::exists( $file ) ){
					JFile::copy( $include , $file );
					$file_list .= '<li>'.$file.'</li>' ;
				}
			}
		endforeach;
		
		return $file_list ;
	}
	
	function _createTable() {
		$db = JFactory::getDbo();
		
		//if($db->getTableCreate( '#__content_images' ))
			//return ;
		
		$sql = <<<SQL
			CREATE TABLE IF NOT EXISTS `#__content_images` (
			 `contentid` INT( 11 ) NOT NULL ,
			 `catid` INT( 11 ) NOT NULL ,
			 `url` VARCHAR( 255 ) NOT NULL ,
			 `link` VARCHAR( 255 ) NOT NULL ,
			 `main` INT( 3 ) NOT NULL ,
			 `ordering` INT( 11 ) NOT NULL
			) ENGINE = MYISAM ;
SQL;
		$db->setQuery( $sql );
		$db->query() ;

	}
}

