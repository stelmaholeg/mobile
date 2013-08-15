<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::core();

// Create some shortcuts.
$params		= &$this->item->params;
$n			= count($this->items);
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
?>

<?php if (empty($this->items)) : ?>

	<?php if ($this->params->get('show_no_articles', 1)) : ?>
	<p><?php echo JText::_('COM_CONTENT_NO_ARTICLES'); ?></p>
	<?php endif; ?>

<?php else : ?>

<form action="<?php echo htmlspecialchars(JFactory::getURI()->toString()); ?>" method="post" name="adminForm" id="adminForm">
	<?php if ($this->params->get('show_headings') || $this->params->get('filter_field') != 'hide' || $this->params->get('show_pagination_limit')) :?>
	<fieldset class="filters" style="display:none;">
		<?php if ($this->params->get('filter_field') != 'hide') :?>
		<legend class="hidelabeltxt">
			<?php echo JText::_('JGLOBAL_FILTER_LABEL'); ?>
		</legend>

		<div class="filter-search">
			<label class="filter-search-lbl" for="filter-search"><?php echo JText::_('COM_CONTENT_'.$this->params->get('filter_field').'_FILTER_LABEL').'&#160;'; ?></label>
			<input type="text" name="filter-search" id="filter-search" value="<?php echo $this->escape($this->state->get('list.filter')); ?>" class="inputbox" onchange="document.adminForm.submit();" title="<?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC'); ?>" />
		</div>
		<?php endif; ?>

		<?php if ($this->params->get('show_pagination_limit')) : ?>
        <div class="display-limit">
			<?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>&#160;
			<?php echo $this->pagination->getLimitBox(); ?>
		</div>
		<?php endif; ?>

	<!-- @TODO add hidden inputs -->
		<input type="hidden" name="filter_order" value="" />
		<input type="hidden" name="filter_order_Dir" value="" />
		<input type="hidden" name="limitstart" value="" />
	</fieldset>
	<?php endif; ?>

	<table class="category">
    	<tbody>
		<?php foreach ($this->items as $i => $article) : ?>
			<?php if ($this->items[$i]->state == 0) : ?>
				<tr class="system-unpublished cat-list-row<?php echo $i % 2; ?>">
			<?php else: ?>
				<tr style="margin-bottom:20px;" class="cat-list-row<?php echo $i % 2; ?>" >
			<?php endif; ?>
				<?php if (in_array($article->access, $this->user->getAuthorisedViewLevels())) : ?>
					<td class="list-title" style="padding-bottom: 25px;">
						<a title="" class="current_town" href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catid)); ?>">
                            <?php $imgs = json_decode($article->images,true); ?>
                            <img alt="" src="<?php echo $imgs['image_intro']; ?>" style="float:left;margin-right:20px;width:100px" />
                            <?php echo $this->escape($article->title); ?>
                        </a>
                        <span class="justify-rec">
                        <?php echo $article->introtext; ?>
                        </span>
						<?php if ($article->params->get('access-edit')) : ?>
						<ul class="actions">
							<li class="edit-icon">
								<?php echo JHtml::_('icon.edit', $article, $params); ?>
							</li>
						</ul>
						<?php endif; ?>
					</td>
                    <!--
					<?php if ($this->params->get('list_show_date')) : ?>
					<td class="list-date">
						<?php echo JHtml::_('date', $article->displayDate, $this->escape(
						$this->params->get('date_format', JText::_('DATE_FORMAT_LC3')))); ?>
					</td>
					<?php endif; ?>

					<?php if ($this->params->get('list_show_author', 1)) : ?>
					<td class="list-author">
						<?php if(!empty($article->author) || !empty($article->created_by_alias)) : ?>
							<?php $author =  $article->author ?>
							<?php $author = ($article->created_by_alias ? $article->created_by_alias : $author);?>

							<?php if (!empty($article->contactid ) &&  $this->params->get('link_author') == true):?>
								<?php echo JHtml::_(
										'link',
										JRoute::_('index.php?option=com_contact&view=contact&id='.$article->contactid),
										$author
								); ?>

							<?php else :?>
								<?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY', $author); ?>
							<?php endif; ?>
						<?php endif; ?>
					</td>
					<?php endif; ?>

					<?php if ($this->params->get('list_show_hits', 1)) : ?>
					<td class="list-hits">
						<?php echo $article->hits; ?>
					</td>
					<?php endif; ?>

				<?php else : // Show unauth links. ?>
					<td>
						<?php
							echo $this->escape($article->title).' : ';
							$menu		= JFactory::getApplication()->getMenu();
							$active		= $menu->getActive();
							$itemId		= $active->id;
							$link = JRoute::_('index.php?option=com_users&view=login&Itemid='.$itemId);
							$returnURL = JRoute::_(ContentHelperRoute::getArticleRoute($article->slug));
							$fullURL = new JURI($link);
							$fullURL->setVar('return', base64_encode($returnURL));
						?>
						<a href="<?php echo $fullURL; ?>" class="register">
							<?php echo JText::_( 'COM_CONTENT_REGISTER_TO_READ_MORE' ); ?></a>
					</td>
				<?php endif; ?>
				    -->
				</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>

<?php // Code to add a link to submit an article. ?>
<?php if ($this->category->getParams()->get('access-create')) : ?>
	<?php echo JHtml::_('icon.create', $this->category, $this->category->params); ?>
<?php  endif; ?>

<?php // Add pagination links ?>
<?php if (!empty($this->items)) : ?>
	<?php if (($this->params->def('show_pagination', 2) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) : ?>
	<div class="pagination">

		<?php if ($this->params->def('show_pagination_results', 1)) : ?>
		 	<p class="counter">
				<?php echo $this->pagination->getPagesCounter(); ?>
			</p>
		<?php endif; ?>

		<?php echo $this->pagination->getPagesLinks(); ?>
	</div>
	<?php endif; ?>
</form>
<?php  endif; ?>