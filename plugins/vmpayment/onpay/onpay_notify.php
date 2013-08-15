<?
/** 
Автор: Стеапнов Максим, Медиацех
Файл: Обмен данными с платежной системой
**/
    error_reporting (0);
	// Тысяча индусов! Да будут принесены они в жертву во имя великого JURI::getInstance()
	$_SERVER['REQUEST_URI']='';
	$_SERVER['SCRIPT_NAME']='';
	$_SERVER['QUERY_STRING']='';
	define('_JEXEC', 1);
	define('DS', DIRECTORY_SEPARATOR);
	$option='com_virtuemart';
	$my_path = dirname(__FILE__);
	$my_path = explode(DS.'plugins',$my_path);	
	$my_path = $my_path[0];			
	if (file_exists($my_path . '/defines.php')) {
		include_once $my_path . '/defines.php';
		}
	if (!defined('_JDEFINES')) {
		define('JPATH_BASE', $my_path);
	require_once JPATH_BASE.'/includes/defines.php';
		}
	define('JPATH_COMPONENT',				JPATH_BASE . '/components/' . $option);
	define('JPATH_COMPONENT_SITE',			JPATH_SITE . '/components/' . $option);
	define('JPATH_COMPONENT_ADMINISTRATOR',	JPATH_ADMINISTRATOR . '/components/' . $option);	
	require_once JPATH_BASE.'/includes/framework.php';
	$app = JFactory::getApplication('site');
	$app->initialise();
	if (!class_exists( 'VmConfig' )) require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart'.DS.'helpers'.DS.'config.php');
	VmConfig::loadConfig();
	if (!class_exists('VirtueMartModelOrders'))
		require( JPATH_VM_ADMINISTRATOR . DS . 'models' . DS . 'orders.php' );			
	if (!class_exists('plgVmPaymentOnpay'))
		require(dirname(__FILE__). DS . 'onpay.php');					
	
/*======================= Необходимо прописать свои параметры =========================*/

function get_constant($name,$order_id) {
	$order = new VirtueMartModelOrders();	
	$method = new plgVmPaymentOnpay($subject, $config);
	$orderitems = $order->getOrder($order_id);	
	$methoditems = $method->__getVmPluginMethod($orderitems['details']['BT']->virtuemart_paymentmethod_id);
	return $methoditems->ONPAY_SECRET_KEY;
}



// функция определения параметров платежной формы
// к примеру, если необходимо добавить e-mail пользователя, который совершает платеж, то
// добавляется строка к результату '&user_email=vasia@mail.ru'
function get_iframe_url_params($operation_id, $sum, $md5check) {
	return "pay_mode=fix&pay_for=$operation_id&price=$sum&currency=RUR&convert=yes&md5=$md5check&url_success=".get_constant('url_success');
}



// функция выборки неоплаченной операции по ID
function data_get_created_operation($order_id) {	
	$order = new VirtueMartModelOrders();	
	$method = new plgVmPaymentOnpay($subject, $config);
	$orderitems = $order->getOrder($order_id);	
	$methoditems = $method->__getVmPluginMethod($orderitems['details']['BT']->virtuemart_paymentmethod_id);
	$method->getPaymentCurrency($methoditems);
	if(($orderitems['details']['BT']->virtuemart_order_id)&&($orderitems['details']['BT']->order_status==$methoditems->status_pending)) return array(true,$orderitems['details']['BT']); 
	return array(false,$orderitems['details']['BT']);
}

// функция обновления статуса операции на оплаченную
function data_set_operation_processed($order_id) {
	$method = new plgVmPaymentOnpay();
	$modelOrder = new VirtueMartModelOrders();
	$orderitems = $modelOrder->getOrder($order_id);		
	$methoditems = $method->__getVmPluginMethod($orderitems['details']['BT']->virtuemart_paymentmethod_id);
	$orderitems['order_status'] = $methoditems->status_success;
	$orderitems['customer_notified'] = 0;
	$orderitems['virtuemart_order_id'] = $order_id;
	$orderitems['comments'] = 'Onpay ID: '.$order_id;
	return $modelOrder->updateStatusForOneOrder($order_id, $orderitems, true);
}


/*==================================== Конец ==========================================*/

//функция проебразует число в число с плавающей точкой 
function to_float($sum) { 
  if (strpos($sum, ".")) {
		$sum = round($sum, 2);
	} else {
		$sum = $sum.".0";
	} 
  return $sum; 
}

//функция выдает ответ для сервиса onpay в формате XML на чек запрос 
function answer($type, $code, $pay_for, $order_amount, $order_currency, $text) { 
  $md5 = strtoupper(md5("$type;$pay_for;$order_amount;$order_currency;$code;".get_constant('ONPAY_SECRET_KEY',$pay_for))); 
  return "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<result>\n<code>$code</code>\n<pay_for>$pay_for</pay_for>\n<comment>$text</comment>\n<md5>$md5</md5>\n</result>";
} 

//функция выдает ответ для сервиса onpay в формате XML на pay запрос 
function answerpay($type, $code, $pay_for, $order_amount, $order_currency, $text, $onpay_id) { 
  $md5 = strtoupper(md5("$type;$pay_for;$onpay_id;$pay_for;$order_amount;$order_currency;$code;".get_constant('ONPAY_SECRET_KEY',$pay_for))); 
  return "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<result>\n<code>$code</code>\n<comment>$text</comment>\n<onpay_id>$onpay_id</onpay_id>\n<pay_for>$pay_for</pay_for>\n<order_id>$pay_for</order_id>\n<md5>$md5</md5>\n</result>"; 
}



function process_api_request() {
	$rezult = ''; 
	$error = ''; 
	//проверяем чек запрос 
	if ($_REQUEST['type'] == 'check') { 
	    //получаем данные, что нам прислал чек запрос 
	    $order_amount 	= $_REQUEST['order_amount']; 
	    $order_currency = $_REQUEST['order_currency']; 
	    $pay_for 				= $_REQUEST['pay_for']; 
	    $md5 						= $_REQUEST['md5']; 
	    $sum = floatval ( $order_amount );
		//выдаем ответ OK на чек запрос 
	    
		$rezult = data_get_created_operation($pay_for);
		if (($rezult[0])&&($rezult[1]->order_total>=$sum)) $rezult = answer($_REQUEST['type'],0, $pay_for, $order_amount, $order_currency, 'OK'); 
			else $rezult = answer ($_REQUEST['type'],2, $pay_for, $order_amount, $order_currency, 'Error order_id:' . $pay_for . ' in order_id!=order_id, order_sum>sum or order_status!=P' );
	} 

	//проверяем запрос на пополнение 
	if ($_REQUEST['type'] == 'pay') { 
	    $onpay_id 					= $_REQUEST['onpay_id']; 
	    $pay_for 						= $_REQUEST['pay_for']; 
	    $order_amount 			= $_REQUEST['order_amount']; 
	    $order_currency			= $_REQUEST['order_currency']; 
	    $balance_amount 		= $_REQUEST['balance_amount']; 
	    $balance_currency 	= $_REQUEST['balance_currency']; 
	    $exchange_rate 			= $_REQUEST['exchange_rate']; 
	    $paymentDateTime 		= $_REQUEST['paymentDateTime']; 
	    $md5 								=  $_REQUEST['md5']; 
	    //производим проверки входных данных 
	    if (empty($onpay_id)) {$error .="Не указан id<br>";} 
	    else {if (!is_numeric(intval($onpay_id))) {$error .="Параметр не является числом<br>";}} 
	    if (empty($order_amount)) {$error .="Не указана сумма<br>";} 
	    else {if (!is_numeric($order_amount)) {$error .="Параметр не является числом<br>";}} 
	    if (empty($balance_amount)) {$error .="Не указана сумма<br>";} 
	    else {if (!is_numeric(intval($balance_amount))) {$error .="Параметр не является числом<br>";}} 
	    if (empty($balance_currency)) {$error .="Не указана валюта<br>";} 
	    else {if (strlen($balance_currency)>4) {$error .="Параметр слишком длинный<br>";}} 
	    if (empty($order_currency)) {$error .="Не указана валюта<br>";} 
	    else {if (strlen($order_currency)>4) {$error .="Параметр слишком длинный<br>";}} 
	    if (empty($exchange_rate)) {$error .="Не указана сумма<br>";} 
	    else {if (!is_numeric($exchange_rate)) {$error .="Параметр не является числом<br>";}} 
	
	    //если нет ошибок 
			if (!$error) { 
				if (is_numeric($pay_for)) {
					//Если pay_for - число 
					$sum = floatval($order_amount); 
					$rezult = data_get_created_operation($pay_for);
					if (($rezult[0])&&($rezult[1]->order_total<=$sum)) { 
						//создаем строку хэша с присланных данных 
						$md5fb = strtoupper(md5($_REQUEST['type'].";".$pay_for.";".$onpay_id.";".$order_amount.";".$order_currency.";".get_constant('ONPAY_SECRET_KEY',$pay_for))); 
						//сверяем строчки хеша (присланную и созданную нами) 
						if ($md5fb != $md5) {
							$rezult = answerpay($_REQUEST['type'], 8, $pay_for, $order_amount, $order_currency, 'Md5 signature is wrong. Expected '.$md5fb, $onpay_id);
						} else { 
							$time = time(); 
							$rezult_operation = data_set_operation_processed($pay_for);
							//если оба запроса прошли успешно выдаем ответ об удаче, если нет, то о том что операция не произошла 
							if ($rezult_operation) {
								$rezult = answerpay($_REQUEST['type'], 0, $pay_for, $order_amount, $order_currency, 'OK', $onpay_id);
							} else {
								$rezult = answerpay($_REQUEST['type'], 9, $pay_for, $order_amount, $order_currency, 'Error in mechant database queries: operation tables error', $onpay_id);
							} 
						}
					} else {
						$rezult = answerpay($_REQUEST['type'], 10, $pay_for, $order_amount, $order_currency, 'Cannot find any pay rows acording to this parameters: wrong payment', $onpay_id);
					} 
				} else {
					//Если pay_for - не правильный формат 
					$rezult = answerpay($_REQUEST['type'], 11, $pay_for, $order_amount, $order_currency, 'Error in parameters data', $onpay_id); 
				} 
			} else {
				//Если есть ошибки 
				$rezult = answerpay($_REQUEST['type'], 12, $pay_for, $order_amount, $order_currency, 'Error in parameters data: '.$error, $onpay_id); 
			} 
	} 
	return $rezult;
}
if (isset ( $_REQUEST ['type'] )) echo process_api_request();



?>
