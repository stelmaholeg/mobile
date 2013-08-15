<?php
/**
*
* Layout for the shopping cart
*
* @package	VirtueMart
* @subpackage Cart
* @author Max Milbers
*
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: cart.php 2551 2010-09-30 18:52:40Z milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
JHTML::script('facebox.js', 'components/com_virtuemart/assets/js/', false);
JHTML::stylesheet('facebox.css', 'components/com_virtuemart/assets/css/', false);

JHtml::_('behavior.formvalidation');
$document = JFactory::getDocument();
$document->addScriptDeclaration("
	jQuery(document).ready(function($) {
		$('div#full-tos').hide();
		$('span.terms-of-service').click( function(){
			//$.facebox({ span: '#full-tos' });
			$.facebox( { div: '#full-tos' }, 'my-groovy-style');
		});
	});
");
$document->addStyleDeclaration('#facebox .content {display: block !important; height: 480px !important; overflow: auto; width: 560px !important; }');

//  vmdebug('car7t pricesUnformatted',$this->cart->pricesUnformatted);
//  vmdebug('cart pricesUnformatted',$this->cart->cartData );
?>

<div class="cart-view">
	<div>
	<div class="width50 floatleft">
		<h1><?php echo JText::_('COM_VIRTUEMART_CART_TITLE'); ?></h1>
	</div>
	<?php if (VmConfig::get('oncheckout_show_steps', 1) && $this->checkout_task==='confirm'){
		vmdebug('checkout_task',$this->checkout_task);
		//echo '<div class="checkoutStep" id="checkoutStep4">'.JText::_('COM_VIRTUEMART_USER_FORM_CART_STEP4').'</div>';
	} ?>
	<div class="width50 floatleft right" style="padding-top:4px;">
        <span style="font-size: 20px;">Нужна помощь в оформлении заказа?</span>
        <div style="background: url('./templates/beez_20/css/images/new/phone.png') no-repeat scroll 0% 0% transparent; height: 35px; margin-top:17px; float: left; margin-left: 60px; width:230px; padding-left: 40px;">
            <div id="t_1tc" class="cart-contact">
                г. Таганрог ул. Александровская 174<br/>
                тел. (928) 226 76 81
            </div>
            <div id="t_2tc" class="cart-contact" style="display:none;">
                г. Ставрополь ул. К.Маркса 76<br/>
                тел. 8 (8634) 231 438
            </div>
            Единый информационный центр<br/>
            (988) 10 40 222
        </div>
        <img alt="" src="./templates/beez_20/css/images/new/support.png" style="float:right; margin-top:8px;" />
	</div>
<div class="clear"></div>
</div>

<?php if(count($this->cart->products)>0){?>

<?php echo shopFunctionsF::getLoginForm($this->cart,false);
//echo $this->loadTemplate('login');


	// This displays the pricelist MUST be done with tables, because it is also used for the emails
	echo $this->loadTemplate('pricelist');
	if ($this->checkout_task) $taskRoute = '&task='.$this->checkout_task;
	else $taskRoute ='';
	?>
    <div>
        <hr/>

        <div class="ie-billto-shipto width50 floatleft" style="padding-top:20px;">
            <div class="billto-shipto">
                <div class="width50 floatleft">
            <span><span class="vmicon vm2-billto-icon"></span>
                <?php echo JText::_('COM_VIRTUEMART_USER_FORM_BILLTO_LBL'); ?></span>
                    <?php // Output Bill To Address ?>
                    <div class="output-billto">
                        <?php
                        foreach($this->cart->BTaddress['fields'] as $item){
                            if(!empty($item['value'])){
                                if($item['name']==='agreed'){
                                    $item['value'] =  ($item['value']===0) ? JText::_('COM_VIRTUEMART_USER_FORM_BILLTO_TOS_NO'):JText::_('COM_VIRTUEMART_USER_FORM_BILLTO_TOS_YES');
                                }
                                ?>
                                <span class="values vm2<?php echo '-'.$item['name'] ?>" ><?php echo $this->escape($item['value']) ?></span>
                                <?php if ($item['name'] != 'title' and $item['name'] != 'first_name' and $item['name'] != 'middle_name' and $item['name'] != 'zip') { ?>
                                    <br class="clear" />
                                    <?php
                                }
                            }
                        } ?>
                        <div class="clear"></div>
                    </div>

                    <a class="current_town" href="<?php echo JRoute::_('index.php?option=com_virtuemart&view=user&task=editaddresscart&addrtype=BT',$this->useXHTML,$this->useSSL) ?>">
                        Добавить&nbsp;/&nbsp;Изменить&nbsp;адрес&nbsp;доставки
                    </a>

                    <input type="hidden" name="billto" value="<?php echo $this->cart->lists['billTo']; ?>"/>
                </div>
                <div class="clear"></div>
            </div>
        </div>

<!--
        <div class="width50 floatleft" style="padding-top:20px;">
            <?php if(!empty($this->cart->STaddress['fields'])){ ?>
            <span><span class="vmicon vm2-shipto-icon"></span>
                <span style="font-size:14px; color:#666666;" class="comment didact"><?php echo JText::_('COM_VIRTUEMART_USER_FORM_SHIPTO_LBL'); ?></span>
            </span>
            <?php // Output Bill To Address ?>
            <div class="output-shipto">
                <?php
                if(empty($this->cart->STaddress['fields'])){
                    echo JText::sprintf('COM_VIRTUEMART_USER_FORM_EDIT_BILLTO_EXPLAIN',JText::_('COM_VIRTUEMART_USER_FORM_ADD_SHIPTO_LBL') );
                } else {
                    if(!class_exists('VmHtml'))require(JPATH_VM_ADMINISTRATOR.DS.'helpers'.DS.'html.php');
                    echo JText::_('COM_VIRTUEMART_USER_FORM_ST_SAME_AS_BT'). VmHtml::checkbox('STsameAsBT',$this->cart->STsameAsBT).'<br />';
                    foreach($this->cart->STaddress['fields'] as $item){
                        if(!empty($item['value'])){ ?>
                            <!-- <span class="titles"><?php echo $item['title'] ?></span> -->
                            <?php
                            if ($item['name'] == 'first_name' || $item['name'] == 'middle_name' || $item['name'] == 'zip') { ?>
                                <span class="values<?php echo '-'.$item['name'] ?>" ><?php echo $this->escape($item['value']) ?></span>
                                <?php } else { ?>
                                <span class="values" ><?php echo $this->escape($item['value']) ?></span>
                                <br class="clear" />
                                <?php
                            }
                        }
                    }
                }
                ?>
                <div class="clear"></div>
            </div>
            <?php if(!isset($this->cart->lists['current_id'])) $this->cart->lists['current_id'] = 0; ?>
            <?php } ?>
            <a class="current_town" href="<?php echo JRoute::_('index.php?option=com_virtuemart&view=user&task=editaddresscart&addrtype=ST&cid[]='.$this->cart->lists['current_id'],$this->useXHTML,$this->useSSL) ?>">
                <?php echo JText::_('COM_VIRTUEMART_USER_FORM_ADD_SHIPTO_LBL'); ?>
            </a>
        </div>
-->
        <div class="width50 floatright">
            <form method="post" id="checkoutForm" name="checkoutForm" action="<?php echo JRoute::_( 'index.php?option=com_virtuemart&view=cart'.$taskRoute,$this->useXHTML,$this->useSSL ); ?>">

                <?php // Leave A Comment Field ?>
                <div class="customer-comment marginbottom15" style="border:none;">
                    <span class="comment didact" style="font-size:14px; color:#666666;"><?php echo JText::_('COM_VIRTUEMART_COMMENT'); ?></span><br />
                    <textarea class="customer-comment" name="customer_comment" cols="50" rows="4" style="width:377px; max-width: 377px; min-width: 377px;"><?php echo $this->cart->customer_comment; ?></textarea>
                </div>
                <?php // Leave A Comment Field END ?>



                <?php // Continue and Checkout Button ?>
                <div class="checkout-button-top" style="border:none;">
                    <?php // Terms Of Service Checkbox
                    if (!class_exists('VirtueMartModelUserfields')){
                        require(JPATH_VM_ADMINISTRATOR . DS . 'models' . DS . 'userfields.php');
                    }
                    $userFieldsModel = VmModel::getModel('userfields');

                    if($userFieldsModel->getIfRequired('agreed')){
                        ?>
                        <label for ="tosAccepted">
                        <?php
                        if(!class_exists('VmHtml'))require(JPATH_VM_ADMINISTRATOR.DS.'helpers'.DS.'html.php');
                        echo VmHtml::checkbox('tosAccepted',$this->cart->tosAccepted,1,0,'class="terms-of-service" checked="checked" style="display:none;"');

                if(VmConfig::get('oncheckout_show_legal_info',1)){
                ?>
                <div class="terms-of-service">
                    <span class="terms-of-service" rel="facebox"><span class="vmicon vm2-termsofservice-icon"></span><?php echo JText::_('COM_VIRTUEMART_CART_TOS_READ_AND_ACCEPTED'); ?><span class="vm2-modallink"></span></span>
                    <div id="full-tos">
                        <h2><?php echo JText::_('COM_VIRTUEMART_CART_TOS'); ?></h2>
                        <?php echo $this->cart->vendor->vendor_terms_of_service;?>

                    </div>
                </div>
                <?php
                } // VmConfig::get('oncheckout_show_legal_info',1)
                        //echo '<span class="tos">'. JText::_('COM_VIRTUEMART_CART_TOS_READ_AND_ACCEPTED').'</span>';
                        ?>
                        </label>
                    <?php
                    }

                    echo $this->checkout_link_html;
                    $text = JText::_('COM_VIRTUEMART_ORDER_CONFIRM_MNU');
                    ?>
                </div>
                <?php //vmdebug('my cart',$this->cart);// Continue and Checkout Button END ?>

                <input type='hidden' name='task' value='<?php echo $this->checkout_task; ?>'/>
                <input type='hidden' name='option' value='com_virtuemart'/>
                <input type='hidden' name='view' value='cart'/>
            </form>
        </div>
<?php } else { ?>
		<div class="clearboth" style="height:150px; font-size:18px; text-align:center; padding-top:100px;">
			Корзина пуста
		</div>
<?php } ?>
        <div class="clearboth"></div>
    </div>
</div>