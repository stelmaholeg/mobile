<?php
/********************************************************************
Product		: FlexicontactPlus
Date		: 10 April 2012
Copyright	: Les Arbres Design 2010-2012
Contact		: http://extensions.lesarbresdesign.info
Licence		: GNU General Public License
*********************************************************************/
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.controller');

class FlexicontactController extends JController
{
function __construct()
{
	parent::__construct();						// automatically maps public functions
	$this->registerTask('help', 'display');
	$this->registerTask('save', 'apply');
	$this->registerTask('save_css', 'apply_css');
}

function display($cachable = false, $urlparams = false)
{
	Flexicontact_Utility::addSubMenu('help');
	$view = $this->getView('help', 'html');
	$view->display();
}
	
function log_list()
{
	Flexicontact_Utility::addSubMenu('log');
	$view = $this->getView('log_list', 'html');
	
	$config_model = $this->getModel('config');
	$config_data = $config_model->getData();

	$logging = (isset($config_data->logging)) ? $config_data->logging : 0;
	$view->assignRef('logging', $logging);
	
	$log_model = $this->getModel('log');
	$log_list = $log_model->getList();
	$view->assignRef('log_list', $log_list);
	
	$pagination = $log_model->getPagination();
	$view->assignRef('pagination',	$pagination);
	
	$view->display();
}

function log_detail()
{
	Flexicontact_Utility::addSubMenu('log');
	$view = $this->getView('log_detail', 'html');

	$id = JRequest::getVar('id');
	$log_model = $this->getModel('log');
	$log_data = $log_model->getOne($id);
	$view->assignRef('log_data', $log_data);

	$config_model = $this->getModel('config');
	$config_data = $config_model->getData();
	$view->assignRef('config_data', $config_data);

	$view->display();
}

function delete_log()
{
	$log_model = $this->getModel('log');
	$cids = JRequest::getVar('cid', array(0), 'post', 'array');
	foreach ($cids as $id)
		$log_model->delete($id);
	$this->setRedirect(LAFC_COMPONENT_LINK."&task=log_list");
}

function config()
{
	Flexicontact_Utility::addSubMenu('config');
	$view_name = JRequest::getVar('view','config_list');
	$view = $this->getView($view_name, 'html');
	$param1 = JRequest::getVar('param1','');
	switch ($view_name)
		{
		case 'config_general':		// these options need the config data, the rest don't
		case 'config_template':
		case 'config_fields':
		case 'config_confirm':
		case 'config_text':
			$config_model = $this->getModel('config');
			$config_model->getData();
			$config_data = $config_model->data;
			$view->assignRef('config_data', $config_data);
			$view->assignRef('param1', $param1);
			break;
		}
	$view->display();
}

function cancel()
{
	$this->setRedirect(LAFC_COMPONENT_LINK."&task=config");
}

function delete_image()
{
	$cids = JRequest::getVar('cid', array(0), 'post', 'array');
	foreach ($cids as $file_name)
		@unlink(LAFC_SITE_IMAGES_PATH.'/'.$file_name);
	$this->setRedirect(LAFC_COMPONENT_LINK."&task=config&view=config_images");
}

function apply()									// save changes to component config
{
	$task = JRequest::getVar('task');				// 'save' or 'apply'
	$view = JRequest::getVar('view');				// could be one of several
	$param1 = JRequest::getVar('param1','');		// 'user_template', 'admin_template', 'page_text', 'bottom_text', etc
	$config_model = $this->getModel('config');
	$config_model->store($view, $param1);			// save config items
	
	if ($view == 'config_general')
		{
		$log_model = $this->getModel('log');
		$log_model->create();						// create log table if not exists
		}

	if ($task == 'apply')
		$this->setRedirect(LAFC_COMPONENT_LINK."&task=config&view=$view&param1=$param1",JText::_('COM_FLEXICONTACT_SAVED'));
	else
		$this->setRedirect(LAFC_COMPONENT_LINK."&task=config",JText::_('COM_FLEXICONTACT_SAVED'));
}   

function apply_css()								// save changes to front end css
{
	$task = JRequest::getVar('task');				// 'save_css' or 'apply_css'
	$css_contents = JRequest::getVar('css_contents',"","","",JREQUEST_ALLOWRAW);
	if (strlen($css_contents) == 0)
		$this->setRedirect(LAFC_COMPONENT_LINK."&task=config");
	$css_path = JPATH_COMPONENT_SITE.'/assets/com_flexicontact.css';
	$length_written = file_put_contents ($css_path, $css_contents);
	if ($length_written == 0)
		$msg = JText::_('COM_FLEXICONTACT_NOT_SAVED');
	else
		$msg = JText::_('COM_FLEXICONTACT_SAVED');
	if ($task == 'apply_css')
		$this->setRedirect(LAFC_COMPONENT_LINK."&task=config&view=config_css",$msg);
	else
		$this->setRedirect(LAFC_COMPONENT_LINK."&task=config",$msg);
}   


}
