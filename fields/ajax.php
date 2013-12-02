<?php
/**
 * @version		$Id: article.php 20196 2011-01-09 02:40:25Z ian $
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

/**
 * Supports a modal article picker.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_content
 * @since		1.6
 */
class JFormFieldAjax extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'Ajax';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput()
	{
		
		$plugin = JPluginHelper::isEnabled('system','asikart_easyset');
		if(!$plugin) return print_r($plugin,1).'需要先啟動外掛！';
		
		$name = str_replace( 'jform_params_' , '' , $this->id );
		
		$ajaxUrl  = JURI::root().'index.php?cmd=ajax.overlay.'.$name ;
		$ajaxCode = 
<<<AJAX
		window.addEvent('domready', function() {
			
			$('$name').addEvent('click', function(e) {
				var myRequest = new Request({
					method: 'get' ,
				    url: '{$ajaxUrl}',
				    onSuccess: function(responseText){
				        $('{$name}_response').set('html', '<input disabled="true" value="'+responseText+'">');
				    }
				});
			 
				myRequest.send();
			});	
		});
		
AJAX;
		$doc = JFactory::getDocument(); 
		$doc->addScriptDeclaration($ajaxCode);

		$link = '<div id="'.$name.'_response" ><input id="'.$name.'" type="button" value="按此覆蓋"></div>';
		return $link ;
	}
}