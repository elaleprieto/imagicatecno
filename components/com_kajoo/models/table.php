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
class KajooModelTable extends JModelList {

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
        
        $partnerValue = JRequest::getVar('partnerValue', 0, '', 'int');
        $this->setState('filter.partnerValue', $partnerValue);

        $searchText = JRequest::getVar('searchText', 0, '', 'STRING');
        $this->setState('filter.searchText', $searchText);
        

        $categories = JRequest::getVar('categories');
        $categories = implode(",", $categories);
        $this->setState('filter.categories', $categories);
        

        $custom_filters = JRequest::getVar('filters');
        
		foreach ($custom_filters as $key=>$filter):
			$custom[$key] = $app->getUserStateFromRequest($this->context.'.filter.'.$filter[0], 'filter_'.$filter[0]);

			$this->setState('filter.'.$filter[0], $filter[1]);			
		endforeach;

        // List state information.
        parent::populateState();
    }
    
    public function filterKaltura($partnerId, $mediaType = 0)
	// Returns all the fields filtered iin the API
	{
		$search = $this->getState('filter.searchText');
		

		$filter = new KalturaMediaEntryFilter();
		$filter->orderBy = KalturaPlayableEntryOrderBy::CREATED_AT_DESC;
		// $filter->advancedSearch = new KalturaMetadataSearchItem();
		
		$search = trim($search);
		
		

		
		$filter->freeText = $search;
		
		// if($this->getState('filter.categories')):
// 
			// $catsArray = explode(",", $this->getState('filter.categories'));
// 
			// if (in_array("all", $catsArray)) {
// 			  
			// }
			// else
			// {
				// $filter->categoriesIdsMatchOr = $this->getState('filter.categories'); 	
			// }
			// JUri::getInstance($uri);
		// endif;
		
		switch ($mediaType) {
			case KalturaMediaType::VIDEO:
				# Type = video
				$filter->mediaTypeEqual = KalturaMediaType::VIDEO;
				break;
			case KalturaMediaType::IMAGE:
				# Type = images
				$filter->mediaTypeEqual = KalturaMediaType::IMAGE;
				break;
			case KalturaMediaType::AUDIO:
				# Type = audio
				$filter->mediaTypeEqual = KalturaMediaType::AUDIO;
				break;
			default:
				# Type = all
				 $filter->idNotIn = 0;
				break;
		}
		
		// Connect to Kaltura API
		$PartnerInfo = KajooHelper::getPartnerInfo($partnerId);
		$kClient = KajooHelper::getKalturaClient($PartnerInfo->partnerid, $PartnerInfo->administratorsecret, true, $PartnerInfo->url);
		try
		{
			$filter->advancedSearch = new KalturaMetadataSearchItem();
			$filter->advancedSearch->value = 'lemon';
			// $filter->freeText = 'Lemon';
			$results = $kClient->media->listAction($filter);
			
		}
		catch(Exception $ex)
		{
			echo self::getKalturaError(JText::_('Error retrieving content in API'));
		}
		
		$filter_search = array();
		foreach ($results->objects as $object):
			$filter_search[] = $object->id;
		endforeach;

		// JFactory::getApplication()->enqueueMessage('Mensaje');
		// JFactory::getApplication()->enqueueMessage($filter_search);
		// JError::raiseError(500, implode("', '", $results->filter_search));
		
		$results->filter_search = $filter_search;
		$results->filter_search_text = "'". implode("', '", $results->filter_search) ."'";

		// JError::raiseError(500, $results->filter_search_text);
		return $results;
		
	}
    /**
     * Build an SQL query to load the list data.
     *
     * @return	JDatabaseQuery
     * @since	1.6
     */
    public function filterCustomFields()
	{

		$filter_custom_fields = array();
		$allFields = $this->getAdminFiltrableFields();
	
		foreach($allFields as $key=>$field):
			$aliasPost = $this->getState('filter.'.$field->alias);
			
			if($aliasPost!='all' && $aliasPost!=''):
				$filter_custom_fields[$key]['id'] = $field->id;
				$filter_custom_fields[$key]['alias'] = $field->alias;
				$filter_custom_fields[$key]['value'] = $aliasPost;
			endif;

		endforeach;

		return $filter_custom_fields;

	}
	public function getAdminFiltrableFields() 
	{
		$db		= $this->getDbo();
        $query = 'select * from #__kajoo_fields';
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    
    public function dataCustFilter()
    {
   	 //Get in an array all the kentry ids that must be filtered
    	$db		= $this->getDbo();
        $filters = $this->filterCustomFields();
        
        $filtered = array();
        if(count($filters)>0):
	        $query = 'SELECT distinct content_id FROM `#__kajoo_field_values_entry`';
	        $query .= ' WHERE';
	
	        $i = 0;
	        foreach ($filters as $filter) {
	            $filter_val = $filter['value'];
	            if ($filter_val and ($filter_val != 'all')):
	                if($i==0):
	                    $query .= ' content_id IN (SELECT content_id FROM `#__kajoo_field_values_entry` WHERE value = "'.$filter_val.'")';
	                else:
	                    $query .= ' AND content_id IN (SELECT content_id FROM `#__kajoo_field_values_entry` WHERE value = "'.$filter_val.'")';
	                endif;
	            $i++;
	            endif;
	            
	        }
	
	        $db->setQuery($query);
	   
	        $filtered['results'] = $db->loadColumn();
	        $filtered['filters'] = 1;

	        return $filtered;
	      else:
		      $filtered['filters'] = 0;  
		      return $filtered;
	      endif; 
	      
    }
    function getFilteredQuery()
    {
    	$filterQuery = array();
    	$filtdata = $this->dataCustFilter();


    	if($filtdata['filters']):
	    	//Build the query for custom filters
	        if(count($filtdata['results'])>0):
	            $i = 0;
	            foreach ($filtdata['results'] as $d):
	                $filtdata['results'][$i] = '"'.$d.'"';
	            $i++;    
	            endforeach;
	
	            $ids = join(',',$filtdata['results']); 
	            $filterQuery['query'] = '('.$ids.')';
	          
	            $filterQuery['type'] = 'filter';
	
	        else:
	        	$filterQuery['query'] = 'noquery';
	            $filterQuery['type'] = 'noresult';
	        endif;    
	    else:
	    	$filterQuery['query'] = 'noquery';
	    	$filterQuery['type'] = 'nofilter';
	    endif;   

        return $filterQuery;
        

    }
    protected function getListQuery($mediaType = 2) {
        // Create a new query object.

        $db = $this->getDbo();
        
        
        $partnerId = $this->getState('filter.partnerValue');
        
        // If Partner ID = 0 => The user selected "all" in the partners filter
        if($partnerId!=0):
	        $filtered_results = $this->filterKaltura($partnerId, $mediaType);
	        $list_entry_ids = $filtered_results->filter_search_text;
	    else:
	    	$availablePartners = KajooHelper::getPartnersList();
	    	$filtered_results_all_filter_array = array();
	    	foreach($availablePartners as $key=>$partner):
	    		$filtered_results_all[$key] = $this->filterKaltura($partner->id, $mediaType);
	    		$filtered_results_all_filter_array[] = $filtered_results_all[$key]->filter_search;
	    	endforeach;
	    	
	    	$res_array_all = array();
	    	foreach ($filtered_results_all_filter_array as $res_array):
	    		$res_array_all = array_merge($res_array_all,$res_array);
	    	endforeach;
			

	    	$list_entry_ids = "'". implode("', '", $res_array_all) ."'";
        endif;
        
		$custom_fields = $this->getFilteredQuery();

        $query = $db->getQuery(true);
        
        // Select the required fields from the table.
        $query->select(
                $this->getState(
                        'list.select', 'a.*'
                )
        );
        $query->from('`#__kajoo_content` AS a');
        
        // Filter by published state
        $published = $this->getState('filter.state');
        if (is_numeric($published)) {
            $query->where('a.state = '.(int) $published);
        } else if ($published === '') {
            $query->where('(a.state IN (0, 1))');
        }
            
       $query->where('(a.entry_id IN ('.$list_entry_ids.'))');
        
        if($custom_fields['type']=='filter'):
	        $query->where('( a.id IN '.$custom_fields['query'].' )');
	    elseif($custom_fields['type']=='noresult'):
        	$query->where('a.id = 0');

        else: //nofilter
        
        endif;
       
        $query->group('a.id');


        return $query;
    }

	protected function _getListQuery($mediaType = 0)
	{
		// Capture the last store id used.
		static $lastStoreId;

		// Compute the current store id.
		$currentStoreId = $this->getStoreId();
		
		// If the last store id is different from the current, refresh the query.
		if ($lastStoreId != $currentStoreId || empty($this->query))
		{
			$lastStoreId = $currentStoreId;
			$this->query = $this->getListQuery($mediaType);
		}

		return $this->query;
	}

}
