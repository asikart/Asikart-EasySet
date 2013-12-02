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
class JFormFieldCodemirror extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'codemirror';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput()
	{
		$editor = JFactory::getEditor('codemirror');
		//return 'codemirror';
		$params['linenumbers'] = 1 ;
		$params['tabmode'] = 'shift' ;
		//AK::show($this);
		$output = $editor->display ( $this->name , $this->value , '400px' , '250px' , 400 , 300 , false , null , null ,null , $params );
		$output = "<div style=\"float:left;margin-bottom:15px;\">{$output}</div>";
		return $output ;
	}
}