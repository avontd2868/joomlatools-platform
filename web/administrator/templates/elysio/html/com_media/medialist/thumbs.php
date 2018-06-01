<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_media
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$params = JComponentHelper::getParams('com_media');
$path   = 'file_path';

JHtml::_('jquery.framework');

JFactory::getDocument()->addScriptDeclaration(
	"
		jQuery(document).ready(function($){
			window.parent.document.updateUploader();
			$('.img-preview, .preview').each(function(index, value) {
				$(this).on('click', function(e) {
					window.parent.jQuery('#imagePreviewSrc').attr('src', $(this).attr('href'));
					window.parent.jQuery('#imagePreview').modal('show');
					return false;
				});
			});
			$('.video-preview').each(function(index, value) {
				$(this).unbind('click');
				$(this).on('click', function(e) {
					e.preventDefault();
					window.parent.jQuery('#videoPreview').modal('show');

					var elementInitialised = window.parent.jQuery('#mejsPlayer').attr('src');

					if (!elementInitialised)
					{
						window.parent.jQuery('#mejsPlayer').attr('src', $(this).attr('href'));
						window.parent.jQuery('#mejsPlayer').mediaelementplayer();
					}

					window.parent.jQuery('#mejsPlayer')[0].player.media.setSrc($(this).attr('href'));

					return false;
				});
			});
		});
	"
);
?>

<script>
    kQuery(function($) {
        $('.k-card__caption').on('click', function() {
            if ( $(this).closest('.k-card').hasClass('k-is-selected') ) {
                $(this).closest('.k-card').removeClass('k-is-selected').find('input[type="checkbox"]').prop( "checked", false );
            } else {
                $(this).closest('.k-card').addClass('k-is-selected').find('input[type="checkbox"]').prop( "checked", true );
            }
        })
    });
</script>

<!-- Wrapper -->
<div class="k-wrapper k-js-wrapper">

    <!-- Content wrapper -->
    <div class="k-content-wrapper">

        <!-- Content -->
        <div class="k-content k-js-content">

            <!-- Component wrapper -->
            <div class="k-component-wrapper">

                <!-- Component -->
                <form class="k-component" target="_parent" action="index.php?option=com_media&amp;tmpl=index&amp;folder=<?php echo $this->state->folder; ?>" method="post" id="mediamanager-form" name="mediamanager-form">

                    <!-- Breadcrumbs -->
                    <div class="k-breadcrumb">
                        <ul>
                            <li class="k-breadcrumb__home">
                                <span class="k-breadcrumb__content" href="/administrator/index.php?option=com_docman&amp;view=categories&amp;parent_id=">
                                    <span class="k-icon-home" aria-hidden="true"></span>
                                    <span class="k-visually-hidden">Home</span>
                                </span>
                            </li>
                            <?php if ($this->state->folder != '') : ?>
                                <li class="k-breadcrumb__active">
                                    <span class="k-breadcrumb__content"><?php echo JText::_('JGLOBAL_ROOT') . ': ' . $params->get($path, 'images'); ?></span>
                                </li>
                            <?php else : ?>
                                <li>
                                    <span class="k-breadcrumb__content"><?php echo JText::_('JGLOBAL_ROOT') . ': ' . $params->get($path, 'images') . '/' . $this->state->folder; ?></span>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div><!-- .k-breadcrumb -->

                    <!-- Gallery -->
                    <div class="k-gallery-container">

                        <div class="k-gallery k-js-gallery">
                            <div class="k-gallery__items manager thumbnails">
                                <?php
                                echo $this->loadTemplate('up');
                                ?>

                                <?php for ($i = 0, $n = count($this->folders); $i < $n; $i++) :
                                    $this->setFolder($i);
                                    echo $this->loadTemplate('folder');
                                endfor; ?>

                                <?php for ($i = 0, $n = count($this->documents); $i < $n; $i++) :
                                    $this->setDoc($i);
                                    echo $this->loadTemplate('doc');
                                endfor; ?>

                                <?php for ($i = 0, $n = count($this->videos); $i < $n; $i++) :
                                    $this->setVideo($i);
                                    echo $this->loadTemplate('video');
                                endfor; ?>

                                <?php for ($i = 0, $n = count($this->images); $i < $n; $i++) :
                                    $this->setImage($i);
                                    echo $this->loadTemplate('img');
                                endfor; ?>

                                <input type="hidden" name="task" value="" />
                                <input type="hidden" name="username" value="" />
                                <input type="hidden" name="password" value="" />
                                <?php echo JHtml::_('form.token'); ?>
                            </div>
                        </div>

                    </div><!-- .k-gallery-container -->

                </form><!-- .k-component -->

            </div><!-- .k-component-wrapper -->

        </div><!-- .k-content -->

    </div><!-- .k-content-wrapper -->

</div><!-- .k-wrapper -->