<?php
/**
 * Created by JetBrains PhpStorm.
 * User: WildMax-3
 * Date: 22.09.12
 * Time: 15:12
 * To change this template use File | Settings | File Templates.
 */
$str = file_get_contents("http://www.odnoklassniki.ru/group/51546412810436");

$strs = explode('hcount',$str);
$strs = explode('panelRounded_body',$strs[1]);
echo str_replace('">','',$strs[0]);


