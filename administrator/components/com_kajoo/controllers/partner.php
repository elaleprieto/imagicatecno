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
 * Partner controller class.
 */
class KajooControllerPartner extends JControllerForm
{

    function __construct() {
        $this->view_list = 'partners';
        parent::__construct();
    }
     public function postSaveHook($model)
	{
        $item = $model->getItem();	
        
        KajooHelper::syncPartner($item->id);
        
        if($item->defaultPlayer==''):
        
        	$PartnerInfo = KajooHelper::getPartnerInfo($item->id);
        	$kClient = KajooHelper::getKalturaClient($PartnerInfo->partnerid, $PartnerInfo->administratorsecret, true,$PartnerInfo->url);
     
        	try
			{
				$resultUi = $kClient->uiConf->listAction();
				$model->updatePartner($item->id,$resultUi->objects[0]->id);
			}
			catch(Exception $ex)
			{
			}

     
        endif;

    }
    

}