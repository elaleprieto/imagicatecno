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

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

$lang = JFactory::getLanguage();
$lang->load( 'com_kajoo', JPATH_ADMINISTRATOR );

?>

<!-- Styling for making front end forms look OK -->
<!-- This should probably be moved to the template CSS file -->
<style>
    .front-end-edit ul {
        padding: 0 !important;
    }
    .front-end-edit li {
        list-style: none;
        margin-bottom: 6px !important;
    }
    .front-end-edit label {
        margin-right: 10px;
        display: block;
        float: left;
        width: 200px !important;
    }
    .front-end-edit .radio label {
        display: inline;
        float: none;
    }
    .front-end-edit .readonly {
        border: none !important;
        color: #666;
    }    
    .front-end-edit #editor-xtd-buttons {
        height: 50px;
        width: 600px;
        float: left;
    }
    .front-end-edit .toggle-editor {
        height: 50px;
        width: 120px;
        float: right;
        
    }
</style>

<div class="partner-edit front-end-edit">
    <h1>Edit <?php echo $this->item->id; ?></h1>

    <form id="form-partner" action="<?php echo JRoute::_('index.php?option=com_kajoo&task=partner.save'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
        <ul>
        
        <li><?php echo $this->form->getLabel('id'); ?>
        <?php echo $this->form->getInput('id'); ?></li>

        
        <li><?php echo $this->form->getLabel('name'); ?>
        <?php echo $this->form->getInput('name'); ?></li>

        
        <li><?php echo $this->form->getLabel('partnerid'); ?>
        <?php echo $this->form->getInput('partnerid'); ?></li>

        
        <li><?php echo $this->form->getLabel('administratorsecret'); ?>
        <?php echo $this->form->getInput('administratorsecret'); ?></li>

        
        <li><?php echo $this->form->getLabel('usersecret'); ?>
        <?php echo $this->form->getInput('usersecret'); ?></li>

        

        <li><?php echo $this->form->getLabel('state'); ?>
                    <?php echo $this->form->getInput('state'); ?></li><li><?php echo $this->form->getLabel('checked_out'); ?>
                    <?php echo $this->form->getInput('checked_out'); ?></li><li><?php echo $this->form->getLabel('checked_out_time'); ?>
                    <?php echo $this->form->getInput('checked_out_time'); ?></li>
    
        </ul>
		<div>
			<button type="submit" class="validate"><span><?php echo JText::_('JSUBMIT'); ?></span></button>
			<?php echo JText::_('or'); ?>
			<a href="<?php echo JRoute::_('index.php?option=com_kajoo&task=partner.cancel'); ?>" title="<?php echo JText::_('JCANCEL'); ?>"><?php echo JText::_('JCANCEL'); ?></a>

			<input type="hidden" name="option" value="com_kajoo" />
			<input type="hidden" name="task" value="partner.save" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
</div>
