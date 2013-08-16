<?php // no direct access
defined('_JEXEC') or die('Restricted access');
//JHTML::stylesheet ( 'menucss.css', 'modules/mod_virtuemart_category/css/', false );

/* ID for jQuery dropdown */
$ID = str_replace('.', '_', substr(microtime(true), -8, 8));
$js="jQuery(document).ready(function() {
		//jQuery('#VMmenu".$ID." li.VmClose ul').hide();
		jQuery('#VMmenu".$ID." li .VmArrowdown').click(
		function() {

			if (jQuery(this).parent().next('ul').is(':hidden')) {
				jQuery('#VMmenu".$ID." ul:visible').delay(500).slideUp(500,'linear').parents('li').addClass('VmClose').removeClass('VmOpen');
				jQuery(this).parent().next('ul').slideDown(500,'linear');
				jQuery(this).parents('li').addClass('VmOpen').removeClass('VmClose');
			}
		});
	});" ;

		$document = JFactory::getDocument();
		$document->addScriptDeclaration($js);

    $hovclass = "";
    $menu = & JSite::getMenu();
    if ($menu->getActive() != $menu->getDefault()) {
        $hovclass="menu-openable";
    }
?>

    <?php $active = JFactory::getApplication()->getMenu()->getActive();
    if($active->alias != "home"){ ?>
<style type="text/css">.menu-opener{top:34px}</style>
    <?php } ?>

<div class="<?php echo $hovclass?> <?php //echo $active->alias; ?>">
    <div class="mhead">
        <div class="lef-mhead"></div>
        <div class="mid-mhead">Категории товаров</div>
        <div class="rig-mhead"></div>
    </div>
    <ul class="VMmenu<?php echo $class_sfx ?>" id="<?php echo "VMmenu".$ID ?>" >
    <?php foreach ($categories as $category) {
            $active_menu = 'class="VmClose"';
            $caturl = JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$category->virtuemart_category_id);
            $cattext = $category->category_name;
            //if ($active_category_id == $category->virtuemart_category_id) $active_menu = 'class="active"';
            if (in_array( $category->virtuemart_category_id, $parentCategories)) $active_menu = 'class="VmOpen"'; ?>
        <li <?php echo $active_menu ?>>
            <div>
                <?php echo JHTML::link($caturl, $cattext, 'class="lvl1_link"');?>
                <div class="menu-opener">
                    <?php
                        $db = JFactory::getDBO();
                        if($category->virtuemart_media_id){
                            $db->setQuery("SELECT file_url FROM `#__virtuemart_medias` WHERE virtuemart_media_id = " . $category->virtuemart_media_id[0]);
                            $list = $db->loadObject();
                            JHTML::_('image', $list->file_url, 'ALT Картинки', 'heght="30" width="30"');
                            echo '<img style="right:0;position:absolute" src="'.$list->file_url.'" alt="" />';
                        } ?>
                    <?php if ($category->childs) { ?>
                    <ul class="menu">
                        <li>
                            <?php echo $cattext; ?>
                            <ul>
                                <?php
                                foreach ($category->childs as $child) {
                                    $caturl = JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$child->virtuemart_category_id);
                                    $cattext = $child->category_name;
                                    ?>
                                    <li>
                                        <div><?php echo JHTML::link($caturl, $cattext); ?></div>
                                    </li>
                                    <?php } ?>
                            </ul>
                        </li>
                    </ul>
                    <?php } ?>
                </div>
            </div>
        </li>
    <?php } ?>
    </ul>
    <script type="text/javascript">
        jQuery(document).ready(function(){
            jQuery('.VMmenu li').hover(
                function(){
                    var opener = jQuery(this).find('.menu-opener');
                    jQuery(opener).css('display','block');
                },
                function(){
                    var opener = jQuery(this).find('.menu-opener');
                    jQuery(opener).css('display','none');
                }
            );
        });
    </script>
</div>