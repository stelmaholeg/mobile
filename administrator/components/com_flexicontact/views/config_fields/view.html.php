<?php
/********************************************************************
Product		: Flexicontact
Date		: 08 March 2012
Copyright	: Les Arbres Design 2010-2012
Contact		: http://extensions.lesarbresdesign.info
Licence		: GNU General Public License
*********************************************************************/

defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.view');

class FlexicontactViewConfig_Fields extends JView
{
function display($tpl = null)
{
	JToolBarHelper::title(LAFC_COMPONENT_NAME.': <small><small>'.JText::_('COM_FLEXICONTACT_CONFIG_FIELDS_NAME').'</small></small>', 'flexicontact.png');
	JToolBarHelper::apply();
	JToolBarHelper::save();
	JToolBarHelper::cancel();

// setup the three field options

	$options = array();
	$options['disabled']  = JText::_('COM_FLEXICONTACT_V_DISABLED');
	$options['optional']  = JText::_('COM_FLEXICONTACT_V_OPTIONAL');
	$options['mandatory'] = JText::_('COM_FLEXICONTACT_V_MANDATORY');

// draw the form

	?>
	<form action="index.php" method="post" name="adminForm" id="adminForm" >
	<input type="hidden" name="option" value="<?php echo LAFC_COMPONENT; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="view" value="config_fields" />
	<?php
	echo '<table>';
	
// subject field	

	echo "\n<tr>";
		echo '<td align="right">'.JText::_('COM_FLEXICONTACT_V_SHOW_SUBJECT').'</td>';
		echo '<td>'.Flexicontact_Utility::make_radio('show_subject',$this->config_data->show_subject).'</td>';
		echo '<td></td>';
		echo '<td align="right">'.JText::_('COM_FLEXICONTACT_V_DEFAULT_SUBJECT').'</td>';
		echo '<td><input type="text" size="40" name="default_subject" value="'.$this->config_data->default_subject.'" /> '.
			Flexicontact_Utility::make_info(JText::_('COM_FLEXICONTACT_V_DEFAULT_SUBJECT_DESC')).'</td>';
	echo "\n</tr>";
	
// main message field

	echo "\n<tr>";
		echo '<td align="right">'.JText::_('COM_FLEXICONTACT_MESSAGE').'</td>';
		echo '<td valign="top">'.Flexicontact_Utility::make_list('area_opt',$this->config_data->area_opt, $options, 0, 'style="margin-bottom:0"').'</td>';
		echo '<td valign="top">&nbsp;&nbsp;</td>';
		echo '<td align="right">'.JText::_('COM_FLEXICONTACT_V_PROMPT').'</td>';
		echo '<td><input type="text" size="40" name="area_prompt" value="'.$this->config_data->area_prompt.'" /> '.
			Flexicontact_Utility::make_info(JText::_('COM_FLEXICONTACT_V_TEXT_AREA_DESC')).'</td>';
		echo '<td colspan="2">'.JText::_('COM_FLEXICONTACT_V_WIDTH');
		echo ' <input type="text" size="5" name="area_width" value="'.$this->config_data->area_width.'" />';
		echo '&nbsp;&nbsp;'.JText::_('COM_FLEXICONTACT_V_HEIGHT');
		echo ' <input type="text" size="5" name="area_height" value="'.$this->config_data->area_height.'" /></td>';
	echo "\n</tr>";
	
// the five optional text fields

	for ($i = 1; $i <= 5; $i++)
		{
		echo "\n<tr>";
			echo '<td  align="right">'.JText::_('COM_FLEXICONTACT_FIELD').' '.$i.'</td>';
			$option_name = 'field_opt'.$i;
			echo '<td>'.Flexicontact_Utility::make_list($option_name, $this->config_data->$option_name, $options, 0, 'style="margin-bottom:0"').'</td>';
			echo '<td>&nbsp;&nbsp;</td>';
			echo '<td align="right">'.JText::_('COM_FLEXICONTACT_V_PROMPT').'</td>';
			$promptname = 'field_prompt'.$i;
			echo '<td colspan="3"><input type="text" size="40" name="'.$promptname.'" value="'.$this->config_data->$promptname.'" /> '.
				Flexicontact_Utility::make_info(JText::_('COM_FLEXICONTACT_V_TEXT_FIELD_DESC')).'</td>';
		echo "\n</tr>";
		}
	echo '</table></form>';
}

}