<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >

<head>
<jdoc:include type="head" />
<LINK rel="icon" href="/favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/system.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/general.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/rhuk_milkyway1/css/template.css" type="text/css" />
<!--[if lte IE 6]>
<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/ieonly.css" rel="stylesheet" type="text/css" />
<![endif]-->

<script type="text/javascript" src="/media/widgetkit/js/func.js"></script>

<?php if($this->direction == 'rtl') : ?>
        <link href="<?php echo $this->baseurl ?>/templates/rhuk_milkyway1/css/template_rtl.css" rel="stylesheet" type="text/css" />
<?php endif; ?>
</head>

<body>
<div class="container">
    <div class="header">
         <div class="header-border-left"></div>
         <div class="header-center">
               <div class="header-center-1">
                    <div class="header-logo"><jdoc:include type="modules" name="logo" /></a></div>
               </div>
               <div class="header-center-2">
                    <div class="header-mensclub"></div>
                    <div class="header-centerframe-top"></div>
                    <div class="header-centerframe-text"><p>Концептуальный интернет-магазин мужской одежды</p></div>
                    <div class="header-centerframe-bottom"></div> 
               </div>
               <div class="header-center-3">
                    <div class="header-phone">7 (495) 970-37-90</div>
					<div class="header-time">Время работы с 9:00 до 22:00</div>
                    <div class="header-contactsframe" style="margin-top:7px;"></div>
                    <div class="header-contactsframe-link"><a href="/contacts">Служба поддержки</a></div>
                    <div class="header-contactsframe" style="margin-top:8px;"></div>
               </div>
               <div class="header-center-4">
                    <div class="header-cart"></div>
                    <div class="header-cart-content"><jdoc:include type="modules" name="shopcart" /></div>
                    <div class="header-cartframe" style="margin-top:8px;"></div>
                    <div class="header-cartframe-login">
<?php 
$user =& JFactory::getUser();
$base = JURI::current() . "\n";
$redirect = base64_encode($base);
if ($user->guest) { ?>

<a href="#" id="ShowAvtoriz">Вход</a>&nbsp;<a href="#">&bull;</a>&nbsp;<a href="/create-an-account/" id="ShowRegistr">Регистрация</a>
<?php }
else 
{
?>

<a href="index.php?option=com_user&task=logout&return=<?php echo $redirect; ?>" class="logout" >Выйти</a>

<?php } ?>

</div>
                    <div class="header-cartframe" style="margin-top:8px;"></div>
               </div>
         </div>
         <div class="header-border-right"></div>
    </div>
<?php if($this->countModules('vertical-menu')) : ?>
    <div class="vertical-menu"><jdoc:include type="modules" name="vertical-menu" /></div>
<?php endif; ?>
	<div class="content-border">
    <div class="content">
	
<?php if($this->countModules('left')) : ?>
         <div class="left"><jdoc:include type="modules" name="left" style="xhtml"  /></div>
<?php endif; ?>
         <div class="center">
				
				<?php echo '<jdoc:include type="component" />'; ?>
                                <?php echo '<jdoc:include type="message" />'; ?>
         </div>
		 <div class="right"></div>
    </div>
	</div>
<?php if($this->countModules('newproducts')) : ?>
    <div class="newproducts"><h3>Новые поступления</h3>
	<a class="latest-products-link" href="/view-all-products-in-shop">Показать все товары</a><jdoc:include type="modules" name="newproducts" /></div>
<?php endif; ?>
    <div class="footer">
         <div class="footer-left"></div>
         <div class="footer-center">
              <div class="footer-top">
                    <div class="footer-top-1">
                          <div class="footer-menu-border-1"></div>
                          <div class="footer-top-menu">
                          <jdoc:include type="modules" name="footer-menu" />
                          </div>
                          <div class="footer-menu-border-2"></div>
                    </div>
                    <div class="footer-top-2">
                    </div>
              </div>
              <div class="footer-line"></div>
              <div class="footer-bottom">
                    <div class="footer-bottom-1">
                          <jdoc:include type="modules" name="footer-bottom-1" />
                    </div>
                    <div class="footer-bottom-vert-line">
                    </div> 
                    <div class="footer-bottom-2">
                          <jdoc:include type="modules" name="footer-bottom-2" />
                    </div>
                    <div class="footer-bottom-vert-line">
                    </div>   
                    <div class="footer-bottom-3">
                          <jdoc:include type="modules" name="footer-bottom-3" />     
                    </div>        
              </div>
         </div>
         <div class="footer-right"></div>
    </div>
</div>
<div class="overlayavt" id="overlayavt" style="display:none;"></div>
 <div class="boxavt" id="boxavt">
<a class="boxcloseavt" id="boxcloseavt"></a>
 <p>
 <jdoc:include type="modules" name="avtoriz" style="rounded" />
 </p>
</div>
</body>
</html>