<?php
defined('JPATH_BASE') or die;

function classOverride($input) {
    switch (true) {
        case strpos($input, 'new');
            $class = "k-icon-plus";
            break;
        case strpos($input, 'edit');
            $class = "k-icon-pencil";
            break;
        case strpos($input, 'delete');
            $class = "k-icon-trash";
            break;
        case strpos($input, 'trash');
            $class = "k-icon-trash";
            break;
        case strpos($input, 'refresh');
            $class = "k-icon-reload";
            break;
        case strpos($input, 'options');
            $class = "k-icon-cog";
            break;
        case strpos($input, 'default');
            $class = "k-icon-star";
            break;
        case strpos($input, 'copy');
            $class = "k-icon-file";
            break;
        case strpos($input, 'unpublish');
            $class = "k-icon-x";
            break;
        case strpos($input, 'publish');
            $class = "k-icon-check";
            break;
        case strpos($input, 'checkin');
            $class = "k-icon-task";
            break;
        case strpos($input, 'unblock');
            $class = "k-icon-circle-check";
            break;
        case strpos($input, 'apply');
            $class = "k-icon-task";
            break;
        case strpos($input, 'save');
            $class = "k-icon-check";
            break;
        case strpos($input, 'cancel');
            $class = "k-icon-x";
            break;
        case strpos($input, 'featured');
            $class = "k-icon-star";
            break;
        case strpos($input, 'archive');
            $class = "k-icon-box";
            break;
        case strpos($input, 'bars');
            $class = "k-icon-bar-chart";
            break;
        case strpos($input, 'remove');
            $class = "k-icon-star";
            break;
    }
    return $class;
}

// Add `k-form-control` class to textfields
function addFormControlClass($input) {

    if (strpos($input, 'class="spacer') !== false) {
        return false;
    }

    // If the field is an input-append
    if (strpos($input, 'class="input-append') !== false) {
        $input = str_replace('class="input-append', 'class="k-input-group ', $input);
        $input = str_replace('class="btn', 'class="k-button k-button--default', $input);
        $input = str_replace('class="modal btn', 'class="k-button k-button--default k-js-iframe-modal', $input);
        $input = str_replace('<button', '<span class="k-input-group__button"><button', $input);
        if (strpos($input, 'class="input-medium') == false) {
            $input = str_replace('type="text"', 'type="text" class="k-form-control"', $input);
        } else {
            $input = str_replace('class="input-medium', 'class="k-form-control', $input);
        }
        $input = str_replace('</button>', '</button></span>', $input);
        $input = str_replace('<a', '<span class="k-input-group__button"><a', $input);
        $input = str_replace('</a>', '</a></span>', $input);
    }

    // If the field is surrounded by an empty div
    if (strpos($input, '<div><input') !== false) {
        $input = str_replace('<div><input', '<input', $input);
        $input = str_replace('</div>', '', $input);
    }

    // If the field is not a text field or textarea; return original
    if (strpos($input, 'type="text') === false && strpos($input, 'type="password') === false && strpos($input, 'type="email') === false && strpos($input, 'type="url') === false && strpos($input, '<textarea') === false) {
        return $input;
    }

    // If the $input is not strictly an input field or textarea but has more markup for example; return original
    elseif (substr($input, 0, strlen('<input')) !== '<input' && substr($input, 0, strlen('<textarea')) !== '<textarea') {
        return $input;
    } else {
        // If there's no class attribute yet
        if (strpos($input, 'class="') === false) {
            if (substr($input, 0, strlen('<input')) === '<input') {
                $field = str_replace('<input ', '<input class="k-form-control" ', $input);
            }
            if (substr($input, 0, strlen('<textarea')) === '<textarea') {
                $field = str_replace('<textarea ', '<textarea class="k-form-control" ', $input);
            }

            // If there's already a class attribute
        } else {
            $field = str_replace('class="', 'class="k-form-control ', $input);
        }
        return $field;
    }
}

// Add `k-form-control` class to textfields
function replaceControlGroup($input) {
    $input = str_replace('<div class="control-group"', '<div class="k-form-group"', $input);
//    $input = str_replace('<div class="controls', '<div class="', $input);
    return $input;
}

// Set input attribute(s)
function setFormInputAttributes($input, $array) {
    $field = $input;
    foreach($array as $key => $item) {
        if (strpos($field, $key.'="') === false) {
            $field = str_replace('<input ', '<input '.$key.'="'.$item.'" ', $field);
        } else {
            $field = str_replace($key.'="', $key.'="'.$item.' ', $field);
        }
    }
    return $field;
}

// Add `k-input-group__addon` class to input group labels
function addInputGroupAddonClass($input) {
    $input = str_replace('class="hasTooltip', 'class="k-input-group__addon', $input);
    $input = str_replace('class="hasPopover', 'class="k-input-group__addon', $input);
    $input = str_replace('*', '', $input);
    return $input;
}


// Add `k-input-group__addon` class to input group labels
function imagesInputGroup($input) {
    $input = str_replace('class="input-prepend input-append', 'class="k-input-group', $input);
    $input = str_replace('class="media-preview add-on', 'class="k-input-group__addon', $input);
    $input = str_replace('class="icon-eye', 'class="k-icon-eye', $input);
    $input = str_replace('class="input-small', 'class="k-form-control', $input);
    $input = str_replace('<a class', '<div class="k-input-group__button"><a class', $input);
    $input = str_replace('modal btn', 'modal k-button k-button--default', $input);
    $input = str_replace('btn hasTooltip', 'k-button k-button--default hasTooltip', $input);
    $input = str_replace('icon-remove', 'k-icon-x', $input);
    $input = str_replace('</a>', '</a></div>', $input);
    return $input;
}

function mediaPrepareTree($folder, $parent = null)
{
    $tree = array();

    if (isset($folder['children']))
    {
        foreach ($folder['children'] as $subfolder)
        {
            $item_id = uniqid();

            $item = array(
                'id'     => $item_id,
                'label'  => $subfolder['data']->name,
                'url'    => 'index.php?option=com_media&view=mediaList&tmpl=component&folder=' . $subfolder['data']->relative,
                'parent' => $parent
            );

            $tree[] = $item;

            if (isset($subfolder['children']) && count($subfolder['children']) > 0)
            {
                $subfolder_parent = count($subfolder['children']) > 0 ? $item_id : 0;
                $items = mediaPrepareTree($subfolder, $subfolder_parent);
                $tree  = array_merge($tree, $items);
            }

        }
    }

    return $tree;
}