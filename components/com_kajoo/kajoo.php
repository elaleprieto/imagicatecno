<?php
/**
 * @version     0.1
 * @package     com_kajoo
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Miguel Puig <miguel@freebandtech.com> - http://freebandtech.com
 */

defined('_JEXEC') or die;
if(!defined('DS')){
define('DS',DIRECTORY_SEPARATOR);
}
 

// Include dependancies
jimport('joomla.application.component.controller');

require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/kajoo.php';
$document =& JFactory::getDocument();

$document->addStyleSheet(JURI::base() . 'administrator/components/com_kajoo/assets/bootstrap/css/bootstrap.css');
$document->addStyleSheet(JURI::base() . 'components/com_kajoo/assets/kajoo.css');
$document->addStyleSheet(JURI::base() . 'components/com_kajoo/assets/css/jquery.fancybox.css');

$document->addStyleSheet(JURI::base() . 'administrator/components/com_kajoo/assets/js/jqueryui/css/custom-theme/jquery-ui-1.8.16.custom.css');
$document->addScript(JURI::base() . 'administrator/components/com_kajoo/assets/js/jquery-1.8.2.js');
$document->addScript(JURI::base() . 'administrator/components/com_kajoo/assets/js/jqueryui/js/jquery-ui.min.js');

$document->addScript(JURI::base() . 'administrator/components/com_kajoo/assets/bootstrap/js/bootstrap-typeahead.js');
$document->addScript(JURI::base() . 'administrator/components/com_kajoo/assets/bootstrap/js/bootstrap-modal.js');
$document->addScript(JURI::base() . 'components/com_kajoo/assets/js/jquery.validate.min.js');
$document->addScript(JURI::base() . 'components/com_kajoo/assets/js/jquery.fancybox.pack.js');

// Execute the task.
$controller	= JControllerLegacy::getInstance('Kajoo');
$controller->execute(JRequest::getVar('task',''));
$controller->redirect();
