<?php
/**
 * @package Unite Revolution Slider for Joomla 1.7-2.5
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

// No direct access.
defined('_JEXEC') or die;
?>
	
	<fieldset class="adminform">
		<legend>Slider Settings</legend>
		<ul class="adminformlist">
		
		<li>
			<?php UniteFunctionJoomlaRev::putFormField($this->form, "slider_type","params"); ?>
			<div class="clear"></div>
		</li>
		<li>
			<label>Slider Size</label>
			<table class="double_table">
				<tr>
					<td class="table_cell1">
						<?php UniteFunctionJoomlaRev::putFormField($this->form, "slider_width","params"); ?>						
					</td>
					<td class="table_cell2">
						<?php UniteFunctionJoomlaRev::putFormField($this->form, "slider_height","params"); ?>			
					</td>					
				</tr>
			</table>		
		</li>
		<li>
			<br>
			<label>Responsive Settings</label>
			<table class="double_table_long">
				<tr>
					<td class="table_cell1">
						<?php UniteFunctionJoomlaRev::putFormField($this->form, "responsitive_w1","params"); ?>
					</td>
					<td class="table_cell2">
						<?php UniteFunctionJoomlaRev::putFormField($this->form, "responsitive_sw1","params"); ?>
					</td>					
				</tr>
				<tr>
					<td class="table_cell1">
						<?php UniteFunctionJoomlaRev::putFormField($this->form, "responsitive_w2","params"); ?>
					</td>
					<td class="table_cell2">
						<?php UniteFunctionJoomlaRev::putFormField($this->form, "responsitive_sw2","params"); ?>
					</td>					
				</tr>
				<tr>
					<td class="table_cell1">
						<?php UniteFunctionJoomlaRev::putFormField($this->form, "responsitive_w3","params"); ?>
					</td>
					<td class="table_cell2">
						<?php UniteFunctionJoomlaRev::putFormField($this->form, "responsitive_sw3","params"); ?>
					</td>					
				</tr>
				<tr>
					<td class="table_cell1">
						<?php UniteFunctionJoomlaRev::putFormField($this->form, "responsitive_w4","params"); ?>
					</td>
					<td class="table_cell2">
						<?php UniteFunctionJoomlaRev::putFormField($this->form, "responsitive_sw4","params"); ?>
					</td>					
				</tr>
				<tr>
					<td class="table_cell1">
						<?php UniteFunctionJoomlaRev::putFormField($this->form, "responsitive_w5","params"); ?>
					</td>
					<td class="table_cell2">
						<?php UniteFunctionJoomlaRev::putFormField($this->form, "responsitive_sw5","params"); ?>
					</td>					
				</tr>
				<tr>
					<td class="table_cell1">
						<?php UniteFunctionJoomlaRev::putFormField($this->form, "responsitive_w6","params"); ?>
					</td>
					<td class="table_cell2">
						<?php UniteFunctionJoomlaRev::putFormField($this->form, "responsitive_sw6","params"); ?>
					</td>					
				</tr>
				
			</table>					
		</li>		
	</ul>
	
	</fieldset>
				