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
	$partners =  KajooHelper::getPartnersList(); ?>
	<?php if(count($partners)>0):?>
		
		<div class="well well-small">
			<legend><?php echo JText::_('COM_KAJOO_CONTENTS_PARTNERS');?></legend>
			
			
		<select id="partner" name="partner" class="kajooInput">
			<option value="all"><?php echo JText::_('COM_KAJOO_CONTENTS_PARTNERS_ALL');?></option>
		<?php foreach($partners as $partner):?>
			<option value="<?php echo $partner->id;?>"><?php echo $partner->name;?></option>
		<?php endforeach;?>
		</select>
			
		</div>

<?php endif;