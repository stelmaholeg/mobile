<?php
/********************************************************************
Product		: Flexicontact
Date		: 10 April 2012
Copyright	: Les Arbres Design 2010-2012
Contact		: http://extensions.lesarbresdesign.info
Licence		: GNU General Public License
*********************************************************************/
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');
jimport('joomla.html.pagination');

class FlexicontactModelLog extends JModel
{
var $_data;
var $_app = null;
var $_pagination = null;

function __construct()
{
	parent::__construct();
	$this->_app = JFactory::getApplication();
}

function initData()					// initialise data for a new row
{
	$this->_data = new stdClass();
	$this->_data->id = 0;
	$this->_data->datetime = 'NOW()';
	$this->_data->name = '';
	$this->_data->email = '';
	$this->_data->subject = '';
	$this->_data->message = '';
	$this->_data->status_main = '';
	$this->_data->status_copy = '';
	$this->_data->ip = '';
	$this->_data->browser_id = 0;
	$this->_data->browser_string = '';
	$this->_data->field1 = '';
	$this->_data->field2 = '';
	$this->_data->field3 = '';
	$this->_data->field4 = '';
	$this->_data->field5 = '';
 	return $this->_data;
}

//-------------------------------------------------------------------------------
// get an existing row
// return false with an error if we couldn't find it
//
function getOne($id)
{
	$query = "SELECT * FROM #__flexicontact_log WHERE id = '$id'";
	$this->_db->setQuery($query);
	$this->_data = $this->_db->loadObject();

	if ($this->_data)
		return $this->_data;
	else
		return false;
}

//---------------------------------------------------------------
//
function store($email_data)
{
	$query = 'INSERT INTO `#__flexicontact_log` 
		(`datetime`, `name`, `email`, `subject`, `message`, `status_main`, `status_copy`, 
			`ip`, `browser_id`, `browser_string`, 
			`field1`, `field2`, `field3`, `field4`, `field5`) 
		VALUES
			( NOW(), '.
			$this->_db->Quote($email_data->from_name).','.
			$this->_db->Quote($email_data->from_email).','.
			$this->_db->Quote($email_data->subject).','.
			$this->_db->Quote($email_data->area_data).','.
			$this->_db->Quote($email_data->status_main).','.
			$this->_db->Quote($email_data->status_copy).','.
			$this->_db->Quote($email_data->ip).','.
			$this->_db->Quote($email_data->browser_id).','.
			$this->_db->Quote($email_data->browser_string).','.
			$this->_db->Quote($email_data->field1).','.
			$this->_db->Quote($email_data->field2).','.
			$this->_db->Quote($email_data->field3).','.
			$this->_db->Quote($email_data->field4).','.
			$this->_db->Quote($email_data->field5).')';

	$this->_db->setQuery($query);
	$this->_db->query();	
	return true;
}

//-------------------------------------------------------------------------------
// Return a pointer to our pagination object
// This should normally be called after getList()
//
function &getPagination()
{
	if ($this->_pagination == Null)
		$this->_pagination = new JPagination(0,0,0);
	return $this->_pagination;
}

//-------------------------------------------------------------------------------
// Get the list of logs for the log list screen
//
function &getList()
{
// if the table doesn't exist yet, just return an empty array

	if (!$this->table_exists())
		{
		$nothing = array();
		return $nothing;
		}
		
// get the filter states, order states, and pagination variables

	$filter_date = $this->_app->getUserStateFromRequest(LAFC_COMPONENT.'.filter_date','filter_date',LAFC_LOG_LAST_28_DAYS,'int');
	$limit = $this->_app->getUserStateFromRequest('global.list.limit', 'limit', $this->_app->getCfg('list_limit'), 'int');
	$limitstart = $this->_app->getUserStateFromRequest(LAFC_COMPONENT.'.limitstart', 'limitstart', 0, 'int');
	$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0); // In case limit has been changed
	$filter_order = $this->_app->getUserStateFromRequest(LAFC_COMPONENT.'.filter_order', 'filter_order', 'datetime');
	$filter_order_Dir = $this->_app->getUserStateFromRequest(LAFC_COMPONENT.'.filter_order_Dir', 'filter_order_Dir', 'desc');

// build the query

	$query_count = "Select count(*) ";
	$query_cols  = "Select id, datetime, name, email, subject, SUBSTRING(message,1,60) AS short_message, status_main, status_copy ";
	$query_from  = "From #__flexicontact_log ";

// where

	$query_where = "Where 1 ";

	switch ($filter_date)
		{
		case LAFC_LOG_ALL:
			break;
		case LAFC_LOG_LAST_7_DAYS:
			$query_where .= "and datetime >= DATE_SUB(CURRENT_DATE(), INTERVAL 7 DAY)";
			break;
		case LAFC_LOG_LAST_28_DAYS:
			$query_where .= "and datetime >= DATE_SUB(CURRENT_DATE(), INTERVAL 28 DAY)";
			break;
		case LAFC_LOG_LAST_12_MONTHS:
			$query_where .= "and datetime >= DATE_SUB(CURRENT_DATE(), INTERVAL 12 MONTH)";
		}	

// order by

	switch ($filter_order)							// validate column name
		{
		case 'name':
		case 'email':
		case 'subject':
			break;
		default:
			$filter_order = 'datetime';
		}

	if (strcasecmp($filter_order_Dir,'ASC') != 0)	// validate 'asc' or 'desc'
		$filter_order_Dir = 'DESC';

	$query_order = " Order by ".$filter_order.' '.$filter_order_Dir;

// get the total row count

	$count_query = $query_count.$query_from.$query_where;
	$this->_db->setQuery($count_query);
	$total = $this->_db->loadResult();
	
	if ($this->_db->getErrorNum())
		{
		$this->_app->enqueueMessage($this->_db->stderr(), 'error');
		$this->_data = false;
		return $this->_data;
		}

// setup the pagination object

	$this->_pagination = new JPagination($total, $limitstart, $limit);

//now get the data, within the limits required

	$main_query = $query_cols.$query_from.$query_where.$query_order;
	$this->_data = $this->_getList($main_query, $limitstart, $limit);

	if ($this->_db->getErrorNum())
		{
		$this->_app->enqueueMessage($this->_db->stderr(), 'error');
		$this->_data = false;
		return $this->_data;
		}

	return $this->_data;
}

//---------------------------------------------------------------
// Create the log table if it doesn't exist
//
function create()
{
	$query = "CREATE TABLE IF NOT EXISTS `#__flexicontact_log` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `datetime` datetime NOT NULL,
				  `name` varchar(60) NOT NULL DEFAULT '',
				  `email` varchar(60) NOT NULL DEFAULT '',
				  `subject` varchar(100) NOT NULL DEFAULT '',
				  `message` text NOT NULL,
				  `status_main` varchar(255) NOT NULL DEFAULT '',
				  `status_copy` varchar(255) NOT NULL DEFAULT '',
				  `ip` varchar(40) NOT NULL DEFAULT '',
				  `browser_id` tinyint(4) NOT NULL DEFAULT '0',
				  `browser_string` varchar(20) NOT NULL DEFAULT '',
				  `field1` varchar(100) DEFAULT NULL,
				  `field2` varchar(100) DEFAULT NULL,
				  `field3` varchar(100) DEFAULT NULL,
				  `field4` varchar(100) DEFAULT NULL,
				  `field5` varchar(100) DEFAULT NULL,
				  PRIMARY KEY (`id`),
				  KEY `DATETIME` (`datetime`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
				
	$this->_db->setQuery($query);
	$this->_db->query();

	return true;
}

//-------------------------------------------------------------------------------
// delete one log entries
//
function delete($id)
{
	$query = "delete from #__flexicontact_log where id = $id";
	$this->_db->setQuery($query);
	$this->_db->query();
}

//-------------------------------------------------------------------------------
// Check whether the log table exists in the database. 
// Returns TRUE if exists, FALSE if it doesn't
//
function table_exists()
{
	$tables = $this->_db->getTableList();
	$table = $this->replaceDbPrefix('#__flexicontact_log');
	if (in_array($table,$tables))
		return true;
	else
		return false;
}

//-------------------------------------------------------------------------------
// Joomla 1.7 took away replacePrefix() for some unknown reason
//
function replaceDbPrefix($sql)
{
	$app = JFactory::getApplication();
	$dbprefix = $app->getCfg('dbprefix');
	return str_replace('#__',$dbprefix,$sql);
}


}
		
		