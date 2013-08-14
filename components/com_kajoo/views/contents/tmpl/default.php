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

<?php $configuration = KajooHelper::getConfig();
	
	$positions = json_decode($configuration[0]->value);

	function getCols($positions)
	{

		$cols = 1;
		foreach ($positions as $key=>$position):
			
			if($position!='empty'):
				
				if($key!=1):
					$cols++;
				endif;
			endif;
		endforeach;

		return $cols;
	}


$numCols = getCols($positions);

if($numCols==1):
	$mainSpan = 12;
	$secSpan = 12;
	
elseif($numCols==2):
	$mainSpan = 8;
	$secSpan = 4;
	
else:
	$mainSpan = 6;
	$secSpan = 3;
	$secSpan = 3;
endif;
?>

<?php # Se verifica si está seteada una delegación y se setea una variable con la delegación buscada y se oculta el buscador ?>
<script language="JavaScript">
	var delegacion = false;
	<?php if(isset($_GET['delegacion'])): ?>
		var delegacion = '<?php echo $_GET['delegacion'] ?>';
		<?php $positions[0] = 'empty' ?>
	<?php endif; ?>
</script> 

<div class="colsKajoo row-flexible" id="topColsKajoo">

	<?php if($positions[0]!='empty'): ?>
		<!--<div class="span<?php echo $secSpan;?>">-->
		<!--Se pone row-fluid para que ocupe todo el ancho-->
		<div class="row-fluid">
			<?php foreach ($positions[0] as $position):?>
				<?php echo $this->loadTemplate($position); ?>		
			<?php endforeach;?>
		</div>	
	<?php endif;?>
	
	
	
		<!--<div class="span<?php echo $mainSpan?> ">-->
		<!--Se pone row-fluid para que ocupe todo el ancho-->
		<div class="row-fluid">
			<?php if($positions[1]!='empty'): ?>
				<?php foreach ($positions[1] as $position):?>
					<?php echo $this->loadTemplate($position); ?>		
				<?php endforeach;?>
			<?php endif;?>
			<?php echo $this->loadTemplate('maincontent'); ?>
		</div>	


	<?php if($positions[2]!='empty' && false): ?>
		<div class="span<?php echo $secSpan;?>">
			<?php foreach ($positions[2] as $position):?>
				<?php echo $this->loadTemplate($position); ?>		
			<?php endforeach;?>
		</div>
	<?php endif;?>

</div>
<div class="clearfix"></div>
