<?php
/**
 * @version     0.1
 * @package     com_kajoo
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Miguel Puig <miguel@freebandtech.com> - http://freebandtech.com
 */


// no direct access
defined('_JEXEC') or die;
if(!defined('DS')){
define('DS',DIRECTORY_SEPARATOR);
}
 
// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_kajoo')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}
require_once JPATH_COMPONENT.'/helpers/kajoo.php';

$document =& JFactory::getDocument();
//http://addyosmani.github.com/jquery-ui-bootstrap/
$document->addStyleSheet(JURI::base() . 'components/com_kajoo/assets/js/jqueryui/css/custom-theme/jquery-ui-1.8.16.custom.css');
$document->addScript(JURI::base() . 'components/com_kajoo/assets/js/jquery-1.8.2.js');
$document->addScript(JURI::base() . 'components/com_kajoo/assets/js/jqueryui/js/jquery-ui.min.js');

$document->addScript(JURI::base() . 'components/com_kajoo/assets/js/jqueryui/third-party/jQuery-UI-Date-Range-Picker/js/date.js');
$document->addScript(JURI::base() . 'components/com_kajoo/assets/js/jqueryui/third-party/jQuery-UI-Date-Range-Picker/js/daterangepicker.jQuery.compressed.js');
$document->addScript(JURI::base() . 'components/com_kajoo/assets/js/jqueryui/third-party/wijmo/jquery.mousewheel.min.js');
$document->addScript(JURI::base() . 'components/com_kajoo/assets/js/jqueryui/third-party/wijmo/jquery.bgiframe-2.1.3-pre.js');
$document->addScript(JURI::base() . 'components/com_kajoo/assets/js/jqueryui/third-party/wijmo/jquery.wijmo-open.1.5.0.min.js');
$document->addScript(JURI::base() . 'components/com_kajoo/assets/js/jqueryui/third-party/jQuery-UI-FileInput/js/enhance.min.js');
$document->addScript(JURI::base() . 'components/com_kajoo/assets/js/jqueryui/third-party/jQuery-UI-FileInput/js/fileinput.jquery.js');

$document->addScript(JURI::base() . 'components/com_kajoo/assets/bootstrap/js/bootstrap-tab.js');
$document->addScript(JURI::base() . 'components/com_kajoo/assets/bootstrap/js/bootstrap-modal.js');
$document->addScript(JURI::base() . 'components/com_kajoo/assets/bootstrap/js/bootstrap-tooltip.js');
$document->addScript(JURI::base() . 'components/com_kajoo/assets/bootstrap/js/bootstrap-transition.js');
$document->addScript(JURI::base() . 'components/com_kajoo/assets/bootstrap/js/bootstrap-collapse.js');
$document->addScript(JURI::base() . 'components/com_kajoo/assets/bootstrap/js/bootstrap-popover.js');
$document->addScript(JURI::base() . 'components/com_kajoo/assets/bootstrap/js/bootstrap-alert.js');


KajooHelper::checkPartnerConfigured();


// Include dependancies
jimport('joomla.application.component.controller');

$controller	= JControllerLegacy::getInstance('Kajoo');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();
