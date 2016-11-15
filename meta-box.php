<?php
require_once("meta-box/meta-box-class/my-meta-box-class.php");

$ssm = new AT_Meta_Box( array (
	'id'             => 'ssswp',          // meta box id, unique per meta box
    'title'          => __('Slide details'),          // meta box title
    'pages'          => array('slide'),      // post types, accept custom post types as well, default is array('post'); optional
    'context'        => 'normal',            // where the meta box appear: normal (default), advanced, side; optional
    'priority'       => 'high',            // order of meta box: high (default), low; optional
    'fields'         => array(),            // list of meta fields (can be added by field arrays)
    'local_images'   => false,          // Use local or hosted images (meta box images for add/remove)
    'use_with_theme' => false          //change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
	));

$ssm->addText(
	'ssm_target_url', 
	array('name'=> __('Target url'),'style' => "width:100%"));
$ssm->Finish();