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

class UniteRevolutionViewItems extends JMasterViewUniteRev
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $arrSliders;
	protected $linkSliderSettings;
	protected $sliderID;
	protected $operations;
	
	
	public function display($tpl = null)
	{
		$this->operations = new HelperUniteOperationsRev();
		
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		
		$this->arrSliders = $this->get("ArrSliders");
		$this->sliderID = $this->get("SliderID");
		
		$this->linkSliderSettings = HelperUniteRev::getViewUrl_Slider($this->sliderID);
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		
		$this->addToolbar();		
		parent::display($tpl);
	}
	
	
	/**
	 * 
	 * additems toolbar
	 */
	protected function addToolbar()
	{
		//$sliderTitle = $this->arrSliders[$this->sliderID]["title"];
		$arrSlider = HelperUniteRev::getSlider($this->sliderID);
		$sliderTitle = $arrSlider["title"];
				
		$title = JText::_('COM_UNITEREVOLUTION'). " - ".$sliderTitle." - ";
		$title .= "<small>[".JText::_('COM_UNITEREVOLUTION_SLIDES')."]</small>";
		
		JToolBarHelper::title($title, 'generic.png');
		
		$numSliders = count($this->arrSliders);
		if($numSliders > 0){
			JUniteToolBarHelperRev::addComboButton("button_new_slide", "Add Slide", "Adding...", "Slide Added!","icon-32-new");			
			JToolBarHelper::divider();
			JUniteToolBarHelperRev::addCustomButton("button_close","Close","icon-32-cancel");
		}
		
	}
	
	
	/**
	 * 
	 * get slide list item html
	 */
	public function getSlideHtml($item,$numItem){
			
			$sliderID = $item->sliderid;
			$itemID = $item->id;
			
			//get params
			$params = new JRegistry();			
			$params->loadString($item->params, "json");
			
			$urlRoot = JURI::root();
			
			//get image url's:
			$urlImage = $params->get("image");
			if(empty($urlImage))
				$urlImage = GlobalsUniteRev::$urlDefaultSlideImage;
			
			$image = UniteFunctionJoomlaRev::getImageFilename($urlImage);
			
			$thumbUrl = UniteFunctionJoomlaRev::getImageOutputUrl($image,200,100,true);
			$imageUrl = $urlRoot.$image;
			
			$img_file = pathinfo($imageUrl,PATHINFO_BASENAME);
			
			$itemTitle = $item->title." ($img_file)";
			
			$itemTitle = htmlspecialchars($itemTitle);
			
			$linkItem = HelperUniteRev::getViewUrl_Item($sliderID,$itemID);
			
			$isPublished = $item->published;
			
			ob_start();
			
			?>
			<li id="item_<?php echo $itemID?>">
				<span class="slide-col col-checkbox">
					<div class='num-item'>
						<label class="label_numitem" for="check_item_<?php echo $itemID?>"><?php echo $numItem?></label>							
					</div>
					
					<div class="published_icon_wrapper">						
						<?php if($isPublished):?>
						
						<a class="jgrid publish_link" data-published="true" data-itemid="<?php echo $itemID?>" title="<?php echo JText::_("COM_UNITEREVOLUTION_UNPUBLISH_ITEM")?>" href="javascript:void(0);">							<div class="publish_loader" style="display:none;"></div>
							<span class="state publish">
								<span class="text"><?php echo JText::_("COM_UNITEREVOLUTION_PUBLISHED")?></span>
							</span>
						</a>
						
						<?php else:?>
						
						<a class="jgrid publish_link" data-published="false" data-itemid="<?php echo $itemID?>" title="<?php echo JText::_("COM_UNITEREVOLUTION_PUBLISH_ITEM")?>" href="javascript:void(0);">
							<div class="publish_loader" style="display:none;"></div>
							<span class="state unpublish">
								<span class="text"><?php echo JText::_("COM_UNITEREVOLUTION_UNPUBLISHED")?></span>
							</span>
						</a>
						
						<?php endif?>	
					</div>
					
				</span>
				<span class="slide-col col-title">
				
					<a class='link_slide_title' href="<?php echo $linkItem?>">
						<?php echo $itemTitle?>
					</a>

					<a href="<?php echo $linkItem?>" data-itemid="<?php echo $itemID?>" class="button_edit_slide btn btn-small btn-small">Edit Slide</a>
					
				</span>
				<span class="slide-col col-image">
					<a class="modal" href="<?php echo $imageUrl ?>">
						<img src="<?php echo $thumbUrl?>" alt="slide image" />
					</a>
				</span>
				<span class="slide-col col-operations">
				
					<a href="javascript:void(0)" data-itemid="<?php echo $itemID?>" class="button_delete_slide btn btn-danger btn-small">Delete</a>
					<span class="deleting_slide_loader" style="display:none;"> <?php echo jText::_("COM_UNITEREVOLUTION_DELETING_SLIDE") ?> </span>
					<a href="javascript:void(0)" data-itemid="<?php echo $itemID?>" class="button_duplicate_slide btn btn-small btn-small">Duplicate</a>
					<span class="duplicate_slide_loader" style="display:none;"><?php echo JText::_("COM_UNITEREVOLUTION_DUPLICATING_SLIDE")?></span>
					
				</span>
				<span class="slide-col col-handle">
					<div class="col-handle-inside">
						<span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
					</div>						
				</span>
			</li>
			
		<?php
		
		$content = ob_get_contents();
		ob_clean();
		ob_end_clean();
		
		return($content);
	}
	
}