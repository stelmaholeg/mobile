<?php
/**
*
* Order detail view
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
* @version $Id: details.php 5825 2012-04-07 20:06:06Z electrocity $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
JHTML::stylesheet('vmpanels.css', JURI::root().'components/com_virtuemart/assets/css/');
if($this->print){
	?>
		<body onload="javascript:print();">
        <style type="text/css">
            span{
                font-size:9px;
                line-height: 10px;
            }
        </style>
        <span>"MOBILE 26"</span><br/>
        <span>Интернет магазин цифровой техники</span><br/>
        <span>ИП Абраменко А.Д.</span><br/>
        <span>ИНН 263602916706</span><br/>
        <span>ОГРНИП 311265127700521 от 04.10.2011</span><br/>
        <span>Ставропольский край, г.Ставрополь, ул. К.Маркса д.76, ТЦ "Звезда"</span><br/>
        <span>Тел: 8 (962) 440-80-04</span><br/>
        <span>Ростовская область, г.Таганрог, ул.Александровская д.174</span><br/>
        <span>Тел: 8 (928) 226-76-81, 226-76-81</span><br/>



        <div class='spaceStyle'>
		<?php
		echo $this->loadTemplate('order');
		?>
		</div>

		<div class='spaceStyle'>
		<?php
		echo $this->loadTemplate('items');
		?>
		</div>
		<?php	echo $this->vendor->vendor_legal_info; ?>
		</body>
		<?php
} else {

	?>
	<h1><?php echo JText::_('COM_VIRTUEMART_ACC_ORDER_INFO'); ?>

	<?php

	/* Print view URL */
    $details_url = juri::root().'index.php?option=com_virtuemart&view=orders&layout=details&tmpl=component&order_pass='.$this->orderdetails['details']['BT']->order_pass.'&order_number='.$this->orderdetails['details']['BT']->order_number;

	$details_link = "<a href=\"javascript:void window.open('$details_url', 'win2', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no');\"  >";
	//$details_link .= '<span class="hasTip print_32" title="' . JText::_('COM_VIRTUEMART_PRINT') . '">&nbsp;</span></a>';
	$button = (JVM_VERSION==1) ? '/images/M_images/printButton.png' : 'system/printButton.png';
	$details_link .= JHtml::_('image',$button, JText::_('COM_VIRTUEMART_PRINT'), NULL, true);
	$details_link  .=  '</a>';
	echo $details_link; ?>
</h1>
<?php if($this->order_list_link){ ?>
	<div class='spaceStyle'>
	    <div class="floatright">
		<a href="<?php echo $this->order_list_link ?>"><?php echo JText::_('COM_VIRTUEMART_ORDERS_VIEW_DEFAULT_TITLE'); ?></a>
	    </div>
	    <div class="clear"></div>
	</div>
<?php }?>
<div class='spaceStyle'>
	<?php
	echo $this->loadTemplate('order');
	?>
	</div>

	<div class='spaceStyle'>
	<?php

	$tabarray = array();

	$tabarray['items'] = 'COM_VIRTUEMART_ORDER_ITEM';
	$tabarray['history'] = 'COM_VIRTUEMART_ORDER_HISTORY';

	shopFunctionsF::buildTabs ($tabarray); ?>
	 </div>
	    <br clear="all"/><br/>
	<?php
}

?>






