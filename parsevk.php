<?php
/**
 * Created by JetBrains PhpStorm.
 * User: WildMax-3
 * Date: 22.09.12
 * Time: 15:12
 * To change this template use File | Settings | File Templates.
 */
$str = file_get_contents("http://vk.com/widget_community.php?gid=46218009&mode=1");
$strs = explode('members_count',$str);
$strs = explode('community_like',$strs[1]);
$number = preg_replace("/[^0-9]/", '', $strs[0]);
echo $number;
