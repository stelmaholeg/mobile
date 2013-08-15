<?php
/**
 * @package Unite Revolution Slider for Joomla 1.7-2.5
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
	
	// Check to ensure this file is included in Joomla!
	defined('_JEXEC') or die( 'Restricted access' );
	
	jimport('joomla.application.component.view');
	
	class JMasterViewUniteRev extends JView{
		
		private $userTemplate;
		
		/**
		 * 
		 * overwrite constructor function.
		 * do ajax operations.
		 * @param unknown_type $config
		 */
		public function __construct($config = array()){
			parent::__construct($config);
			
			if($this->_layout == "ajax"){
				$actions = new ActionsUniteRev();
				$actions->operate();
				exit();
			}
		}
		
		
		/**
		 * 
		 * get some master template path
		 */
		private function getMasterTemplatePath($filename){
			$filepath = dirname(__FILE__)."/tpl/$filename";
			return($filepath);
		}
		
		/**
		 * 
		 * display master template (master.php from tpl folder) 
		 */
		private function displayMasterTemplate(){
			
			
			//each view has self controls
			UniteControlsRev::emptyControls();
			
			if(isset($this->form))
				UniteControlsRev::loadControlsFromForm($this->form);
			
			$filepath = dirname(__FILE__)."/tpl/master.php";
			
			if(!is_file($filepath))
				UniteFunctionsRev::throwError("master template not found: $filepath");
			
			$arrControls = UniteControlsRev::getArrayForJsOutput();
			$jsonControls = json_encode($arrControls);
			
			//prepare content
			ob_start();
			require $filepath;				
			$output = ob_get_contents();
			ob_end_clean();
			
			//output content
			echo $output;
		}
		
		
		
		/**
		 * 
		 * replace the display function by display master function
		 * and all the files will go via the master.
		 */
		public function display($tpl = null){
			
			//displa user template inside the master
			$this->userTemplate = $tpl;
			
			$this->displayMasterTemplate();
		}
		
	}
	
?>