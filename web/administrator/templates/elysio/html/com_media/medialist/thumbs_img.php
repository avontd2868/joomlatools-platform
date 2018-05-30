<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_media
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

$user       = JFactory::getUser();
$params     = new Registry;
$dispatcher = JEventDispatcher::getInstance();
$dispatcher->trigger('onContentBeforeDisplay', array('com_media.file', &$this->_tmp_img, &$params));
?>

<div class="k-gallery__item k-gallery__item--file">
    <div class="k-card">
        <a href="<?php echo COM_MEDIA_BASEURL . '/' . $this->_tmp_img->path_relative; ?>" class="k-card__body img-preview">
            <div class="k-ratio-block k-ratio-block--4-to-3">
                <div class="k-ratio-block__body">
                    <?php echo JHtml::_('image', COM_MEDIA_BASEURL . '/' . $this->_tmp_img->path_relative, JText::sprintf('COM_MEDIA_IMAGE_TITLE', $this->_tmp_img->title, JHtml::_('number.bytes', $this->_tmp_img->size))); ?>
                </div>
            </div>
        </a>
        <div class="k-card__caption">
            <div class="k-flag-object">
                <div class="k-flag-object__aside">
                    <input class="pull-left" type="checkbox" name="rm[]" value="<?php echo $this->_tmp_img->name; ?>" />
                </div>
                <div class="k-flag-object__body k-overflow-hidden" style="padding-left: 4px;">
                    <div class="k-ellipsis">
                        <div class="k-ellipsis__item">
                            <?php echo JHtml::_('string.truncate', $this->_tmp_img->name, 20, false); ?>
                        </div>
                    </div>
                </div>
                <?php if ($user->authorise('core.delete', 'com_media')):?>
                <div class="k-flag-object__aside" style="padding-left: 4px;">
                    <a target="_top"
                       href="index.php?option=com_media&amp;task=file.delete&amp;tmpl=index&amp;<?php echo JSession::getFormToken(); ?>=1&amp;folder=<?php echo $this->state->folder; ?>&amp;rm[]=<?php echo $this->_tmp_img->name; ?>"
                       rel="<?php echo $this->_tmp_img->name; ?>" title="<?php echo JText::_('JACTION_DELETE'); ?>">
                        <span class="k-icon-trash"></span>
                    </a>
                </div>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>

<?php $dispatcher->trigger('onContentAfterDisplay', array('com_media.file', &$this->_tmp_img, &$params));
