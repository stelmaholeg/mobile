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
 * @version $Id: default_images.php 5406 2012-02-09 12:22:33Z alatak $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Product Main Image
if (!empty($this->product->images[0])) {
    ?>
    <div class="main-image">
	<?php echo '<li>'.$this->product->images[0]->displayMediaFull('class="product-image additional-image"', true, 'class="modal"', true, true).'</li>';
          //echo $this->product->images[0]->displayMediaFull('class="medium-image" id="medium-image"', false, "class='modal'", true); ?>

    </div>
<?php } // Product Main Image END ?>

<?php
// Showing The Additional Images
// if(!empty($this->product->images) && count($this->product->images)>1) {
if (!empty($this->product->images)) {
    ?>
    <div class="additional-images">
        <div id="product-slider">
            <div id="image" style="display:none;">
            </div>
            <div>
                <div id="slider">
                    <a id="previmg" class="imgnav" href="javascript:slideShow.nav(-1)" style="float:left;"> < </a>
                    <div id="thumbarea">
                        <ul id="thumbs">
                            <?php if (count($this->product->images) > 0) {
                                foreach ($this->product->images as $image) {
                                    echo '<li rel="'.$image->file_url.'">'.$image->displayMediaThumb('class="product-image additional-image"', false, '', true, true).'</li>';
                                }
                            } ?>
                        </ul>
                    </div>
                    <a id="nextimg" class="imgnav" href="javascript:slideShow.nav(-1)" style="float:left;"> > </a>
                </div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
<?php
} // Showing The Additional Images END ?>