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
?>

<div class="well well-small">
	<!--<legend><?php echo JText::_('COM_KAJOO_CONTENTS_SEARCH');?></legend>-->
	<input class="kajooInput" id="tableSearchButton" type="text" placeholder="<?php echo JText::_('COM_KAJOO_CONTENTS_SEARCH');?>..." data-provide="typeahead" data-items="4">
	<br>
	
	<button id="searchBtn" class="btn btn-success" type="button"><?php echo JText::_('COM_KAJOO_CONTENTS_SEARCH');?></button>
	<button id="searchBtnclear" class="btn" type="button"><?php echo JText::_('COM_KAJOO_CONTENTS_SEARCHCLEAR');?></button>
</div>