<?php
/**
 * @version		$Id: readmore.php 10709 2008-08-21 09:58:52Z eddieajau $
 * @package		Joomla
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

/**
 * Editor Readmore buton
 *
 * @package Editors-xtd
 * @since 1.5
 */
class plgButtonKalturaAdd extends JPlugin
{
	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @param 	object $subject The object to observe
	 * @param 	array  $config  An array that holds the plugin configuration
	 * @since 1.5
	 */
	function plgButtonKalturaAdd(& $subject, $config)
	{
		parent::__construct($subject, $config);
	}

	/**
	 * readmore button
	 * @return array A two element array of ( imageName, textToInsert )
	 */
	function onDisplay($name)
	{
		global $mainframe;

		$doc 		=& JFactory::getDocument();
		$template 	= $mainframe->getTemplate();         	

		$doc->addStyleSheet( JURI::Base() . 'components/com_kalturavideo/css/kaltura_rt.css', 'text/css', null, array() );

		
		JPlugin::loadLanguage('plg_editors-xtd_kalturaadd', JPATH_ADMINISTRATOR );		

		$link = 'index.php?option=com_kalturavideo&view=kaltura&tmpl=component';
		JHTML::_('behavior.modal');

		$button = new JObject();
		$button->set('modal', true);
		$button->set('link', $link);
		$button->set('text', JText::_('KALTURAAD'));
		$button->set('name', 'kaltura');
		$button->set('options', "{handler: 'iframe', size: {x: 790, y: 440}}");

		return $button;
	}
}