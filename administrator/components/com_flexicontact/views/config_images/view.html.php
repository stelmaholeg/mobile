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

class FlexicontactViewConfig_Images extends JView
{
function display($tpl = null)
{
	JToolBarHelper::title(JText::_('COM_FLEXICONTACT_TOOLBAR_IMAGES'), 'flexicontact.png');
	JToolBarHelper::deleteList('','delete_image');
	JToolBarHelper::cancel();
	
// Get the list of themes

	$app = JFactory::getApplication();
	$filter_theme = $app->getUserStateFromRequest(LAFC_COMPONENT.'.filter_theme','filter_theme', THEME_ALL,'word');
	
	$theme_list = Flexicontact_Utility::make_theme_list();
	$theme_list_html = Flexicontact_Utility::make_list('filter_theme', $filter_theme, $theme_list, 0, 'onchange="submitform( );"');
	
// load the front end Flexicontact language file for the current language

	$lang = JFactory::getLanguage();
	$lang->load(strtolower(LAFC_COMPONENT), JPATH_SITE);	// 3rd parameter could specify language

// load the additional language file provided by our image packs

	$lang = JFactory::getLanguage();
	$lang->load('com_flexicontact_captcha', JPATH_SITE);

// get an array of filenames
	
    $imageFiles = array();					// create array
    $handle = opendir(LAFC_SITE_IMAGES_PATH);
	if (!$handle)
		{
		echo JText::_('COM_FLEXICONTACT_NO_IMAGES_DIRECTORY');
		return;
		}
		
	while (($filename = readdir($handle)) != false)
	    {
    	if ($filename == '.' or $filename == '..')
    		continue;
    	$imageInfo = @getimagesize(LAFC_SITE_IMAGES_PATH.'/'.$filename);
    	if ($imageInfo === false)
    		continue;					// not an image
    	if ($imageInfo[3] > 3)			// only support gif, jpg or png
    		continue;
    	if ($imageInfo[0] > 150)		// if X size > 150 pixels ..
    		continue;					// .. it's too big so skip it
	// Do we display it?
	$prefix = substr($filename, 0, 2);
	
	switch ($filter_theme)
		{
		case THEME_ALL:
			$imageFiles[] = $filename;
			break;
		case THEME_STANDARD:
			if (substr($prefix, 0, 1) == '0')
				$imageFiles[] = $filename;
			break;
		case THEME_TOYS:
			if ($prefix == 'A_' or $prefix == 'B_')
				$imageFiles[] = $filename;
			break;
		case THEME_NEON:
			if ($prefix == 'C_')
				$imageFiles[] = $filename;
			break;
		case THEME_WHITE:
			if ($prefix == 'D_')
				$imageFiles[] = $filename;
			break;
		case THEME_BLACK:
			if ($prefix == 'E_')
				$imageFiles[] = $filename;
			break;
			
		}
    	}
    closedir($handle);
    if (empty($imageFiles) and $filter_theme == THEME_ALL)
		{
		echo JText::_('COM_FLEXICONTACT_NO_IMAGES');
		return;
		}
    $image_count = count($imageFiles);
	sort($imageFiles);
	$rowCount = 0;
	$columns = 4;
	$column_width = intval(100 / ($columns * 2));

	echo '<form action="index.php" method="post" name="adminForm" id="adminForm" >';
	echo '<input type="hidden" name="option" value="com_flexicontact" />';
	echo '<input type="hidden" name="task" value="config" />';
	echo '<input type="hidden" name="view" value="config_images" />';
	echo '<input type="hidden" name="boxchecked" value="0" />';
	echo '<input type="hidden" name="hidemainmenu" value="0" />';
	
// filters

	echo "\n".'<div>';
	echo $image_count.' '.JText::_('COM_FLEXICONTACT_IMAGES').' ';
	echo '<input type="checkbox" name="toggle" value="" onclick="checkAll('.$image_count.');" /> ';
	echo $theme_list_html;
	echo "\n</div>";
	echo "\n".'<table class="adminlist">'."\n";

// draw the images
	
	$i = 0;
	foreach ($imageFiles as $filename)
		{
		$imageInfo = getimagesize(LAFC_SITE_IMAGES_PATH.'/'.$filename);
		if ($imageInfo !== false)
			{
			$imageX = $imageInfo[0];
			$imageY = $imageInfo[1];
			}
		
		$text_name = 'COM_FLEXICONTACT_IMAGE_'.strtoupper($filename);
		$description = JText::_($text_name);	// resolved by front end language file
		if ($text_name == $description)			// highlight if not resolved
			$description = '<span style="color: red">'.$description.'</span>';
		
		if ($rowCount == 0)
			echo '<tr>';
		echo "\n".'<td valign="top" width="'.$column_width.'%">';
		echo "\n".'  <img hspace="0" vspace="0" border="0" src="'.JURI::root().'components/com_flexicontact/images/'.$filename.'" alt="" /></td>';
		echo "\n".'<td valign="top" width="'.$column_width.'%"><b>'.utf8_encode($filename).'</b><br />';
		echo $description.'<br />';
		echo $imageX.'x'.$imageY.'<br />';
// don't need the old delete links now		
//		echo "\n".' <a href="index.php?option=com_flexicontact&amp;task=delete_file&amp;file_name='.$filename.'"> '; 
//		echo JText::_('COM_FLEXICONTACT_DELETE').'</a><br />';
		echo "\n".JHTML::_('grid.id',   $i++, $filename);
		echo '</td>';
		$rowCount++;
		if ($rowCount == $columns)
			{
			echo "</tr>\n";
			$rowCount=0;
			}
		}

	if (($rowCount > 0) and ($rowCount < $columns))
		{
		$colsleft = ($columns - $rowCount) * 2;
		echo '<td colspan="'.$colsleft.'"></td>';
		echo '</tr>';
		}
	echo '</table>';
	echo '</form>';
}

}