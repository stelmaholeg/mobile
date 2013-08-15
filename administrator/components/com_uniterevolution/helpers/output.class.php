<?php
/**
 * @package Unite Revolution Slider for Joomla 1.7-2.5
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

// No direct access.
defined('_JEXEC') or die;

	class UniteRevolutionOutput{
		
		private static $counter = 0;
		
		private $sliderID;
		private $sliderJSID;
		private $slider;
		private $params;
		private $sliderHtmlID;
		private $sliderHtmlID_wrapper;
		private $putJSInBody = false;
		private $noConflictMode = true;
		private $arrSlides = array();
		private $slidesNumIndex;
		private $oneSlideMode = false;
		private $oneSlideData;
		private $previewMode = false;	//admin preview mode
		
		
		/**
		 * 
		 * output error message
		 */
		private function outputErrorMessage($message){
			?>
			<div style="width:700px;height:130px;font-size:14px;text-align:center;padding-top:30px;border:1px solid black;">
				<?php echo $message?>
			</div>
			<?php 
		}
		
		/**
		 * 
		 * set one slide mode for preview
		 */
		public function setOneSlideMode($data){
			$this->oneSlideMode = true;
			$this->oneSlideData = $data;
		}
		
		/**
		 * 
		 * set preview mode
		 */
		public function setPreviewMode(){
			$this->previewMode = true;
		}
		
		
		/**
		 * 
		 * include slider files.
		 */
		private function includeClientFiles(){
			
			//include custom css
			$document = JFactory::getDocument();
			
			$params = $this->params;
			$loadGoogleFont = $params->get("load_googlefont","false");
			if($loadGoogleFont != "false"){
				$googleFont = $params->get("google_font","");
				if(!empty($googleFont)){
					$urlGoogleFont = "http://fonts.googleapis.com/css?family={$googleFont}";
					$document->addStyleSheet($urlGoogleFont);
				}				
			}
						
			//put item js library
			$urlJsPlugins = GlobalsUniteRev::$urlItemPlugin."js/jquery.themepunch.plugins.min.js";
			$urlJsRevolution = GlobalsUniteRev::$urlItemPlugin."js/jquery.themepunch.revolution.min.js";
			
			if($this->putJSInBody == false){		 //put item js in head
				$document->addScript($urlJsPlugins);
				$document->addScript($urlJsRevolution);
			}
			else{								 //put item js in body
				?>
					<script type="text/javascript" src="<?php echo $urlJsPlugins?>"></script>
					<script type="text/javascript" src="<?php echo $urlJsRevolution?>"></script>
				<?php
			}
			
			//add styles			
			$urlSettingsCSS = GlobalsUniteRev::$urlItemPlugin."css/settings.css";
			$urlCaptionsCSS = GlobalsUniteRev::$urlCaptionsCss;
			
			$document->addStyleSheet($urlSettingsCSS);
			$document->addStyleSheet($urlCaptionsCSS);
		} 
	
		
		/**
		 * 
		 * init the slider, set all class vars
		 */
		private function initSlider($sliderID){
			self::$counter++;
			
			//set basic vars
			$this->sliderID = $sliderID;
			$this->slider = HelperUniteRev::getSlider($sliderID);			
			$this->params = $this->slider["params"];
			$this->sliderJSID = "unite_revolution_slider_".$this->sliderID."_".self::$counter;
			$this->arrSlides = HelperUniteRev::getArrSlides($this->sliderID);
			$this->slidesNumIndex =  HelperUniteRev::getSlidesNumbersByIDs($this->arrSlides);
		}
		
		
		/**
		 * 
		 * get slide full width video data
		 */
		private function getSlideFullWidthVideoData(JRegistry $slideParams){
			
			$response = array("found"=>false);
			
			//deal full width video:
			$enableVideo = $slideParams->get("enable_video","false");
			if($enableVideo != "true")
				return($response);
				
			$videoID = $slideParams->get("video_id","");
			$videoID = trim($videoID);
			
			if(empty($videoID))
				return($response);
				
			$response["found"] = true;
			
			$videoType = is_numeric($videoID)?"vimeo":"youtube";
			$videoAutoplay = $slideParams->get("video_autoplay");
			
			$response["type"] = $videoType;
			$response["videoID"] = $videoID;
			$response["autoplay"] = UniteFunctionsRev::strToBool($videoAutoplay);
			
			return($response);
		}
		
		/**
		 * 
		 * put full width video layer
		 */
		private function putFullWidthVideoLayer($videoData){
			if($videoData["found"] == false)
				return(false);
			
			$autoplay = UniteFunctionsRev::boolToStr($videoData["autoplay"]);
			
			$htmlParams = 'data-x="0" data-y="0" data-speed="500" data-start="10" data-easing="easeOutBack"';
			
			$videoID = $videoData["videoID"];
			
			if($videoData["type"] == "youtube"):	//youtube
				?>	
				<div class="caption fade fullscreenvideo" data-autoplay="<?php echo $autoplay?>" <?php echo $htmlParams?>><iframe src="http://www.youtube.com/embed/<?php echo $videoID?>?hd=1&amp;wmode=opaque&amp;controls=1&amp;showinfo=0;rel=0;" width="100%" height="100%"></iframe></div>				
				<?php 
			else:									//vimeo
				?>
				<div class="caption fade fullscreenvideo" data-autoplay="<?php echo $autoplay?>" <?php echo $htmlParams?>><iframe src="http://player.vimeo.com/video/<?echo $videoID?>?title=0&amp;byline=0&amp;portrait=0;api=1" width="100%" height="100%"></iframe></div>
				<?php
			endif;
		}
		
		
		/**
		 * 
		 * filter the slides for one slide preview
		 */		
		private function filterOneSlide(){
			$oneSlideID = $this->oneSlideData["slideid"];
			$oneSlideParams = (array)$this->oneSlideData["params"];
			$oneSlideLayers = (array)$this->oneSlideData["layers"];
			$oneSlideLayers = UniteFunctionsRev::convertStdClassToArray($oneSlideLayers);
			
			$oneSlideParamsObj = new JRegistry();
			$oneSlideParamsObj->loadArray($oneSlideParams);
			
			$newSlides = array();
			
			//if the slide is new slide:
			$slide = array();
			$slide["id"] = 0;
			$slide["title"] = "Demo Slide";
			$slide["image"] = $this->oneSlideData["image"];
			$slide["params"] = $oneSlideParamsObj;	
			$slide["layers"] = $oneSlideLayers;
													
			$newSlides[] = $slide;	//add 2 slides
			$newSlides[] = $slide;
				
			$this->arrSlides = $newSlides;
		}
		
		
		/**
		 * 
		 * output the slides
		 */
		private function putSlides(){

			$sliderParams = $this->params;
			
			if(empty($this->arrSlides)):
				?>
				<div class="no-slides-text">
					No slides found, please add some slides
				</div>
				<?php 
			endif;
			
			$thumbWidth = $sliderParams->get("thumb_width",100);
			$thumbHeight = $sliderParams->get("thumb_height",50);
			
			$slideWidth = $sliderParams->get("slider_width",900);
			$slideHeight = $sliderParams->get("slider_height",350);
			
			$navigationType = $sliderParams->get("navigaion_type","none"); 
			$isThumbsActive = ($navigationType == "thumb")?true:false;
			
			$flagReize = $sliderParams->get("php_resize","none");
			$flagReize = ($flagReize == "on")?true:false;
			
			//for one slide preview
			if($this->oneSlideMode == true)				
				$this->filterOneSlide();
			
			?>
				<ul>
			<?php
			
			foreach($this->arrSlides as $slide){
				
				$slideParams = $slide["params"];
				
				$transition = $slideParams->get("slide_transition","random");
								
				$slotAmount = $slideParams->get("slot_amount","7");
				
				//get slide image url
				$urlSlideImage = UniteFunctionsRev::getVal($slide, "image");
				if(empty($urlSlideImage))
					$urlSlideImage = GlobalsUniteRev::$urlDefaultSlideImage;
				
				$urlSlideImage = UniteFunctionJoomlaRev::getImageUrl($urlSlideImage);
					
				$filenameSlideImage = UniteFunctionJoomlaRev::getImageFilename($urlSlideImage);
				$filepathSlideImage = UniteFunctionJoomlaRev::getImageFilepath($filenameSlideImage);
					
				//resize the image file with php resize if needed.
				if($flagReize == true){
					if(!empty($filenameSlideImage) && file_exists($filepathSlideImage))
						$urlSlideImage = UniteFunctionJoomlaRev::getImageOutputUrl($filenameSlideImage,$slideWidth,$slideHeight,true);
				}
				
				//get thumb url
				$htmlThumb = "";
				if($isThumbsActive == true){
					
					$urlThumb = $slideParams->get("slide_thumb","");
										
					if(empty($urlThumb)){	//try to get resized thumb
						
						if(!empty($filenameSlideImage) && file_exists($filepathSlideImage))
							$urlThumb = UniteFunctionJoomlaRev::getImageOutputUrl($filenameSlideImage,$thumbWidth,$thumbHeight,true);
					}
					
					//if not - put regular image:
					if(empty($urlThumb))						
						$urlThumb = $urlSlideImage;
					
					$urlThumb = UniteFunctionJoomlaRev::getImageUrl($urlThumb);
					
					$htmlThumb = 'data-thumb="'.$urlThumb.'" ';
				}

				//get link
				
				//get link
				$htmlLink = "";
				$enableLink = $slideParams->get("enable_link","false");
				if($enableLink == "true"){
					$linkType = $slideParams->get("link_type","regular");
					switch($linkType){
						
						//---- normal link
						default:		
						case "regular":
							$link = $slideParams->get("link","");
							if(!empty($link)){
								$linkOpenIn = $slideParams->get("link_open_in","same");
								$htmlTarget = "";
								if($linkOpenIn == "new")
									$htmlTarget = ' data-target="_blank"';
								
								$htmlLink = "data-link=\"$link\" $htmlTarget ";
							}
						break;		
						
						//---- link to slide
						
						case "slide":
							$slideLink = $slideParams->get("slide_link");
							if(!empty($slideLink) && $slideLink != "nothing"){
								//get slide index from id
								if(is_numeric($slideLink))
									$slideLink = UniteFunctionsRev::getVal($this->slidesNumIndex, $slideLink);
								
								if(!empty($slideLink))
									$htmlLink = "data-link=\"slide\" data-linktoslide=\"$slideLink\" ";
							}
						break;
					}
				}
				
				//set link position:
				$linkPos = $slideParams->get("link_pos","front");
				if($linkPos == "back")
					$htmlLink .= ' data-slideindex="back"';
				
				//set delay
				$htmlDelay = "";
				$delay = $slideParams->get("delay","");
				if(!empty($delay) && is_numeric($delay))
					$htmlDelay = "data-delay=\"$delay\" ";
									
				//get duration
				$htmlDuration = "";
				$duration = $slideParams->get("transition_duration","");
				if(!empty($duration) && is_numeric($duration))
					$htmlDuration = "data-masterspeed=\"$duration\" ";

				//get rotation
				$htmlRotation = "";
				$rotation = $slideParams->get("transition_rotation","");
				if(!empty($rotation)){
					$rotation = (int)$rotation;
					if($rotation != 0){
						if($rotation > 720 && $rotation != 999)
							$rotation = 720;
						if($rotation < -720)
							$rotation = -720;
					}
					$htmlRotation = "data-rotate=\"$rotation\" ";
				}
					
				$videoData = $this->getSlideFullWidthVideoData($slideParams);
				
				$htmlParams = $htmlDuration.$htmlLink.$htmlThumb.$htmlDelay.$htmlRotation;
				
				//Html
				?>					
					<li data-transition="<?php echo $transition?>" data-slotamount="<?php echo $slotAmount?>" <?php echo $htmlParams?>> 
					    
						<img src="<?php echo $urlSlideImage?>" alt=""/>
						
						<?php	//put video:
							if($videoData["found"] == true)
								$this->putFullWidthVideoLayer($videoData);
								
							$this->putCreativeLayer($slide)
						?>
					</li>
				<?php 
			}	//get foreach
			
			?>
				</ul>
			<?php
		}
		
		
		/**
		 * 
		 * put creative layer
		 */
		private function putCreativeLayer($slide){
			
			$layers = $slide["layers"];
			$layers = UniteFunctionsRev::convertStdClassToArray($layers);
			
			if(empty($layers))
				return(false);
			?>
				<?php foreach($layers as $layer):
					
					$type = UniteFunctionsRev::getVal($layer, "type","text");
										
					$class = UniteFunctionsRev::getVal($layer, "style");
					$animation = UniteFunctionsRev::getVal($layer, "animation","fade");
										
					//set output class:
					$outputClass = "tp-caption ". trim($class);
						$outputClass = trim($outputClass) . " ";
											
					$outputClass .= trim($animation);
					
					$left = UniteFunctionsRev::getVal($layer, "left",0);
					$top = UniteFunctionsRev::getVal($layer, "top",0);
					$speed = UniteFunctionsRev::getVal($layer, "speed",300);
					$time = UniteFunctionsRev::getVal($layer, "time",0);
					$easing = UniteFunctionsRev::getVal($layer, "easing","easeOutExpo");
					$randomRotate = UniteFunctionsRev::getVal($layer, "random_rotation","false");
					$randomRotate = UniteFunctionsRev::boolToStr($randomRotate);
										
					$text = UniteFunctionsRev::getVal($layer, "text");
					
					$htmlVideoAutoplay = "";
					
					//set html:
					//set html:
					$html = "";
					switch($type){
						default:
						case "text":						
							$html = $text;
						break;
						case "image":
							$urlImage = UniteFunctionsRev::getVal($layer, "image_url");
							$html = '<img src="'.$urlImage.'" alt="'.$text.'"/>';
							$imageLink = UniteFunctionsRev::getVal($layer, "link","");
							if(!empty($imageLink)){
								$openIn = UniteFunctionsRev::getVal($layer, "link_open_in","same");

								$target = "";
								if($openIn == "new")
									$target = ' target="_blank"';
									
								$html = '<a href="'.$imageLink.'"'.$target.' title="">'.$html.'</a>';
							}								
						break;
						case "video":
							$videoType = trim(UniteFunctionsRev::getVal($layer, "video_type"));
							$videoID = trim(UniteFunctionsRev::getVal($layer, "video_id"));
							$videoWidth = trim(UniteFunctionsRev::getVal($layer, "video_width"));
							$videoHeight = trim(UniteFunctionsRev::getVal($layer, "video_height"));
							
							switch($videoType){
								case "youtube":
									$html = "<iframe src='http://www.youtube.com/embed/{$videoID}?hd=1&amp;wmode=opaque&amp;controls=1&amp;showinfo=0;rel=0' width='{$videoWidth}' height='{$videoHeight}' style='width:{$videoWidth}px;height:{$videoHeight}px;'></iframe>";
								break;
								case "vimeo":
									$html = "<iframe src='http://player.vimeo.com/video/{$videoID}?title=0&amp;byline=0&amp;portrait=0' width='{$videoWidth}' height='{$videoHeight}' style='width:{$videoWidth}px;height:{$videoHeight}px;'></iframe>";
								break;
								default:
									UniteFunctionsRev::throwError("wrong video type: $videoType");
								break;
							}
							
							$videoAutoplay = UniteFunctionsRev::getVal($layer, "video_autoplay");
							if($videoAutoplay == "true")
								$htmlVideoAutoplay = ' data-autoplay="true"';								
							
						break;
					}

					//handle end transitions:
					$endTime = trim(UniteFunctionsRev::getVal($layer, "endtime"));
					$htmlEnd = "";
					if(!empty($endTime)){
						$htmlEnd = "data-end=\"$endTime\"";
						$endSpeed = trim(UniteFunctionsRev::getVal($layer, "endspeed"));
						if(!empty($endSpeed))
							 $htmlEnd .= " data-endspeed=\"$endSpeed\"";
							 
						$endEasing = trim(UniteFunctionsRev::getVal($layer, "endeasing"));
						if(!empty($endSpeed) && $endEasing != "nothing")
							 $htmlEnd .= " data-endeasing=\"$endEasing\"";
						
						//add animation to class
						$endAnimation = trim(UniteFunctionsRev::getVal($layer, "endanimation"));
						if(!empty($endAnimation) && $endAnimation != "auto")
							$outputClass .= " ".$endAnimation;	
					}
					
					//slide link
					$htmlLink = "";
					$slideLink = UniteFunctionsRev::getVal($layer, "link_slide");
					if(!empty($slideLink) && $slideLink != "nothing"){
						//get slide index from id
						if(is_numeric($slideLink))
							$slideLink = UniteFunctionsRev::getVal($this->slidesNumIndex, $slideLink);
						
						if(!empty($slideLink))
							$htmlLink = " data-linktoslide=\"$slideLink\"";
					}
					
					//hidden under resolution
					$htmlHidden = "";
					$layerHidden = UniteFunctionsRev::getVal($layer, "hiddenunder");
					
					if($layerHidden == "true")
						$htmlHidden = ' data-captionhidden="on"';
					
					$htmlParams = $htmlEnd.$htmlLink.$htmlVideoAutoplay.$htmlHidden;
					
				?>
				
				<div class="<?php echo $outputClass?>"  
					 data-x="<?php echo $left?>" 
					 data-y="<?php echo $top?>" 
					 data-speed="<?php echo $speed?>" 
					 data-start="<?php echo $time?>" 
					 data-easing="<?php echo $easing?>" <?php echo $htmlParams?> ><?php echo $html?></div>
				
				<?php endforeach;?>
			<?php 
		}
		
		
		/**
		 * 
		 * put slider javascript
		 */
		private function putJS(){
			
			$params = $this->params;
			
			$sliderType = $params->get("slider_type","fixed");
			$optFullWidth = ($sliderType == "fullwidth")?"on":"off";						
			
			//set thumb amount
			$numSlides = count($this->arrSlides);
			
			$thumbAmount = (int)$params->get("thumb_amount","5");
			if($thumbAmount > $numSlides)
				$thumbAmount = $numSlides;
			
			//get stop slider options
			 $stopSlider = $params->get("stop_slider","off");
			 $stopAfterLoops = $params->get("stop_after_loops","0");
			 $stopAtSlide = $params->get("stop_at_slide","2");
			 
			 if($stopSlider == "off"){
				 $stopAfterLoops = "-1";
				 $stopAtSlide = "-1";
			 }

			// set hide navigation after
			$hideThumbs = $params->get("hide_thumbs","200");
			$alwaysOn = $params->get("navigaion_always_on","false");
			if($alwaysOn == "true")
				$hideThumbs = "0";
			 
			
			//treat hide slider at limit
			$hideSliderAtLimit = $params->get("hide_slider_under","0");
			if(!empty($hideSliderAtLimit))
				$hideSliderAtLimit++;

			//this option is disabled in full width slider
			if($sliderType == "fullwidth")
				$hideSliderAtLimit = "0";
			
			$hideCaptionAtLimit = $params->get("hide_defined_layers_under","0");
			if(!empty($hideCaptionAtLimit))
				$hideCaptionAtLimit++;
			
			$hideAllCaptionAtLimit = $params->get("hide_all_layers_under","0");
			if(!empty($hideAllCaptionAtLimit))
				$hideAllCaptionAtLimit++;
				
			?>
			
			<script type="text/javascript">
				
				var tpj=jQuery;
				
				<?php if($this->noConflictMode == true):?>
					tpj.noConflict();
				<?php endif;?>

				var revapi<?php echo $this->sliderID?>;
				
				tpj(document).ready(function() {
				
				if (tpj.fn.cssOriginal != undefined)
					tpj.fn.css = tpj.fn.cssOriginal;
				
				if(tpj('#<?php echo $this->sliderHtmlID?>').revolution == undefined)
					revslider_showDoubleJqueryError('#<?php echo $this->sliderHtmlID?>',"joomla");
				else				
					revapi<?php echo $this->sliderID?> = tpj('#<?php echo $this->sliderHtmlID?>').show().revolution(
					 {
						delay:<?php echo $params->get("delay","9000")?>,
						startwidth:<?php echo $params->get("slider_width","900")?>,
						startheight:<?php echo $params->get("slider_height","350")?>,
						hideThumbs:<?php echo $hideThumbs?>,
						
						thumbWidth:<?php echo $params->get("thumb_width","100")?>,
						thumbHeight:<?php echo $params->get("thumb_height","50")?>,
						thumbAmount:<?php echo $thumbAmount?>,
						
						navigationType:"<?php echo $params->get("navigaion_type","none")?>",
						navigationArrows:"<?php echo $params->get("navigation_arrows","nexttobullets")?>",
						navigationStyle:"<?php echo $params->get("navigation_style","round")?>",
						
						touchenabled:"<?php echo $params->get("touchenabled","on")?>",
						onHoverStop:"<?php echo $params->get("stop_on_hover","on")?>",
						
						navOffsetHorizontal:<?php echo $params->get("nav_offset_hor","0")?>,
						navOffsetVertical:<?php echo $params->get("nav_offset_vert","20")?>,
						
						shadow:<?php echo $params->get("shadow_type","2")?>,
						fullWidth:"<?php echo $optFullWidth?>",

						stopLoop:"<?php echo $stopSlider?>",
						stopAfterLoops:<?php echo $stopAfterLoops?>,
						stopAtSlide:<?php echo $stopAtSlide?>,
								
						shuffle:"<?php echo $params->get("shuffle","off") ?>",
						
						hideSliderAtLimit:<?php echo $hideSliderAtLimit?>,
						hideCaptionAtLimit:<?php echo $hideCaptionAtLimit?>,
						hideAllCaptionAtLilmit:<?php echo $hideAllCaptionAtLimit?>
					});
				
				});	//ready
				
			</script>
			
			<?php			
		}
				
		
		/**
		 * put html images preload
		 */
		private function putPreloadImages(){
			
		}
		
		/**
		 * 
		 * fill the responsitive slider values for further output
		 */
		private function getResponsitiveValues(){
			$params = $this->params;
			
			$sliderWidth = (int)$params->get("slider_width");
			$sliderHeight = (int)$params->get("slider_height");
			
			$percent = $sliderHeight / $sliderWidth;
			
			$w1 = (int) $params->get("responsitive_w1",0);
			$w2 = (int) $params->get("responsitive_w2",0);
			$w3 = (int) $params->get("responsitive_w3",0);
			$w4 = (int) $params->get("responsitive_w4",0);
			$w5 = (int) $params->get("responsitive_w5",0);
			$w6 = (int) $params->get("responsitive_w6",0);
			
			$sw1 = (int) $params->get("responsitive_sw1",0);
			$sw2 = (int) $params->get("responsitive_sw2",0);
			$sw3 = (int) $params->get("responsitive_sw3",0);
			$sw4 = (int) $params->get("responsitive_sw4",0);
			$sw5 = (int) $params->get("responsitive_sw5",0);
			$sw6 = (int) $params->get("responsitive_sw6",0);
			
			$arrItems = array();
			
			//add main item:
			$arr = array();				
			$arr["maxWidth"] = -1;
			$arr["minWidth"] = $w1;
			$arr["sliderWidth"] = $sliderWidth;
			$arr["sliderHeight"] = $sliderHeight;
			$arrItems[] = $arr;
			
			//add item 1:
			if(empty($w1))
				return($arrItems);
				
			$arr = array();				
			$arr["maxWidth"] = $w1-1;
			$arr["minWidth"] = $w2;
			$arr["sliderWidth"] = $sw1;
			$arr["sliderHeight"] = floor($sw1 * $percent);
			$arrItems[] = $arr;
			
			//add item 2:
			if(empty($w2))
				return($arrItems);
			
			$arr["maxWidth"] = $w2-1;
			$arr["minWidth"] = $w3;
			$arr["sliderWidth"] = $sw2;
			$arr["sliderHeight"] = floor($sw2 * $percent);
			$arrItems[] = $arr;
			
			//add item 3:
			if(empty($w3))
				return($arrItems);
			
			$arr["maxWidth"] = $w3-1;
			$arr["minWidth"] = $w4;
			$arr["sliderWidth"] = $sw3;
			$arr["sliderHeight"] = floor($sw3 * $percent);
			$arrItems[] = $arr;
			
			//add item 4:
			if(empty($w4))
				return($arrItems);
			
			$arr["maxWidth"] = $w4-1;
			$arr["minWidth"] = $w5;
			$arr["sliderWidth"] = $sw4;
			$arr["sliderHeight"] = floor($sw4 * $percent);
			$arrItems[] = $arr;

			//add item 5:
			if(empty($w5))
				return($arrItems);
			
			$arr["maxWidth"] = $w5-1;
			$arr["minWidth"] = $w6;
			$arr["sliderWidth"] = $sw5;
			$arr["sliderHeight"] = floor($sw5 * $percent);
			$arrItems[] = $arr;
			
			//add item 6:
			if(empty($w6))
				return($arrItems);
			
			$arr["maxWidth"] = $w6-1;
			$arr["minWidth"] = 0;
			$arr["sliderWidth"] = $sw6;
			$arr["sliderHeight"] = floor($sw6 * $percent);
			$arrItems[] = $arr;
			
			return($arrItems);
		}
		
		
		/**
		 * 
		 * put responsitive inline styles
		 */
		private function putResponsitiveStyles(){
			
			$params = $this->params;
			$sliderWidth = (int)$params->get("slider_width");
			$sliderHeight = (int)$params->get("slider_height");
			
			$arrItems = $this->getResponsitiveValues();
			
			?>
			 
			<style type='text/css'>
			#<?php echo $this->sliderHtmlID?>, #<?php echo $this->sliderHtmlID_wrapper?> { width:<?php echo $sliderWidth?>px; height:<?php echo $sliderHeight?>px;}	
			
			<?php
			foreach($arrItems as $item):			
				$strMaxWidth = "";
				
				if($item["maxWidth"] >= 0)
					$strMaxWidth = "and (max-width: {$item["maxWidth"]}px)";
			?>
			
			   @media only screen and (min-width: <?php echo $item["minWidth"]?>px) <?php echo $strMaxWidth?> {
			 		  #<?php echo $this->sliderHtmlID?>, #<?php echo $this->sliderHtmlID_wrapper?> { width:<?php echo $item["sliderWidth"]?>px; height:<?php echo $item["sliderHeight"]?>px;}	
			   }
			
			<?php 
			endforeach;
			echo "</style>";
		}
		
		
		/**
		 * 
		 * output slider body
		 */
		private function putBody(){
			$params = $this->params;
						
			try{
				$bannerWidth = $params->get("slider_width");
				$bannerHeight = $params->get("slider_height");
				
				UniteFunctionsRev::validateNotEmpty($bannerWidth,"banner width");
				UniteFunctionsRev::validateNotEmpty($bannerHeight,"banner height");
								
				$sliderType = $params->get("slider_type","fixed");
				
				//set wrapper height
				$wrapperHeigh = $bannerHeight;
				
				//add thumb height
				if($params->get("navigaion_type","none") == "thumb"){
					$wrapperHeigh += $params->get("thumb_height");
				}
								
				$this->sliderHtmlID = "rev_slider_".$this->sliderID."_".self::$counter;
				$this->sliderHtmlID_wrapper = $this->sliderHtmlID."_wrapper";
								
				$containerStyle = "";
				
				//set position:
				$sliderPosition = $params->get("position","center");
				switch($sliderPosition){
					case "center":
					default:
						$containerStyle .= "margin:0px auto;";
					break;
					case "left":
						$containerStyle .= "float:left;";
					break;
					case "right":
						$containerStyle .= "float:right;";
					break;
				}
				
				//add background color
				$backgrondColor = trim($params->get("background_color"));
				if(!empty($backgrondColor))
					$containerStyle .= "background-color:$backgrondColor;";
								
				//set padding			
				$containerStyle .= "padding:".$params->get("padding","5")."px;";
				
				//set margin:
				if($sliderPosition != "center"){
					$containerStyle .= "margin-left:".$params->get("margin_left","0")."px;";
					$containerStyle .= "margin-right:".$params->get("margin_right","0")."px;";
				}
				
				$containerStyle .= "margin-top:".$params->get("margin_top","0")."px;";
				$containerStyle .= "margin-bottom:".$params->get("margin_bottom","0")."px;";
								
				//set height and width:
				$bannerStyle = "display:none;";	
				
				//add background image (to banner style)
				$showBackgroundImage = $params->get("show_background_image","false");
				if($showBackgroundImage == "true"){					
					$backgroundImage = $params->get("background_image");					
					if(!empty($backgroundImage))
						$bannerStyle .= "background-image:url($backgroundImage);background-repeat:no-repeat;";
				}
								
				//set wrapper and slider class:
				$sliderWrapperClass = "rev_slider_wrapper";
				$sliderClass = "rev_slider";
				
				switch($sliderType){
					default:
					case "fixed":
						$bannerStyle .= "height:{$bannerHeight}px;width:{$bannerWidth}px;";
						$containerStyle .= "height:{$bannerHeight}px;width:{$bannerWidth}px;";
					break;
					case "responsitive":
						$this->putResponsitiveStyles();
					break;
					case "fullwidth":
						$sliderWrapperClass .= " fullwidthbanner-container";
						$sliderClass .= " fullwidthabanner";
						$bannerStyle .= "max-height:{$bannerHeight}px;height:{$bannerHeight}px;";
						$containerStyle .= "max-height:{$bannerHeight}px;";
					break;
				}
								
				$htmlTimerBar = "";
				if($params->get("show_timerbar","true") == "true"){
					$timerPosition = $params->get("timebar_position","top");
					if($timerPosition == "top")
						$htmlTimerBar = '<div class="tp-bannertimer"></div>';
					else
						$htmlTimerBar = '<div class="tp-bannertimer tp-bottom"></div>';
				}
									
				$containerStyle.="direction:ltr;";
				
				?>	
				
				<!-- START REVOLUTION SLIDER -->
				
				<div id="<?php echo $this->sliderHtmlID_wrapper?>" class="<?php echo $sliderWrapperClass?>" style="<?php echo $containerStyle?>">
					<div id="<?php echo $this->sliderHtmlID ?>" class="<?php echo $sliderClass?>" style="<?php echo $bannerStyle?>">						
						<?php $this->putSlides()?>
						<?php echo $htmlTimerBar?>
					</div>
				</div>
				<?php 
				
				$this->putJS();
				?>
				<!-- END REVOLUTION SLIDER -->
				<?php 
				
			}catch(Exception $e){
				$message = $e->getMessage();
				$this->putErrorMessage($message);
			}
			
		}
		
		
		/**
		 * 
		 * set the js to load from the body
		 */
		public function setJsInBody(){
			$this->putJSInBody = true;
		}
		
		/**
		 * 
		 * set noconflict mode
		 */
		public function setConflictMode($mode){
			$this->noConflictMode = $mode;
		}
		
		
		/**
		 * 
		 * output the slider
		 * @param $sliderID
		 */
		public function outputSlider($sliderID){
			
			try{
				$this->initSlider($sliderID);				
				$this->putBody();
				$this->includeClientFiles();			
				
			}catch(Exception $e){
				$message = $e->getMessage();
				$this->outputErrorMessage($message);
			}			
		}
		
	}

?>