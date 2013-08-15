<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_articles_category
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
defined('_JEXEC') or die;
$view = JRequest::getString('view');
if($view == "productdetails" || $view == "cart"  ){
    return;
}
?>

<div class="m-akcii">
<div>
    <?php echo $module->title; ?>
</div>
    
<ul class="category-module<?php echo $moduleclass_sfx; ?>">
<?php if ($grouped) : ?>
<?php else : ?>
	<?php foreach ($list as $item) : ?>
	    <li>
	   	<?php if ($params->get('link_titles') == 1) : ?>
                <a class="mod-articles-category-title <?php echo $item->active; ?>" href="<?php echo $item->link; ?>" title="Прочитать подробности о &quot;<?php echo $item->title; ?>&quot;"><?php echo $item->title; ?></a>
        <?php else :?>
        <?php echo $item->title; ?>        	
        <?php endif; ?>
        </li>
	<?php endforeach; ?>
<?php endif; ?>
</ul>

<?php $ids = implode(',',json_decode($module->params)->catid); ?>
<span class="all-news">
    <?php if($ids == "10"){ ?>
        <a title="Перейти к просмотру всех акций" class="didact" style="color:#888;text-decoration:none" href="/akcii">все акции</a>
    <?php } ?>
    <?php if($ids == "8"){ ?>
        <a title="Перейти к просмотру всех новостей" class="didact hover-on-underline" style="color:#888;text-decoration:none" href="/novosti">все новости</a>
    <?php } ?>
</span>


</div>
