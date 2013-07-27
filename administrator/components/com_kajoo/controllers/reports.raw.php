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
class KajooControllerReports extends JControllerForm
{

    function __construct() {
        $this->view_list = 'reports';
        parent::__construct();
    }
    function getInfo()
    {
    	$showThumb = JRequest::getVar('showThumb', 1, 'POST', 'INT');
    	$showTitle = JRequest::getVar('showTitle', 1, 'POST', 'INT');
    	$showId = JRequest::getVar('showId', 1, 'POST', 'INT');
    	$showPartner = JRequest::getVar('showPartner', 1, 'POST', 'INT');
    	$fieldFilters = JRequest::getVar('fieldFilters', 1, 'POST', 'ARRAY');
    	$arrayTitles = JRequest::getVar('arrayTitles', 1, 'POST', 'ARRAY');
    	
    	
    	$view =& $this->getView('reportspdf', 'html');
    	$view->display();

    }

  

}