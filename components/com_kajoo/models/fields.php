<?php

/**
 * @version     0.1
 * @package     com_kajoo
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Miguel Puig <miguel@freebandtech.com> - http://freebandtech.com
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Kajoo records.
 */
class KajooModelFields extends JModelList {

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array()) {
        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @since	1.6
     */
    protected function populateState($ordering = null, $direction = null) {
        
        // Initialise variables.
        $app = JFactory::getApplication();

        // List state information
        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'));
        $this->setState('list.limit', $limit);

        $limitstart = JRequest::getVar('limitstart', 0, '', 'int');
        $this->setState('list.start', $limitstart);

        // List state information.
        parent::populateState();
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return	JDatabaseQuery
     * @since	1.6
     */
    protected function getListQuery() {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
                $this->getState(
                        'list.select', 'a.*'
                )
        );
        $query->from('`#__kajoo_fields` AS a');

        
        
            // Filter by published state
            $published = $this->getState('filter.state');
            if (is_numeric($published)) {
                $query->where('a.state = '.(int) $published);
            } else if ($published === '') {
                $query->where('(a.state IN (0, 1))');
            }
            
        
        return $query;
    }

    public function getAllFields()
	{
		try
		{
			$db =& JFactory::getDBO();
			$query  = 'SELECT * FROM `#__kajoo_fields`';    	
	    	$query .= ' WHERE state = 1';
	    	$query .= ' AND  sitevisible  = 1';
	    	$query .= ' ORDER BY ordering';
			$db->setQuery($query);
			$fields = $db->loadObjectList();
		}
		catch(Exception $ex)
		{
			die("Error retrieving fields");
		}
		
		foreach ($fields as $key=>$field):
			$query  = 'SELECT * FROM `#__kajoo_field_values`';    	
	    	$query .= ' WHERE fieldid = '.$field->id;
	    	$query .= ' ORDER BY ordering';
			$db->setQuery($query);
			$fields[$key]->values = $db->loadObjectList();
		endforeach;
		
		return $fields;
	}

}
