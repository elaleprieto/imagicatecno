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
 * Field controller class.
 */
class KajooControllerField extends JControllerForm
{

    function __construct() {
        $this->view_list = 'fields';
        parent::__construct();
    }
    

	public function deletevalue()
	{
		
		$id = JRequest::getVar('id', 0, 'get', 'INT');		
		$model = $this->getModel('field');
		$model->deleteValue($id);
  
	}


}