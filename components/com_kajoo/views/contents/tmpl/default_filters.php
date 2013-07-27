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

 if(count($this->allFields)>0):

	
?>

	<div class="well well-small" id="fieldsKajoo">
		<legend><?php echo JText::_('COM_KAJOO_CONTENTS_FILTERS');?></legend>
		
		<?php foreach ($this->allFields as $field):	?>
			
			<?php if($field->type==1 && count($field->values)>0):?>
				<h4><?php echo $field->name;?></h4>
				<select name="<?php echo $field->alias;?>" id="<?php echo $field->alias;?>" class="fieldfilter kajooInput">
						<option value="all"><?php echo JText::_('COM_KAJOO_CONTENTS_FILTERS_ALL');?></option>
					<?php foreach ($field->values as $value):?>
						<option value="<?php echo $value->id;?>"><?php echo $value->value;?></option>
					<?php endforeach;?>
				</select>
			<?php endif;?>
		<?php endforeach;?>
	</div>

<?php endif;?>