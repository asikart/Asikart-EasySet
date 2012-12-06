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
		
		
		
		// Show Installed table
		// ========================================================================
		include_once $path.'/windwalker/html/grid.php';
		$grid = new AKGrid();
		
		$option['class'] = 'adminlist table table-striped table-bordered' ;
		$option['style'] = JVERSION >=3 ? 'width: 750px;' : 'width: 80%; margin: 15px;' ;
		$grid->setTableOptions($option);
		$grid->setColumns( array('num', 'type', 'name', 'version', 'state', 'info') ) ;
		
		$grid->addRow(array(), 1) ;
		$grid->setRowCell('num', '#' , array());
		$grid->setRowCell('type', JText::_('COM_INSTALLER_HEADING_TYPE') , array());
		$grid->setRowCell('name', JText::_('COM_INSTALLER_HEADING_NAME') , array());
		$grid->setRowCell('version', JText::_('JVERSION') , array());
		$grid->setRowCell('state', JText::_('JSTATUS') , array());
		$grid->setRowCell('info', JText::_('COM_INSTALLER_MSG_DATABASE_INFO') , array());
		
		
		// Set cells
		$i = 0 ;
		
		if(JVERSION >= 3){
			$tick 	= '<i class="icon-publish"></i>' ;
			$cross 	= '<i class="icon-unpublish"></i>' ;
		}else{
			$tick 	= '<img src="templates/bluestork/images/admin/tick.png" alt="Success" />' ;
			$cross 	= '<img src="templates/bluestork/images/admin/publish_y.png" alt="Fail" />' ;
		}
		
		$td_class = array('style' => 'text-align:center;') ;
		
		
		// Set component install success info
		$grid->addRow(array( 'class' => 'row'.($i % 2) )) ;
		$grid->setRowCell('num', ++$i , $td_class);
		$grid->setRowCell('type', JText::_('COM_INSTALLER_TYPE_PLUGIN') , $td_class);
		$grid->setRowCell('name', JText::_(strtoupper($manifest->name)) , array());
		$grid->setRowCell('version', $manifest->version , $td_class);
		$grid->setRowCell('state', $tick , $td_class);
		$grid->setRowCell('info', '', array());
		
		
		
		// Install WindWalker
		// ========================================================================
		// Do install
		$installer 		= new JInstaller();
		$install_path 	= $path.'/windwalker';
		if($result[] = $installer->install($install_path)){
			$status = $tick ;
		}else{
			$status = $cross ;
		}
		// Set success table
		$grid->addRow(array( 'class' => 'row'.($i % 2) )) ;
		$grid->setRowCell('num', ++$i , $td_class);
		$grid->setRowCell('type', JText::_('COM_INSTALLER_TYPE_LIBRARY') , $td_class);
		$grid->setRowCell('name', JText::_('LIB_WINDWALKER') , array());
		$grid->setRowCell('version', $installer->manifest->version , $td_class);
		$grid->setRowCell('state', $status , $td_class);
		$grid->setRowCell('info', JText::_($installer->manifest->description), array());
		
		
		// Render install information
		/*
		echo '<h1>'.JText::_(strtoupper($manifest->name)).'</h1>' ;
		$img = JURI::base().'/components/'.strtolower($manifest->name).'/images/'.strtolower($manifest->name).'_logo.png' ;
		$img = JHtml::_('image', $img, 'LOGO' ) ;
		$link = JRoute::_("index.php?option=".$manifest->name);
		echo '<div id="ak-install-img">'.JHtml::link($link, $img).'</div>';
		echo '<div id="ak-install-msg">'.JText::_( strtoupper($manifest->name).'_INSTALL_MSG' ).'</div>';
		echo '<br /><br />';
		*/
		
		echo $grid ;
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

