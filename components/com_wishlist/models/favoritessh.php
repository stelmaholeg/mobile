<?php
/**
 * Favorites Model for Favorites Component
 * 
 * @package    Favorites & Wishlist
 * @subpackage com_wishlist
 * @license  GNU/GPL v2
 * @copyright Copyright (C) 2010 MobyJam.net. All rights reserved.
 * This program is distributed under the terms of the GNU General Public License
 *
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

/**
 * Favorites Model
 *
 * @package    Joomla.Components
 * @subpackage 	Favorites
 */
class FavoritesModelFavoritessh extends JModel{

	/**
	 * Favoritessh data array for tmp store
	 *
	 * @var array
	 */
	private $_data;
	
	/**
	 * Method to share favorites
	 *
	 * @access	private
	 */
	private function shareFav($sh_mode){
			$acc_type = JRequest::getString('acc_type',  "public");
			if ($acc_type == "public") $var_date = "share_date=NOW()"; else $var_date = "share_date='1900-01-01'";
			$iswishlist = JRequest::getInt('is_wishlist',  0);
			$share_title = JRequest::getString('share_title',  "");
			$share_desc = JRequest::getString('share_desc',  "");
			$user =& JFactory::getUser();
			$db =& JFactory::getDBO();
			switch ($sh_mode) {
				case "share":
					$db->setQuery("SELECT COUNT(*) FROM #__virtuemart_favorites_sh WHERE user_id=" . $user->id );
					$sh_counter = $db->loadResult();
					if ($sh_counter == 0) $db->setQuery("INSERT INTO #__virtuemart_favorites_sh SET user_id=" . $user->id . ", ".$var_date.", share_title='" . $share_title . "', share_desc='" . $share_desc . "', isWishList=" . $iswishlist);
					else $db->setQuery("UPDATE #__virtuemart_favorites_sh SET ".$var_date.", share_title='" . $share_title . "', share_desc='" . $share_desc . "', isWishList=" . $iswishlist . " WHERE user_id=" . $user->id);
					$db->query();
        			break;
    			case "unshare":
        			$db->setQuery("DELETE FROM #__virtuemart_favorites_sh WHERE user_id=" . $user->id);
					$db->query();
					$db->setQuery("UPDATE #__virtuemart_favorites SET product_qty=-1 WHERE user_id=" . $user->id);
					$db->query();
        			break;	
			}
	}
	
	/**
	 * Method to send email(s)
	 *
	 * @access	private
	 */
	 private function sendmail($to, $subject, $body){
				$user =& JFactory::getUser();
				$db =& JFactory::getDBO();
				$query = "SELECT name, email from  #__users where id=".$user->id;
				$db->setQuery( $query );
				$email_header = $db->loadObject();
				if ($email_header->email != "") {
					$body.= ' '.JText::_('VM_EMAIL_INVITE').' '.JURI::base().'?option=com_wishlist&view=sharelist&user_id='.$user->id;
					$headers = "From: " . $email_header->name . " <". $email_header->email .">";
					//send the email
					$mail_sent = @mail( $to, $subject, $body, $headers );
					//if the message is sent successfully print "Mail sent". Otherwise print "Mail failed"
					echo $mail_sent ? "<div class='fav_header'>". JText::_('VM_EMAIL_SENT')."</div>" : "<div class='fav_header'>". JText::_('VM_EMAIL_ERROR')."</div>";
				}
			}
	
	/**
	 * Gets the data
	 * @return mixed The data to be displayed to the user
	 */
	public function getData(){
		$mode = JRequest::getCmd('mode');	
		switch ($mode) {
			case "share":
				$this->shareFav("share");
				break;
			case "unshare":
				$this->shareFav("unshare");
				break;
			case "sendmail":
				$email_to = JRequest::getString('email_to',  "");
				$email_subj = JRequest::getString('email_subj',  "");
				$email_body = JRequest::getString('email_body',  "");
				//Demo System no email will be sent
				//echo "<div class='fav_header'>This is a demo system, no Email will be sent from here</div>";
				$this->sendmail($email_to,$email_subj,$email_body);
				break;
		}
		// Lets load the data if it doesn't already exist
		if (empty( $this->_data )){
			$user =& JFactory::getUser();
			$db =& JFactory::getDBO();
			$query = "SELECT * FROM `#__virtuemart_favorites_sh` where `user_id` = " . $user->id;
			$db->setQuery( $query );
			$this->_data = $db->loadObject();
		}
		return $this->_data;
	}
}
