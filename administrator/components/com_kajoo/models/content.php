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

jimport('joomla.application.component.modeladmin');

/**
 * Kajoo model.
 */
class KajooModelcontent extends JModelAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	1.6
	 */
	protected $text_prefix = 'COM_KAJOO';


	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = 'Content', $prefix = 'KajooTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		An optional array of data for the form to interogate.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	JForm	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Initialise variables.
		$app	= JFactory::getApplication();

		// Get the form.
		$form = $this->loadForm('com_kajoo.content', 'content', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_kajoo.edit.content.data', array());

		if (empty($data)) {
			$data = $this->getItem();
            
		}

		return $data;
	}

	/**
	 * Method to get a single record.
	 *
	 * @param	integer	The id of the primary key.
	 *
	 * @return	mixed	Object on success, false on failure.
	 * @since	1.6
	 */
	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk)) {

			//Do any procesing on fields here if needed

		}

		return $item;
	}

	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @since	1.6
	 */
	protected function prepareTable(&$table)
	{
		jimport('joomla.filter.output');

		if (empty($table->id)) {

			// Set ordering to the last item if not set
			if (@$table->ordering === '') {
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__kajoo_content');
				$max = $db->loadResult();
				$table->ordering = $max+1;
			}

		}
	}
	public function updateField($itemid,$fieldid,$value)
	{
		$db = JFactory::getDbo();
		//check if exists

		$query  = 'SELECT id FROM `#__kajoo_field_values_entry`';    	
    	$query .= ' WHERE content_id = '.(int)$itemid;
    	$query .= ' AND field_id = '.(int)$fieldid;
		$db->setQuery($query);
		$id = $db->loadResult();
		
		if(!isset($id)):
			$data =new stdClass();
			$data->content_id = $itemid;
			$data->field_id = $fieldid;
			$data->value = $value;
			$db->insertObject( '#__kajoo_field_values_entry', $data);
		else:
			$data =new stdClass();
			$data->id = $id;
			$data->content_id = $itemid;
			$data->field_id = $fieldid;
			$data->value = $value;
			$db->updateObject( '#__kajoo_field_values_entry', $data, 'id');
		endif;

	}
	public function getCustomFields($content_id)
	{
		$db = JFactory::getDbo();
		$query  = 'SELECT * FROM `#__kajoo_field_values_entry`';    	
    	$query .= ' WHERE content_id = '.(int)$content_id;
		$db->setQuery($query);
		$results = $db->loadObjectList();
		return $results;
	}

}