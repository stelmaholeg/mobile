<?php
/**
 * Print orderdetails
 *
 * @package	VirtueMart
 * @subpackage Orders
 * @author Oscar van Eijk
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: order_print.php 5820 2012-04-06 19:14:38Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
 echo 'Wrong call, shouldnt be used, deprecated layout'; return;
?>
<head>
    <?php
    echo '<link rel="stylesheet" href="' . 'templates' . DS . 'system' . DS . 'css' . DS . 'system.css' . '" type="text/css" />' . "\n";
    if (JVM_VERSION === 2) {
	echo '<link rel="stylesheet" href="' . 'templates' . DS . 'bluestork' . DS . 'css' . DS . 'template.css' . '" type="text/css" />' . "\n";
    } else {
	echo '<link rel="stylesheet" href="' . 'templates' . DS . 'khepri' . DS . 'css' . DS . 'template.css' . '" type="text/css" />' . "\n";
    }
    ?>
    <title><?php echo JText::_('COM_VIRTUEMART_ORDER_PRINT_PO_LBL') . ' ' . $this->orderNumber; ?></title>
    <style type="text/css">
        .td-order-item{padding-right:5px;text-align:right}
    </style>  
</head>
<body onload="javascript:print();">
    <table class="adminlist">
	<tr>
	    <td valign="top" width="50%">
		<table class="adminlist">
		    <tr>
			<td><strong><?php echo JText::_('COM_VIRTUEMART_ORDER_PRINT_PO_NUMBER') ?>:</strong></td>
			<td><?php echo $this->orderNumber; ?></td>
		    </tr>
		    <tr>
			<td><strong><?php echo JText::_('COM_VIRTUEMART_ORDER_PRINT_PO_DATE') ?>:</strong></td>
			<td><?php echo vmJsApi::date($this->orderbt->created_on, 'LC2', true); ?></td>
		    </tr>
		    <tr>
			<td><strong><?php echo JText::_('COM_VIRTUEMART_ORDER_PRINT_PO_STATUS') ?>:</strong></td>
			<td><?php echo JText::_($this->orderbt->order_status_name); ?></td>
		    </tr>
<?php if (VmConfig::get('coupons_enable') == '1') { ?>
    		    <tr>
    			<td><strong><?php echo JText::_('COM_VIRTUEMART_COUPON_CODE') ?>:</strong></td>
    			<td><?php echo $this->orderbt->coupon_code; ?></td>
    		    </tr>
<?php } ?>
		</table>
	    </td>
	</tr>
    </table>
    &nbsp;
    <table class="adminlist">
	<tr>
	    <td valign="top">
		<strong><em><?php echo JText::_('COM_VIRTUEMART_ORDER_PRINT_BILL_TO_LBL') ?></em></strong><br/>
		<table style="border:0"><?php
foreach ($this->userfields['fields'] as $_field) {
    if (!empty($_field['value'])) {
	echo '<tr><td class="key">' . $_field['title'] . '</td>'
	. '<td>' . $_field['value'] . '</td></tr>';
    }
}
?></table>
	    </td>
	    <td style="vertical-align:top">
		<strong><em><?php echo JText::_('COM_VIRTUEMART_ORDER_PRINT_SHIP_TO_LBL') ?></em></strong><br/>
		<table style="border:0"><?php
		    foreach ($this->shipmentfields['fields'] as $_field) {
			if (!empty($_field['value'])) {
			    echo '<tr><td class="key">' . $_field['title'] . '</td>'
			    . '<td>' . $_field['value'] . '</td></tr>';
			}
		    }
?></table>
	    </td>
	</tr>
    </table>
    <hr style="width:100%">
    <table class="adminlist" style="width:100%">
	<tr>
	    <td colspan="2">
		<table class="adminlist">
		    <thead>
			<tr>
			    <th class="title" style="width:47px;text-align:left"><?php echo JText::_('COM_VIRTUEMART_ORDER_PRINT_QUANTITY') ?></th>
			    <th class="title" width="*" style="align:left"><?php echo JText::_('COM_VIRTUEMART_ORDER_PRINT_NAME') ?></th>
			    <th class="title" style="width:10%;text-align:left"><?php echo JText::_('COM_VIRTUEMART_ORDER_PRINT_SKU') ?></th>
			    <th class="title" style="width:10%"><?php echo JText::_('COM_VIRTUEMART_ORDER_PRINT_PRODUCT_STATUS') ?></th>
			    <th class="title" style="width:130px"><?php echo JText::_('COM_VIRTUEMART_PRODUCT_FORM_PRICE_NET') ?></th>
			    <th class="title" style="width:130px"><?php echo JText::_('COM_VIRTUEMART_PRODUCT_FORM_PRICE_GROSS') ?></th>
			    <?php if ( VmConfig::get('show_tax')) { ?>
				<th class="title" style="width:130px"><?php echo JText::_('COM_VIRTUEMART_PRODUCT_FORM_PRICE_TAX') ?></th>
                                <?php } ?>
			    <th class="title" style="width:130"><?php echo JText::_('COM_VIRTUEMART_ORDER_PRINT_TOTAL') ?></th>
			</tr>
		    </thead>
<?php foreach ($this->orderdetails['items'] as $item) { ?>
    		    <tr valign="top">
    			<td><?php echo $item->product_quantity; ?></td>
    			<td><?php
		    echo $item->order_item_name;
		    if (!empty($item->product_attribute)) {
			echo '<div>' . $item->product_attribute . '</div>';
		    }
    ?></td>
    			<td><?php echo $item->order_item_sku; ?></td>
    			<td align="center"><?php echo $this->orderstatuslist[$item->order_status]; ?>
    			</td>
    			<td align="right"><?php
			    //echo $item->product_item_price.' price '.$this->currency->priceDisplay($item->product_item_price,'',false);
			    echo $this->currency->priceDisplay($item->product_item_price);
    ?>
    			</td>
    			<td align="right"><?php echo $this->currency->priceDisplay($item->product_final_price); ?>
    			</td>
			<?php if ( VmConfig::get('show_tax')) { ?>
				<td align="right"><?php echo "<span  style='color:gray'>".$this->currency->priceDisplay($item->product_quantity * $item->product_tax) ."</span>" ?></td>
                                <?php } ?>
    			<td align="right"><?php echo $this->currency->priceDisplay($item->product_quantity * $item->product_final_price); ?>
    			</td>
    		    </tr>

<?php } ?>
		<!--/table>
		<table class="adminlist" -->


                        <tr>

				<td style="text-align:right" colspan="4">
				<div style="text-align:right"><strong> <?php echo JText::_('COM_VIRTUEMART_ORDER_PRINT_SUBTOTAL') ?>:
				</strong></div>
				</td>
				<td class="td-order-item"><?php echo $this->currency->priceDisplay($this->orderbt->order_subtotal); ?></td>
				<td class="td-order-item">&nbsp;</td>
				<td class="td-order-item"><?php echo $this->currency->priceDisplay($this->orderbt->order_tax); ?></td>
				<td class="td-order-item" style="width:15%">&nbsp;</td>
			</tr>
			<?php
			/* COUPON DISCOUNT */
			if ($this->orderbt->coupon_discount > 0 || $this->orderbt->coupon_discount < 0) {

					?>
			<tr>
				<td style="text-align:right" colspan="4"><strong><?php echo JText::_('COM_VIRTUEMART_COUPON_DISCOUNT') ?></strong></td>
				<td class="td-order-item">&nbsp;</td>
				<td class="td-order-item">&nbsp;</td>
				<td class="td-order-item">&nbsp;</td>
				<td class="td-order-item"><?php
				echo "- ".$this->currency->priceDisplay($this->orderbt->coupon_discount);  ?></td>
			</tr>
			<?php
				}
			?>
			<tr>
				<td style="text-align:right" colspan="4"><strong><?php echo JText::_('COM_VIRTUEMART_ORDER_PRINT_SHIPPING') ?>:</strong></td>
				<td class="td-order-item"><?php echo $this->currency->priceDisplay($this->orderbt->order_shipment); ?></td>
				<td class="td-order-item">&nbsp;</td>
				<td class="td-order-item"><?php echo $this->currency->priceDisplay($this->orderbt->order_shipment_tax); ?></td>
				<td class="td-order-item"><?php echo $this->currency->priceDisplay($this->orderbt->order_shipment+$this->orderbt->order_shipment_tax); ?></td>

			</tr>
			 <tr>
				<td style="text-align:right" colspan="4"><strong><?php echo JText::_('COM_VIRTUEMART_ORDER_PRINT_PAYMENT') ?>:</strong></td>
				<td class="td-order-item"><?php echo $this->currency->priceDisplay($this->orderbt->order_payment); ?></td>
				<td class="td-order-item">&nbsp;</td>
				<td class="td-order-item"><?php echo $this->currency->priceDisplay($this->orderbt->order_payment_tax); ?></td>
				<td class="td-order-item"><?php echo $this->currency->priceDisplay($this->orderbt->order_payment+$this->orderbt->order_payment_tax); ?></td>

			 </tr>
			<tr>
				<td style="text-align:right" colspan="4"><strong><?php echo JText::_('COM_VIRTUEMART_ORDER_PRINT_TOTAL_TAX') ?>:</strong></td>
				<td class="td-order-item">&nbsp;</td>
				<td class="td-order-item">&nbsp;</td>
				<td class="td-order-item"><?php echo $this->currency->priceDisplay($this->orderbt->order_tax+$this->orderbt->order_payment_tax+$this->orderbt->order_shipment_tax); ?></td>
				<td class="td-order-item">&nbsp;</td>
			</tr>
			<tr>
				<td style="text-align:right" colspan="4"><strong><?php echo JText::_('COM_VIRTUEMART_ORDER_PRINT_TOTAL') ?>:</strong></td>
				<td class="td-order-item">&nbsp;</td>
				<td class="td-order-item">&nbsp;</td>
				<td class="td-order-item">&nbsp;</td>
				<td class="td-order-item"><strong><?php echo $this->currency->priceDisplay($this->orderbt->order_total); ?></strong>
				</td>
			</tr>

		</table>
		    <?php //$ps_order_change_html->html_change_add_item(); ?></td>
	</tr>
    </table>
    &nbsp;
<?php
if(!class_exists('vmPSPlugin')) require(JPATH_VM_PLUGINS.DS.'vmpsplugin.php');
JPluginHelper::importPlugin('vmpayment');
JPluginHelper::importPlugin('vmshipment');

$dispatcher = JDispatcher::getInstance();
?>
    <table width="100%">
	<tr>
	    <td valign="top"><?php
    $returnValues = $dispatcher->trigger('plgVmonShowOrderPrintPayment', array($this->orderNumber, $this->virtuemart_shipmentmethod_id));
    foreach ($returnValues as $returnValue) {
	if ($returnValue !== null) {
	    echo $returnValue;
	}
    }
?>
	    </td>
	    <td valign="top"><?php
		$returnValues = $dispatcher->trigger('plgVmonShowOrderPrintPayment', array( $this->orderNumber , $this->orderbt->virtuemart_paymentmethod_id));
		foreach ($returnValues as $returnValue) {
		    if ($returnValue !== null) {
			echo $returnValue;
		    }
		}
?></td>
	</tr>
	<tr>
	    <!-- Customer Note -->
	    <td valign="top" width="30%" colspan="2">
		<table class="adminlist" cellspacing="0" cellpadding="0">
		    <thead>
			<tr>
			    <th><?php echo JText::_('COM_VIRTUEMART_ORDER_PRINT_CUSTOMER_NOTE') ?></th>
			</tr>
		    </thead>
		    <tr>
			<td valign="top" align="left" width="50%"><?php echo $this->orderbt->customer_note; ?></td>
		    </tr>
		</table>
	    </td>
	</tr>
    </table>

</body>