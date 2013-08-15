<?php
/**
 *
 * Show the product details page
 *
 * @package	VirtueMart
 * @subpackage
 * @author Max Milbers, Valerie Isaksen

 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default_customfields.php 5699 2012-03-22 08:26:48Z ondrejspilka $
 */

// Check to ensure this file is included in Joomla!
defined ( '_JEXEC' ) or die ( 'Restricted access' );
?>
<div class="product-fields">
    <table>
	    <?php
	    $custom_title = null;
        $i = "odd";
	    foreach ($this->product->customfieldsSorted[$this->position] as $field) {
            if ( $field->is_hidden )
	    		continue;
			if ($field->display && (
                    ($this->tab == 'summ' &&
                        ($field->is_on_common_tab)
                    ) ||
                    ($this->tab == 'full' &&
                        ($field->is_on_fullinfo_tab || ($field->is_group_name && $field->is_on_fullinfo_tab))
                    )
                )) {
            if($i == "odd") {$i = "even";} else {$i = "odd";}
            if($field->field_type == "P") { ?>
                <tr class="odd product-field product-field-type-S" style="height:22px;">
                <td colspan="3" style="padding-top:3px; padding-bottom: 3px;">
                    <span class="product-fields-title" ><b><?php echo JText::_($field->custom_field_desc ? $field->custom_field_desc : $field->custom_title ); ?></b></span>
                </td>
                </tr>
    <?php
            } else {;
            ?>
            <tr class="<?php echo $i; ?> product-field product-field-type-<?php echo $field->field_type ?>">
                <td style="width:200px;">
                    <?php if ($field->custom_title != $custom_title) { ?>
                    <span class="product-fields-title" ><?php echo JText::_($field->custom_field_desc ? $field->custom_field_desc : $field->custom_title ); ?></span>
                    <?php
                    if ($field->custom_tip);
                        //echo JHTML::tooltip($field->custom_tip, JText::_($field->custom_title), 'tooltip.png');
                    } ?>
                </td>
                <td class="product-field-divider" style="width:10px;">:</td>
                <td style="padding-left:5px;">
                    <span class="product-fields-value"><?php echo $field->display; ?></span>
                </td>
            </tr>
                <?php
            }
		    $custom_title = $field->custom_title;
			}
	    }
	    ?>
    </table>
</div>
