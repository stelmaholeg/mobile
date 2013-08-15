<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_articles_category
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>

<div id="m-news">
<div class="m-news-title">
    <?php $ids = implode(',',json_decode($module->params)->catid); ?>
    <?php if($ids == "10"){ ?>
    <h3>Акции</h3>
    <?php } ?>
    <?php if($ids == "8"){ ?>
    <h3>Новости</h3>
    <?php } ?>
    <div class="didact" style="float:right;margin-right:20px;margin-top:0">
        <?php if($ids == "10"){ ?>
        <a title="Перейти к просмотру всех акций" class="didact" style="color:#888;text-decoration:none" href="/akcii">все акции</a>
        <?php } ?>
        <?php if($ids == "8"){ ?>
        <a title="Перейти к просмотру всех новостей" class="didact hover-on-underline" style="color:#888;text-decoration:none" href="/novosti">все новости</a>
        <?php } ?>
    </div>
    <div class="clear"></div>
</div>
<ul class="category-module horiz-news<?php echo $moduleclass_sfx; ?>" style="width:770px">
	<?php $i=0;
        foreach ($list as $item) :
        if($i++ < 3){ ?>
            <li>
	    <!--li style="" commit oleg-->
            <div class="sh-block">
                <?php if ($params->get('link_titles') == 1) : ?>
                <h4>
                    <a title="Перейти к прочтению &quot;<?php echo $item->title; ?>&quot;" class="mod-articles-category-title <?php echo $item->active; ?>" href="<?php echo $item->link; ?>"><?php echo $item->title; ?></a>
                </h4>
                <?php else :?>
                <h4><?php echo $item->title; ?></h4>
                <?php endif; ?>
            </div>
            <div class="img">
            <?php if(json_decode($item->images)->image_intro) { ?>
                <img src="/<?php echo json_decode($item->images)->image_intro;?>" alt="" style="width:105px" />
            <?php } ?>
            </div>
            <div class="mod-articles-category-body">
                <span class="mod-articles-category-date" style="color:#3994BF;font-size:10px;text-align:right">
                    <?php $adate = date("Y.m.d", strtotime(JDate::getInstance($item->created)));
                     echo $adate; ?>
                </span>
                <span class="mod-articles-category-introtext">
                    <?php echo $item->displayIntrotext; ?>
                </span>
                <a title="" style="float:right;color:#3994C0;" href="<?php echo $item->link; ?>">подробнее...</a>
            </div>
        </li>
        <?php } ?>
	<?php endforeach; ?>
</ul>
<div style="clear:both"></div>
</div>
<div class="grad-underslider"></div>

