<?php
/********************************************************************
Product		: Flexicontact
Date		: 10 April 2012
Copyright	: Les Arbres Design 2010-2012
Contact		: http://extensions.lesarbresdesign.info
Licence		: GNU General Public License
*********************************************************************/

defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.view');

class FlexicontactViewConfig_Confirm extends JView
{
function display($tpl = null)
{
	JToolBarHelper::title(LAFC_COMPONENT_NAME.': <small><small>'.JText::_('COM_FLEXICONTACT_CONFIG_CONFIRM_NAME').'</small></small>', 'flexicontact.png');
	JToolBarHelper::apply();
	JToolBarHelper::save();
	JToolBarHelper::cancel();
	
// setup the wysiwg editor

	$editor = JFactory::getEditor();
	
	?>
	<form action="index.php" method="post" name="adminForm" id="adminForm" >
	<input type="hidden" name="option" value="<?php echo LAFC_COMPONENT; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="view" value="config_confirm" />
	
	<?php 
	echo "\n".'<table><tr>';
	echo '<td>'.JText::_('COM_FLEXICONTACT_LINK').'</td>';
	echo '<td><input type="text" size="60" name="confirm_link" value="'.$this->config_data->confirm_link.'" /> '.
		Flexicontact_Utility::make_info(JText::_('COM_FLEXICONTACT_CONFIRM_LINK_DESC')).'</td>';
	echo "\n<td></td></tr>";
	
	echo "\n<tr>";
	echo '<td valign="top">'.JText::_('COM_FLEXICONTACT_TEXT').'</td>';
	echo '<td>'.$editor->display('confirm_text', htmlspecialchars($this->config_data->confirm_text, ENT_QUOTES),'700','350','60','20',array('pagebreak', 'readmore'));
	echo "\n".'</td>';
	echo "\n".'</tr></table>';
	?>
	</form>
	<?php 
}

}