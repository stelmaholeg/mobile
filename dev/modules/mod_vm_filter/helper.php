<?php 
    defined('_JEXEC') or die('Restricted access');
/* Создаем класс */
class ModNameToClass {
    /* Создаем функцию */
    function getNameTofunction($params)  {
/* Тело нашей функции */
/* Вызываем параметр из настроек модуля под именем name */
$youname = $params->get('name');
/* Занесли это значение в переменную $youname и проверяем пустое оно или нет */
if($youname) {
/* Если имя введено, то переменная $view содержит имя */
$view = $youname;
} else {
/* Если имя не введено, то $view содержит следующую фразу */
$view = 'Ошибка! Вы не ввели парметр';
}
/* Функция имеет результат со значением в переменной $view */
return $view;
    }
}
?>