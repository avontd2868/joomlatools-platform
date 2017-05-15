<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_templates
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$user      = JFactory::getUser();
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
?>

<?php JFactory::getDocument()->setBuffer($this->sidebar, 'modules', 'sidebar'); ?>

<!-- Form -->
<form class="k-component k-js-component k-js-grid-controller k-js-grid" action="<?php echo JRoute::_('index.php?option=com_templates&view=styles'); ?>" method="post" name="adminForm" id="adminForm">

    <?php // Scopebar ?>
    <?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>

    <!-- Table -->
    <div class="k-table-container">
        <div class="k-table">
            <table class="k-js-fixed-table-header k-js-responsive-table">
                <thead>
                    <tr>
                        <th width="5">
                            &#160;
                        </th>
                        <th>
                            <?php echo JHtml::_('grid.sort', 'COM_TEMPLATES_HEADING_STYLE', 'a.title', $listDirn, $listOrder); ?>
                        </th>
                        <th width="5%" class="nowrap center">
                            <?php echo JHtml::_('grid.sort', 'COM_TEMPLATES_HEADING_DEFAULT', 'a.home', $listDirn, $listOrder); ?>
                        </th>
                        <th width="5%" class="nowrap center hidden-phone">
                            <?php echo JText::_('COM_TEMPLATES_HEADING_ASSIGNED'); ?>
                        </th>
                        <th width="10%" class="nowrap center">
                            <?php echo JHtml::_('grid.sort', 'JCLIENT', 'a.client_id', $listDirn, $listOrder); ?>
                        </th>
                        <th class="center hidden-phone">
                            <?php echo JHtml::_('grid.sort', 'COM_TEMPLATES_HEADING_TEMPLATE', 'a.template', $listDirn, $listOrder); ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->items as $i => $item) :
                        $canCreate = $user->authorise('core.create',     'com_templates');
                        $canEdit   = $user->authorise('core.edit',       'com_templates');
                        $canChange = $user->authorise('core.edit.state', 'com_templates');
                    ?>
                    <tr>
                        <td width="1%">
                            <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                        </td>
                        <td>
                            <?php if ($this->preview && $item->client_id == '0') : ?>
                                <a target="_blank" href="<?php echo JUri::root() . 'index.php?tp=1&templateStyle='.(int) $item->id ?>" class="jgrid">
                                <i class="icon-eye-open hasTooltip" title="<?php echo JHtml::tooltipText(JText::_('COM_TEMPLATES_TEMPLATE_PREVIEW'), $item->title, 0); ?>" ></i></a>
                            <?php elseif ($item->client_id == '1') : ?>
                                <i class="icon-eye-close disabled hasTooltip" title="<?php echo JHtml::tooltipText('COM_TEMPLATES_TEMPLATE_NO_PREVIEW_ADMIN'); ?>" ></i>
                            <?php else: ?>
                                <i class="icon-eye-close disabled hasTooltip" title="<?php echo JHtml::tooltipText('COM_TEMPLATES_TEMPLATE_NO_PREVIEW'); ?>" ></i>
                            <?php endif; ?>
                            <?php if ($canEdit) : ?>
                            <a href="<?php echo JRoute::_('index.php?option=com_templates&task=style.edit&id=' . (int) $item->id); ?>">
                                <?php echo $this->escape($item->title);?></a>
                            <?php else : ?>
                                <?php echo $this->escape($item->title);?>
                            <?php endif; ?>
                        </td>
                        <td class="k-table-data-icon k-table-data--center">
                            <?php if ($item->home == '0' || $item->home == '1'):?>
                                <?php if(1==2): ?>
                                    <?php echo JHtml::_('jgrid.isdefault', $item->home != '0', $i, 'styles.', $canChange && $item->home != '1');?>
                                <?php else: ?>
                                    <a href="#" class="k-button k-button--tiny">
                                        <span class="k-icon-star k-icon--size-default" aria-hidden="true"></span>
                                        <span class="k-visually-hidden">Make default</span>
                                    </a>
                                <?php endif; ?>
                            <?php elseif ($canChange):?>
                                <a href="<?php echo JRoute::_('index.php?option=com_templates&task=styles.unsetDefault&cid[]=' . $item->id . '&' . JSession::getFormToken() . '=1');?>">
                                    <?php echo JHtml::_('image', 'mod_languages/' . $item->image . '.gif', $item->language_title, array('title' => JText::sprintf('COM_TEMPLATES_GRID_UNSET_LANGUAGE', $item->language_title)), true);?>
                                </a>
                            <?php else:?>
                                <?php echo JHtml::_('image', 'mod_languages/' . $item->image . '.gif', $item->language_title, array('title' => $item->language_title), true);?>
                            <?php endif;?>
                        </td>
                        <td>
                            <?php if ($item->assigned > 0) : ?>
                                <i class="icon-ok tip hasTooltip" title="<?php echo JHtml::tooltipText(JText::plural('COM_TEMPLATES_ASSIGNED', $item->assigned), '', 0); ?>"></i>
                            <?php else : ?>
                                &#160;
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php echo $item->client_id == 0 ? JText::_('JSITE') : JText::_('JADMINISTRATOR'); ?>
                        </td>
                        <td>
                            <a href="<?php echo JRoute::_('index.php?option=com_templates&view=template&id=' . (int) $item->e_id); ?>  ">
                                <?php echo ucfirst($this->escape($item->template));?>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <input type="hidden" name="task" value="" />
            <input type="hidden" name="boxchecked" value="0" />
            <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
            <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
            <?php echo JHtml::_('form.token'); ?>
        </div><!-- .k-table -->

        <!-- Pagination -->
        <div class="k-table-pagination">
            <?php echo $this->pagination->getListFooter(); ?>
        </div><!-- .k-table-pagination -->

    </div><!-- .k-table-container -->

</form><!-- .k-list-layout -->
