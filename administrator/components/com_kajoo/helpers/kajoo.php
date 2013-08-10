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
require_once JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'kaltura'.DS.'KalturaClient.php';
/**
 * Kajoo helper.
 */
class KajooHelper
{
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($vName = '')
	{
		JHtmlSidebar::addEntry(
			'<i class="icon-user"></i> '.JText::_('COM_KAJOO_TITLE_PARTNERS').'</span>',
			'index.php?option=com_kajoo&view=partners',
			$vName == 'partners'
		);
		JHtmlSidebar::addEntry(
			'<i class="icon-file"></i> '.JText::_('COM_KAJOO_TITLE_NOTES').'</span>',
			'index.php?option=com_kajoo&view=notes',
			$vName == 'notes'
		);
		JHtmlSidebar::addEntry(
			'<i class="icon-eye-open"></i> '.JText::_('COM_KAJOO_TITLE_CONTENTS').'</span>',
			'index.php?option=com_kajoo&view=contents',
			$vName == 'contents'
		);
		JHtmlSidebar::addEntry(
			'<i class="icon-list"></i> '.JText::_('COM_KAJOO_TITLE_FIELDS').'</span>',
			'index.php?option=com_kajoo&view=fields',
			$vName == 'fields'
		);
		JHtmlSidebar::addEntry(
			'<i class="icon-wrench"></i> '.JText::_('COM_KAJOO_TITLE_CONFIG').'</span>',
			'index.php?option=com_kajoo&view=configuration',
			$vName == 'configuration'
		);
		JHtmlSidebar::addEntry(
			'<i class="icon-upload"></i> '.JText::_('COM_KAJOO_TITLE_UPLOAD').'</span>',
			'index.php?option=com_kajoo&view=upload',
			$vName == 'upload'
		);
		JHtmlSidebar::addEntry(
			'<i class="icon-arrow-down"></i> '.JText::_('COM_KAJOO_TITLE_REPORTS').'</span>',
			'index.php?option=com_kajoo&view=reports',
			$vName == 'reports'
		);
		
		$valid = self::isValid();
		if($valid[0]):
			$icon = 'icon-ok';
		else:
			$icon = 'icon-ban-circle';
		endif;
		
		JHtmlSidebar::addEntry(
			'<i class="'.$icon.'"></i> '.JText::_('COM_KAJOO_TITLE_VALIDATE').'</span>',
			'index.php?option=com_kajoo&view=validation',
			$vName == 'validation'
		);

	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return	JObject
	 * @since	1.6
	 */
	public static function getActions()
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		$assetName = 'com_kajoo';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}
	
	public function getKalturaClient($partnerId, $adminSecret, $isAdmin, $url='')
	{
		$kConfig = new KalturaConfiguration($partnerId);
		if($url==''):
			$kConfig->serviceUrl = 'http://www.kaltura.com';
		else:
			$kConfig->serviceUrl = $url;
		endif;
		$client = new KalturaClient($kConfig);
		
		$userId = "admin";
		$sessionType = ($isAdmin)? KalturaSessionType::ADMIN : KalturaSessionType::USER; 
		try
		{
			$ks = $client->generateSession($adminSecret, $userId, $sessionType, $partnerId);
			$client->setKs($ks);
		}
		catch(Exception $ex)
		{
			die("Could not start session - check configuration in KajooHelper class");
		}
		
		return $client;
	}
	public function getKalturaError($error)
	{
		return '<div class="alert alert-error">'.$error.'</div>';
	}
	public function getPartnerInfo($partnerId)
	{
		try
		{
			$db =& JFactory::getDBO();
			$query  = 'SELECT * FROM `#__kajoo_partners`';    	
	    	$query .= ' WHERE id = '.(int)$partnerId;
			$db->setQuery($query);
			$partnerInfo = $db->loadObject();
		}
		catch(Exception $ex)
		{
			die("Check the partner ID");
		}
		
		return $partnerInfo;
		
		
	}
	public function checkPartnerConfigured()
	{
			$db =& JFactory::getDBO();
			$app = JFactory::getApplication();
			
			$query  = 'SELECT count(*) FROM `#__kajoo_partners`';
			$db->setQuery($query);
			$partnerResult = $db->loadResult();

			if($partnerResult==0):
				$msg = JText::_('COM_KAJOO_PARTNER_CREATEONE');
				$view = JRequest::getVar('view');
				
				if(($view=='notes') || ($view=='contents') || ($view=='fields') || ($view=='configuration') || ($view=='upload')):
					$app->redirect(JRoute::_('index.php?option=com_kajoo&view=partners',false), $msg);
				endif;
			endif;
	}
	public function syncPartner($partnerId)
	// Syncs the database with the new kaltura items providing a PartnerId
	{
			// Connect to Kaltura API
			$PartnerInfo = self::getPartnerInfo($partnerId);
			$kClient = self::getKalturaClient($PartnerInfo->partnerid, $PartnerInfo->administratorsecret, true,$PartnerInfo->url);
			
			$filter = new KalturaMediaEntryFilter();
			// $filter->mediaTypeEqual = 1; //only sync videos
			$filter->idNotIn = 0; //not only sync videos
			
			try
			{
				$results = $kClient->media->listAction($filter);
			}
			
			catch(Exception $ex)
			{
				echo self::getKalturaError(JText::_('COM_KAJOO_PARTNER_NOTCONNECTED'));
			}
			
			
			// Get all content
			$db =& JFactory::getDBO();
			$query  = 'SELECT entry_id,id FROM `#__kajoo_content` WHERE partner_id = '.(int)$partnerId;
			$db->setQuery($query);
			$dbContent = $db->loadRowList();

			foreach($results->objects as $key=>$result):
			
				$existsInDb =  KajooHelper::in_array_r($result->id, $dbContent);
			
				if ($existsInDb) {
				    //If api entry is in db update it
				    $query  = 'SELECT id FROM `#__kajoo_content` WHERE entry_id = "'.$result->id.'"';
			
					$db->setQuery($query);
					$idDB = $db->loadResult();
				    $data =new stdClass();
				    $data->id = $idDB;
					$data->entry_id = $result->id;
					$data->name = $result->name;
					$data->description = $result->description;
					$data->thumburl = $result->thumbnailUrl;
					$data->searchText = $result->searchText;
					$data->duration = $result->msDuration;
					$data->partner_id = $partnerId;
					$data->updated = JFactory::getDate('now');
					$db->updateObject( '#__kajoo_content', $data,'id');
				}
				else{
					//If api entry is NOT in DB add it
		
					$data =new stdClass();
					$data->entry_id = $result->id;
					$data->name = $result->name;
					$data->description = $result->description;
					$data->thumburl = $result->thumbnailUrl;
					$data->searchText = $result->searchText;
					$data->partner_id = $partnerId;
					$data->added = JFactory::getDate('now');
					$data->updated = JFactory::getDate('now');
					$db->insertObject( '#__kajoo_content', $data);
				}
			endforeach;	

			//Insert the object in an array for fast processing
			$result_array = array();
			foreach($results->objects as $resobj):
				$result_array[] = $resobj->id;
			endforeach;
			
			
			//Check if the entry_id in DB is in the API results array and delete if not
			foreach ($dbContent as $key=>$c):

				$existsInKaltura =  KajooHelper::in_array_r($c[0], $result_array);
			
				if (!$existsInKaltura) {
					//Delete content
				   $query = 'DELETE FROM `#__kajoo_content` WHERE entry_id = "'.$c[0].'"';
				   $db->setQuery($query);
				   $db->query();
				   
				   //Delete custom fields
				   $query = 'DELETE FROM `#__kajoo_field_values_entry` WHERE content_id = '.$c[1];
				   $db->setQuery($query);
				   $db->query();
				}
				
			endforeach;
				
	}
	
	public function in_array_r($needle, $haystack, $strict = true) {
	    foreach ($haystack as $item) {
	        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && self::in_array_r($needle, $item, $strict))) {
	            return true;
	        }
	    }
	
	    return false;
    }
    
    public function formatTime($miliseconds)
    {
	    $uSec = $miliseconds % 1000;
		$miliseconds = floor($miliseconds / 1000);
		
		$seconds = $miliseconds % 60;
		$miliseconds = floor($miliseconds / 60);
		if($seconds<10)
			$seconds = '0'.$seconds;
		
		$minutes = $miliseconds % 60;
		$miliseconds = floor($miliseconds / 60); 
		if($minutes<10)
			$minutes = '0'.$minutes;
		
		$hours = $miliseconds % 60;
		$miliseconds = floor($miliseconds / 60); 
		if($hours<10)
			$hours = '0'.$hours;
		return $formated_duration = $hours.':'.$minutes.':'.$seconds;
    }
    
	public function embedPlayer($partnerId,$entry_id,$configId)
	{
		
		// Connect to Kaltura API
		$PartnerInfo = self::getPartnerInfo($partnerId);
		$kClient = self::getKalturaClient($PartnerInfo->partnerid, $PartnerInfo->administratorsecret, true, $PartnerInfo->url);
		
		try
		{
			$uiConf = $kClient->uiConf->get($configId);
		}
		catch(Exception $ex)
		{
			echo self::getKalturaError(JText::_('Fail on getting uiconf'));
		}
		
		if($PartnerInfo->url==''):
			$PartnerInfo->url = 'http://www.kaltura.com';
		endif;


		
$video = '<script type="text/javascript" src="http://html5.kaltura.org/js"></script>		
		
<div id="myVideoTarget" style="width:720px;height:306px;">
	<!--  SEO and video metadata go here -->
	<span property="dc:description" content="An Open Source Movie from the Project Durian"></span>
	<span property="media:title" content="Sintel"></span>
	<span property="media:width" content="720"></span>
	<span property="media:height" content="306"></span>
</div>

<script>
	mw.setConfig( "KalturaSupport.LeadWithHTML5", false );
	mw.setConfig("Kaltura.ForceFlashOnDesktop", true );
	kWidget.embed({
		"targetId": "myVideoTarget",
		"wid": "_'.$PartnerInfo->partnerid.'",
		"uiconf_id" : "'.$uiConf->id.'",
		"entry_id" : "'.$entry_id.'",
		"flashvars":{
			"externalInterfaceDisabled" : false,
			"autoPlay" : false,
			"fooBar": "cats"
		},
		"readyCallback": function( playerId ){
			console.log( "kWidget player ready: " + playerId );
			var kdp = $("#" + playerId ).get(0);
		}
	});
</script>';
		
		
		
		return $video;	
	}
	
	public function getUrlEmbed($partnerId,$entry_id,$configId)
	{
		// Connect to Kaltura API
		$PartnerInfo = self::getPartnerInfo($partnerId);
		$kClient = self::getKalturaClient($PartnerInfo->partnerid, $PartnerInfo->administratorsecret, true, $PartnerInfo->url);
		
		try
		{
			$uiConf = $kClient->uiConf->get($configId);
		}
		catch(Exception $ex)
		{
			echo self::getKalturaError(JText::_('Fail on getting uiconf'));
		}
		
		if($PartnerInfo->url==''):
			$typeEmbed = 'sas';
			$PartnerInfo->url = 'http://www.kaltura.com';
		else:
			$typeEmbed = 'cdn';
		endif;
	

	$video  = '<script type="text/javascript" src="http://html5.kaltura.org/js"></script>';
	$video .= '<object 
	  id="video_'.$entry_id.'" 
	  name="video_'.$entry_id.'" 
	  type="application/x-shockwave-flash" 
	  height="'.$uiConf->height.'" 
	  width="'.$uiConf->width.'" 
	  allowFullScreen="true" 
	  allowNetworking="all" 
	  allowScriptAccess="always" 
	  data="'.$PartnerInfo->url.'/kwidget/wid/_'.$PartnerInfo->partnerid.'/uiconf_id/'.$uiConf->id.'/entry_id/'.$entry_id.'" 
	  xmlns:dc="http://purl.org/dc/terms/" 
	  xmlns:media="http://search.yahoo.com/searchmonkey/media/" 
	  rel="media:video" 
	  resource="'.$PartnerInfo->url.'/kwidget/wid/_'.$PartnerInfo->partnerid.'/uiconf_id/'.$uiConf->id.'/entry_id/'.$entry_id.'">
	  <param name="allowFullScreen" value="true" />
	  <param name="allowNetworking" value="all" />
	  <param name="allowScriptAccess" value="always" />
	  <param name="bgcolor" value="#000000" />
	  <param name="flashVars" value="" />
	  <param name="movie" value="'.$PartnerInfo->url.'/kwidget/wid/_'.$PartnerInfo->partnerid.'/uiconf_id/'.$uiConf->id.'/entry_id/'.$entry_id.'" />
	</object>';
	

	
	return $video;
		
	}
	
	public function getContentFieldValue($contentId,$fieldId,$type)
	{
		$db = JFactory::getDbo();
		$query  = 'SELECT value FROM `#__kajoo_field_values_entry`';    	
    	$query .= ' WHERE content_id = '.(int)$contentId;
    	$query .= ' AND field_id = '.(int)$fieldId;
		$db->setQuery($query);
		$result = $db->loadResult();
		
		if($type==1):
			$query  = 'SELECT value FROM `#__kajoo_field_values`';    	
	    	$query .= ' WHERE id = '.(int)$result;
			$db->setQuery($query);
			$result = $db->loadResult();	
			return $result;
		else:
			return $result;		
		endif;

	}
	public function getContentPartnerValue($partnerId)
	{
		$db = JFactory::getDbo();
		$query  = 'SELECT name FROM `#__kajoo_partners`';    	
    	$query .= ' WHERE id = '.$partnerId;
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}
	public function getFrontEndPositionDetails()
	{
		$alias = array();
		$alias['searchbox']['name'] = JText::_('COM_KAJOO_SEARCHBOX');
		$alias['filters']['name'] = JText::_('COM_KAJOO_FILTERS');
		$alias['maincontent']['name'] = JText::_('COM_KAJOO_MAINCONTENT');
		$alias['categories']['name'] = JText::_('COM_KAJOO_CATEGORIES');
		$alias['partners']['name'] = JText::_('COM_KAJOO_TITLE_PARTNERS');
		$alias['wishlist']['name'] = JText::_('COM_KAJOO_TITLE_WISHLIST');
		
		return $alias;
	}
	public function buildCatTree($items) {

    	$childs = array();

	    foreach($items->objects as $item)
	        $childs[$item->parentId][] = $item;
	
	    foreach($items->objects as $item) if (isset($childs[$item->id]))
	        $item->childs = $childs[$item->id];
	
	    return $childs[0];
	}
	
	
	public function getPartnersList()
	{
		//get default partner
		$db =& JFactory::getDBO();
		$query  = 'SELECT * FROM `#__kajoo_partners` WHERE state = 1';
		$db->setQuery($query);
		$partners = $db->loadObjectList();
		return $partners;
		
	}

	
	public function getCategoryList($partnerId=0)
	{
		//get default partner
		$db =& JFactory::getDBO();
		$query  = 'SELECT min(id) FROM `#__kajoo_partners` WHERE state = 1';
		$db->setQuery($query);
		$defaultPartnerId = $db->loadResult();
		if($partnerId==0):
			$partnerId = $defaultPartnerId;
		endif;
		
		// Connect to Kaltura API
		$PartnerInfo = self::getPartnerInfo($partnerId);
		$kClient = self::getKalturaClient($PartnerInfo->partnerid, $PartnerInfo->administratorsecret, true,$PartnerInfo->url);
		try
		{
			$api_result_categories = $kClient->category->listAction();
			
			$tree = self::buildCatTree($api_result_categories);
			
			return $tree;
		}
		catch(Exception $ex)
		{
			echo self::getKalturaError(JText::_('Fail on geting categories'));
			return false;
		}
	}
	public function getCategoryListRaw($partnerId=0)
	{
		//get default partner
		$db =& JFactory::getDBO();
		$query  = 'SELECT min(id) FROM `#__kajoo_partners` WHERE state = 1';
		$db->setQuery($query);
		$defaultPartnerId = $db->loadResult();
		if($partnerId==0):
			$partnerId = $defaultPartnerId;
		endif;
		
		// Connect to Kaltura API
		$PartnerInfo = self::getPartnerInfo($partnerId);
		$kClient = self::getKalturaClient($PartnerInfo->partnerid, $PartnerInfo->administratorsecret, true,$PartnerInfo->url);
		try
		{
			$api_result_categories = $kClient->category->listAction();
						
			return $api_result_categories;
		}
		catch(Exception $ex)
		{
			echo self::getKalturaError(JText::_('Fail on geting categories'));
			return false;
		}
	}
	public function limit_words($string, $word_limit)
	{
		$words = explode(" ",$string);
		return implode(" ",array_splice($words,0,$word_limit));
	}
	public function getConfig()
	{
		$db =& JFactory::getDBO();
		$query  = 'SELECT * FROM `#__kajoo_config`';
		$db->setQuery($query);
		$config = $db->loadObjectList();
		return $config;
	}
		public function sendMail($body,$subject,$recipient,$sender)
	{
		$mailer =& JFactory::getMailer();
		$config =& JFactory::getConfig();
		 
		$mailer->setSender($sender);
	
		$mailer->addRecipient($recipient);
		
		
		$mailer->isHTML(true);
		$mailer->setSubject($subject);
		$mailer->setBody($body);
		
		$send =& $mailer->Send();
		if ( $send !== true ) {
		    echo JText::_('COM_KAJOO_TITLE_WISHLIST_MAILSENTCLOSENOT');
		} else {
		    echo JText::_('COM_KAJOO_TITLE_WISHLIST_MAILSENTCLOSE');
		}
		
		
	}
	public function isValid()
	{
		$db =& JFactory::getDBO();
		$query  = 'SELECT value FROM `#__kajoo_config` WHERE id = 2';
		$db->setQuery($query);
		$valid = $db->loadResult();
		$valid = json_decode($valid);
		if($valid[3]==1)
			$valid[0] = 0;
		
		$valid[0] = 1;	
		return $valid;
	}
	public function rediretLicense($error)
	{

        JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_kajoo&view=validation',false), $error, 'error' );
	}

}
