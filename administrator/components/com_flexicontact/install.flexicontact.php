<?php
/********************************************************************
Product		: Flexicontact
Date		: 10 April 2012
Copyright	: Les Arbres Design 2009-2012
Contact		: http://extensions.lesarbresdesign.info
Licence		: GNU General Public License
*********************************************************************/
defined('_JEXEC') or die('Restricted Access');

// check the PHP version

$php_version = phpversion();
if ($php_version{0} < 5)
	echo "<h2>Warning: You are running an old version of PHP ($php_version) This extension requires at least version 5.0. Some functions may not work properly</h2>";
	
// check the MySql version

$query = "SELECT version()";
$db	= JFactory::getDBO();
$db->setQuery($query);
$mysql_version = $db->loadResult();
if ($mysql_version{0} < 5)
	echo "<h2>Warning: You are running an old version of MySql ($mysql_version) This extension requires at least version 5.0. Some functions may not work properly</h2>";

// check the Joomla version and get component version from the component manifest xml file

$version = new JVersion();
$joomla_version = $version->RELEASE;

switch ($joomla_version)
	{
	case '1.0':
		echo '<h3>'."Flexicontact cannot run on this version of Joomla ($joomla_version)".'</h3>';
		return false;
	case '1.5':
		$component_version = $this->manifest->version[0]->_data;
		break;
	case '1.6';
	case '1.7';
	case '2.5';
		$component_version = $this->manifest->version;
		break;
	default:
		$component_version = $this->manifest->version;
		echo "This version of Flexicontact has not been tested on this version of Joomla ($joomla_version). Some functions may not work properly.";
		break;
	}

// delete files from older versions

@unlink(JPATH_ROOT.'/administrator/components/com_flexicontact/toolbar.flexicontact.html.php'); 
@unlink(JPATH_ROOT.'/administrator/components/com_flexicontact/toolbar.flexicontact.php'); 
@unlink(JPATH_ROOT.'/administrator/components/com_flexicontact/admin.flexicontact.html.php');
@unlink(JPATH_ROOT.'/components/com_flexicontact/flexicontact.html.php');
@unlink(JPATH_ROOT.'/components/com_flexicontact/RL_flexicontact.html.php');

echo "Flexicontact version $component_version installed.";

return true;


?>
