<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @since		1.6
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<div class="registration<?php echo $this->pageclass_sfx?>">
    <h1 style="float:left;">Регистрация</h1>
    <h1 style="float:right; color:#CECECE;">Шаг 1/1</h1>
    <div style="clear: both;"></div>
<?php if ($this->params->get('show_page_heading')) : ?>
	<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
<?php endif; ?>

	<form id="login-form" action="<?php echo JRoute::_('index.php?option=com_users&task=registration.register'); ?>" method="post" class="form-validate">
    <fieldset>
        <p style="margin:0;"><b>Основная информация</b></p>
<?php foreach ($this->form->getFieldsets() as $fieldset): // Iterate through the form fieldsets and display each one.?>
	<?php $fields = $this->form->getFieldset($fieldset->name);?>
	<?php if (count($fields)):?>
		<?php if (isset($fieldset->label)): ?>
		<?php endif;?>
		<?php foreach($fields as $field):// Iterate through the fields in the set and display them.?>
			<?php if ($field->hidden):// If the field is hidden, just display the input.?>
				<?php echo $field->input;?>
			<?php else:?>
                <?php if($i == 0) {$i = 1; continue;} ?>
                <?php if(base64_encode($field->label) == "PGxhYmVsIGlkPSJqZm9ybV9lbWFpbDEtbGJsIiBmb3I9Impmb3JtX2VtYWlsMSIgY2xhc3M9Imhhc1RpcCByZXF1aXJlZCIgdGl0bGU9ItCQ0LTRgNC10YEg0Y3Qu9C10LrRgtGA0L7QvdC90L7QuSDQv9C+0YfRgtGLOjrQktCy0LXQtNC40YLQtSDQsNC00YDQtdGBINGN0LvQtdC60YLRgNC+0L3QvdC+0Lkg0L/QvtGH0YLRiyI+0JDQtNGA0LXRgSDRjdC70LXQutGC0YDQvtC90L3QvtC5INC/0L7Rh9GC0Ys8c3BhbiBjbGFzcz0ic3RhciI+JiMxNjA7Kjwvc3Bhbj48L2xhYmVsPg=="){ ?>
                <p style="margin:15px 0 5px 0;"><b>Контактные данные</b></p>
                <?php } ?>
                <p>
					<?php echo $field->label; ?>
					<!--<?php if (!$field->required && $field->type!='Spacer'): ?>
						<span class="optional"><?php echo JText::_('COM_USERS_OPTIONAL'); ?></span>
					<?php endif; ?>-->
				    <?php echo ($field->type!='Spacer') ? $field->input : "&#160;"; ?>
                </p>
			<?php endif;?>
		<?php endforeach;?>
	<?php endif;?>
<?php endforeach;?>
        <p style="padding-left:211px;">
            <strong class="red">*</strong> - Поля, обязательные для заполнения
        </p>
        <p style="text-align: right;">
            <a target="_blank" href="index.php?option=com_content&view=article&id=23&Itemid=153">Преимущества регистрации</a>
        </p>
        <p style="text-align: right;">
            <a href=index.php?option=com_content&view=article&id=24&Itemid=108"">Пользовательское соглашение</a>
        </p>
    </fieldset>
		<div>
			<button type="submit" class="addtocart-button" style="cursor: pointer;"><?php echo JText::_('JREGISTER');?></button>
			<a style="float:right; margin-top:6px;" href="<?php echo JRoute::_('');?>" title="<?php echo JText::_('JCANCEL');?>"><?php echo JText::_('JCANCEL');?></a>
			<input type="hidden" name="option" value="com_users" />
			<input type="hidden" name="task" value="registration.register" />
			<?php echo JHtml::_('form.token');?>
		</div>
	</form>
</div>

