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

$partnerid = JRequest::getVar('partnerid', 0, 'get','INT');

$cats=  KajooHelper::getCategoryList($partnerid);

?>
<?php if(count($cats)>0):?>
<div class="well well-small">
	<legend><?php echo JText::_('COM_KAJOO_CONTENTS_CATEGORIES');?></legend>

	<?php
		function get_categories($cats,$i=0)
		{
			if($i==0):
				$html = '<ul id="navigationCategories" class="nav nav-pills nav-stacked">';
				$html .= '<li id="all"> <a href="#" rel="panel">All</a></li>';
				
			else:	
			    $html = '<ul class="nav nav-pills nav-stacked">';
		    endif;
		    
		    $i++;  
		    
		    foreach ($cats as $cat)
		    {
		
		        $html .= '<li id="' . $cat->id. '"><a href="#" rel="panel">' . $cat->name. '</a>';
		        if(isset($cat->childs))
		        {
		            $html .= get_categories($cat->childs,$i);
		        }
		        $html .= '</li>';
		    }
		    $html .= '</ul>';
		    return $html;
		  
		}

	?>
	

<?php if(count($cats)>0):?>
	<?php echo get_categories($cats); ?>
<?php endif; ?>


	<a href="#" id="selectAllCats">All</a> | <a href="#" id="unselectAllCats">None</a>
</div>
<?php endif;?>