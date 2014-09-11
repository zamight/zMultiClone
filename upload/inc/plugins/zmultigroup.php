<?php
/* by Zamight [zskillers.com]; Copyright (C) 2014
 released under Creative Commons BY-NC-SA 3.0 license: http://creativecommons.org/licenses/by-nc-sa/3.0/ */

// Disallow direct access to this file for security reasons
if (!defined("IN_MYBB")) {
    die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

$plugins->add_hook("postbit", "zmultigroup_postbit");
$plugins->add_hook("postbit_classic", "zmultigroup_postbit");

function zmultigroup_info()
{
    return array(
        "name" => "zMultiGroup",
        "description" => "Plugin that display multiple usergroups.",
        "website" => "http://www.zskillers.com",
        "author" => "Zamight",
        "authorsite" => "http://www.zskillers.com",
        "version" => "1.0",
        "guid" => "",
        "compatibility" => "18*"
    );
}

function zmultigroup_activate()
{
    global $db;
    require_once MYBB_ROOT . "/inc/adminfunctions_templates.php";

    $string = '{$post[\'usertitle\']}';

    find_replace_templatesets(
        "postbit",
        "#" . preg_quote($string) . "#i",
        $string . '
	{$post[\'zmultigroup\']}'
    );

    find_replace_templatesets(
        "postbit_classic",
        "#" . preg_quote($string) . "#i",
        $string . '
	{$post[\'zmultigroup\']}'
    );
}

function zmultigroup_deactivate()
{
    global $db;
    require_once MYBB_ROOT . "/inc/adminfunctions_templates.php";
    $string = '{$post[\'zmultigroup\']}';
    find_replace_templatesets(
        "postbit",
        "#" . preg_quote($string) . "#i",
        '');

    find_replace_templatesets(
        "postbit_classic",
        "#" . preg_quote($string) . "#i",
        '');
}

function zmultigroup_postbit(&$post)
{
    global $db, $mybb;

    $arrayUsergroups = explode(',', $post['additionalgroups']);
    $post['zmultigroup'] = '';
    foreach ($arrayUsergroups as $usergroup) {
        $query = $db->simple_select("usergroups", "*",
            "gid='{$usergroup}'");

        $image = $db->fetch_field($query, "image");

        if ($image != '') {
            if ($mybb->user['classicpostbit'] == 1) {
                $post['zmultigroup'] .= '<br />';
            }
            $post['zmultigroup'] .= "<img src='{$image}'>";
        }
    }
}