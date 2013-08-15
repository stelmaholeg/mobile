<?php
/**
 * @package Unite Revolution Slider for Joomla 1.7-2.5
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

/**
 * operations that are unique to the revolution slider
 */

defined('_JEXEC') or die;

class HelperUniteOperationsRev{

		private $db;
		
		public function __construct(){
			$this->db = new UniteDBRev();
		}

		/**
		 * 
		 * valdiate that a slider exists
		 */
		public function validateSliderExists($sliderID){
			$sliderID = (int)$sliderID;			
			$rows = $this->db->fetch(GlobalsUniteRev::TABLE_SLIDERS,"id=$sliderID");
			if(empty($rows))
				UniteFunctionsRev::throwError("slider not found: $sliderID");
		}
		
		/**
		 * normalize layers text, and get layers
		 * 
		 */
		public function normalizeLayersBeforeSave($arrLayers){
		
			foreach ($arrLayers as $key=>$layer){
				$text = $layer["text"];
				$text = stripslashes($text);
				
				$arrLayers[$key]["text"] = $text;
				
				if(isset($layer["image_url"]))
					$arrLayers[$key]["image_url"] = UniteFunctionJoomlaRev::getImageFilename($layer["image_url"]);
				
			}
			
			return($arrLayers);
		}
		
		
		/**
		 * 
		 * get slider raw data
		 * @param $sliderID
		 */
		private function getSliderRawData($sliderID){
			$sliderID = (int)$sliderID;
			
			$rows = $this->db->fetch(GlobalsUniteRev::TABLE_SLIDERS,"id=$sliderID");
			
			if(empty($rows))
				UniteFunctionsRev::throwError("slider not found: $sliderID");
				
			$row = $rows[0];			
			return($row);
		}
		
		
		/**
		 * 
		 * get slides from the database
		 */
		private function getSlidesRawData($sliderID){
			$sliderID = (int)$sliderID;
			$rows = $this->db->fetch(GlobalsUniteRev::TABLE_SLIDES,"sliderid=".$sliderID);
			return($rows);
		}
		
		
		/**
		 * 
		 * get slides short data
		 */
		public function getSlidesShort($sliderID){
			$rows = $this->getSlidesRawData($sliderID);
			$arrSlides = array();
			foreach($rows as $index => $row){
				$slideID = $row["id"];
				$title = $row["title"];
				$pathImage = $row["image"];
				$info = pathinfo($pathImage);
				$filename = UniteFunctionsRev::getVal($info, "basename");
				$counter = $index+1;
				$name = "Slide $counter ($filename)";
				$arrSlides[$slideID] = $name;
			}
			
			return($arrSlides);
		}
		
		
		/**
		 * get slider
		 */
		public function getSlider($sliderID){
			
			$row = $this->getSliderRawData($sliderID);
			
			$params = UniteFunctionJoomlaRev::decodeRegistryToArray($row, "params");
	
			$paramsReg = new JRegistry();
			$paramsReg->loadArray($params);
			
			$row["params"] = $paramsReg;
			
			return($row);
		}
		
		
		/**
		 * 
		 * add new slider
		 */
		public function addNewSlider($params){
			$arrInsert = array();
			$arrInsert["title"] = $params["title"];
			$arrInsert["published"] = 1;
			$arrInsert["params"] = json_encode($params);
			
			$sliderID = $this->db->insert(GlobalsUniteRev::TABLE_SLIDERS, $arrInsert);
			
			return($sliderID);
		}
		
		/**
		 * 
		 * save the slider, get sliderid and data array
		 */
		public function saveSlider($sliderID,$data){
			$sliderID = (int)$sliderID;
			
			$this->validateSliderExists($sliderID);
			$slider = $this->getSlider($sliderID);
			
			$arrUpdate = array();
			$arrUpdate["title"] = $data["title"];
			$arrUpdate["published"] = $data["published"];
			
			unset($data["id"]);
			unset($data["title"]);
			unset($data["published"]);
			
			$arrUpdate["params"] = json_encode($data);
			$this->db->update(GlobalsUniteRev::TABLE_SLIDERS, $arrUpdate, "id=$sliderID");	
		}
		
		/**
		 * 
		 * duplicate slider
		 */
		public function duplicateSlider($sliderID){
			$sliderID = (int)$sliderID;
			$this->validateSliderExists($sliderID);
			
			$response = $this->db->fetch(GlobalsUniteRev::TABLE_SLIDERS);
			$numSliders = count($response);
			$newSliderSerial = $numSliders+1;
			$newSliderTitle = "Slider".$newSliderSerial;
			
			//insert a new slider
			$sqlSelect = "select ".GlobalsUniteRev::FIELDS_SLIDER." from ".GlobalsUniteRev::TABLE_SLIDERS." where id={$sliderID}";
			$sqlInsert = "insert into ".GlobalsUniteRev::TABLE_SLIDERS." (".GlobalsUniteRev::FIELDS_SLIDER.") ($sqlSelect)";
			
			$this->db->runSql($sqlInsert);
			$lastID = $this->db->getLastInsertID();
			UniteFunctionsRev::validateNotEmpty($lastID);
			
			//update the new slider with the title and the alias values
			$arrUpdate = array();
			$arrUpdate["title"] = $newSliderTitle;
			$this->db->update(GlobalsUniteRev::TABLE_SLIDERS, $arrUpdate, "id={$lastID}");
			
			//duplicate slides
			$fields_slide = GlobalsUniteRev::FIELDS_SLIDE;
			$fields_slide = str_replace("sliderid", $lastID, $fields_slide);
			
			$sqlSelect = "select ".$fields_slide." from ".GlobalsUniteRev::TABLE_SLIDES." where sliderid={$sliderID}";
			$sqlInsert = "insert into ".GlobalsUniteRev::TABLE_SLIDES." (".GlobalsUniteRev::FIELDS_SLIDE.") ($sqlSelect)";
			
			$this->db->runSql($sqlInsert);
			
			return($lastID);
		}
		
		
		/**
		 * 
		 * get max order of the current slider
		 */
		private function getMaxOrder($sliderID){
			
			$maxOrder = 0;
			$arrSlideRecords = $this->db->fetch(GlobalsUniteRev::TABLE_SLIDES,"sliderid=".$sliderID,"ordering desc","","limit 1");
			
			if(empty($arrSlideRecords))
				return($maxOrder);
				
			$maxOrder = $arrSlideRecords[0]["ordering"];
			
			return($maxOrder);
		}
		
		
		/**
		 * 
		 * add new slide
		 */
		public function addNewSlide($sliderID,$arrParams = array(),$arrLayers = array()){
			
			$maxOrder = $this->getMaxOrder($sliderID);
			$newOrder = $maxOrder+1;
			
			$sliderID = (int)$sliderID;	//escaping
			
			$jsonParams = json_encode($arrParams);
			
			$arrLayers = $this->normalizeLayersBeforeSave($arrLayers);

			$jsonLayers = json_encode($arrLayers);
			
			$arrInsert = array();
			$arrInsert["title"] = UniteFunctionsRev::getVal($arrParams, "title");
			$arrInsert["published"] = UniteFunctionsRev::getVal($arrParams, "published");
			$arrInsert["image"] = UniteFunctionsRev::getVal($arrParams, "image");
			$arrInsert["params"] = $jsonParams;
			$arrInsert["sliderid"] = $sliderID;
			$arrInsert["layers"] = $jsonLayers;	
			$arrInsert["ordering"] = $newOrder;	
			
			$slideID = $this->db->insert(GlobalsUniteRev::TABLE_SLIDES, $arrInsert);
			return($slideID);
		}
		
		
		/**
		 * 
		 * save slide to database
		 */
		public function saveSlide($slideID,$arrParams,$arrLayers){
			
			$slideID = (int)$slideID;	//escaping			
			$jsonParams = json_encode($arrParams);
			
			$arrLayers = $this->normalizeLayersBeforeSave($arrLayers);
			$jsonLayers = json_encode($arrLayers);
			
			$arrUpdate = array();
			$arrUpdate["title"] = UniteFunctionsRev::getVal($arrParams, "title");
			$arrUpdate["published"] = UniteFunctionsRev::getVal($arrParams, "published");
			$arrUpdate["image"] = UniteFunctionsRev::getVal($arrParams, "image");
			$arrUpdate["params"] = $jsonParams;
			$arrUpdate["layers"] = $jsonLayers;
			
			$this->db->update(GlobalsUniteRev::TABLE_SLIDES, $arrUpdate, "id='$slideID'");
		}
		
		/**
		 * 
		 * duplicate slide in db
		 */
		public function duplicateSlide($slideID){
			
			$slide = $this->getSlide($slideID);
			$sliderID = $slide["sliderid"];
			$order = $slide["ordering"];
			 			
			$newOrder = $order+1;
			
			$this->shiftOrder($sliderID,$newOrder);
						
			//do duplication
			$sqlSelect = "select ".GlobalsUniteRev::FIELDS_SLIDE." from ".GlobalsUniteRev::TABLE_SLIDES." where id={$slideID}";
			$sqlInsert = "insert into ".GlobalsUniteRev::TABLE_SLIDES." (".GlobalsUniteRev::FIELDS_SLIDE.") ($sqlSelect)";
			
			$this->db->runSql($sqlInsert);
			$lastID = $this->db->getLastInsertID();
			
			UniteFunctionsRev::validateNotEmpty($lastID,"last insert id");
			
			//update order
			$arrUpdate = array("ordering"=>$newOrder);
			
			$this->db->update(GlobalsUniteRev::TABLE_SLIDES, $arrUpdate, "id=$lastID");
			
			return($lastID);
		}
		
		
		/**
		 * 
		 * shift order of the slides from specific order
		 */
		private function shiftOrder($sliderID, $fromOrder){
			
			$where = " sliderid={$sliderID} and ordering >= $fromOrder";
			$sql = "update ".GlobalsUniteRev::TABLE_SLIDES." set ordering=(ordering+1) where $where";
			$this->db->runSql($sql);
		}
		
		
		/**
		 * 
		 * get slide data
		 */
		private function getSlide($slideID){
			$slideID = (int)$slideID;
			$response = $this->db->fetch(GlobalsUniteRev::TABLE_SLIDES,"id=$slideID");
			if(empty($response))
				UniteFunctionsRev::throwError("Slide with id: $slideID not found");
			$arrSlide = $response[0];
			return($arrSlide);
		}
		
		
		/**
		 * 
		 * parse css file and get the classes from there.
		 */
		public function getArrCaptionClasses($contentCSS){
						
			//parse css captions file
			$parser = new UniteCssParserRev();
			$parser->initContent($contentCSS);
			$arrCaptionClasses = $parser->getArrClasses();
			return($arrCaptionClasses);
		}
	
		/**
		 * 
		 * get contents of the css file
		 */
		public function getCaptionsContent(){
			$filepath = GlobalsUniteRev::$pathCaptionsCss;
			if(!file_exists($filepath))
				UniteFunctionsRev::throwError("Captions file: $filepath don't exists!");
				
			$contentCSS = file_get_contents($filepath);
			$contentCSS = trim($contentCSS);
			return($contentCSS);
		}

		/**
		 * 
		 * get contents of the css file
		 */
		public function getCaptionsContentOgirinal(){
			$filepath = GlobalsUniteRev::$pathCaptionsCssOriginal;
			if(!file_exists($filepath))
				UniteFunctionsRev::throwError("Captions original file: $filepath don't exists!");
			
			$contentCSS = file_get_contents($filepath);
			$contentCSS = trim($contentCSS);
			return($contentCSS);
		}
		
		
		/**
		 * 
		 * update captions css file content
		 * @return new captions html select 
		 */
		public function updateCaptionsContentData($content){
			$content = stripslashes($content);
			$content = trim($content);
			UniteFunctionsRev::writeFile($content, GlobalsUniteRev::$pathCaptionsCss);
			
			//output captions array 
			$arrCaptions = $this->getArrCaptionClasses($content);
			return($arrCaptions);
		}
		
		/**
		 * 
		 * copy from original css file to the captions css.
		 */
		public function restoreCaptionsCss(){
			
			if(!file_exists(GlobalsUniteRev::$pathCaptionsCssOriginal))
				UniteFunctionsRev::throwError("The original css file: captions_original.css doesn't exists.");
			
			$success = @copy(GlobalsUniteRev::$pathCaptionsCssOriginal, GlobalsUniteRev::$pathCaptionsCss);
			if($success == false)
				UniteFunctionsRev::throwError("Failed to restore from the original captions file.");
		}

		
		/**
		 * 
		 * export slider
		 */
		public function exportSlider($sliderID){
			
			$slider = $this->getSliderRawData($sliderID);
			$slides = $this->getSlidesRawData($sliderID);
			
			$params = $slider["params"];
			
			//modify slides:
			foreach($slides as $key=>$slide){
				unset($slide["id"]);
				unset($slide["sliderid"]);
				$slides[$key] = $slide;
			}
			
			$arrExport = array(
				"params" => $params,
				"slides" => $slides
			);
			
			$strExport = serialize($arrExport);
			$title = $slider["title"];
			if(empty($title))
				$title = "slider".$slider["id"];
			 
			UniteFunctionsRev::downloadFileFromString($strExport,$title.".txt");
		}
		
		/**
		 * 
		 * import slider
		 */
		public function importSlider($sliderID){
			
			$filepath = $_FILES["import_file"]["tmp_name"];
			$content = file_get_contents($filepath);
			$arrImport = unserialize($content);
			
			//update params
			$arrUpdate = array("params"=>$arrImport["params"]);
			$this->db->update(GlobalsUniteRev::TABLE_SLIDERS, $arrUpdate, "id=".$sliderID);
			
			//delete slides
			$this->db->delete(GlobalsUniteRev::TABLE_SLIDES, "sliderid=".$sliderID);
			
			//insert new slides
			$arrSlides = $arrImport["slides"];			
			foreach($arrSlides as $slideData){
				$slideData["sliderid"] = $sliderID;
				$this->db->insert(GlobalsUniteRev::TABLE_SLIDES, $slideData);
			}
			
		}
		
		
		/**
		 * 
		 * get button classes
		 */
		public function getButtonClasses(){
			
			$arrButtons = array(
				"red"=>"Red Button",
				"green"=>"Green Button",
				"blue"=>"Blue Button",
				"orange"=>"Orange Button",
				"darkgrey"=>"Darkgrey Button",
				"lightgrey"=>"Lightgrey Button",
			);
			
			return($arrButtons);
		}
		
		
		/**
		 * 
		 * preview slider output
		 * if output object is null - create object
		 */
		public function previewOutput($sliderID,$output = null){
						
			if($output == null)
				$output = new UniteRevolutionOutput();
			
			$output->setPreviewMode();
			
			//put the output html
			$urlPlugin = GlobalsUniteRev::$urlItemPlugin;
			
			
			?>
				<html>
					<head>
						<link rel='stylesheet' href='<?php echo GlobalsUniteRev::$urlItemPlugin?>css/settings.css' type='text/css' media='all' />
						<link rel='stylesheet' href='<?php echo GlobalsUniteRev::$urlItemPlugin?>css/captions.css' type='text/css' media='all' />
						<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js'></script>
						<script type='text/javascript' src='<?php echo GlobalsUniteRev::$urlItemPlugin?>js/jquery.themepunch.plugins.min.js'></script>
						<script type='text/javascript' src='<?php echo GlobalsUniteRev::$urlItemPlugin?>js/jquery.themepunch.revolution.js'></script>
					</head>
					<body style="padding:0px;margin:0px;">
						<?php
							$output->outputSlider($sliderID);		 
						?>
					</body>
				</html>
			<?php 
			exit();
		}
		
		
		/**
		 * 
		 * put slide preview by data
		 */
		public function putSlidePreviewByData($data){
			
			$data = UniteFunctionsRev::jsonDecodeFromClientSide($data);
			
			$sliderID = $data["sliderid"];
			
			$output = new UniteRevolutionOutput();
			$output->setOneSlideMode($data);
			
			$this->previewOutput($sliderID,$output);
		}

		
		/**
		 * 
		 * update slides order
		 */
		public function updateSlidesOrderFromData($data){
			$sliderID = $data["sliderid"];
			$this->validateSliderExists($sliderID);
			$arrIDs = $data["arrIDs"];
			
			foreach($arrIDs as $key=>$id){
				$arrUpdate = array();
				$order = $key+1;
				$arrUpdate["ordering"] = $order;
				
				$this->db->update(GlobalsUniteRev::TABLE_SLIDES, $arrUpdate, "id=".$id);
			}
		}
		
		
		/**
		 * 
		 * publish item form data
		 */
		public function updateItemPublished($itemID,$published){
			$itemID = (int)$itemID;
			
			$arrUpdate = array();
			$arrUpdate["published"] = $published;
			$this->db->update(GlobalsUniteRev::TABLE_SLIDES, $arrUpdate, "id=".$itemID);
		}
		
		
		/**
		 * 
		 * publish some item from data
		 */
		public function publishUnpublishItemFromData($data){
			$itemID = $data["itemID"];
			
			$isPublished = $data["isPublished"];
			$isPublished = UniteFunctionsRev::strToBool($isPublished);
			
			$newState = !$isPublished;
			$this->updateItemPublished($itemID,$newState);
			
			return($newState);
		}
		
		
		/**
		 * 
		 * delete slide
		 */
		private function deleteSlide($slideID){
			$slideID = (int)$slideID;
			$where = "id=$slideID";
			$success = $this->db->delete(GlobalsUniteRev::TABLE_SLIDES, $where);
		}
		
		
		/**
		 * 
		 * delete slide from data
		 */
		public function deleteSlideFromData($data){
			$slideID = $data["itemID"];
			$this->deleteSlide($slideID);
		}
		
		/**
		 * 
		 * add slide from data
		 */
		public function addSlideFromData($data){
			$sliderID = $data["sliderid"];
			$arrParams = array();
			$arrParams["published"] = true;
			$arrParams["title"] = JText::_("COM_UNITEREVOLUTION_SLIDE");
			
			$slideID = $this->addNewSlide($sliderID,$arrParams);
			
			return($slideID);
		}
		
		/**
		 * duplicate slide from data
		 */
		public function duplicateSlideFromData($data){
			$slideID = $data["itemID"];
			$this->duplicateSlide($slideID);
		}
		
}


?>