<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php jimport('joomla.html.pagination'); ?>
<?php $numCols = 0; // number of td tag... ie does not support colspan="0" :( ?>
<form action="index.php" method="post" name="adminForm"  id="adminForm">
<div id="editcell">
	<table>
		<tr>
			<td align="left" width="100%">
				<?php echo JText::_( 'FILTER' ); ?>:
				<input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area"/>
				<button onclick="this.form.submit();"><?php echo JText::_( 'GO' ); ?></button>
				<button onclick="document.getElementById('search').value=''; this.form.submit();"><?php echo JText::_( 'RESET' ); ?></button>
			</td>
			<td nowrap="nowrap">&nbsp;
				
			</td>
		</tr>
	</table>

	<table class="adminlist">
	<thead>
		<tr>
			<th width="20" class="jcb_fieldDiv jcb_fieldLabel"><?php $numCols++; ?>
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->rows); ?>);" />
			</th>
			<th class="jcb_fieldDiv jcb_fieldLabel"><?php $numCols++; ?>
				<?php echo JText::_( 'EDIT' ); ?>
			</th>
			<!-- Joomla! Component Builder - begin code  -->
	<th class="jcb_fieldDiv jcb_fieldLabel"><?php $numCols++; ?>
		<?php echo JHTML::_('grid.sort', JText::_( 'FAV_ID' ), 'fav_id', $this->lists['order_Dir'], $this->lists['order']); ?>
	</th>
	<th class="jcb_fieldDiv jcb_fieldLabel"><?php $numCols++; ?>
		<?php echo JHTML::_('grid.sort', JText::_( 'PRODUCT_ID' ), 'product_id', $this->lists['order_Dir'], $this->lists['order']); ?>
	</th>
    <th class="jcb_fieldDiv jcb_fieldLabel"><?php $numCols++; ?>
		<?php echo JHTML::_('grid.sort', JText::_( 'PRODUCT_NAME' ), 'product_name', $this->lists['order_Dir'], $this->lists['order']); ?>
	</th>
	<th class="jcb_fieldDiv jcb_fieldLabel"><?php $numCols++; ?>
		<?php echo JHTML::_('grid.sort', JText::_( 'PRODUCT_QTY' ), 'product_qty', $this->lists['order_Dir'], $this->lists['order']); ?>
	</th>
	<th class="jcb_fieldDiv jcb_fieldLabel"><?php $numCols++; ?>
		<?php echo JHTML::_('grid.sort', JText::_( 'USER_ID' ), 'user_id', $this->lists['order_Dir'], $this->lists['order']); ?>
	</th>
    <th class="jcb_fieldDiv jcb_fieldLabel"><?php $numCols++; ?>
		<?php echo JHTML::_('grid.sort', JText::_( 'NAME' ), 'name', $this->lists['order_Dir'], $this->lists['order']); ?>
	</th>
	<th class="jcb_fieldDiv jcb_fieldLabel"><?php $numCols++; ?>
		<?php echo JHTML::_('grid.sort', JText::_( 'FAV_DATE' ), 'fav_date', $this->lists['order_Dir'], $this->lists['order']); ?>
	</th>

			<!-- Joomla! Component Builder - end code  -->
			<?php if(isset($this->rows[0]->ordering)): ?>
			<th width="20" class="jcb_fieldDiv jcb_fieldLabel"><?php $numCols++; ?>
				<?php echo JHTML::_('grid.sort', JText::_( 'ORDERING' ), 'ordering', $this->lists['order_Dir'], $this->lists['order']); ?><?php echo JHTML::_('grid.order',  $this->rows ); ?>
			</th>
			<?php endif; ?>
			<?php if(isset($this->rows[0]->published)): ?>
			<th class="jcb_fieldDiv jcb_fieldLabel"><?php $numCols++; ?>
				<?php echo JHTML::_('grid.sort', JText::_( 'PUBLISHED' ), 'published', $this->lists['order_Dir'], $this->lists['order']); ?>
			</th>
			<?php endif; ?>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="<?php echo $numCols; ?>">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
	
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->rows ); $i < $n; $i++)	{
		$row = &$this->rows[$i];
		$checked = JHTML::_('grid.id', $i, $row->fav_id);
		if(isset($this->rows[$i]->published)){
			$published	= JHTML::_('grid.published', $row, $i);
		}
		$link = JRoute::_( 'index.php?option=com_wishlist&controller=favoriteslist&task=edit&cid[]='. $row->fav_id);
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $checked; ?>
			</td>
			<td>
				<!-- You can use $link var for link edit controller -->
				<a href="<?php echo $link; ?>">[edit]</a>
			</td>
			<!-- Joomla! Component Builder - begin code  -->
	<td class="jcb_fieldDiv jcb_fieldValue">
		<?php echo $row->fav_id; ?>
	</td>
	<td class="jcb_fieldDiv jcb_fieldValue">
		<?php echo $row->product_id; ?>
	</td>
    <td class="jcb_fieldDiv jcb_fieldValue">
		<?php echo $row->product_name; ?>
	</td>
	<td class="jcb_fieldDiv jcb_fieldValue">
		<?php echo $row->product_qty; ?>
	</td>
	<td class="jcb_fieldDiv jcb_fieldValue">
		<?php echo $row->user_id; ?>
	</td>
    <td class="jcb_fieldDiv jcb_fieldValue">
		<?php echo $row->name; ?>
	</td>
	<td class="jcb_fieldDiv jcb_fieldValue">
		<?php echo $row->fav_date; ?>
	</td>

			<!-- Joomla! Component Builder - end code  -->
			<?php if(isset($this->rows[$i]->ordering)): ?>
			<td nowrap>
            	<?php 
					$page = new JPagination( $n, 1, $n );
				?>
					<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="text_area" style="text-align: center" />
					<span><?php echo $page->orderUpIcon( $i, $i>0, 'orderup', JText::_( 'MOVE_UP' ), true ); ?></span>
					<span><?php echo $page->orderDownIcon( $i, $n, $i<$n, 'orderdown', JText::_( 'MOVE_DOWN' ), true ); ?></span>					
			</td>
			<?php endif; ?>
			<?php if(isset($this->rows[$i]->published)): ?>
			<td>
				<?php echo $published; ?>
			</td>
			<?php endif; ?>
		</tr>
	<?php
		$k = 1 - $k;
	}
	?>
	</table>
</div>

<input type="hidden" name="option" value="com_wishlist" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="favoriteslist" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>
