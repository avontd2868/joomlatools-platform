<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$title = $displayData['title'];

?>
<button data-toggle="modal" data-target="#collapseModal" class="k-button k-button--default">
	<span class="k-icon-checkbox-partial" title="<?php echo $title; ?>" aria-hidden="true"></span>
	<?php echo $title; ?>
</button>
