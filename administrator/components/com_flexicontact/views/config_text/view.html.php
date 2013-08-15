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

class FlexicontactViewConfig_Text extends JView
{
function display($tpl = null)
{
	$text_name = $this->param1;			// param1 is the text name, 'page_text' or 'bottom_text'
	
	if ($text_name == 'page_text')
		JToolBarHelper::title(LAFC_COMPONENT_NAME.': <small><small>'.JText::_('COM_FLEXICONTACT_V_TOP_TEXT').'</small></small>', 'flexicontact.png');
	else
		JToolBarHelper::title(LAFC_COMPONENT_NAME.': <small><small>'.JText::_('COM_FLEXICONTACT_V_BOTTOM_TEXT').'</small></small>', 'flexicontact.png');
	JToolBarHelper::apply();
	JToolBarHelper::save();
	JToolBarHelper::cancel();
	
// setup the wysiwg editor

	$editor = JFactory::getEditor();
	
	?>
	<form action="index.php" method="post" name="adminForm" id="adminForm" >
	<input type="hidden" name="option" value="<?php echo LAFC_COMPONENT; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="view" value="config_text" />
	<input type="hidden" name="param1" value="<?php echo $text_name; ?>" />
	
	<?php 
	echo $editor->display($text_name, htmlspecialchars($this->config_data->$text_name, ENT_QUOTES),'700','350','60','20',array('pagebreak', 'readmore'));
	?>
	</form>
	<?php 
}

}