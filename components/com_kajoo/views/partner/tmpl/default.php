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
?>

<?php if( $this->item ) : ?>

    <div class="item_fields">
        
        <ul class="fields_list">

        
        
            <li><?php echo 'id'; ?>: 
            <?php echo $this->item->id; ?></li>

        
        
            <li><?php echo 'added'; ?>: 
            <?php echo $this->item->added; ?></li>

        
        
            <li><?php echo 'updated'; ?>: 
            <?php echo $this->item->updated; ?></li>

        
        
            <li><?php echo 'ordering'; ?>: 
            <?php echo $this->item->ordering; ?></li>

        
        
            <li><?php echo 'state'; ?>: 
            <?php echo $this->item->state; ?></li>

        
        
            <li><?php echo 'checked_out'; ?>: 
            <?php echo $this->item->checked_out; ?></li>

        
        
            <li><?php echo 'checked_out_time'; ?>: 
            <?php echo $this->item->checked_out_time; ?></li>

        
        
            <li><?php echo 'name'; ?>: 
            <?php echo $this->item->name; ?></li>

        
        
            <li><?php echo 'partnerid'; ?>: 
            <?php echo $this->item->partnerid; ?></li>

        
        
            <li><?php echo 'administratorsecret'; ?>: 
            <?php echo $this->item->administratorsecret; ?></li>

        
        
            <li><?php echo 'usersecret'; ?>: 
            <?php echo $this->item->usersecret; ?></li>

        

        </ul>
        
    </div>
    <?php if(JFactory::getUser()->authorise('core.edit', 'com_kajoo.partner'.$this->item->id)): ?>
		<a href="<?php echo JRoute::_('index.php?option=com_kajoo&task=partner.edit&id='.$this->item->id); ?>">Edit</a>
	<?php endif; ?>
<?php else: ?>
    Could not load the item
<?php endif; ?>
