<?php
/********************************************************************
Product		: Flexicontact
Date		: 10 April 2012
Copyright	: Les Arbres Design 2009-2012
Contact		: http://extensions.lesarbresdesign.info
Licence		: GNU General Public License
*********************************************************************/
defined('_JEXEC') or die('Restricted Access');

// Pull in the helper file

require_once JPATH_COMPONENT_ADMINISTRATOR .'/helpers/flexicontact_helper.php';

if (file_exists(JPATH_ROOT.'/LA.php'))
	require_once JPATH_ROOT.'/LA.php';

$task = JRequest::getVar('task');

if ($task == 'image')
	{
	require_once(LAFC_HELPER_PATH.'/flexi_captcha.php');
	Flexi_captcha::show_image();
	return;
	}

// load our css

$document = JFactory::getDocument();
$document->addStyleSheet('components/'.LAFC_COMPONENT.'/assets/'.LAFC_COMPONENT.'.css');

jimport('joomla.application.component.controller');

require_once( JPATH_COMPONENT.'/controller.php' );
$controller = new FlexicontactController();

$task = JRequest::getVar('task');

$controller->execute($task);

$controller->redirect();

?>
