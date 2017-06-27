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
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.modal');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$user		= JFactory::getUser();
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
?>

<?php JFactory::getDocument()->setBuffer($this->sidebar, 'modules', 'sidebar'); ?>

<!-- Form -->
<form class="k-form-layout -koowa-grid" action="<?php echo JRoute::_('index.php?option=com_templates&view=templates'); ?>" method="post" name="adminForm" id="adminForm">

    <!-- Scopebar -->
    <div class="k-scopebar" id="filter-bar">

        <!-- Filters -->
        <div class="k-scopebar__item k-scopebar__item--fluid">

            <!-- Search button -->
            <button type="button" class="k-toggle-search"><span class="k-icon-magnifying-glass"></span><span class="visually-hidden">Search</span></button>

        </div><!-- .k-scopebar__item--fluid -->

        <!-- Search -->
        <div class="k-scopebar__item k-scopebar__search">
            <div class="k-search__container k-search__container--has-both-buttons">
                <input class="k-search__field" type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" />
                <button type="submit" class="k-search__button-search" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>">
                    <span class="k-icon-magnifying-glass"></span>
                </button>
                <button type="button" class="k-search__button-empty" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';this.form.submit();">
                    <span>X</span>
                </button>
            </div>
        </div><!-- .k-scopebar__search -->

    </div><!-- .k-scopebar -->

	<!-- Table -->
	<div class="k-table-container">
		<div class="k-table">
			<table class="table--fixed footable select-rows" id="template-mgr">
				<thead>
					<tr>
						<th class="col1template">
							&#160;
						</th>
						<th>
							<?php echo JHtml::_('grid.sort', 'COM_TEMPLATES_HEADING_TEMPLATE', 'a.element', $listDirn, $listOrder); ?>
						</th>
						<th width="10%">
							<?php echo JHtml::_('grid.sort', 'JCLIENT', 'a.client_id', $listDirn, $listOrder); ?>
						</th>
						<th width="10%">
							<?php echo JText::_('JVERSION'); ?>
						</th>
						<th width="15%">
							<?php echo JText::_('JDATE'); ?>
						</th>
						<th width="25%">
							<?php echo JText::_('JAUTHOR'); ?>
						</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($this->items as $i => $item) : ?>
					<tr>
						<td>
							<?php echo JHtml::_('templates.thumb', $item->element, $item->client_id); ?>
						</td>
						<td>
							<strong><?php echo JText::sprintf('COM_TEMPLATES_TEMPLATE_DETAILS', ucfirst($item->name)); ?></strong>
							<p>
							<?php if ($this->preview && $item->client_id == '0') : ?>
								<a href="<?php echo JUri::root() . 'index.php?tp=1&template=' . $item->element; ?>" target="_blank">
									<?php echo JText::_('COM_TEMPLATES_TEMPLATE_PREVIEW'); ?></a>
							<?php elseif ($item->client_id == '1') : ?>
								<?php echo JText::_('COM_TEMPLATES_TEMPLATE_NO_PREVIEW_ADMIN'); ?>
							<?php else : ?>
								<span class="hasTooltip" title="<?php echo JHtml::tooltipText('COM_TEMPLATES_TEMPLATE_NO_PREVIEW_DESC'); ?>">
									<?php echo JText::_('COM_TEMPLATES_TEMPLATE_NO_PREVIEW'); ?></span>
							<?php endif; ?>
							</p>
						</td>
						<td>
							<?php echo $item->client_id == 0 ? JText::_('JSITE') : JText::_('JADMINISTRATOR'); ?>
						</td>
						<td>
							<?php echo $this->escape($item->xmldata->get('version')); ?>
						</td>
						<td>
							<?php echo $this->escape($item->xmldata->get('creationDate')); ?>
						</td>
						<td>
							<?php if ($author = $item->xmldata->get('author')) : ?>
								<p><?php echo $this->escape($author); ?></p>
							<?php else : ?>
								&mdash;
							<?php endif; ?>
							<?php if ($email = $item->xmldata->get('authorEmail')) : ?>
								<p><?php echo $this->escape($email); ?></p>
							<?php endif; ?>
							<?php if ($url = $item->xmldata->get('authorUrl')) : ?>
								<p><a href="<?php echo $this->escape($url); ?>">
									<?php echo $this->escape($url); ?></a></p>
							<?php endif; ?>
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