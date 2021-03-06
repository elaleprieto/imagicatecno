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

require_once JPATH_COMPONENT.'/controller.php';

/**
 * Contents list controller class.
 */
class KajooControllerContents extends KajooController
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function &getModel($name = 'Contents', $prefix = 'KajooModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	public function gettypeHead()
	{
		$db = JFactory::getDBO();

        $query = 'select * from #__kajoo_content';
        $db->setQuery($query);
        $list = $db->loadObjectList();
        
		$return = array();
		foreach ($list as $l):
			array_push($return,array('id'=>$l->id,'name'=>$l->name));
		endforeach;
    
		
		echo(json_encode($return));

		
	}

	
	public function sendRequest()
	{
		
		$site=JURI::base();
		$name = JRequest::getVar('name', '', 'post');
		$company = JRequest::getVar('company', '', 'post');
		$position = JRequest::getVar('position', '', 'post');
		$email = JRequest::getVar('email', '', 'post');
		$phone= JRequest::getVar('phone', '', 'post');
		$mobile = JRequest::getVar('mobile', '', 'post');
		$comments= JRequest::getVar('comments', '', 'post');
		$mobile = JRequest::getVar('mobile', '', 'post');
		$wishlist = JRequest::getVar('wishlist',  0, '', 'array');

		$params = &JComponentHelper::getParams( 'com_kajoo' );
		$emailManager = $params->get( 'emailManager','' );
		$subject = 'Info request';
		$body  = '<h2>New info request from Kajoo</h2>';
		if($name):
			$body .= '<hr><strong>Name: </strong>'. $name;
		endif;
		if($company):
			$body .= '<br /><strong>Company: </strong>'. $company;
		endif;
		if($position):
			$body .= '<br /><strong>Position: </strong>'. $position;
		endif;
		if($email):
			$body .= '<br /><strong>Email: </strong>'. $email;
		endif;
		if($phone):
			$body .= '<br /><strong>Phone: </strong>'. $phone;
		endif;
		if($mobile):
			$body .= '<br /><strong>Mobile: </strong>'. $mobile;
		endif;
		if($comments):
			$body .= '<br /><strong>Comments: </strong>'. $comments;
		endif;
		
		$body .='<h3>Titles of interest:</h3>';
		foreach ($wishlist as $key=>$wl):
			$body .= $wl."<br>";
		endforeach;
		$body .='<br /><hr><br />Site: '.$site;

		KajooHelper::sendMail($body,$subject,$emailManager,$email);
		
	}
}