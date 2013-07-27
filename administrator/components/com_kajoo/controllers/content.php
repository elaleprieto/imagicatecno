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
class KajooControllerContent extends JControllerForm
{

    function __construct() {
        $this->view_list = 'contents';
        parent::__construct();
    }

    public function postSaveHook($model)
	{
        $item = $model->getItem();	

        
        $type = JRequest::getVar('jform[type]', 1, 'post', 'INT');
        $name = JRequest::getVar('name', '', 'post', 'STRING');
        $description = JRequest::getVar('description', '', 'post', 'STRING');
        $tags = JRequest::getVar('tags', '', 'post', 'STRING');
        $partnerid = JRequest::getVar('embedpartnerId', 1, 'post', 'INT');
        $categories = JRequest::getVar('categories');
        $currenttab = JRequest::getVar('currenttab', '', 'post', 'STRING');
        
        
		$session =& JFactory::getSession();
		$session->set( 'currenttab', $currenttab );        

       $comma_separated_categories = implode(",", $categories);
       
       
       //Update Kaltura API object
		$PartnerInfo = KajooHelper::getPartnerInfo($partnerid);
		$kClient = KajooHelper::getKalturaClient($PartnerInfo->partnerid, $PartnerInfo->administratorsecret, true,$PartnerInfo->url);
		
		$k = new KalturaMediaEntry();          
		$k->name=$name;
		$k->description=$description;
		$k->tags=$tags;
		$k->categoriesIds=$comma_separated_categories;		
		
		try
		{
			$api_result = $kClient->media->update($item->entry_id,$k);
		}
		catch(Exception $ex)
		{
			echo JText::_('Cannot update media');
		}
		//Sync content
		KajooHelper::syncPartner($partnerid);
		
		//Update cutom fields
		$fieldsModel = & JModelLegacy::getInstance('fields', 'KajooModel'); 
		$custom_fields = $fieldsModel->getAllFields();
		foreach($custom_fields as $field):
			$value = JRequest::getVar($field->alias, '', 'post', 'STRING');
			$model->updateField($item->id,$field->id,$value);
		endforeach;

	}
}