<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_aftms
 *
 * @copyright   Copyright (C) 2015 Florian Denizot. All rights reserved.
 * @license     LTBD
 */

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('behavior.formvalidator');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');

$this->ignore_fieldsets = array('jmetadata');

$app = JFactory::getApplication();
$input = $app->input;

JFactory::getDocument()->addScriptDeclaration('
	Joomla.submitbutton = function(task)
	{
		if (task == "' . $this->getName() . '.cancel" || document.formvalidator.isValid(document.getElementById("item-form")))
		{
			Joomla.submitform(task, document.getElementById("item-form"));
		}
	};
');

?>

<form action="<?php echo JRoute::_('index.php?option=com_aftms&view=' . $this->getName() . '&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-validate">

	<?php echo JLayoutHelper::render('joomla.edit.title_alias', $this); ?>

	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_AFTMS_DETAILS_FIELDSET_LABEL', true)); ?>
		<div class="row-fluid">
			<div class="span9">  
       
        <?php echo $this->form->getControlGroup('campusid'); ?>
        
			</div>
			<div class="span3">
				<?php echo JLayoutHelper::render('joomla.edit.global', $this); ?>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publishing', JText::_('COM_AFTMS_PUBLISHING_FIELDSET_LABEL', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span6">
				<?php echo JLayoutHelper::render('joomla.edit.publishingdata', $this); ?>
			</div>
			<div class="span6">
				<?php echo JLayoutHelper::render('joomla.edit.metadata', $this); ?>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
    
		<?php echo JLayoutHelper::render('joomla.edit.params', $this); ?>
    
    <?php if ($this->canDo->get('core.admin')) : ?>
			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'permissions', JText::_('COM_AFTMS_RULES_FIELDSET_LABEL', true)); ?>
				<?php echo $this->form->getInput('rules'); ?>
			<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php endif; ?>

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>

		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>