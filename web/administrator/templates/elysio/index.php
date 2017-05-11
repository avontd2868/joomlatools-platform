<?php
/**
 * @package     elysio-template
 * @copyright   Copyright (C) 2015 Timble CVBA (http://www.timble.net)
 */

// No direct access
defined('_JEXEC') or die;

// Connect with Joomla
$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$user = JFactory::getUser();
$menu = $app->getMenu();

// Getting params from template
$params = JFactory::getApplication()->getTemplate(true)->params;

// Detecting Active Variables
$option = $app->input->getCmd('option', '');
$view = $app->input->getCmd('view', '');
$layout = $app->input->getCmd('layout', '');
$task = $app->input->getCmd('task', '');
$itemid = $app->input->getCmd('Itemid', '');
$sitename = $app->getCfg('sitename');
$menuactive = $menu->getActive();
$debug = $app->getCfg('debug', 0);
$cpanel = ($option === 'com_cpanel');

$showSubmenu = JFactory::getDocument()->getBuffer('modules', 'submenu') && !JFactory::getApplication()->input->getBool('hidemainmenu');
$showSidebar = JFactory::getDocument()->getBuffer('modules', 'sidebar');
$showIcons = $this->countModules('icon') && $option == 'com_cpanel';

// Set MetaData
$doc->setCharset('utf8');
$doc->setGenerator($sitename);
$doc->setMetaData('viewport', 'width=device-width, initial-scale=1.0');
$doc->setMetaData('mobile-web-app-capable', 'yes');
$doc->setMetaData('apple-mobile-web-app-capable', 'yes');
$doc->setMetaData('apple-mobile-web-app-status-bar-style', 'black');
$doc->setMetaData('apple-mobile-web-app-title', 'Elysio');
$doc->setMetaData('X-UA-Compatible', 'IE=edge', true);

// Set links
$doc->addHeadLink($params->get('logo').'.ico', 'shortcut icon', 'rel', array('type' => 'image/ico'));
$doc->addHeadLink($params->get('logo').'.png', 'shortcut icon', 'rel', array('type' => 'image/png', "sizes" => "192x192"));

// Add Stylesheets
$doc->addStyleSheet('templates/' . $this->template . '/css/admin.css');

// Add Script
JHtml::_('bootstrap.framework');
$doc->addScript('templates/'.$this->template.'/js/modernizr.js', 'text/javascript');
$doc->addScript('templates/'.$this->template.'/js/admin.js', 'text/javascript');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<jdoc:include type="head" />
</head>
<body class="<?php echo $option . ' view-' . $view . ' layout-' . $layout . ' task-' . $task . ' itemid-' . $itemid; ?> no-js">
<div class="k-ui-namespace k-ui-container">

    <!-- navigation -->
    <?php include_once('navigation.php'); ?>

    <!-- Message container -->
    <div class="k-message-container">
        <jdoc:include type="message" />
    </div>

    <!-- Content wrapper -->
    <div class="k-content-wrapper">

        <?php if (($showSidebar || $showSubmenu || $showIcons) && $option != 'com_docman') : ?>
        <!-- Sidebar -->
        <div class="k-sidebar-left k-js-sidebar-left">
            <?php if($showSubmenu) : ?>
            <jdoc:include type="modules" name="submenu" style="none" />
            <?php endif ?>
            <?php if($showIcons) : ?>
            <jdoc:include type="modules" name="icon" style="none" />
            <?php endif ?>
            <?php if($showSidebar) : ?>
                <jdoc:include type="modules" name="sidebar" style="none" />
            <?php endif ?>
        </div>
        <?php endif; ?>

        <!-- Content -->
        <div class="k-content k-js-content">

            <?php if ($this->countModules('toolbar')) : ?>
            <!-- Toolbar -->
            <div class="k-toolbar">
                <jdoc:include type="modules" name="toolbar" style="none" />
            </div>
            <?php endif; ?>

            <!-- Component wrapper -->
            <div class="k-component-wrapper">

                <!-- Component -->
                <div class="k-component k-js-component">

                <?php if ($this->countModules('top')) : ?>
                    <jdoc:include type="modules" name="top" style="xhtml" />
                <?php endif; ?>

                <jdoc:include type="component" />

                <?php if ($this->countModules('bottom')) : ?>
                    <jdoc:include type="modules" name="bottom" style="xhtml" />
                <?php endif; ?>

                </div><!-- .k-component -->

            </div><!-- .k-component-wrapper -->

        </div><!-- k-content -->

    </div><!-- .k-content-wrapper -->

    <?php if ($this->countModules('debug')) : ?>
    <div class="k-debug-container">
        <jdoc:include type="modules" name="debug" style="none" />
    </div>
    <?php endif; ?>

<!--    <script>-->
<!--        jQuery(document).ready(function($) {-->
<!--            var modal = $('#collapseModal');-->
<!--            if (modal.length) {-->
<!--                modal.appendTo('body');-->
<!--            }-->
<!--        });-->
<!--    </script>-->
</div>
</body>
</html>
