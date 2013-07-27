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
		$params = &JComponentHelper::getParams( 'com_kajoo' );
		$emailManager = $params->get( 'emailManager','' );
?>

<div class="well well-small">
	<legend><?php echo JText::_('COM_KAJOO_TITLE_WISHLIST');?> <span id="numslist" class="badge badge-info">0</span></legend>
	<ul class="" id="wishlist">
	
	</ul>
	<button class="btn btn-small" id="clearWishtlist"><?php echo JText::_('COM_KAJOO_TITLE_WISHLIST_CLEAR');?></button>
	<a href="#myModal" id="reqinfobtnkjoo" data-toggle="modal" class="btn btn-info btn-small"><?php echo JText::_('COM_KAJOO_TITLE_WISHLIST_REQINFO');?></a>
</div>


     
    <!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	    <h3 id="myModalLabel"><?php echo JText::_('COM_KAJOO_TITLE_WISHLIST_REQINFO');?></h3>
    </div>
    
    <div class="modal-body">
    <?php if($emailManager==''):?>
    	<?php echo JText::_('COM_KAJOO_TITLE_WISHLIST_CONFIGUREMAIL');?>
    <?php else: ?>
        <form class="form-horizontal" id="reqinfo">
		    	<div class="control-group">
			    	<label class="control-label" for="name"><?php echo JText::_('COM_KAJOO_TITLE_WISHLIST_NAME');?></label>
				    <div class="controls">
				    	<input type="text" id="name" name="name" placeholder="Your name">
				    </div>
			    </div>
			    
			    <div class="control-group">
			    	<label class="control-label" for="company"><?php echo JText::_('COM_KAJOO_TITLE_WISHLIST_COMPANY');?></label>
				    <div class="controls">
				    	<input type="text" id="company" name="company" placeholder="Company">
				    </div>
			    </div>
			    
			    <div class="control-group">
			    	<label class="control-label" for="position"><?php echo JText::_('COM_KAJOO_TITLE_WISHLIST_POSITION');?></label>
				    <div class="controls">
				    	<input type="text" id="position" name="position" placeholder="Position">
				    </div>
			    </div>
			    
			    <div class="control-group">
			    	<label class="control-label" for="email"><?php echo JText::_('COM_KAJOO_TITLE_WISHLIST_EMAIL');?></label>
				    <div class="controls">
				    	<input type="text" id="email" name="email" placeholder="Email">
				    </div>
			    </div>
			    
			    <div class="control-group">
			    	<label class="control-label" for="phone"><?php echo JText::_('COM_KAJOO_TITLE_WISHLIST_PHONE');?></label>
				    <div class="controls">
				    	<input type="text" id="phone" name="phone" placeholder="Phone">
				    </div>
			    </div>
			    
			    <div class="control-group">
			    	<label class="control-label" for="mobile"><?php echo JText::_('COM_KAJOO_TITLE_WISHLIST_MOBILE');?></label>
				    <div class="controls">
				    	<input type="text" id="mobile" name="mobile" placeholder="Mobile">
				    </div>
			    </div>
			    
			    <div class="control-group">
			    	<label class="control-label" for="comments"><?php echo JText::_('COM_KAJOO_TITLE_WISHLIST_COMMENTS');?></label>
				    <div class="controls">
				    	<textarea rows="3" name="comments" id="comments"></textarea>
				    </div>
			    </div>
			    
			    
			   	<button type="submit" id="submitForm" class="btn btn-success"><?php echo JText::_('COM_KAJOO_TITLE_WISHLIST_SUBMITFORM');?></button>
			
	    </form>
	 <?php endif; ?>
    </div>

</div>

