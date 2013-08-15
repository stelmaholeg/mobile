<?php
/**
 * @package                Joomla.Site
 * @subpackage	Templates.beez_20
 * @copyright        Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license                GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
jimport('joomla.filesystem.file');
// check modules
$showRightColumn	= ($this->countModules('position-3') or $this->countModules('position-6') or $this->countModules('position-8'));
$showbottom			= ($this->countModules('position-9') or $this->countModules('position-10') or $this->countModules('position-11'));
$showleft			= ($this->countModules('position-4') or $this->countModules('position-7') or $this->countModules('position-5'));
if ($showRightColumn==0 and $showleft==0) {
	$showno = 0;
}
JHtml::_('behavior.framework', true);
// get params
$color				= $this->params->get('templatecolor');
$logo				= $this->params->get('logo');
$navposition		= $this->params->get('navposition');
$app				= JFactory::getApplication();
$doc				= JFactory::getDocument();
$templateparams		= $app->getTemplate(true)->params;

$cView = JRequest::getString('option');

$doc->addScript('jsonparse.js');
$doc->addStyleSheet($this->baseurl.'/templates/system/css/system.css');
$doc->addStyleSheet($this->baseurl.'/templates/'.$this->template.'/css/position.css', $type = 'text/css', $media = 'screen,projection');
$doc->addStyleSheet($this->baseurl.'/templates/'.$this->template.'/css/layout.css', $type = 'text/css', $media = 'screen,projection');
$doc->addStyleSheet($this->baseurl.'/templates/'.$this->template.'/css/print.css', $type = 'text/css', $media = 'print');
$doc->addStyleSheet($this->baseurl.'/templates/'.$this->template.'/css/jquery-ui-1.8.21.custom.css', $type = 'text/css', $media = 'screen,projection');
$files = JHtml::_('stylesheet', 'templates/'.$this->template.'/css/general.css', null, false, true);
if ($files):
	if (!is_array($files)):
		$files = array($files);
	endif;
	foreach($files as $file):
		$doc->addStyleSheet($file);
	endforeach;
endif;
$doc->addStyleSheet('templates/'.$this->template.'/css/'.htmlspecialchars($color).'.css');
if ($this->direction == 'rtl') {
	$doc->addStyleSheet($this->baseurl.'/templates/'.$this->template.'/css/template_rtl.css');
	if (file_exists(JPATH_SITE . '/templates/' . $this->template . '/css/' . $color . '_rtl.css')) {
		$doc->addStyleSheet($this->baseurl.'/templates/'.$this->template.'/css/'.htmlspecialchars($color).'_rtl.css');
	}
} ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<link href='http://fonts.googleapis.com/css?family=Didact+Gothic&subset=latin,cyrillic,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css' />
<link href='http://fonts.googleapis.com/css?family=Prosto+One&subset=latin,cyrillic' rel='stylesheet' type='text/css' />
<link href='http://fonts.googleapis.com/css?family=Ubuntu:300&subset=latin,cyrillic-ext,cyrillic' rel='stylesheet' type='text/css' />
<link href='http://fonts.googleapis.com/css?family=Advent+Pro' rel='stylesheet' type='text/css' />

<meta http-equiv="X-UA-Compatible" content="IE=100" /> <!-- IE9 mode -->
<jdoc:include type="head" />
<!--[if lte IE 6]>
<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/ieonly.css" rel="stylesheet" type="text/css" />
<?php if ($color=="personal") : ?>
    <style type="text/css">
        #line {
            width:98% ;
        }
        #header ul.menu {
            display:block !important;
            width:98.2% ;
        }
    </style>
<?php endif; ?>
<![endif]-->

<!--[if IE 7]>
<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/ie7only.css" rel="stylesheet" type="text/css" />
<![endif]-->
<script type="text/javascript">
jQuery.noConflict();
jQuery(document).ready(function($) {
    jQuery(".product-f").each(function(index,value){
        jQuery(this).submit(function(e){
            e.preventDefault();
            var m_id = jQuery(this).attr('id');

            var m_method = jQuery(this).attr('method');
            var m_action = jQuery(this).attr('action');
            var m_data = jQuery(this).serialize();
            jQuery.ajax({
                method: m_method,
                url: m_action,
                data: m_data,
                dataType: 'json',
                success: function(result){}
            });
        });
    });
    jQuery('.expander-link-1').click(function(){jQuery('.expander-div-1').toggle();});
    jQuery('.expander-link-2').click(function(){jQuery('.expander-div-2').toggle();});
});
</script>
<style type="text/css">
    .expander-link-1,.expander-link-2{
        cursor: pointer;
    }
</style>

<script type="text/javascript">
	var big ='<?php echo (int)$this->params->get('wrapperLarge');?>%';
	var small='<?php echo (int)$this->params->get('wrapperSmall'); ?>%';
	var altopen='<?php echo JText::_('TPL_BEEZ2_ALTOPEN', true); ?>';
	var altclose='<?php echo JText::_('TPL_BEEZ2_ALTCLOSE', true); ?>';
	var bildauf='<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/images/plus.png';
	var bildzu='<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/images/minus.png';
	var rightopen='<?php echo JText::_('TPL_BEEZ2_TEXTRIGHTOPEN', true); ?>';
	var rightclose='<?php echo JText::_('TPL_BEEZ2_TEXTRIGHTCLOSE', true); ?>';
	var fontSizeTitle='<?php echo JText::_('TPL_BEEZ2_FONTSIZE', true); ?>';
	var bigger='<?php echo JText::_('TPL_BEEZ2_BIGGER', true); ?>';
	var reset='<?php echo JText::_('TPL_BEEZ2_RESET', true); ?>';
	var smaller='<?php echo JText::_('TPL_BEEZ2_SMALLER', true); ?>';
	var biggerTitle='<?php echo JText::_('TPL_BEEZ2_INCREASE_SIZE', true); ?>';
	var resetTitle='<?php echo JText::_('TPL_BEEZ2_REVERT_STYLES_TO_DEFAULT', true); ?>';
	var smallerTitle='<?php echo JText::_('TPL_BEEZ2_DECREASE_SIZE', true); ?>';
</script>

<script type="text/javascript" src="templates/beez_20/javascript/jquery.colorbox-min.js"></script>
<script type="text/javascript" src="templates/beez_20/javascript/jquery.anythingslider.js"></script>
<script type="text/javascript" src="templates/beez_20/javascript/jquery.easing.1.2.js"></script>
<script type="text/javascript" src="templates/beez_20/javascript/slider.js"></script>
<script type="text/javascript" src="templates/beez_20/javascript/slides.min.jquery.js"></script>
<link href="templates/beez_20/css/colorbox.css" rel="stylesheet" type="text/css" />
<style type="text/css">
#container{width:350px;margin:0 auto}
ul{list-style:none;margin:0;padding:0}
.width{overflow:hidden;width:1140px}
.bar{font-weight:bold}
.leftText{text-align:right}
.right{text-align:right}
.width table tr th #content #menu8 ul li a font{font-weight:bold}
.socstyles{width:88px;float:left;margin-left:25px}
.socstyles img{margin-right:10px;float:left}
.socstyles .soc-counter{display:inline-block;text-align:center;background:#fff;padding-top:2px;padding-left:2px;padding-right:2px;height:18px;width:50px;border:1px solid #ababab;color:#666}
</style>
</head>
<body>
<div class="hidden">
    <div id="popup-login">
        <jdoc:include type="modules" name="popup-login" />
    </div>
</div>
<div id="all">
    <div id="back">
        <div id="header">
            <div class="hdiv float-left" style="margin-right:40px">
                <a title="" id="hlogo" href="/"></a>
            </div>
            <div class="hdiv float-left" style="padding-top:5px">
                <div>
            <?php
                $user = JFactory::getUser();
                if (!$user->id){ ?>
                <a title="" href="#" class="show-login-form didact"><b>Вход на сайт</b></a>&nbsp;|&nbsp;<a title="" href="index.php?option=com_users&view=registration" class="show-registr-form didact">Регистрация</a>
            <?php } else { ?>
                <span class="didact" style="color:black; margin-right:10px;">Добро пожаловать, <a style="margin-right:10px" class="current_town" href="index.php?option=com_virtuemart&view=user&layout=edit"><?php echo $user->username; ?></a></span>
                <a title="" class="current_town" href="index.php?option=com_users&task=user.logout&<?php echo JSession::getFormToken().'=1'; ?>">Выход</a>
            <?php } ?>
                </div>
                <div style="margin-top:14px">
                    <jdoc:include type="modules" name="town_selector"/>
                </div>
            </div>
            <div class="hdiv" style="height:24px;position:absolute;left:50%;margin-left:30px;margin-top:50px">
                <div class="socstyles vkstyle">
                    <img alt="" src="/images/mobile/vk.png" />
                    <span class="soc-counter">0</span>
                    <script type="text/javascript">

                        var v_url = "/parsevk.php";
                        jQuery(document).ready(function(){jQuery.ajax({url: v_url, success: function(a){jQuery('.vkstyle .soc-counter').html(a)}});});
                    </script>
                </div>
                <div class="socstyles odnstyle">
                    <img alt="" src="/images/mobile/odnokl.png" />
                    <span class="soc-counter">0</span>
                    <script type="text/javascript">
                        var o_url = "/parseodn.php";
                        jQuery(document).ready(function(){jQuery.ajax({url: o_url, success: function(a){jQuery('.odnstyle .soc-counter').html(a)}});});
                    </script>
                </div>

                <div class="socstyles facebookstyle">
                    <img alt="" src="/images/mobile/facebook.png" />
                    <span class="soc-counter">0</span>
                    <script type="application/x-javascript">
			   var f_url = "https://graph.facebook.com/105928902896018?fields=members&access_token=AAAHJHcBvSZA4BAH5SAdWcEz6qlKeZCzugRwnwJSCQa1wHsmQYsqCam9QGBl9syGZCyiLUizW6BtEG9kS4VQkCSXo2qFQQbeqTkd8hOOXAZDZD";

                        jQuery(document).ready(function () {
                            jQuery.getJSON(f_url + '&callback=?',  function (a) { jQuery('.facebookstyle .soc-counter').html(a.members.data.length); });});
                    </script>
                </div>

            </div>
            <div class="hdiv float-right" style="margin-left:23px;width:100px">
                <jdoc:include type="modules" name="cart-position" />
            </div>
            <div class="hdiv float-right">
                <!--<div style="height: 48px;">
                </div>-->
                <div>
                    <jdoc:include type="modules" name="virtuemart_search"/>
                </div>
            </div>
            <div class="hdiv float-right">
                <div style="height:48px">
                </div>
                <div style="margin-right:0;margin-top:0;position:absolute;left:416px">
                    <!--jdoc:include type="modules" name="wishlist-links"/-->
					<?php $user = JFactory::getUser();
					if ($user->id){ ?>
					<a title="" class="current_town" href="http://www.mobile26.net/?option=com_wishlist&view=favoriteslist&Itemid=154">Лист желаний</a>
					<?php } ?>
                </div>
            </div>
        </div>
        <div style="width:1000px;clear:both"></div>
        <div style="width:190px;float:left">
            <jdoc:include type="modules" name="position-4" style="beezHide" headerLevel="3" state="0 " />
            <jdoc:include type="modules" name="right-slider-placeholder" />
            <jdoc:include type="modules" name="left-second" style="beezHide" headerLevel="3" state="0 " />
            <!-- Фильтры -->
            <jdoc:include type="modules" name="filters-1" />

            <jdoc:include type="modules" name="akcies-2" />
            <jdoc:include type="modules" name="articles-3" />
            <jdoc:include type="modules" name="right-socials" style="beezHide" headerLevel="3" state="0 " />
            <jdoc:include type="modules" name="right-polls" style="beezHide" headerLevel="3" state="0 " />
            <jdoc:include type="modules" name="relcats-4" />
        </div>
        <div style="width:775px;float:left;margin-left:30px">
            <div style="margin-bottom:25px;height:34px">
                <!-- Меню основное -->
                <div class="hmenu-left"></div>
                <div class="hmenu-cont"><jdoc:include type="modules" name="position-7" style="beezDivision" headerLevel="3" /></div>
                <div class="hmenu-right"></div>
            </div>

            <?php if ($cView != "com_wishlist") { ?>
			<jdoc:include type="modules" name="position-2" style="beezHide" headerLevel="3" state="0 " />
			<?php } ?>

            <!-- Слайдер (только для главной) -->
            <jdoc:include type="modules" name="main-slider" style="beezHide" headerLevel="3" state="0 " />
			 <!--jdoc:include type="modules" name="adv" style="beezHide" headerLevel="3" state="0 " /-->
			
			<jdoc:include type="modules" name="adv" />
			
            <div style="margin-top:18px">
                <jdoc:include type="modules" name="right-akcii-1" style="beezHide" headerLevel="3" state="0 " />
                <jdoc:include type="modules" name="right-news-1" style="beezHide" headerLevel="3" state="0 " />
            </div>
            <jdoc:include type="modules" name="akcslider" />
			<jdoc:include type="modules" name="news-content-slider"/>
			<jdoc:include type="modules" name="news-akcii-slider"/>
            <jdoc:include type="component" />
        </div>
        <div style="display:none">
            <jdoc:include type="modules" name="search" />
            <jdoc:include type="modules" name="virtuemart_search"/>
            <jdoc:include type="modules" name="position-1" />
        </div>

        <div style="clear:both;width:1100px"></div>
        <div id="<?php echo $showRightColumn ? 'contentarea2' : 'contentarea'; ?>">
        <?php if ($navposition=='left' and $showleft) : ?>
            <div class="left1 <?php if ($showRightColumn==NULL){ echo 'leftbigger';} ?>" id="nav">
                <jdoc:include type="modules" name="position-5" style="beezTabs" headerLevel="2"  id="3" />
            </div>
        <?php endif; ?>

        <div id="<?php echo $showRightColumn ? 'wrapper' : 'wrapper2'; ?>" <?php if (isset($showno)){echo 'class="shownocolumns"';}?>>
            <div id="main">
                <jdoc:include type="modules" name="slider" />
            <?php if (false && ($this->countModules('position-12'))): ?>
                <div id="top">
                    <jdoc:include type="modules" name="position-12"   />
                </div>
            <?php endif; ?>
            </div>
            <div style="margin-left:-225px;margin-top:3px;height:105px;width:1100px"></div>
        </div>
        <div style="clear:both;width:100%;height:20px"></div>
        <div class="featured-items" style="1100px;background:red;display:none">
            <jdoc:include type="modules" name="vm-featured"/>
        </div>
<?php if ($showRightColumn) : ?>
        <h2 class="unseen"><?php echo JText::_('TPL_BEEZ2_ADDITIONAL_INFORMATION'); ?></h2>
        <div id="close">
            <a title="" href="#" onclick="auf('right')">
                <span id="bild"><?php echo JText::_('TPL_BEEZ2_TEXTRIGHTCLOSE'); ?></span>
            </a>
        </div>
        <div id="right">
            <a id="additional"></a>
            <!--
            <jdoc:include type="modules" name="position-6" style="beezDivision" headerLevel="3"/>
            <jdoc:include type="modules" name="position-8" style="beezDivision" headerLevel="3"  />
            <jdoc:include type="modules" name="position-3" style="beezDivision" headerLevel="3"  />
            -->
        </div>
            <?php endif; ?>
            <?php if ($navposition=='center' and $showleft) : ?>
        <div class="left <?php if ($showRightColumn==NULL){ echo 'leftbigger';} ?>" id="nav" >
            <jdoc:include type="modules" name="position-7"  style="beezDivision" headerLevel="3" />
            <jdoc:include type="modules" name="position-4" style="beezHide" headerLevel="3" state="0 " />
            <jdoc:include type="modules" name="position-5" style="beezTabs" headerLevel="2"  id="3" />
        </div>
   <?php endif; ?>
        <div class="wrap"></div>
            </div> <!-- end contentarea -->
        </div><!-- back -->
    </div><!-- all -->
    <div id="footer-outer">
    <?php if ($showbottom) : ?>
    <div id="footer-inner">
        <div id="bottom">
            <!--
            <div class="box box1"> <jdoc:include type="modules" name="position-9" style="beezDivision" headerlevel="3" /></div>
            <div class="box box2"> <jdoc:include type="modules" name="position-10" style="beezDivision" headerlevel="3" /></div>
            <div class="box box3"> <jdoc:include type="modules" name="position-11" style="beezDivision" headerlevel="3" /></div>
            -->
        </div>
    </div>
    <?php endif ; ?>
    <div id="footer-sub">
        <div id="footer" style="padding-bottom:0;padding-top:0">
            <div class="f-menu">
                <h3>Mobile26</h3>
                <jdoc:include type="modules" name="menu-bottom-1" />
            </div>
            <div class="f-menu">
                <h3>Личный кабинет</h3>
                <ul class="menu">
                    <?php if (!$user->id){ ?>
                    <li>
                        <a title="" href="javascript:" class="show-login-form">Вход для пользователей</a>
                    </li>
                    <li>
                        <a title="" href="/index.php?option=com_users&view=registration" class="show-registr-form">Зарегистрироваться</a>
                    </li>

                    <?php } else { ?>
                    <li>
                        <a title="" href="index.php?option=com_virtuemart&view=user&layout=edit">Личный кабинет</a>
                    </li>
                    <li>
                        <a title="" href="index.php?option=com_users&task=user.logout&<?php echo JSession::getFormToken().'=1'; ?>">Выход</a>
                    </li>
                    <?php } ?>
                    <li>
                        <a title="" href="index.php?option=com_content&view=article&id=17&Itemid=108#clients">Принципы работы с клиентами</a>
                    </li>
                </ul>
                <!--<jdoc:include type="modules" name="menu-bottom-2" />-->
            </div>
            <div class="f-menu">
                <h3>Наши преимущества</h3>
                <jdoc:include type="modules" name="menu-bottom-3" />
            </div>
            <div class="f-menu">
                <h3>Покупателю</h3>
                <jdoc:include type="modules" name="menu-bottom-4" />
            </div>
            <div style="clear:both;"></div>
            <table id="t_1t" class='footer-table' style="width:100%;border:none;border-collapse:collapse">
                <tbody><tr class="text">
                    <th scope="col" align="left" style="padding-top:22px"><p style="font-weight:normal">г. Таганрог ул. Александровская 174</p>
                        <p style="font-weight:normal">тел. (928) 226 76 81</p></th>
                    <th scope="col"><p class="center" style="padding-top:22px;font-weight:normal">Единый информационный центр</p>
                        <p class="center">(988) 10 40 222</p></th>
                    <th style="width:285px">
                        <div style="width:280px;height:20px;margin-top:22px;float:right">
                            <span style="color:rgb(94, 94, 94);vertical-align:top;font-size:11px">Разработка сайта - </span>
                            <a style="font-size:0;text-decoration:none;text-indent:-9999px" title="Разработка и поддержка сайта - компания WildMax" href="http://wildmax.org/">
                                <img alt="Разработка и поддержка сайта - компания WildMax" src="/images/logo_wildmax2.png" style="margin-top:-2px;"/>
                                <span>Разработка сайтов, интернет систем, десктопных и мобильных приложений</span>
                            </a>
                        </div>
                    </th>
                </tr>
            </tbody>
            </table>
            <table id="t_2t" class='footer-table' style="width:100%;border:none;border-collapse:collapse">
                <tbody><tr class="text">
                    <th scope="col" align="left" style="padding-top:22px"><p style="font-weight:normal">г. Ставрополь ул. К.Маркса 76</p>
                        <p style="font-weight:normal">тел. 8 (962) 440 80 04</p></th>
                    <th scope="col"><p class="right" style="padding-top:22px;font-weight:normal">Единый информационный центр</p>
                        <p class="right">(988) 10 40 222</p></th>
                </tr>
                </tbody>
            </table>
        </div><!-- end footer -->
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('#bottomslider #slider').anythingSlider({
            // Appearance
            width               : "500px"  , height              : "68px",
            theme               : "default", expand              : false,    resizeContents      : true,      vertical            : false,
            showMultiple        : false,     easing              : "swing",  buildArrows         : true,      buildNavigation     : false,
            buildStartStop      : false,      appendForwardTo    : "#bottomslider #fi-sl-fw", appendBackTo     : "#bottomslider #fi-sl-bw", appendControlsTo  : null,
            appendNavigationTo  : null,      appendStartStopTo   : null,     toggleArrows        : false,     toggleControls      : false,
            startText           : "Start",   stopText            : "Stop",   forwardText         : "»",       backText            : "«",
            tooltipClass        : "tooltip", enableArrows        : true,     enableNavigation    : false,      enableStartStop     : false,
            enableKeyboard      : true,      startPanel          : 1,        changeBy            : 1,         hashTags            : true,
            infiniteSlides      : true,      navigationFormatter : null,     navigationSize      : false,     autoPlay            : false,
            autoPlayLocked      : false,     autoPlayDelayed     : true,     pauseOnHover        : true,      stopAtEnd           : false,
            playRtl             : true,      delay               : 4000,     resumeDelay         : 000,       animationTime       : 400,
            delayBeforeAnimate  : 0,         clickForwardArrow   : "click",  clickBackArrow      : "click",   clickControls       : "click focusin",
            clickSlideshow      : "click",   resumeOnVideoEnd    : true,     addWmodeToObject    : "opaque"
        });
    });
</script>
<script type="text/javascript" src="/templates/beez_20/javascript/slider.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('.show-login-form').click(function(){
            jQuery.colorbox({
                html:jQuery('#popup-login').html(),
                opacity: 0.5,
                close: 'close'
            });
			return false;
        });
        /*
        jQuery('.show-registr-form').click(function(){
            jQuery.colorbox({
                href:"/index.php?option=com_users&view=registration",
                opacity: 0.5,
                close: 'close'
            });
        });
        */
    });
</script>
<script type="text/javascript">
    var imgid = 'image';
    var imgdir = 'fullsize';
    var imgext = '.jpg';
    var thumbid = 'thumbs';
    var auto = true;
    var autodelay = 5;
</script>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery(".addtofav_button").click(function(){
            var curform = jQuery(this).parent();
            jQuery("form#filters").find("input:checked").each(function(i,n){
                var name = jQuery(n).attr("name");
                var value = jQuery(n).attr("value");
                jQuery(curform).append("<input type='hidden' value='"+value+"' name='"+name+"' />")
            });
        });
    });
</script>
<script type="text/javascript" src="/templates/beez_20/javascript/slide.js"></script>
<!-- Rating@Mail.ru counter -->
<script type="text/javascript">//<![CDATA[
var a='',js=10;try{a+=';r='+escape(document.referrer);}catch(e){}try{a+=';j='+navigator.javaEnabled();js=11;}catch(e){}
try{s=screen;a+=';s='+s.width+'*'+s.height;a+=';d='+(s.colorDepth?s.colorDepth:s.pixelDepth);js=12;}catch(e){}
try{if(typeof((new Array).push('t'))==="number")js=13;}catch(e){}
try{document.write('<a href="http://top.mail.ru/jump?from=2245205">'+
'<img src="http://d2.c4.b2.a2.top.mail.ru/counter?id=2245205;t=94;js='+js+a+';rand='+Math.random()+
'" alt="Рейтинг@Mail.ru" style="border:0;" height="18" width="88" \/><\/a>');}catch(e){}//]]></script>
<noscript><p><a href="http://top.mail.ru/jump?from=2245205">
<img src="http://d2.c4.b2.a2.top.mail.ru/counter?js=na;id=2245205;t=94" 
style="border:0;" height="18" width="88" alt="Рейтинг@Mail.ru" /></a></p></noscript>
<!-- //Rating@Mail.ru counter -->

</body>
</html>
