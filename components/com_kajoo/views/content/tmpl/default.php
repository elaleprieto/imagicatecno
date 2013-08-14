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

$document =& JFactory::getDocument();
$document->addScript(JURI::base() . 'components/com_kajoo/assets/js/content.js');

?>
<?php JHTML::_( 'behavior.modal' ); ?> 

<?php if( $this->item ) :

$params = &JComponentHelper::getParams( 'com_kajoo' );
$playerConfigId = $params->get( 'playerConfigId',8847102 );
$allow_download = $params->get( 'allow_download',1 );
$show_fields = $params->get( 'show_fields',1 );
$show_description = $params->get( 'show_description',1 );
$show_thumbs = $params->get( 'show_thumbs',1 );
$show_embed = $params->get( 'show_embed',1 );

// Connect to Kaltura API
$PartnerInfo = KajooHelper::getPartnerInfo($this->item->partner_id);
$kClient = KajooHelper::getKalturaClient($PartnerInfo->partnerid, $PartnerInfo->administratorsecret, true,$PartnerInfo->url);

try
{
	$thumbs = $kClient->thumbAsset->getbyentryid($this->item->kaltura_video->id);
}
catch(Exception $ex)
{
	echo KajooHelper::getKalturaError(JText::_('Fail on geting thumbs'));
}

try
{
	// $data = $kClient->data->get('0_9oj2uhzo');
	// echo $kClient->getKs();
	
	// $client = getKalturaClient ();
	$filter = new KalturaMetadataFilter();
	$filter->objectIdEqual = $this->item->kaltura_video->id;
	$filter->metadataObjectTypeEqual = KalturaMetadataObjectType::ENTRY;
	$result = $kClient->metadata->listAction($filter);
	// print_r($result);
	// print_r($this->item->kaltura_video); 
	
	
    // $filter_meta = new KalturaMetadataFilter();
    // $filter_meta->metadataObjectTypeEqual = KalturaMetadataObjectType::ENTRY;
    // $filter_meta->objectIdEqual = $entry->id;
    // $filter_meta->statusEqual = KalturaMetadataStatus::VALID;
    // $metadataPlugin = KalturaMetadataClientPlugin::get($client);
    // $result = $metadataPlugin->metadata->listAction($filter_meta);
    //print_r($result);
    if(isset($result->objects[0]->xml)){
        $xml = simplexml_load_string($result->objects[0]->xml);
        // $record_date = (int)$xml->RecordDate;
        $nombre = $xml->Nombre;
        $coordinador = $xml->Coordinador;
        $ciudad = $xml->Ciudad;
        $provincia = $xml->Provincia;
        $pdf = $xml->PDF;
    }
		
    // else{
        // $record_date = $entry->createdAt;
    // }
    // echo '<li id="'.$count.'"><img src="'.$entry->thumbnailUrl.'"/> <strong>Name:</strong> '.$entry->name.' <strong>Record Date:</strong> '.date('m/d/Y', $record_date).' <strong>Duration:</strong> '.$entry->duration.' sec</li>';
    // $count++;

	
	
	// $metadataPlugin = KalturaMetadataClientPlugin::get($kClient);
	// $service = new KalturaMetadataService();
	// $listRespo = $metadataPlugin->metadata->listAction($filter, $pager);
	// $meta = $metadataPlugin->metadata->get(36339822);
	// $meta = $service->get(36339822);
	
	// $xmlMeta = simplexml_load_string($meta->xml);
// 	
	// print_r($meta->xml->metadata);
// 	$Meta->xml
	// foreach ($xmlMeta as $mensaje)
  		// echo $mensaje.' ';
// 	
	
	// $fieldsModel = & JModelLegacy::getInstance('fields', 'KajooModel'); 
	// $custom_fields = $fieldsModel->getAllFields();
	
	// print_r($fieldsModel);
	
	// if ($listRespo->totalCount < 1) {
		// throw Exception("Error");
	// }
// 	
// 
// 	
// 
	// $Meta = $listRespo->objects[0];
// 	
	// $data = $Meta->xml;
// 	
// 	
	// print_r($data);
	// echo "<br />------------<br />";
	// print_r($kClient->metadataProfile->get(1));
	// echo $data;
}
catch(Exception $ex)
{
	echo 'Error: '.$ex;
}
?>

<!-- <a href="<?php echo JRoute::_('index.php?option=com_kajoo&view=contents'); ?>" class="btn btn-small"><i class="icon-chevron-left"></i> <?php echo JText::_('COM_KAJOO_ITEMCONTENT_PREVPAGE');?></a> -->

<div class="infoDetail_container">
	<br>	
	<h2><?php echo $this->item->name;?></h2>
	<br>
	<div class="infoDetail_video">
		<?php 
		# Se verifica si el mediaType corresponde a Video
		if($this->item->kaltura_video->mediaType == 1): 
		?>
			<?php echo KajooHelper::getUrlEmbed($this->item->partner_id,$this->item->kaltura_video->id, $PartnerInfo->defaultPlayer); ?>
			<hr>
			<?php if($show_embed):?>
				<textarea><?php  echo KajooHelper::getUrlEmbed($this->item->partner_id,$this->item->kaltura_video->id, $PartnerInfo->defaultPlayer); ?></textarea>
			<?php endif;?> 
		<?php 
		# Si el mediaType se corresponde con Audio
		elseif($this->item->kaltura_video->mediaType == 5): 
		?>
			<?php echo KajooHelper::getUrlEmbed($this->item->partner_id,$this->item->kaltura_video->id, $PartnerInfo->audioPlayer); ?>
			<hr>
			<?php if($show_embed):?>
				<textarea><?php  echo KajooHelper::getUrlEmbed($this->item->partner_id,$this->item->kaltura_video->id, $PartnerInfo->audioPlayer); ?></textarea>
			<?php endif;?> 
		<?php 
		# Si el mediaType se corresponde con Imagen, se verifica si tiene enlace
		elseif($this->item->kaltura_video->mediaType == 2): 
			// if($this->item->kaltura_video->description): 
			if($pdf): 
		?>  
				<!-- <a href="<?php //echo $this->item->kaltura_video->description ?>" target="_blank"> -->
					<!-- <img src="<?php //echo $this->item->kaltura_video->thumbnailUrl ?>" /> -->
				<!-- </a> -->
				<?php //echo $this->item->kaltura_video->description ?> 
				<?php echo $pdf ?> 
			<?php
			# Si  el mediaType se corresponde con Imagen y no tiene enlace asociado, se deja la imagen sin enlace...
			else:
			?>
				<img src="<?php echo $this->item->kaltura_video->thumbnailUrl ?>" />
			<?php endif; ?>
		<?php endif; ?>
	</div>
	<?php if($show_thumbs && false):?>
		<div class="well">
		<ul class="gallerythumbsFront">
			<?php foreach($thumbs as $thumb):
				try
				{
					$thumb_url = $kClient->thumbAsset->serve($thumb->id);
				}
				catch(Exception $ex)
				{
					echo KajooHelper::getKalturaError(JText::_('Fail on geting thumb info'));
				}
				
			?>
				<li id="<?php echo $thumb->id;?>">
					<a href="<?php echo $thumb_url;?>.jpg" class="fancybox-button" rel="fancybox-button" title="<?php echo $this->item->name;?>">
						<img class="collectionthumbs" src="<?php echo $thumb_url;?>" />
					</a>
					
				</li>
			<?php endforeach;?>
	
		</ul>
		<div class="clearfix"></div>
		</div>
	<?php endif; //show thumbs ?>	
		
	<div class="infoDetail_share">
	
	<?php if($show_description && false):?>	
		<?php if($this->item->kaltura_video->description!=''): ?>
		<div class="infoDetail_description">
			<div class="well well-small">
			<?php echo $this->item->description;?>	
			</div>
		</div>
		<?php endif;?>
	<?php endif;?>
	
		<div class="well">
			<ul class="itemListDetails">
				<li>
					<span class="tit_itemList">Delegación</span> 
					<?php echo $nombre ?>
				</li>
				<li>
					<span class="tit_itemList">Coordinador</span> 
					<?php echo $coordinador ?>
				</li>
				<li>
					<span class="tit_itemList">Ciudad</span> 
					<?php echo $ciudad ?>
				</li>
				<li>
					<span class="tit_itemList">Provincia</span> 
					<?php echo $provincia ?>
				</li>
				<li>
					<span class="tit_itemList"><?php echo JText::_('COM_KAJOO_ITEMCONTENT_CREATED');?></span> 
					<?php echo date('d/m/Y H:i', $this->item->kaltura_video->createdAt) ?>
				</li>
				<li>
					<span class="tit_itemList"><?php echo JText::_('COM_KAJOO_ITEMCONTENT_UPDATED');?></span> 
					<?php echo date('d/m/Y H:i', $this->item->kaltura_video->updatedAt) ?>
				</li>
				<li><span class="tit_itemList"><?php echo JText::_('COM_KAJOO_ITEMCONTENT_LENGTH');?></span> <?php echo KajooHelper::formatTime($this->item->kaltura_video->msDuration);?></li>
				<li><span class="tit_itemList"><?php echo JText::_('COM_KAJOO_ITEMCONTENT_VIEWS');?></span> <?php echo $this->item->kaltura_video->views;?></li>
				<li><span class="tit_itemList"><?php echo JText::_('COM_KAJOO_ITEMCONTENT_PLAYS');?></span> <?php echo $this->item->kaltura_video->plays;?></li>
				
				<?php if($this->item->kaltura_video->categories!=''): ?>
					<li><span class="tit_itemList"><?php echo JText::_('COM_KAJOO_ITEMCONTENT_CATEGORIES');?></span> <?php echo $this->item->kaltura_video->categories;?></li>
				<?php endif;?>
				
				<?php if($this->item->kaltura_video->tags!=''): ?>
					<li><span class="tit_itemList"><?php echo JText::_('COM_KAJOO_ITEMCONTENT_KEYS');?></span> <?php echo $this->item->kaltura_video->tags;?></li>
				<?php endif;?>
				
				<?php if($allow_download && false): ?>
					<li>
						<span class="tit_itemList">
							<?php echo JText::_('COM_KAJOO_ITEMCONTENT_DOWNLOAD');?>
						</span> 
						<a href="<?php echo $this->item->kaltura_video->dataUrl;?>">Descargar archivo fuente</a>
					</li>
				<?php endif;?>
				
				<?php if($show_fields):?>
					<hr>
					<?php foreach ($this->item->fields as $field):?>
						<?php $fieldValue = KajooHelper::getContentFieldValue($this->item->id,$field->id,$field->type); ?>
						<?php if($fieldValue):?>
							<li><span class="tit_itemList"><?php echo $field->name;?></span> <?php echo $fieldValue;?></li>
						<?php endif;?>
					<?php endforeach;?>
				<?php endif;?>
				
			</ul>
		</div>
	</div>

	
</div>
 
     <?php if(JFactory::getUser()->authorise('core.edit', 'com_kajoo.content'.$this->item->id)): ?>
		<a href="<?php echo JRoute::_('index.php?option=com_kajoo&task=content.edit&id='.$this->item->id); ?>">Edit</a>
	<?php endif; ?>

<?php else: ?>
    Could not load the item
<?php endif; ?>
