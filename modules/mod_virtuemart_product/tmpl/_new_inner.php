<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$col = 1;
$currentview = JRequest::getString('view');
$currentitemId = JRequest::getString('Itemid');
$kw = JRequest::getString('keyword');
if($currentview == "productdetails" ||
   $currentview == "cart" ||
   $currentview == "article" ||
   $currentview == "user" ||
   $currentview == "login" ||
   $currentview == "registration" ||
   $currentview == "remind" ||
   $currentview=="reset" ||
   ($currentview=="category" && $currentitemId != null )
){
    return;
}
if($kw!=null){
    return;
}
$pwidth = ' width' . floor(100 / $products_per_row);
if ($products_per_row > 1) {
    $float = "floatleft";
}
else {
    $float = "center";
}
?>
<div class="vmgroup h-akcii">
    <div class="vmheader-green">
        <div class="h-left"></div>
        <div class="h-mid">Лучшие предложения</div>
        <div class="h-right"></div>
    </div>
    <div>
    <?php $last = count($products) - 1; ?>
    <div class="items-akc">
        <ul class="vmproduct<?php echo $params->get('moduleclass_sfx'); ?>">
            <?php
            $i = 0;
            foreach ($products as $product) :
                if (!empty($product->images[0]))
                    $image = $product->images[0]->displayMediaThumb('class="featuredProductImage" style="height:80px;border:0"', false);
                else $image = ''; ?>
            <?php if($i==0) { $i=1; $first = "first";} else {$first = "";} ?>
            <li class="vm-item <?php echo $first; ?> <?php echo $pwidth ?> <?php echo $float ?>" style="width:144px; height:289px; padding:0;">
                <div class="vm-item-title" style="margin: 0 0 24px 0; text-align: center; height:30px; font-size:12px;">
                    <?php $url = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id); ?>
                    <a href="<?php echo $url ?>"><?php echo $product->product_name ?></a>
                </div>
                <div style="height:110px; width:100%; text-align: center;">
                    <?php echo JHTML::_('link', JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id), $image, array('title' => $product->product_name)); ?>
                </div>
                <div style="overflow:hidden; height:80px; width:95%; margin-left:5px; height:84px; margin-top:10px; text-align: center; font-size: 11px;;">
                    <?php echo $product->product_s_desc; ?>...
                </div>
                <div style="height:27px; width:100%;">
                    <p style="text-align:right; color:#000000;">
                        Цена: <span style="color: #3F9005; font-weight:bold; font-size:18px;"> <? echo round($product->prices['salesPrice'],2,PHP_ROUND_HALF_UP); ?> руб.</span>
                    </p>
                </div>
            </li>
            <?php
            if ($col == $products_per_row && $products_per_row && $last) {
                echo '</ul><div class="clear"></div><ul  class="vmproduct' . $params->get('moduleclass_sfx') . '">';
                $col = 1;
            } else {
                $col++;
            }
            $last--;
        endforeach; ?>
        </ul>
    </div>
    <div class="clear"></div>
    </div>
</div>
<div class="long-bestoff-bottom"></div>