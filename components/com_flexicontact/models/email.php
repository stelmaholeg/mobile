<?php
/********************************************************************
Product		: Flexicontact
Date		: 19 June 2012
Copyright	: Les Arbres Design 2010-2012
Contact		: http://extensions.lesarbresdesign.info
Licence		: GNU General Public License
*********************************************************************/
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class FlexicontactModelEmail extends JModel
{
var $_app = null;

function __construct()
{
	parent::__construct();
	$this->_app = JFactory::getApplication();
}

//--------------------------------------------------------------------------------
// Get post data
//
function getPostData($config_data)
{
// Get the user name and email defaults
//
	switch ($config_data->autofill)
		{
		case 'off':
			$user_name = '';
			$user_email = '';
			break;
		case 'username':
			$user = JFactory::getUser();
			$user_name = $user->username;
			$user_email = $user->email;
			break;
		case 'name':
			$user = JFactory::getUser();
			$user_name = $user->name;
			$user_email = $user->email;
			break;
		}
		
	$this->data = new stdclass();
	$this->data->from_name = JRequest::getVar('from_name',$user_name);
	$this->data->from_email = JRequest::getVar('from_email',$user_email);
	$this->data->subject = JRequest::getVar('subject',$config_data->default_subject);
	$this->data->copy_me = JRequest::getVar('copy_me','');					// checkbox
	$this->data->agreement_check = JRequest::getVar('agreement_check','');		// checkbox
	$this->data->field1 = JRequest::getVar('field1','');
	$this->data->field2 = JRequest::getVar('field2','');
	$this->data->field3 = JRequest::getVar('field3','');
	$this->data->field4 = JRequest::getVar('field4','');
	$this->data->field5 = JRequest::getVar('field5','');
	$this->data->area_data = JRequest::getVar('area_data','');
	$this->data->magic_word = JRequest::getVar('magic_word','');
	$this->data->pic_selected = JRequest::getVar('picselected','');
	return $this->data;
}

// -------------------------------------------------------------------------------
// Validate the user input
//
function validate(&$errors, $config_data)
{
	if (!JRequest::checkToken())
		{
		$errors['top'] = 'Your session timed out';
		return;
		}
	
// if using captcha, validate that the correct image was chosen
// if the user gets it wrong more than 5 times, tell the controller to kill the session

	if ($config_data->num_images > 0)
		{
		require_once(LAFC_HELPER_PATH.'/flexi_captcha.php');
		$pic_selected = substr($this->data->pic_selected,2);	// strip off the i_
		$ret = Flexi_captcha::check($pic_selected);
		if ($ret == 1)
			$errors['imageTest'] = JText::_('COM_FLEXICONTACT_WRONG_PICTURE');
		if ($ret == 2)
			{
			$errors['kill'] = 'Yes';		// tell the controller to kill the session
			return;
			}
		}
	
// if using magic word, validate the word

	if ($config_data->magic_word != '')
		if (strcasecmp($this->data->magic_word,$config_data->magic_word) != 0)
			$errors['magic_word'] = JText::_('COM_FLEXICONTACT_WRONG_MAGIC_WORD');
	
// validate the from name

	if (empty($this->data->from_name))
		$errors['from_name'] = JText::_('COM_FLEXICONTACT_REQUIRED');

// validate the from address

	jimport('joomla.mail.helper');
	if (!JMailHelper::isEmailAddress($this->data->from_email) and  (empty($this->data->field1)))
		$errors['from_email'] = JText::_('COM_FLEXICONTACT_BAD_EMAIL');

// validate the subject

	if (($config_data->show_subject) and (empty($this->data->subject)))
		$errors['subject'] = JText::_('COM_FLEXICONTACT_REQUIRED');

// validate user fields

	if (($config_data->field_opt1 == "mandatory") and (empty($this->data->field1)))
		$errors['field1'] = JText::_('COM_FLEXICONTACT_REQUIRED');
	if (($config_data->field_opt2 == "mandatory") and (empty($this->data->field2)))
		$errors['field2'] = JText::_('COM_FLEXICONTACT_REQUIRED');
	if (($config_data->field_opt3 == "mandatory") and (empty($this->data->field3)))
		$errors['field3'] = JText::_('COM_FLEXICONTACT_REQUIRED');
	if (($config_data->field_opt4 == "mandatory") and (empty($this->data->field4)))
		$errors['field4'] = JText::_('COM_FLEXICONTACT_REQUIRED');
	if (($config_data->field_opt5 == "mandatory") and (empty($this->data->field5)))
		$errors['field5'] = JText::_('COM_FLEXICONTACT_REQUIRED');

// validate message

	if (($config_data->area_opt == "mandatory") and (empty($this->data->area_data)))
		$errors['area_data'] = JText::_('COM_FLEXICONTACT_REQUIRED');
}

//-----------------------------------------
// Get client's IP address
//
function getIPaddress()
{
	if (isset($_SERVER["REMOTE_ADDR"]))
		return $_SERVER["REMOTE_ADDR"];
	if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
		return $_SERVER["HTTP_X_FORWARDED_FOR"];
	if (isset($_SERVER["HTTP_CLIENT_IP"]))
		return $_SERVER["HTTP_CLIENT_IP"];
	return "unknown";
} 

//-------------------------------------------------------------------------------
// Get client's browser (changed for 5.06)
// Returns 99 for unknown, 0 for msie, 1 for firefix, etc
//
function getBrowser(&$browser_string)
{ 
    $u_agent = $_SERVER['HTTP_USER_AGENT']; 
    $browser_string = 'Unknown';

    if (strstr($u_agent, 'MSIE') && !strstr($u_agent, 'Opera')) 
    	{ 
        $browser_string = 'MSIE'; 
        return 0; 
    	} 
    if (strstr($u_agent, 'Firefox')) 
    	{ 
        $browser_string = 'Firefox'; 
        return 1; 
    	} 
    if (strstr($u_agent, 'Chrome')) 	 // must test for Chrome before Safari!
    	{ 
        $browser_string = 'Chrome'; 
        return 3; 
    	} 
    if (strstr($u_agent, 'Safari')) 
    	{ 
        $browser_string = 'Safari'; 
        return 2; 
    	} 
    if (strstr($u_agent, 'Opera')) 
    	{ 
        $browser_string = 'Opera'; 
        return 4; 
    	} 
    if (strstr($u_agent, 'Netscape')) 
    	{ 
        $browser_string = 'Netscape'; 
        return 5; 
    	} 
    if (strstr($u_agent, 'Konqueror')) 
    	{ 
        $browser_string = 'Konqueror'; 
        return 6; 
    	} 
} 

//-------------------------------------------------------------------------------
// Resolve an email variable
//
function email_resolve($config_data, $variable)
{
	switch ($variable)
		{
		case LAFC_T_FROM_NAME:
			return $this->data->from_name;
		case LAFC_T_FROM_EMAIL:
			return $this->data->from_email;
		case LAFC_T_SUBJECT:
			return $this->data->subject;
		case LAFC_T_MESSAGE_PROMPT:
			return $config_data->area_prompt;
		case LAFC_T_MESSAGE_DATA:
			return $this->data->area_data;
		case LAFC_T_FIELD1_PROMPT:
			return $config_data->field_prompt1;
		case LAFC_T_FIELD1_DATA:
			return $this->data->field1;
		case LAFC_T_FIELD2_PROMPT:
			return $config_data->field_prompt2;
		case LAFC_T_FIELD2_DATA:
			return $this->data->field2;
		case LAFC_T_FIELD3_PROMPT:
			return $config_data->field_prompt3;
		case LAFC_T_FIELD3_DATA:
			return $this->data->field3;
		case LAFC_T_FIELD4_PROMPT:
			return $config_data->field_prompt4;
		case LAFC_T_FIELD4_DATA:
			return $this->data->field4;
		case LAFC_T_FIELD5_PROMPT:
			return $config_data->field_prompt5;
		case LAFC_T_FIELD5_DATA:
			return $this->data->field5;
		case LAFC_T_BROWSER:
			return $this->data->browser_string;
		case LAFC_T_IP_ADDRESS:
			return $this->data->ip;
		default: return '';
		}
}

//-------------------------------------------------------------------------------
// Merge an email template with post data
//
function email_merge($template_text, $config_data)
{
	$text = $template_text;
	$variable_regex = "#%V_*(.*?)%#s";

	preg_match_all($variable_regex, $text, $variable_matches, PREG_SET_ORDER);

	foreach ($variable_matches as $match)
		{
		$resolved_text = $this->email_resolve($config_data, $match[0]);
		$text = str_replace($match[0], $resolved_text, $text);
		}

	return $text;
}

// -------------------------------------------------------------------------------
// Send the email
// Returns blank if ok, or an error message on failure
//
function sendEmail($config_data)
{
// get the user's ip address, browser

	$this->data->ip = $this->getIPaddress();
	$this->data->browser_id = $this->getBrowser($this->data->browser_string);

// build the message to be sent to the site admin

	$body = $this->email_merge($config_data->admin_template, $config_data);
	jimport('joomla.mail.helper');
	$clean_body = JMailHelper::cleanBody($body);
	$clean_subject = JMailHelper::cleanSubject($this->data->subject);

// build the Joomla mail object

	$app = JFactory::getApplication();
	$mail = JFactory::getMailer();

	if ($config_data->email_html)
		$mail->IsHTML(true);
	else
		$clean_body = $this->html2text($clean_body);

	$mail->setSender(array($app->getCfg('mailfrom'), $app->getCfg('fromname')));
	$to_add = explode(',',$config_data->toPrimary);
	$mail->addRecipient($to_add);
	if (!empty($config_data->ccAddress))
		{
		$cc_add = explode(',',$config_data->ccAddress);
		$mail->addCC($cc_add);
		}
	if (!empty($config_data->bccAddress))
		{
		$bcc_add = explode(',',$config_data->ccAddress);
		$mail->addBCC($bcc_add);
		}
	$mail->addReplyTo(array($this->data->from_email, $this->data->from_name));
	$mail->setSubject($clean_subject);
	$mail->setBody($clean_body);
	$ret_main = $mail->Send();
	if ($ret_main === true)
		$this->data->status_main = '1';
	else
		$this->data->status_main = $mail->ErrorInfo;
	
// if we should send the user a copy, send it separately

	if (($config_data->show_copy == LAFC_COPYME_ALWAYS) or ($this->data->copy_me == 1))
		{
		$body = $this->email_merge($config_data->user_template, $config_data);
		$clean_body = JMailHelper::cleanBody($body);
		$mail = JFactory::getMailer();
		if ($config_data->email_html)
			$mail->IsHTML(true);
		else
			$clean_body = $this->html2text($clean_body);
		$mail->setSender(array($app->getCfg('mailfrom'), $app->getCfg('fromname')));
		$mail->addRecipient($this->data->from_email);
		$mail->setSubject($clean_subject);
		$mail->setBody($clean_body);
		$ret_copy = $mail->Send();
		if ($ret_copy === true)
			$this->data->status_copy = '1';
		else
			$this->data->status_copy = $mail->ErrorInfo;
		}
	else
		$this->data->status_copy = '0';		// copy not requested
		
	return $this->data->status_main;		// both statuses are logged, but the main status decides what happens next
}

// -------------------------------------------------------------------------------
// Found at http://sb2.info/php-script-html-plain-text-convert/
//
function html2text($html)
{
    $tags = array (
    0 => '~<h[123][^>]+>~si',
    1 => '~<h[456][^>]+>~si',
    2 => '~<table[^>]+>~si',
    3 => '~<tr[^>]+>~si',
    4 => '~<li[^>]+>~si',
    5 => '~<br[^>]+>~si',
    6 => '~<p[^>]+>~si',
    7 => '~<div[^>]+>~si',
    );
    $html = preg_replace($tags,"\n",$html);
    $html = preg_replace('~</t(d|h)>\s*<t(d|h)[^>]+>~si',' - ',$html);
    $html = preg_replace('~<[^>]+>~s','',$html);
    // reducing spaces
    $html = preg_replace('~ +~s',' ',$html);
    $html = preg_replace('~^\s+~m','',$html);
    $html = preg_replace('~\s+$~m','',$html);
    // reducing newlines
    $html = preg_replace('~\n+~s',"\n",$html);
    return $html;
}

//-------------------------------------------------------------------------------
// Validate single email address
// if invalid, returns an error message
// 
function email_check($config_data)
{
	$msg = '';
	jimport('joomla.mail.helper');
	// load the front end Flexicontact language file for the current language
	$lang = JFactory::getLanguage();
	$lang->load(strtolower(LAFC_COMPONENT), JPATH_ADMINISTRATOR);

	if (isset($config_data->toPrimary))
		{
		if (empty($config_data->toPrimary) or !JMailHelper::isEmailAddress($config_data->toPrimary))
			$msg .= '('.JText::_('COM_FLEXICONTACT_V_EMAIL_TO').')';
		}
	else
		$msg .= '('.JText::_('COM_FLEXICONTACT_V_EMAIL_TO').')';
		
	if (isset($config_data->ccAddress))
		{
		if (!JMailHelper::isEmailAddress($config_data->ccAddress))
			$msg .= ' ('.JText::_('COM_FLEXICONTACT_V_EMAIL_CC').')';
		}
	if (isset($config_data->bccAddress))
		{
		if (!JMailHelper::isEmailAddress($config_data->bccAddress))
			$msg .= ' ('.JText::_('COM_FLEXICONTACT_V_EMAIL_BCC').')';
		}
		
	if ($msg != '')
		$msg = JText::_('COM_FLEXICONTACT_BAD_EMAIL').' - '.$msg;
		
	return $msg;
}

}
		
		