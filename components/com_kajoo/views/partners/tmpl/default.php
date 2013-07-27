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

<?php if($this->items) : ?>

    <div class="items">

        <ul class="items_list">

            <?php foreach ($this->items as $item) :?>

                
				<li><a href="<?php echo JRoute::_('index.php?option=com_kajoo&view=partner&id=' . (int)$item->id); ?>"><?php echo $item->name; ?></a></li>

            <?php endforeach; ?>

        </ul>

    </div>

     <div class="pagination">
        <p class="counter">
            <?php echo $this->pagination->getPagesCounter(); ?>
        </p>
        <?php echo $this->pagination->getPagesLinks(); ?>
    </div>

<?php else: ?>
    
    There are no items in the list

<?php endif; ?>