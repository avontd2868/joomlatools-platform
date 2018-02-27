<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_tags
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');

$app		= JFactory::getApplication();
$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$ordering 	= ($listOrder == 'a.lft');
$canOrder	= $user->authorise('core.edit.state',	'com_tags');
$saveOrder 	= ($listOrder == 'a.lft' && $listDirn == 'asc');
if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_tags&task=tags.saveOrderAjax';
	JHtml::_('sortablelist.sortable', 'categoryList', 'adminForm', strtolower($listDirn), $saveOrderingUrl, false, true);
}
$sortFields = $this->getSortFields();
?>

<?php JFactory::getDocument()->setBuffer($this->sidebar, 'modules', 'submenu'); ?>

<!-- Component -->
<form class="k-component k-js-component k-js-grid-controller k-js-grid" action="<?php echo JRoute::_('index.php?option=com_tags&view=tags');?>" method="post" name="adminForm" id="adminForm">

    <!-- Scopebar -->
    <div class="k-scopebar k-js-scopebar" id="filter-bar">

        <!-- Toggle buttons -->
        <div class="k-scopebar__item k-scopebar__item--toggle-buttons">
            <button type="button" class="k-scopebar__button k-toggle-scopebar-search k-js-toggle-search">
                <span class="k-icon-magnifying-glass" aria-hidden="true">
                    <span class="k-visually-hidden">Search toggle</span>
                    <div class="k-js-search-count k-scopebar__item-label k-scopebar__item-label--numberless" style="display: none"></div>
                </span>
            </button>
            <button type="button" class="k-scopebar__button k-toggle-scopebar-filters k-js-toggle-filters">
                <span class="k-icon-filter" aria-hidden="true">
                    <span class="k-visually-hidden">Filters toggle</span>
                    <div class="k-js-filter-count k-scopebar__item-label k-scopebar__item-label--numberless"></div>
                </span>
            </button>
        </div>

        <!-- search -->
        <div class="k-scopebar__item k-scopebar__item--search">
            <div class="k-search k-search--has-both-buttons">
                <label for="k-search-input"><?php echo JText::_('JSEARCH_FILTER'); ?></label>
                <input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" class="k-search__field" title="<?php echo JHtml::tooltipText('COM_TAGS_ITEMS_SEARCH_FILTER'); ?>" />
                <button type="submit" class="k-search__submit" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>">
                    <span class="k-icon-magnifying-glass" aria-hidden="true"></span>
                    <span class="k-visually-hidden">Search</span>
                </button>
                <button type="button" class="k-search__empty" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';this.form.submit();">
                    <span class="k-search__empty-area">
                        <span class="k-icon-x" aria-hidden="true"></span>
                        <span class="k-visually-hidden">Clear search</span>
                    </span>
                </button>
            </div>
        </div>
    </div><!-- .k-scopebar -->

    <!-- Onboarding -->
    <?php echo JLayoutHelper::render('elysio.onboarding', array('items' => $this->items, 'type' => 'tag')); ?>

    <!-- Table -->
    <div class="k-table-container<?php echo (!$this->items) ? ' k-hidden' : '' ?>">
        <div class="k-table">
            <table class="k-js-responsive-table" id="categoryList">
				<thead>
					<tr>
                        <th width="1%" class="k-table-data--icon">
							<?php echo JHtml::_('grid.sort', '<i class="k-icon-move"></i>', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
						</th>
                        <th width="1%" class="k-table-data--form">
							<?php echo JHtml::_('grid.checkall'); ?>
						</th>
                        <th width="1%" class="k-table-data--toggle" data-toggle="true"></th>
						<th width="1%">
							<?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.published', $listDirn, $listOrder); ?>
						</th>
						<th>
							<?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
						</th>
                        <th width="1%" data-hide="phone">
                            <?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ACCESS', 'a.access', $listDirn, $listOrder); ?>
                        </th>
                        <th width="1%" data-hide="phone,tablet">
                            <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_LANGUAGE', 'language', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
                        </th>
                        <th width="1%" data-hide="phone,tablet">
                            <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
                        </th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach ($this->items as $i => $item) :
					$orderkey   = array_search($item->id, $this->ordering[$item->parent_id]);
					$canCreate  = $user->authorise('core.create',     'com_tags');
					$canEdit    = $user->authorise('core.edit',       'com_tags');
					$canCheckin = $item->checked_out == $user->get('id')|| $item->checked_out == 0;
					$canChange  = $user->authorise('core.edit.state', 'com_tags') && $canCheckin;
					// Get the parents of item for sorting
					if ($item->level > 1)
					{
						$parentsStr = "";
						$_currentParentId = $item->parent_id;
						$parentsStr = " ".$_currentParentId;
						for ($j = 0; $j < $item->level; $j++)
						{
							foreach ($this->ordering as $k => $v)
							{
								$v = implode("-", $v);
								$v = "-" . $v . "-";
								if (strpos($v, "-" . $_currentParentId . "-") !== false)
								{
									$parentsStr .= " " . $k;
									$_currentParentId = $k;
									break;
								}
							}
						}
					}
					else
					{
						$parentsStr = "";
					}
					?>
						<tr sortable-group-id="<?php echo $item->parent_id;?>" item-id="<?php echo $item->id?>" parents="<?php echo $parentsStr?>" level="<?php echo $item->level?>">
							<td>
								<?php
								$iconClass = '';
								if (!$canChange)
								{
									$iconClass = ' inactive';
								}
								elseif (!$saveOrder)
								{
									$iconClass = ' inactive tip-top hasTooltip" title="' . JHtml::tooltipText('JORDERINGDISABLED');
								}
								?>
								<span class="sortable-handler<?php echo $iconClass ?>">
									<i class="icon-menu"></i>
								</span>
								<?php if ($canChange && $saveOrder) : ?>
									<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $orderkey + 1;?>" />
								<?php endif; ?>
							</td>
							<td>
								<?php echo JHtml::_('grid.id', $i, $item->id); ?>
							</td>
                            <td class="k-table-data--toggle"></td>
							<td>
								<?php echo JHtml::_('jgrid.published', $item->published, $i, 'tags.', $canChange);?>
							</td>
							<td>
								<?php if ($item->level > 0): ?>
								<?php echo str_repeat('<span class="gi">&mdash;</span>', $item->level - 1) ?>
								<?php endif; ?>
								<?php if ($item->checked_out) : ?>
									<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'tags.', $canCheckin); ?>
								<?php endif; ?>
								<?php if ($canEdit || $canEditOwn) : ?>
									<a href="<?php echo JRoute::_('index.php?option=com_tags&task=tag.edit&id='.$item->id);?>">
										<?php echo $this->escape($item->title); ?></a>
								<?php else : ?>
									<?php echo $this->escape($item->title); ?>
								<?php endif; ?>
								<span class="small" title="<?php echo $this->escape($item->path); ?>">
									<?php if (empty($item->note)) : ?>
										<?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias));?>
									<?php else : ?>
										<?php echo JText::sprintf('JGLOBAL_LIST_ALIAS_NOTE', $this->escape($item->alias), $this->escape($item->note));?>
									<?php endif; ?>
								</span>
							</td>
						<td>
							<?php echo $this->escape($item->access_title); ?>
						</td>
						<td>
						<?php if ($item->language == '*') : ?>
							<?php echo JText::alt('JALL', 'language'); ?>
							<?php else:?>
								<?php echo $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
							<?php endif;?>
							</td>
							<td>
								<span title="<?php echo sprintf('%d-%d', $item->lft, $item->rgt); ?>">
									<?php echo (int) $item->id; ?></span>
							</td>
						</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
        </div><!-- .k-table -->

        <!-- Pagination -->
        <div class="k-table-pagination">
            <div class="btn-group">
                <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
                <?php echo $this->pagination->getLimitBox(); ?>
            </div>
            <div class="btn-group">
                <label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC');?></label>
                <select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
                    <option value=""><?php echo JText::_('JFIELD_ORDERING_DESC');?></option>
                    <option value="asc" <?php if ($listDirn == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING');?></option>
                    <option value="desc" <?php if ($listDirn == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING');?></option>
                </select>
            </div>
            <div class="btn-group">
                <label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY');?></label>
                <select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
                    <option value=""><?php echo JText::_('JGLOBAL_SORT_BY');?></option>
                    <?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder);?>
                </select>
            </div>
        </div>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>

	</div><!-- .k-table-container -->

</form><!-- .k-component -->

<div class="k-dynamic-content-holder">
    <?php echo $this->loadTemplate('batch'); ?>

    <script type="text/javascript">
        Joomla.orderTable = function() {
            table = document.getElementById("sortTable");
            direction = document.getElementById("directionTable");
            order = table.options[table.selectedIndex].value;
            if (order != '<?php echo $listOrder; ?>')
            {
                dirn = 'asc';
            } else {
                dirn = direction.options[direction.selectedIndex].value;
            }
            Joomla.tableOrdering(order, dirn, '');
        }
    </script>
</div>
