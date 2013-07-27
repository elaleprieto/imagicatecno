<?php
/**
 * @version     0.1
 * @package     com_kajoo
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Miguel Puig <miguel@freebandtech.com> - http://freebandtech.com
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * Fields list controller class.
 */
class KajooControllerFields extends JControllerAdmin
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel($name = 'field', $prefix = 'KajooModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	public function delete()
	{
		$db =& JFactory::getDBO();
		$cid = JRequest::getVar( 'cid', array(0), "", 'array' );

		if (is_array ( $cid ) && count ( $cid ) > 0) {

			foreach($cid as $c):
				//Delete custom values
			   $query = 'DELETE FROM `#__kajoo_field_values` WHERE fieldid = '.$c;
			   $db->setQuery($query);
			   $db->query();
			   
			   $query = 'DELETE FROM `#__kajoo_field_values_entry` WHERE field_id = '.$c;
			   $db->setQuery($query);
			   $db->query();
			   
			endforeach;
		}
		
		
		parent::delete();

	}

}