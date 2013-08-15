<?php
/********************************************************************
Product		: Flexicontact
Date		: 10 April 2012
Copyright	: Les Arbres Design 2009-2012
Contact		: http://extensions.lesarbresdesign.info
Licence		: GNU General Public License
*********************************************************************/
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.view');

class FlexicontactView_Confirm extends JView
{
function display($tpl = null)
	{
	echo "\n".'<div class="flexicontact">';

	echo $this->message;

	echo "\n</div>";							// end the <div class="flexicontact">
	return;
	}
}
?>
