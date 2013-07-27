<?php
/**
 * @version     0.1
 * @package     com_kajoo
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Miguel Puig <miguel@freebandtech.com> - http://freebandtech.com
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Content controller class.
 */
class KajooControllerNotes extends JControllerForm
{

    function __construct() {
        $this->view_list = 'notes';
        parent::__construct();
    }
    function addCLient()
    {
	    $name = JRequest::getVar('name', '', 'post');
	    $position = JRequest::getVar('position', '', 'post');
	    $company = JRequest::getVar('company', '', 'post');
	    $email = JRequest::getVar('email', '', 'post');
	    $phone = JRequest::getVar('phone', '', 'post');
	    $observations = JRequest::getVar('observations', '', 'post');
	    
	    $db =& JFactory::getDBO();
	    $data =new stdClass();
		$data->name = $name;
		$data->position = $position;
		$data->company = $company;
		$data->email= $email;
		$data->phone = $phone;
		$data->observations = $observations;
		$data->added = JFactory::getDate('now');
		$db->insertObject( '#__kajoo_clients', $data);
		echo $db->insertid();

    }
    function editClient()
    {
    	
	    $idClient = JRequest::getVar('idClient', '', 'post');
	    $name = JRequest::getVar('name', '', 'post');
	    $position = JRequest::getVar('position', '', 'post');
	    $company = JRequest::getVar('company', '', 'post');
	    $email = JRequest::getVar('email', '', 'post');
	    $phone = JRequest::getVar('phone', '', 'post');
	    $observations = JRequest::getVar('observations', '', 'post');
	    
	    $db =& JFactory::getDBO();
	    $data =new stdClass();
		$data->id = $idClient;
		$data->name = $name;
		$data->position = $position;
		$data->company = $company;
		$data->email= $email;
		$data->phone = $phone;
		$data->observations = $observations;
		$data->added = JFactory::getDate('now');
		$db->updateObject( '#__kajoo_clients', $data, 'id');
		echo $idClient;
    }
    
    function deleteClient()
    {
	    $id = JRequest::getVar('id', '', 'get');
	    $db =& JFactory::getDBO();
	    $query = 'DELETE FROM #__kajoo_clients WHERE id = '.$id;
		$db->setQuery($query);
		$db->query(); 
		
		$query = 'DELETE FROM #__kajoo_content_client_rel WHERE client_id = '.$id;
		$db->setQuery($query);
		$db->query();
		
		echo $id;
	   
    }
    function getClient()
    {
	    $id = JRequest::getVar('id', '', 'get');
	    $db = JFactory::getDbo();
		$query  = 'SELECT * FROM `#__kajoo_clients`';    	
    	$query .= ' WHERE id = '.(int)$id;
		$db->setQuery($query);
		$result = $db->loadObjectList();
		
		echo json_encode($result);
    }
    function deleteContent()
   	{
	   	$idClient = JRequest::getVar('idClient', '', 'POST');
	    $idContent = JRequest::getVar('idContent', '', 'POST');
	    $db =& JFactory::getDBO();
	    $query = 'DELETE FROM #__kajoo_content_client_rel WHERE content_id = '.$idContent.' AND client_id = '.$idClient;
		$db->setQuery($query);
		$db->query(); 
   	}	 
    function assignContent()
    {

	    $idClient = JRequest::getVar('idClient', '', 'POST');
	    $idContent = JRequest::getVar('idContent', '', 'POST');
	    
	    $db =& JFactory::getDBO();
	    $query  = 'SELECT count(*) FROM `#__kajoo_content_client_rel`';    	
    	$query .= ' WHERE content_id = '.(int)$idContent;
    	$query .= ' AND client_id = '.(int)$idClient;
		$db->setQuery($query);
		$result = $db->loadResult();
		
	    if($result==0): 
		    $data =new stdClass();
			$data->content_id = $idContent;
			$data->client_id = $idClient;
			$db->insertObject( '#__kajoo_content_client_rel', $data);
		endif;
    }
    function changeState()
    {
	    $idStatus = JRequest::getVar('idStatus', '', 'POST');
	    $soldStatus = JRequest::getVar('soldStatus', '', 'POST');
	    if($soldStatus==0):
	    	$newstate = 1;
	    elseif($soldStatus==1):
	    	$newstate = 2;
	    else:
	    	$newstate=0;
	    endif;
	    
	    $db =& JFactory::getDBO();
	    $data->id = $idStatus;
		$data->state = $newstate;
		$data->updated = JFactory::getDate('now');
		$db->updateObject( '#__kajoo_content_client_rel', $data, 'id');
	
	    
	    
    }
}