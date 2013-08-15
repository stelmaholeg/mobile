<?php defined('_JEXEC') or die('Restricted access');
/**
 *
 * Layout for the shopping cart
 *
 * @package	VirtueMart
 * @subpackage Cart
 * @author Max Milbers
 * @author Patrick Kohl
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 *
 */

// Check to ensure this file is included in Joomla!

// jimport( 'joomla.application.component.view');
// $viewEscape = new JView();
// $viewEscape->setEscape('htmlspecialchars');

?>
<!--<div class="billto-shipto">
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

		<a class="details" href="<?php echo JRoute::_('index.php?option=com_virtuemart&view=user&task=editaddresscart&addrtype=BT',$this->useXHTML,$this->useSSL) ?>">
		<?php echo JText::_('COM_VIRTUEMART_USER_FORM_EDIT_BILLTO_LBL'); ?>
		</a>

		<input type="hidden" name="billto" value="<?php echo $this->cart->lists['billTo']; ?>"/>
	</div>
    <div class="clear"></div>
</div>-->

<fieldset>
    <?php if($this->cart->getDataValidated()) { ?>
    <span class="didact" style="font-weight:bold; font-size: 14px; color:#1D70B7;">Проверьте правильность введённой информации и подтвердите заказ</span>
    <br/><br/>
    <?php } ?>
	<table
		class="cart-summary"
		cellspacing="0"
		cellpadding="0"
		border="0"
		width="100%">
		<tr>
			<th align="left" style="padding-left:20px;" class="didact">Наименование товара</th>
			<th class="didact" align="left" width="70px" ><?php echo JText::_('COM_VIRTUEMART_CART_SKU') ?></th>
			<th class="didact" align="left" width="60px">Цена</th>
            <th class="didact" align="left" style="padding-left:20px;" width="90px">Скидка</th>
			<th class="didact" align="left" style="padding-left:20px; width:90px;" width="90px"><?php echo JText::_('COM_VIRTUEMART_CART_QUANTITY') ?></th>
            <?php if ( VmConfig::get('show_tax')) { ?>
                                <!--<th align="right" width="60px"><?php  echo "<span  class='priceColor2'>".JText::_('COM_VIRTUEMART_CART_SUBTOTAL_TAX_AMOUNT') ?></th>-->
				<?php } ?>
                                <!--<th align="right" width="60px"><?php echo "<span  class='priceColor2'>".JText::_('COM_VIRTUEMART_CART_SUBTOTAL_DISCOUNT_AMOUNT') ?></th>-->
				<th class="didact" align="left" style="padding-left:20px; padding-right:10px;" width="70px">Стоимость</th>
			</tr>
		<?php
		$i=1;
		foreach( $this->cart->products as $pkey =>$prow ) { ?>
			<tr valign="top" class="sectiontableentry<?php echo $i ?>">
				<td align="left" style="padding:15px;">
					<?php if ( $prow->virtuemart_media_id) {  ?>
						<div class="cart-images" style="float:left; margin-right: 15px;">
						 <?php
						 if(!empty($prow->image)) echo $prow->image->displayMediaThumb('',false);
						 ?>
						</div>
					<?php } ?>
					<?php echo JHTML::link($prow->url, $prow->product_name).$prow->customfields; ?>

				</td>
				<td align="left" style="padding-top:20px;"><?php  echo $prow->product_sku ?></td>
				<td align="center" style="padding-top:20px;">
				<?php
// 					vmdebug('$this->cart->pricesUnformatted[$pkey]',$this->cart->pricesUnformatted[$pkey]['priceBeforeTax']);
					echo $this->currencyDisplay->createPriceDiv('basePrice','', $this->cart->pricesUnformatted[$pkey],false);
// 					echo $prow->salesPrice ;
					?>
				</td>
                <td align="center" style="padding-top:20px;">
                    <?php echo "<span class='priceColor2'>".$this->currencyDisplay->createPriceDiv('discountAmount','', $this->cart->pricesUnformatted[$pkey],false,false,1)."</span>" ?>
                </td>
				<td style="padding-top:20px;" align="right" ><form action="<?php echo JRoute::_('index.php'); ?>" method="post" class="inline">

				<input type="text" title="<?php echo  JText::_('COM_VIRTUEMART_CART_UPDATE') ?>" class="inputbox" size="3" maxlength="4" name="quantity" value="<?php echo $prow->quantity ?>" />
				<input type="submit" class="vmicon vm2-add_quantity_cart" name="update" title="<?php echo  JText::_('COM_VIRTUEMART_CART_UPDATE') ?>" align="middle" value=" "/>
                <input type="hidden" name="option" value="com_virtuemart" />
                <input type="hidden" name="view" value="cart" />
                <input type="hidden" name="task" value="update" />
                <input type="hidden" name="cart_virtuemart_product_id" value="<?php echo $prow->cart_item_id  ?>" />
			  </form>
					<a class="vmicon vm2-remove_from_cart" title="<?php echo JText::_('COM_VIRTUEMART_CART_DELETE') ?>" align="middle" href="<?php echo JRoute::_('index.php?option=com_virtuemart&view=cart&task=delete&cart_virtuemart_product_id='.$prow->cart_item_id  ) ?>"> </a>
				</td>
				<td style="padding-top:20px;" colspan="1" align="right">
				<?php
				if (VmConfig::get('checkout_show_origprice',1) && !empty($this->cart->pricesUnformatted[$pkey]['basePriceWithTax']) && $this->cart->pricesUnformatted[$pkey]['basePriceWithTax'] != $this->cart->pricesUnformatted[$pkey]['salesPrice'] ) {
					echo '<span class="line-through">'.$this->currencyDisplay->createPriceDiv('basePriceWithTax','', $this->cart->pricesUnformatted[$pkey],true,false,$prow->quantity) .'</span><br />' ;
				}
				echo $this->currencyDisplay->createPriceDiv('salesPrice','', $this->cart->pricesUnformatted[$pkey],false,false,$prow->quantity) ?>

                </td>
			</tr>
		<?php
			$i = 1 ? 2 : 1;
		} ?>
		<!--Begin of SubTotal, Tax, Shipment, Coupon Discount and Total listing -->
                  <?php if ( VmConfig::get('show_tax')) { $colspan=3; } else { $colspan=2; } ?>
		<tr>
			<td colspan="5">&nbsp;</td>

			<td colspan="<?php echo $colspan-2 ?>"><!--hr /--></td>
		</tr>
		  <tr class="sectiontableentry1">
            <td colspan="3">
            </td>
            <td colspan="2" align="left" style="font-size:15px;">
                <b class="didact">Сумма заказа:</b>
            </td>
			<td colspan="" align="right">
                <b class="didact"><?php echo $this->currencyDisplay->createPriceDiv('salesPrice','', $this->cart->pricesUnformatted,false) ?></b>
            </td>
		  </tr>
		<?php foreach($this->cart->cartData['DBTaxRulesBill'] as $rule){ ?>
			<tr class="sectiontableentry<?php $i ?>">
				<td colspan="4" align="right"><?php echo $rule['calc_name'] ?> </td>

                                   <?php if ( VmConfig::get('show_tax')) { ?>
				<!--<td align="right"> </td>-->
                                <?php } ?>
				<!--<td align="right"><?php echo $this->currencyDisplay->createPriceDiv($rule['virtuemart_calc_id'].'Diff','', $this->cart->pricesUnformatted[$rule['virtuemart_calc_id'].'Diff'],false); ?></td>-->
				<td align="right"><?php echo $this->currencyDisplay->createPriceDiv($rule['virtuemart_calc_id'].'Diff','', $this->cart->pricesUnformatted[$rule['virtuemart_calc_id'].'Diff'],false); ?> </td>
			</tr>
			<?php
			if($i) $i=1; else $i=0;
		} ?>

		<?php

		foreach($this->cart->cartData['taxRulesBill'] as $rule){ ?>
			<tr class="sectiontableentry<?php $i ?>">
				<td colspan="4" align="right"><?php echo $rule['calc_name'] ?> </td>
				<?php if ( VmConfig::get('show_tax')) { ?>
				<!--<td align="right"><?php echo $this->currencyDisplay->createPriceDiv($rule['virtuemart_calc_id'].'Diff','', $this->cart->pricesUnformatted[$rule['virtuemart_calc_id'].'Diff'],false); ?> </td>
				 <?php } ?>
				<td align="right"><?php ?> </td>-->
				<td align="right"><?php echo $this->currencyDisplay->createPriceDiv($rule['virtuemart_calc_id'].'Diff','', $this->cart->pricesUnformatted[$rule['virtuemart_calc_id'].'Diff'],false); ?> </td>
			</tr>
			<?php
			if($i) $i=1; else $i=0;
		}

		foreach($this->cart->cartData['DATaxRulesBill'] as $rule){ ?>
			<tr class="sectiontableentry<?php $i ?>">
				<td colspan="4" align="right"><?php echo   $rule['calc_name'] ?> </td>

                                     <?php if ( VmConfig::get('show_tax')) { ?>
				<!--<td align="right"> </td>

                                <?php } ?>
				<td align="right"><?php echo $this->currencyDisplay->createPriceDiv($rule['virtuemart_calc_id'].'Diff','', $this->cart->pricesUnformatted[$rule['virtuemart_calc_id'].'Diff'],false); ?>  </td>-->
				<td align="right"><?php echo $this->currencyDisplay->createPriceDiv($rule['virtuemart_calc_id'].'Diff','', $this->cart->pricesUnformatted[$rule['virtuemart_calc_id'].'Diff'],false); ?> </td>
			</tr>
			<?php
			if($i) $i=1; else $i=0;
		} ?>

	<tr class="sectiontableentry1">
        <td colspan="3" align="left" style="padding-top:10px;">
				<?php echo $this->cart->cartData['shipmentName']; ?>
        </td>
        <td colspan="2" align="left" style="padding-top:10px;">
            <a class="current_town didact" href="javascript:" onclick="javascript: jQuery('#shipmentrow').toggle();">Выбрать способ доставки</a>
        </td>
        <td align="right" style="padding-top:10px;"><?php echo $this->currencyDisplay->createPriceDiv('salesPriceShipment','', $this->cart->pricesUnformatted['salesPriceShipment'],false); ?> </td>
    </tr>
    <tr id="shipmentrow" style="display:none;">
        <td colspan="6">
            <form method="post" id="userForm" name="chooseShipmentRate" action="<?php echo JRoute::_('index.php'); ?>" class="form-validate" style="margin-bottom:15px;">
                <?php
                if ($this->found_shipment_method) {
                    echo "<fieldset style='margin-top: 0px; padding-top: 10px;'>\n";
                    foreach ($this->shipments_shipment_rates as $shipment_shipment_rates) {
                        if (is_array($shipment_shipment_rates)) {
                            foreach ($shipment_shipment_rates as $shipment_shipment_rate) {
                                echo $shipment_shipment_rate."<br />\n";
                            }
                        }
                    }
                    echo "</fieldset>\n";
                }
                ?>
                <input type="hidden" name="option" value="com_virtuemart" />
                <input type="hidden" name="view" value="cart" />
                <input type="hidden" name="task" value="setshipment" />
                <input type="hidden" name="controller" value="cart" />
                <button class="addtocart-button" type="submit" ><?php echo JText::_('COM_VIRTUEMART_SAVE'); ?></button>  &nbsp;
            </form>
        </td>
    </tr>
    <tr class="sectiontableentry1">
        <td colspan="3" align="left" style="padding-top:8px;">
		    <?php echo $this->cart->cartData['paymentName']; ?>
        </td>
        <td colspan="2" style="padding-top:10px;">
            <a class="current_town didact" href="javascript:" onclick="javascript: jQuery('#paymenntrow').toggle();">Выбрать способ оплаты</a>
        </td>
        <td  style="padding-top:10px;" align="right"><?php  echo $this->currencyDisplay->createPriceDiv('salesPricePayment','', $this->cart->pricesUnformatted['salesPricePayment'],false); ?> </td>
    </tr>
    <tr id="paymenntrow" style="display:none;">
        <td colspan="6">
            <form method="post" id="paymentForm" name="choosePaymentRate" action="<?php echo JRoute::_('index.php'); ?>" class="form-validate">
                <?php
                if ($this->found_payment_method) {
                    echo "<fieldset style='margin-top: 0px; padding-top: 10px;'>";
                    foreach ($this->paymentplugins_payments as $paymentplugin_payments) {
                        if (is_array($paymentplugin_payments)) {
                            foreach ($paymentplugin_payments as $paymentplugin_payment) {
                                echo $paymentplugin_payment.'<br />';
                            }
                        }
                    }
                    echo "</fieldset>";
                }
                ?>
                <p>
                    При выборе пункта "Оплата через onpay.ru" вы можете оплатить продукцию следующими способами:<br/><br/>
                    <img style="width:28px;" src="/images/icons/coins.png" alt="" title="Наличная оплата" />
                    <img style="width:28px;" src="/images/icons/webmoney.png" alt="" title="WebMoney" />
                    <img style="width:28px;" src="/images/icons/paypal.png" alt="" title="PayPal" />
                    <img style="width:32px;" src="/images/icons/moneymail.png" alt="" title="MoneyMail" />
                    <img style="width:28px;" src="/images/icons/rbk.png" alt="" title="RBKmoney.ru" />
                    <img style="width:28px;" src="/images/icons/yandex.png" alt="" title="Yandex.Деньги" />
                    <img style="width:32px;" src="/images/icons/bank.png" alt="" title="Банковский перевод" />
                    <img style="width:85px;" src="/images/icons/qiwi.png" alt="" title="Терминалы оплаты Qiwi" />
                    <img style="width:28px;" src="/images/icons/eleksnet.jpg" alt="" title="Терминалы Элекснет" />
                    <img style="width:28px;" src="/images/icons/mts.png" alt="" title="Салоны МТС" />
                    <img style="width:73px;" src="/images/icons/evroset.png" alt="" title="Салоны Евросеть" />
                    <img style="width:130px;" src="/images/icons/svaznoy.png" alt="" title="Салоны Связной" />
                    <img style="width:100px;" src="/images/icons/visa.png" alt="" title="Viza Mastercard" />
                </p>
                <br/>
                <br/>
                <input type="hidden" name="option" value="com_virtuemart" />
                <input type="hidden" name="view" value="cart" />
                <input type="hidden" name="task" value="setpayment" />
                <input type="hidden" name="controller" value="cart" />
                <button class="addtocart-button" type="submit"><?php echo JText::_('COM_VIRTUEMART_SAVE'); ?></button>
            </form>
        </td>
    </tr>
    <tr>
        <td colspan="6" style="padding: 10px 0px;"><hr /></td>
    </tr>
    <tr class="sectiontableentry2">
        <td colspan="3"></td>
        <td colspan="2"  class="didact" align="left" style="font-size:15px;"><b>Итого к оплате:</b></td>
        <td colspan="1" class="didact" align="right"><strong><?php echo $this->currencyDisplay->createPriceDiv('billTotal','', $this->cart->pricesUnformatted['billTotal'],false); ?></strong></td>
    </tr>
		    <?php
		    if ( $this->totalInPaymentCurrency) {
			?>

    <tr class="sectiontableentry2">
        <td colspan="4" align="right"><?php echo JText::_('COM_VIRTUEMART_CART_TOTAL_PAYMENT') ?>: </td>
            <?php if ( VmConfig::get('show_tax')) { ?>
        <td align="right">  </td>
		    <?php } ?>
        <td align="right">  </td>
        <td align="right"><strong><?php echo $this->totalInPaymentCurrency;   ?></strong></td>
    </tr>
            <?php } ?>
	</table>
</fieldset>