<?php
/**
*
* Order items view
*
* @package	VirtueMart
* @subpackage Orders
* @author Oscar van Eijk, Valerie Isaksen
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: details_items.php 5836 2012-04-09 13:13:21Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if($this->format == 'pdf'){
	$widthTable = '100';
	$widtTitle = '27';
} else {
	$widthTable = '100';
	$widtTitle = '49';
}
?>
<table width="<?php echo $widthTable ?>%" cellspacing="0" cellpadding="0" border="1" style="font-size:12px;">
	<tr align="left" class="sectiontableheader">
		<th align="left">Наименование изделия</th>
        <th align="left">Артикул</th>
        <th align="left" width="5%">Количество</th>
        <th align="right" width="10%" >Цена</th>
        <th align="right" width="10%" >Сумма</th>
    </tr>
<?php
	foreach($this->orderdetails['items'] as $item) {
		$qtt = $item->product_quantity;
		$_link = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_category_id=' . $item->virtuemart_category_id . '&virtuemart_product_id=' . $item->virtuemart_product_id);
?>
		<tr valign="top">
			<td align="left">
				<a href="<?php echo $_link; ?>"><?php echo $item->order_item_name; ?></a>
			</td>
            <td align="left">
                <?php echo $item->order_item_sku; ?>
            </td>
			<td align="right" >
				<?php echo $qtt; ?>
			</td>
            <td align="right"   class="priceCol" >
                <?php echo '<span >'.$this->currency->priceDisplay($item->product_item_price) .'</span><br />'; ?>
				<?php echo  $this->currency->priceDisplay( $item->product_subtotal_discount );  //No quantity is already stored with it ?>
			</td>
			<td align="right"  class="priceCol">
				<?php
				$item->product_basePriceWithTax = (float) $item->product_basePriceWithTax;
				$class = '';
				if(!empty($item->product_basePriceWithTax) && $item->product_basePriceWithTax != $item->product_final_price ) {
					echo '<span class="line-through" >'.$this->currency->priceDisplay($item->product_basePriceWithTax,0,$qtt) .'</span><br />' ;
				}
				echo $this->currency->priceDisplay(  $item->product_subtotal_with_tax ,0); //No quantity or you must use product_final_price ?>
			</td>
		</tr>
<?php
	}
?>
 <tr class="sectiontableentry1">
			<td colspan="4" align="right"><?php echo JText::_('COM_VIRTUEMART_ORDER_PRINT_PRODUCT_PRICES_TOTAL'); ?></td>
			<td align="right"><?php echo $this->currency->priceDisplay($this->orderdetails['details']['BT']->order_salesPrice) ?></td>
		  </tr>
<!--
<?php
if ($this->orderdetails['details']['BT']->coupon_discount <> 0.00) {
    $coupon_code=$this->orderdetails['details']['BT']->coupon_code?' ('.$this->orderdetails['details']['BT']->coupon_code.')':'';
	?>
	<tr>
		<td align="right" class="pricePad" colspan="5"><?php echo JText::_('COM_VIRTUEMART_COUPON_DISCOUNT').$coupon_code ?></td>
			<td align="right">&nbsp;</td>

			<?php if ( VmConfig::get('show_tax')) { ?>
				<td align="right">&nbsp;</td>
                                <?php } ?>
		<td align="right"><?php echo '- '.$this->currency->priceDisplay($this->orderdetails['details']['BT']->coupon_discount); ?></td>
		<td align="right">&nbsp;</td>
	</tr>
<?php  } ?>
-->

	<?php
		foreach($this->orderdetails['calc_rules'] as $rule){
			if ($rule->calc_kind== 'DBTaxRulesBill') { ?>
			<tr >
				<td colspan="6"  align="right" class="pricePad"><?php echo $rule->calc_rule_name ?> </td>

                                   <?php if ( VmConfig::get('show_tax')) { ?>
				<td align="right"> </td>
                                <?php } ?>
				<td align="right"> <?php echo  $this->currency->priceDisplay($rule->calc_amount);  ?></td>
				<td align="right"><?php echo  $this->currency->priceDisplay($rule->calc_amount);  ?> </td>
			</tr>
			<?php
			} elseif ($rule->calc_kind == 'taxRulesBill') { ?>
			<tr >
				<td colspan="6"  align="right" class="pricePad"><?php echo $rule->calc_rule_name ?> </td>
				<?php if ( VmConfig::get('show_tax')) { ?>
				<td align="right"><?php echo $this->currency->priceDisplay($rule->calc_amount); ?> </td>
				 <?php } ?>
				<td align="right"><?php    ?> </td>
				<td align="right"><?php echo $this->currency->priceDisplay($rule->calc_amount);   ?> </td>
			</tr>
			<?php
			 } elseif ($rule->calc_kind == 'DATaxRulesBill') { ?>
			<tr >
				<td colspan="6"   align="right" class="pricePad"><?php echo $rule->calc_rule_name ?> </td>
				<?php if ( VmConfig::get('show_tax')) { ?>
				<td align="right"> </td>
				 <?php } ?>
				<td align="right"><?php  echo   $this->currency->priceDisplay($rule->calc_amount);  ?> </td>
				<td align="right"><?php echo $this->currency->priceDisplay($rule->calc_amount);  ?> </td>
			</tr>

			<?php
			 }

		}
		?>
    <tr>
        <td align="right" class="pricePad" colspan="4"><?php echo JText::_('COM_VIRTUEMART_ORDER_PRINT_SHIPPING') ?></td>
        <td align="right"><?php echo $this->currency->priceDisplay($this->orderdetails['details']['BT']->order_shipment+ $this->orderdetails['details']['BT']->order_shipment_tax); ?></td>
    </tr>
    <tr>
		<td align="right" class="pricePad" colspan="4"><?php echo JText::_('COM_VIRTUEMART_ORDER_PRINT_PAYMENT') ?></td>
		<td align="right"><?php echo $this->currency->priceDisplay($this->orderdetails['details']['BT']->order_payment+ $this->orderdetails['details']['BT']->order_payment_tax); ?></td>
	</tr>
	<tr>
		<td align="right" class="pricePad" colspan="4"><strong><?php echo JText::_('COM_VIRTUEMART_ORDER_PRINT_TOTAL') ?></strong></td>
		<td align="right"><strong><?php echo $this->currency->priceDisplay($this->orderdetails['details']['BT']->order_total); ?></strong></td>
	</tr>
</table>
<table width="<?php echo $widthTable ?>%" cellspacing="0" cellpadding="0" style="font-size:10px;">
    <tr style="height:20px;"><td colspan="5"></td></tr>
    <tr>
        <td>Сумма прописью</td>
        <td colspan="4" style="border-bottom:solid 1px #000000;"></td>
    </tr>
    <tr style="height:20px;"><td colspan="5"></td></tr>
    <tr>
        <td>Оплатил заказчик</td>
        <td style="border-bottom:solid 1px #000000;"></td>
        <td style="padding-left:20px;">Принял кассир</td>
        <td colspan="2" style="border-bottom:solid 1px #000000;"></td>
    </tr>
    <tr>
        <td></td>
        <td align="center">(Ф.И.О., подпись)</td>
        <td></td>
        <td align="center">(Ф.И.О., подпись)</td>
        <td align="center">М.П.</td>
    </tr>
    <tr style="height:20px;"><td colspan="5"></td></tr>
    <tr>
        <td>Дата оплаты</td>
        <td>"_____" ________________ 20___ г.</td>
        <td colspan="3"></td>
    </tr>
</table>

