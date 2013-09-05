<?php
/**
 * @package      ITPrism Modules
 * @subpackage   ITPFacebookActivityFeed
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2010 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * ITPFacebookActivityFeed is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

// no direct access
defined( "_JEXEC" ) or die; ?>

<?php if($params->get("fbRootDiv", 1)) {?>
<div id="fb-root"></div>
<?php }?>

<?php if($params->get("fbLoadJsLib", 1)) {?>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/<?php echo $locale;?>/all.js#xfbml=1<?php echo $appId;?>";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<?php }?>

<?php 

switch ($params->get("fbRendering",0)) { 

 case 1: // XFBML

     $html = '
<fb:activity  
site="' .$params->get("fbDomain") .'"  
app_id="'.$params->get('fbAppId') .'"  
action="'. $params->get('fbActions') .'"  
width="'. $params->get('fbWidth') .'"  
height="'. $params->get('fbHeight') .'" 
header="'. $params->get('fbHeader') .'"  
linktarget="'. $params->get('fbLinkTarget') .'" 
font="'. $params->get('fbFont') .'" 
colorscheme="'. $params->get('fbColour') .'" 
recommendations="'. $params->get('fbRecommendation') .'" 
border_color="'. $params->get('fbBorderColour') .'" ' ;
     
  if($params->get("fbMaxAge")) { 
     $html .= ' max_age="' . (int)$params->get("fbMaxAge") . '"';
  }
  
  if($params->get("fbRef")) { 
      $referral = htmlentities($params->get("fbRef"), ENT_QUOTES, "UTF-8");
      $html .= ' ref="' . $referral . '"';
  }
  
  if($params->get("fbFilter")) { 
     $filter  = htmlentities($params->get("fbFilter"), ENT_QUOTES, "UTF-8");
     $html .= ' filter="' . $filter . '"';
  }
  
  $html .='></fb:activity>';

  break; // END XFBML

  case 2: // HTML5

     $html = '
<div class="fb-activity" 
data-site="' .$params->get("fbDomain") .'" 
data-app-id="'.$params->get('fbAppId') .'" 
data-action="'. $params->get('fbActions') .'" 
data-width="'. $params->get('fbWidth') .'" 
data-height="'. $params->get('fbHeight') .'" 
data-header="'. $params->get('fbHeader') .'" 
data-linktarget="'. $params->get('fbLinkTarget') .'" 
data-colorscheme="'. $params->get('fbColour') .'" 
data-border-color="'. $params->get('fbBorderColour') .'" 
data-font="'. $params->get('fbFont') .'" 
data-recommendations="'. $params->get('fbRecommendation') .'" ' ;
     
  if($params->get("fbMaxAge")) { 
     $html .= ' data-max-age="'. (int)$params->get('fbMaxAge') .'"';
  }
  
  if($params->get("fbRef")) { 
      $referral = htmlentities($params->get("fbRef"), ENT_QUOTES, "UTF-8");
      $html .= ' data-ref="' . $referral . '"';
  }
  
  if($params->get("fbFilter")) { 
     $filter  = htmlentities($params->get("fbFilter"), ENT_QUOTES, "UTF-8");
     $html .= ' data-filter="' . $filter . '"';
  }
  
  $html .='></div>';

  break; // END HTML5
  
default:  // IFRAME

 $html = '<iframe src="http://www.facebook.com/plugins/activity.php?site='.$params->get("fbDomain") .'&amp;locale='.$locale.'&amp;width='.$params->get("fbWidth") .'&amp;height='.$params->get("fbHeight") .'&amp;header='.$params->get("fbHeader") .'&amp;colorscheme='.$params->get("fbColour") .'&amp;font='. $params->get("fbFont") .'&amp;border_color='. $params->get("fbBorderColour").'&amp;linktarget='. $params->get("fbLinkTarget").'&amp;recommendations='. $params->get("fbRecommendation").'&amp;app_id='. $params->get("fbAppId");

 if($params->get("fbMaxAge")) { 
    $html .=  '&amp;max_age=' . (int)$params->get('fbMaxAge');
 } 
 
 if($params->get("fbRef")) { 
    $html .=  '&amp;ref=' . rawurlencode($params->get("fbRef"));
 } 

 if($params->get("fbFilter")) { 
     $html .= '&amp;filter=' . rawurlencode($params->get("fbFilter"));
 }
  
$html .= '"
scrolling="no" 
frameborder="0" 
allowTransparency="true" 
style="border:none; overflow:hidden; width:'. $params->get("fbWidth").'px; height:'. $params->get("fbHeight").'px;" ></iframe>';

break;

}
echo $html;

?>