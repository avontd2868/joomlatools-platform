<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_config
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

include_once(JPATH_WEB.'/administrator/templates/elysio/html/overrides.php');

$app = JFactory::getApplication();
$template = $app->getTemplate();

// Load the tooltip behavior.
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.formvalidator');

// Load JS message titles
JText::script('ERROR');
JText::script('WARNING');
JText::script('NOTICE');
JText::script('MESSAGE');

JFactory::getDocument()->addScriptDeclaration(
	'
	Joomla.submitbutton = function(task)
	{
		if (task === "config.cancel.component" || document.formvalidator.isValid(document.getElementById("component-form")))
		{
			jQuery("#permissions-sliders select").attr("disabled", "disabled");
			Joomla.submitform(task, document.getElementById("component-form"));
		}
	};

	// Select first tab
	jQuery(document).ready(function() {
		jQuery("#configTabs a:first").tab("show");
	});'
);
?>

<?php JFactory::getDocument()->setBuffer($this->loadTemplate('navigation'), 'modules', 'sidebar'); ?>

<form class="k-component" action="<?php echo JRoute::_('index.php?option=com_config'); ?>" id="component-form" method="post" name="adminForm" autocomplete="off">
    <div class="k-tabs-container">
        <?php $fieldSets = $this->fieldsets; ?>
        <?php if(count($fieldSets) > 1) : ?>
        <ul class="nav nav-tabs" id="configTabs">
            <?php foreach ($fieldSets as $name => $fieldSet) : ?>
                <?php $rel = ''; ?>
                <?php if (!empty($fieldSet->showon)) : ?>
                    <?php JHtml::_('jquery.framework'); ?>
                    <?php JHtml::_('script', 'jui/cms.js', false, true); ?>
                    <?php $showonarr = array(); ?>
                    <?php foreach (preg_split('%\[AND\]|\[OR\]%', $fieldSet->showon) as $showonfield) : ?>
                        <?php $showon = explode(':', $showonfield, 2); ?>
                        <?php $showonarr[] = array(
                            'field'  => $this->form->getFormControl() . '[' . $showon[0] . ']',
                            'values' => explode(',', $showon[1]),
                            'op'     => (preg_match('%\[(AND|OR)\]' . $showonfield . '%', $fieldSet->showon, $matches)) ? $matches[1] : ''
                        ); ?>
                    <?php endforeach; ?>
                    <?php $rel = ' data-showon=\'' . json_encode($showonarr) . '\''; ?>
                <?php endif; ?>
                <?php $label = empty($fieldSet->label) ? 'COM_CONFIG_' . $name . '_FIELDSET_LABEL' : $fieldSet->label; ?>
                <li<?php echo $rel; ?>><a data-toggle="tab" href="#<?php echo $name; ?>"><?php echo JText::_($label); ?></a></li>
            <?php endforeach; ?>
        </ul><!-- /configTabs -->
        <?php endif ?>

        <?php echo count($fieldSets) > 1 ? '<div class="tab-content" id="configContent">' : '';?>
            <?php foreach ($this->fieldsets as $name => $fieldSet) : ?>
                <div class="tab-pane" id="<?php echo $name; ?>">
                    <div class="k-container">
                        <div class="k-container__<?php echo ($name != "permissions") ? 'main' : 'full'; ?>">
                            <?php if (count($fieldSets) == 1) : ?>
                            <div class="k-heading"><?php echo JText::_($fieldSet->label); ?></div>
                            <?php endif ?>
                            <?php if (isset($fieldSet->description) && !empty($fieldSet->description)) : ?>
                            <p class="k-alert k-alert--info"><?php echo JText::_($fieldSet->description) ?></p>
                            <?php endif ?>
                            <?php foreach ($this->form->getFieldset($name) as $field) : ?>
                                <?php $datashowon = ''; ?>
                                <?php if ($showonstring = $field->getAttribute('showon')) : ?>
                                    <?php JHtml::_('jquery.framework'); ?>
                                    <?php JHtml::_('script', 'jui/cms.js', false, true); ?>
                                    <?php $showonarr = array(); ?>
                                    <?php foreach (preg_split('%\[AND\]|\[OR\]%', $showonstring) as $showonfield) : ?>
                                        <?php $showon = explode(':', $showonfield, 2); ?>
                                        <?php $showonarr[] = array(
                                            'field'  => $this->form->getFormControl() . '[' . $this->form->getFieldAttribute($showon[0], 'name') . ']',
                                            'values' => explode(',', $showon[1]),
                                            'op'     => (preg_match('%\[(AND|OR)\]' . $showonfield . '%', $showonstring, $matches)) ? $matches[1] : ''
                                        ); ?>
                                    <?php endforeach; ?>
                                    <?php $datashowon = ' data-showon=\'' . json_encode($showonarr) . '\''; ?>
                                <?php endif; ?>
                                <?php if ($field->hidden) : ?>
                                    <?php echo $field->input; ?>
                                <?php else : ?>
                                    <div class="k-form-group"<?php echo $datashowon; ?>>
                                        <?php if ($name != "permissions") : ?>
                                            <?php echo $field->label; ?>
                                        <?php endif; ?>
                                        <?php echo addFormControlClass($field->input); ?>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php echo count($fieldSets) > 1 ? '</div>' : '';?>

        <input type="hidden" name="id" value="<?php echo $this->component->id; ?>" />
        <input type="hidden" name="component" value="<?php echo $this->component->option; ?>" />
        <input type="hidden" name="return" value="<?php echo $this->return; ?>" />
        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>
