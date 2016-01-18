<?php
/**
 * $Id: menu.php,v 1.7 2006/03/03 11:52:53 malanciault Exp $
 * Module: wordbook. adapted from:
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */

$moduleDirName = basename(dirname(__DIR__));

$moduleHandler =& xoops_gethandler('module');
$module        =& $moduleHandler->getByDirname($moduleDirName);
$pathIcon32    = '../../' . $module->getInfo('sysicons32');
xoops_loadLanguage('modinfo', $module->dirname());

$xoopsModuleAdminPath = XOOPS_ROOT_PATH . '/' . $module->getInfo('dirmoduleadmin');
if (!file_exists($fileinc = $xoopsModuleAdminPath . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/' . 'main.php')) {
    $fileinc = $xoopsModuleAdminPath . '/language/english/main.php';
}
include_once $fileinc;

$adminmenu[] = array(
    'title' => _AM_MODULEADMIN_HOME,
    'link'  => 'admin/index.php',
    'icon'  => $pathIcon32 . '/home.png');

$adminmenu[] = array(
    'title' => _MI_WB_ADMENU1,
    'link'  => 'admin/main.php',
    'icon'  => $pathIcon32 . '/manage.png');

$adminmenu[] = array(
    'title' => _MI_WB_ADMENU2,
    'link'  => 'admin/category.php',
    'icon'  => $pathIcon32 . '/category.png');

$adminmenu[] = array(
    'title' => _MI_WB_ADMENU3,
    'link'  => 'admin/entry.php',
    'icon'  => $pathIcon32 . '/faq.png');

$adminmenu[] = array(
    'title' => _MI_WB_ADMENU8,
    'link'  => 'admin/submissions.php',
    'icon'  => $pathIcon32 . '/add.png');

//$adminmenu[] = array(
//    'title' => _MI_WB_ADMENU10,
//    'link'  => 'admin/about0.php',
//    'icon'  => $pathIcon32 . '/about.png');
//
//$adminmenu[] = array(
//    'title' => _MI_WB_HELP,
//    'link'  => 'admin/about0.php?op=readme',
//    'icon'  => $pathIcon32 . '/help.png');

$adminmenu[] = array(
    'title' => _AM_MODULEADMIN_ABOUT,
    'link'  => 'admin/about.php',
    'icon'  => $pathIcon32 . '/about.png');
