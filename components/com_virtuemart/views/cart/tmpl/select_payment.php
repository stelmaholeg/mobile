<?php
/**
 *
 * Layout for the payment selection
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
 * @version $Id: select_payment.php 5451 2012-02-15 22:40:08Z alatak $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

?>

<?php
if (VmConfig::get('oncheckout_show_steps', 1)) {
    //echo '<div class="checkoutStep" id="checkoutStep3">' . JText::_('COM_VIRTUEMART_USER_FORM_CART_STEP3') . '</div>';
}
?>
<form method="post" id="paymentForm" name="choosePaymentRate" action="<?php echo JRoute::_('index.php'); ?>" class="form-validate">
<?php
	echo "<h1>".JText::_('COM_VIRTUEMART_CART_SELECT_PAYMENT')."</h1>";
	if($this->cart->getInCheckOut()){
		$buttonclass = 'button vm-button-correct';
	} else {
		$buttonclass = 'default';
	}
?>
<?php
     if ($this->found_payment_method) {


    echo "<fieldset>";
		foreach ($this->paymentplugins_payments as $paymentplugin_payments) {
		    if (is_array($paymentplugin_payments)) {
			foreach ($paymentplugin_payments as $paymentplugin_payment) {
			    echo $paymentplugin_payment.'<br />';
			}
		    }
		}
    echo "</fieldset>";

    } else {
	 echo "<h1>".$this->payment_not_found_text."</h1>";
    } ?>

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

    <button class="addtocart-button" type="submit"><?php echo JText::_('COM_VIRTUEMART_SAVE'); ?></button>
    &nbsp;
    <button class="addtocart-button" type="reset" onClick="window.location.href='<?php echo JRoute::_('index.php?option=com_virtuemart&view=cart'); ?>'" ><?php echo JText::_('COM_VIRTUEMART_CANCEL'); ?></button>

    <input type="hidden" name="option" value="com_virtuemart" />
    <input type="hidden" name="view" value="cart" />
    <input type="hidden" name="task" value="setpayment" />
    <input type="hidden" name="controller" value="cart" />
</form>