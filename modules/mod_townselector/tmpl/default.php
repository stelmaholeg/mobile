<?php
/**
 * @package	Joomla.Tutorials
 * @subpackage	Module
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license	License GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die; ?>

<?php ?>
<div class="select">
    <span class="active" id="current_town">Таганрог</span>
    <a href="javascript:" class="opener" title="Выбрать город">&nbsp;</a>
    <div style="margin-top: 24px; width: 175px;" class="vars">
        <ul>
            <li id="t_1" class="first isDefault active">
                <div id="isDefault">
                    <span class="t_town">Таганрог</span>
                </div>
            </li>
            <li id="t_2" class="">
                <div>
                    <span class="t_town">Ставрополь</span>
                </div>
            </li>
        </ul>
        <div class="bottomStripe">
            <div class="left">&nbsp;</div>
            <div class="right">&nbsp;</div>
            <div class="center">&nbsp;</div>
        </div>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('a.opener, #current_town').click(function(){jQuery('.vars').css('zIndex', 9999); jQuery('.vars').toggle();});
        jQuery('.vars li').click(function(){
            var town = jQuery(this).find('.t_town').html();
            jQuery('#current_town').html(town);
            jQuery('.vars').toggle();
            jQuery('.footer-table').hide();
            jQuery('.cart-contact').hide();
            var id = jQuery(this).attr('id');
            jQuery('#'+id+'t').show();
            jQuery('#'+id+'tc').show();
        });
    });
</script>