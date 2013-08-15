<?php
/**
 * $ModDesc
 * 
 * @version		$Id: helper.php $Revision
 * @package		modules
 * @subpackage	$Subpackage.
 * @copyright	Copyright (C) May 2010 LandOfCoder.com <@emai:landofcoder@gmail.com>.All rights reserved.
 * @license		GNU General Public License version 2
 */
// no direct access
defined('_JEXEC') or die;
global $mosConfig_absolute_path, $vm_mainframe, $sess, $option;
// Load the virtuemart main parse code
if( file_exists(dirname(__FILE__).'/../../components/com_virtuemart/virtuemart_parser.php' )) {
	require_once( dirname(__FILE__).'/../../components/com_virtuemart/virtuemart_parser.php' );
} else {
	require_once( dirname(__FILE__).'/../components/com_virtuemart/virtuemart_parser.php' );
}

if( $option == 'com_virtuemart' && (int)JRequest::getVar('category_id') > 0 ){
    // Include the syndicate functions only once
    require_once dirname(__FILE__).DS.'helper.php';

    $group = $params->get( 'group','content' );
    $tmp = $params->get( 'module_height', 'auto' );
    $moduleHeight   =  ( $tmp=='auto' ) ? 'auto' : (int)$tmp.'px';
    $tmp = $params->get( 'module_width', 'auto' );
    $moduleWidth    =  ( $tmp=='auto') ? 'auto': (int)$tmp.'px';
    $theme 			= $params->get( 'theme' , '');
    $isCountProducts = true;
    $currentURL =& JURI::getInstance()->toString();
    $textAbove = modIceVmFilterHelper::getTextAbove( $params );
    $maxParamsDisplay = (int)$params->get('max_params_visible',4);
    $currentURL .=strpbrk($currentURL, '?')? '':'?';
    if( preg_match("/page=shop.product_details/", $currentURL) ){
        $currentURL = preg_replace( "/page=shop.product_details/", "page=shop.browse", $currentURL );
    }

    $urlparams =  explode( "&", $currentURL );
   //  echo '<pre>'.print_r($urlparams,1); die;
    modIceVmFilterHelper::loadMediaFiles($params, $module);
 //   echo $currentURL;die;

    $types = modIceVmFilterHelper::getListTypes( $params );
    require(JModuleHelper::getLayoutPath('mod_ice_vmfilter', $params->get('fillter_mode','default') ));
}

$text_whenhide								= $params->get('text_whenhide', 'See more...');
$text_whenshow								= $params->get('text_whenshow', 'Hide...');


// Add this to Head
$doc =&JFactory::getDocument();
		
  	global $Itemid;

		$document =& JFactory::getDocument();
			
			$js = '

			$(window).addEvent("load",function(){
    var ICE_LANG_CLOSE= " ' . $text_whenhide . ' ";
    var ICE_LANG_OPEN= " ' . $text_whenshow . ' ";
    $$(".ice-vmfilter-gp").each(function(item){
        item.isShowed=false;
        item.addEvent("click",function(){
             if( $defined($$("."+this.id)) &&  item.isShowed==true ){
                 this.removeClass("ice-vmfilter-active").addClass("ice-vmfilter-inactive");
                 $$("."+this.id).removeClass("ice-vmfilter-show").addClass("ice-vmfilter-hide");
                 this.setHTML(ICE_LANG_CLOSE);
                 item.isShowed=false;
             }else {
                this.removeClass("ice-vmfilter-inactive").addClass("ice-vmfilter-active");
                $$("."+this.id).removeClass("ice-vmfilter-hide").addClass("ice-vmfilter-show");
                this.setHTML(ICE_LANG_OPEN);

                item.isShowed=true;
             }
       });
    });
});
			
			';
			
					
			$document->addScriptDeclaration($js);



?>
