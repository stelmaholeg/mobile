<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

header('Content-Type: text/html; charset=utf-8');
if($task=='save') {
    $config = $_POST['CONFIG'];
    ksort($config);

    $contents = "<?php\n";
    $contents .= "defined( '_JEXEC' ) or die( 'Restricted access' );\n\n";
    $contents .= '$conf = array ('."\n";
    $keys = array_keys($config);
    for($i=0,$n=count($keys);$i<$n;$i++) {
        if (!ini_get('magic_quotes_gpc')) {
            $config[$keys[$i]] = addslashes($config[$keys[$i]]);
        }
        $contents .=  '\''. $keys[$i] . '\' => \'' . $config[$keys[$i]] . "',\n";
    }
    $contents .= ");\n\n?>";
    if(!is_writable(dirname(__FILE__) . '/config.php')) {
        mosRedirect('index2.php?option=' . $option . '&act=config', 'Configuration file is Нетt writable');
        return;
    }

    $fp = fopen(dirname(__FILE__) . '/config.php', 'w');

    fwrite($fp, $contents);
    fclose($fp);
}
include dirname(__FILE__) . '/config.php';
$db =& JFactory::getDBO();
//запрашиваем типы
$query = "SELECT `product_type_id`, `product_type_name` FROM `#__vm_product_type` ORDER BY `product_type_name`";
$db->setQuery($query);
$types = $db->loadObjectList();

//запрашиваем характеристики
$query = "SELECT `product_type_id`, `parameter_name`, `parameter_label` FROM `#__vm_product_type_parameter` ORDER BY `product_type_id`, `parameter_list_order`";
$db->setQuery($query);
$result = $db->loadObjectList();
//создаем из характеристик массив
$params = array();
foreach ($result as $param) {
    $params[$param->product_type_id][$param->parameter_name] = $param->parameter_label;
}

unset ($result);
?>
<form action="index2.php" method="post" name="adminForm">
    <table width="100%" class="adminform">
        <tr>
            <th colspan="2">
                <h2>Настройки поиска</h2>
            </th>
        </tr>
        <tr>
            <td valign="top" align="right">
		Подключать jquery в компоненте:
            </td>
            <td>
                <input type="radio" name="CONFIG[jquery]" value="1"<?php if(!empty($conf['jquery'])) {
                    echo " checked";
                } ?>>Да
                <input type="radio" name="CONFIG[jquery]" value="0"<?php if(empty($conf['jquery'])) {
                    echo " checked";
                } ?>>Нет
            </td>
        </tr>
        <tr>
            <td valign="top" align="right">
		Подключать jquery.form в компоненте:
            </td>
            <td>
                <input type="radio" name="CONFIG[jquery_form]" value="1"<?php if(!empty($conf['jquery_form'])) {
                    echo " checked";
                } ?>>Да
                <input type="radio" name="CONFIG[jquery_form]" value="0"<?php if(empty($conf['jquery_form'])) {
                    echo " checked";
                } ?>>Нет
            </td>
        </tr>
        <tr>
            <td valign="top" align="right">
		Показывать отладочную информацию:
            </td>
            <td>
                <input type="radio" name="CONFIG[debug_show]" value="1"<?php if(!empty($conf['debug_show'])) {
                    echo " checked";
                } ?>>Да
                <input type="radio" name="CONFIG[debug_show]" value="0"<?php if(empty($conf['debug_show'])) {
                    echo " checked";
                } ?>>Нет
            </td>
        </tr>
        <tr>
            <td colspan="2" valign="top" align="right">
                <h2>Результат поиска</h2>
            </td>
	</tr>
        <tr>
            <td valign="top" align="right">
		Показывать краткое описание:
            </td>
            <td>
                <input type="radio" name="CONFIG[show_desc]" value="1"<?php if(!empty($conf['show_desc'])) {
                    echo " checked";
                } ?>>Да
                <input type="radio" name="CONFIG[show_desc]" value="0"<?php if(empty($conf['show_desc'])) {
                    echo " checked";
                } ?>>Нет
            </td>
        </tr>
        <tr>
            <td valign="top" align="right">
		Показывать цены:
            </td>
            <td>
                <input type="radio" name="CONFIG[show_price]" value="1"<?php if(!empty($conf['show_price'])) {
    echo " checked";
} ?>>Да
                <input type="radio" name="CONFIG[show_price]" value="0"<?php if(empty($conf['show_price'])) {
    echo " checked";
} ?>>Нет
            </td>
        </tr>

        <tr>
            <td valign="top" align="right">
		Показывать картинки:
            </td>
            <td>
                <input type="radio" name="CONFIG[show_image]" value="1"<?php if(!empty($conf['show_image'])) {
    echo " checked";
} ?>>Да
                <input type="radio" name="CONFIG[show_image]" value="0"<?php if(empty($conf['show_image'])) {
                    echo " checked";
} ?>>Нет
            </td>
        </tr>

        <tr>
            <td valign="top" align="right">
		Ширина картинки:
            </td>
            <td>
                <input type="text" name="CONFIG[t_width]" value="<?php if(empty($conf['t_width'])) {
    echo "90";
} else {
    echo $conf['t_width'];
} ?>">
            </td>
        </tr>

        <tr>
            <td valign="top" align="right">
		Высота картинки:
            </td>
            <td>
                <input type="text" name="CONFIG[t_height]" value="<?php if(empty($conf['t_height'])) {
    echo "90";
} else {
    echo $conf['t_height'];
} ?>">
            </td>
        </tr>
        <tr>
            <td valign="top" align="right">
		Показывать кнопку "купить":
            </td>
            <td>
                <input type="radio" name="CONFIG[show_add_to_cart_in_search_result]" value="1"<?php if(!empty($conf['show_add_to_cart_in_search_result'])) {
                    echo " checked";
                } ?>>Да
                <input type="radio" name="CONFIG[show_add_to_cart_in_search_result]" value="0"<?php if(empty($conf['show_add_to_cart_in_search_result'])) {
                    echo " checked";
                } ?>>Нет
            </td>
        </tr>
        <tr>
            <td valign="top" align="right">
		Результатов поиска на страницу:
            </td>
            <td>
                <input type="text" name="CONFIG[search_result_per_page]" value="<?php if(empty($conf['search_result_per_page'])) {
    echo "10";
                } else {
    echo $conf['search_result_per_page'];
} ?>">
            </td>
        </tr>
        <tr>
            <td colspan="2" valign="top" align="right">
                <h2>Форма отбора</h2>
            </td>
	</tr>
        <tr>
            <td valign="top" align="right" width="200px">
		Показывать форму поиска:
            </td>
            <td>
                <input type="radio" name="CONFIG[show_search_form]" value="1"<?php if(!empty($conf['show_search_form'])) {
    echo " checked";
} ?>>Да
                <input type="radio" name="CONFIG[show_search_form]" value="0"<?php if(empty($conf['show_search_form'])) {
    echo " checked";
} ?>>Нет
            </td>
        </tr>
        <tr>
            <td valign="top" align="right">
		Показывать категории:
            </td>
            <td>
                <input type="radio" name="CONFIG[show_category]" value="1"<?php if(!empty($conf['show_category'])) {
                    echo " checked";
                } ?>>Да
                <input type="radio" name="CONFIG[show_category]" value="0"<?php if(empty($conf['show_category'])) {
                    echo " checked";
                } ?>>Нет
            </td>
        </tr>
		<tr>
            <td valign="top" align="right">
		Показывать производителей:
            </td>
            <td>
                <input type="radio" name="CONFIG[show_man]" value="1"<?php if(!empty($conf['show_man'])) {
                    echo " checked";
                } ?>>Да
                <input type="radio" name="CONFIG[show_man]" value="0"<?php if(empty($conf['show_man'])) {
                    echo " checked";
                } ?>>Нет
            </td>
        </tr>
	<tr>
            <td valign="top" align="right">
		Показывать типы:
            </td>
            <td>
                <input type="radio" name="CONFIG[show_type]" value="1"<?php if(!empty($conf['show_type'])) {
                    echo " checked";
                } ?>>Да
                <input type="radio" name="CONFIG[show_type]" value="0"<?php if(empty($conf['show_type'])) {
                    echo " checked";
                } ?>>Нет
            </td>
        </tr>
        <tr>
            <td valign="top" align="right">
		Печатать лейблы параметров:
            </td>
            <td>
                <input type="radio" name="CONFIG[show_label]" value="1"<?php if(!empty($conf['show_label'])) {
                    echo " checked";
                } ?>>Да
                <input type="radio" name="CONFIG[show_label]" value="0"<?php if(empty($conf['show_label'])) {
                    echo " checked";
                } ?>>Нет
            </td>
        </tr>
        <tr>
            <td valign="top" align="right">
		Показывать поиск по цене:
            </td>
            <td>
                <input type="radio" name="CONFIG[show_price_form]" value="1"<?php if(!empty($conf['show_price_form'])) {
                    echo " checked";
                } ?>>Да
                <input type="radio" name="CONFIG[show_price_form]" value="0"<?php if(empty($conf['show_price_form'])) {
                    echo " checked";
                } ?>>Нет
            </td>
        </tr>
        <input type="hidden" name="task" value="save">
        <input type="hidden" name="option" value="<? echo $option; ?>">
    </table>
</form>