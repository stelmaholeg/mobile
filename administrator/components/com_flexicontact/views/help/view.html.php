<?php
/********************************************************************
Product    : Flexicontact
Date       : 08 March 2012
Copyright  : Les Arbres Design 2009-2012
Contact	   : http://extensions.lesarbresdesign.info
Licence    : GNU General Public License
*********************************************************************/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
jimport('joomla.html.html');

class FlexicontactViewHelp extends JView
{
function display($tpl = null)
{
	JToolBarHelper::title(JText::_('COM_FLEXICONTACT_TOOLBAR_HELP'), 'flexicontact.png');
	JToolBarHelper::back();
	
// get the component version

	$xml_array = JApplicationHelper::parseXMLInstallFile(JPATH_COMPONENT_ADMINISTRATOR.'/flexicontact.xml');
	$component_version = $xml_array['version'];
	
// setup links	
	
	$link_doc = "http://extensions.lesarbresdesign.info/extensions/flexicontact";
	$link_images = "http://extensions.lesarbresdesign.info/extensions/flexicontact/captcha-image-packs";
	$link_version = "http://extensions.lesarbresdesign.info/version-history/flexicontact";
	$link_rating = "http://extensions.joomla.org/extensions/contacts-and-feedback/contact-forms/9743";
	$link_chrisguk = "http://extensions.joomla.org/extensions/owner/chrisguk";
	$link_LAextensions = "http://extensions.lesarbresdesign.info/";

// draw the page

	?>
	<form action="index.php" method="get" name="adminForm" id="adminForm" >
	<input type="hidden" name="option" value="com_flexicontact" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="view" value="help" />
	</form>
	<p style="color:#0B55C4; font-size:15px"><?php echo LAFC_COMPONENT_NAME.': '.JText::_('COM_FLEXICONTACT_HELP_TITLE');?></p>
	<p><?php echo JText::_('COM_FLEXICONTACT_VERSION').' '.$component_version?></p>
	<p><?php echo '<strong>'.JText::_('COM_FLEXICONTACT_HELP_CONFIG').'</strong>';?></p>
	<p><?php echo '<span style="color:#980403; font-size:15px; font-weight:bold;">'.JText::_('COM_FLEXICONTACT_HELP_FLEXICONTACTPLUS').'</span> '.JHTML::link($link_doc, "www.lesarbresdesign.info", 'target="_blank"');?></p>
	<p><?php echo JText::_('COM_FLEXICONTACT_HELP_DOC').' '.JHTML::link($link_doc, "www.lesarbresdesign.info", 'target="_blank"');?></p>
	<p><?php echo JText::_('COM_FLEXICONTACT_HELP_IMAGES').' '.JHTML::link($link_images, "www.lesarbresdesign.info", 'target="_blank"');?></p>
	<p><?php echo JText::_('COM_FLEXICONTACT_HELP_CHECK').' '.JHTML::link($link_version, 'Les Arbres Design - Flexicontact', 'target="_blank"');?></p>
	<p><?php echo JText::sprintf('COM_FLEXICONTACT_HELP_RATING', LAFC_COMPONENT_NAME).' '.JHTML::link($link_rating, 'Joomla! Extensions', 'target="_blank"');?></p>
	<p><?php echo JText::sprintf('COM_FLEXICONTACT_HELP_LES_ARBRES', LAFC_COMPONENT_NAME, JHTML::link($link_chrisguk, 'Joomla! Extensions', 'target="_blank"')).' '.JHTML::link($link_LAextensions, 'Les Arbres Design', 'target="_blank"');?></p>
	<table>
		<tr>
			<td><?php echo JText::sprintf('COM_FLEXICONTACT_HELP_FUND_ONE', LAFC_COMPONENT_NAME);?><br />
				<?php echo JText::_('COM_FLEXICONTACT_HELP_FUND_TWO');?>
			</td>
			<td>
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
					<input type="hidden" name="cmd" value="_s-xclick" />
					<input type="hidden" name="hosted_button_id" value="5DSLW6K9KULKS" />
					<input type="hidden" name="item_name" value="FlexiContact">
					<input type="image" src="<?php echo JText::_('COM_FLEXICONTACT_HELP_DONATE_BUTTON');?>" name="submit" style="border:none;" alt="PayPal - The safer, easier way to pay online." />
				</form>
			</td>
		</tr>
	</table>
	<?php	
}
				
			
}