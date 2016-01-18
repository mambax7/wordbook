<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package      Wordbook - a multicategory glossary
 * @since        8 May 2004
 * @author       hsalazar, XOOPS Development Team
 * @version      $Id $
 */

// Module Info
// The name of this module
global $xoopsModule;
define("_MI_WB_MD_NAME", "Wordbook");

// A brief description of this module
define("_MI_WB_MD_DESC", "A multicategory glossary");

// Sub menus in main menu block
define("_MI_WB_SUB_SMNAME1", "Submit an entry");
define("_MI_WB_SUB_SMNAME2", "Request a definition");
define("_MI_WB_SUB_SMNAME3", "Search for a definition");

define("_MI_WB_RANDOMTERM", "WB Random term");

// A brief description of this module
define("_MI_WB_ALLOWSUBMIT", "1. Can users submit entries?");
define("_MI_WB_ALLOWSUBMITDSC", "If set to 'Yes', users will have access to a submission form");

define("_MI_WB_ANONSUBMIT", "2. Can guests submit entries?");
define("_MI_WB_ANONSUBMITDSC", "If set to 'Yes', guests will have access to a submission form");

define("_MI_WB_DATEFORMAT", "3. In what format should the date appear?");
define("_MI_WB_DATEFORMATDSC", "Use the final part of language/english/global.php to select a display style. Example: 'd-M-Y H:i' translates to '23-Mar-2004 22:35'");

define("_MI_WB_PERPAGE", "4. Number of entries per page (Admin side)?");
define("_MI_WB_PERPAGEDSC", "Number of entries that will be shown at once in the table that displays active entries in the admin side.");

define("_MI_WB_PERPAGEINDEX", "5. Number of entries per page (User side)?");
define("_MI_WB_PERPAGEINDEXDSC", "Number of entries that will be shown on each page in the user side of the module.");

define("_MI_WB_AUTOAPPROVE", "6. Approve entries automatically?");
define("_MI_WB_AUTOAPPROVEDSC", "If set to 'Yes', XOOPS will publish submitted entries without admin intervention.");

define("_MI_WB_MULTICATS", "7. Do you want to have glossary categories?");
define("_MI_WB_MULTICATSDSC", "If set to 'Yes', will allow you to have glossary categories. If set to no, will have a single automatic category.");

define("_MI_WB_CATSINMENU", "8. Should the categories be shown in the menu?");
define("_MI_WB_CATSINMENUDSC", "If set to 'Yes' if you want links to categories in the main menu.");

define("_MI_WB_CATSPERINDEX", "9. Number of categories per page (User side)?");
define("_MI_WB_CATSPERINDEXDSC", "This will define how many categories will be shown in the index page.");

define("_MI_WB_ALLOWADMINHITS", "10. Will the admin hits be included in the counter?");
define("_MI_WB_ALLOWADMINHITSDSC", "If set to 'Yes', will increase counter for each entry on admin visits.");

define("_MI_WB_MAILTOADMIN", "11. Send mail to admin on each new submission?");
define("_MI_WB_MAILTOADMINDSC", "If set to 'Yes', the manager will receive an e-mail for every submitted entry.");
define("_MI_WB_RANDOMLENGTH", "12. Length of string to show in random definitions?");
define("_MI_WB_RANDOMLENGTHDSC", "How many characters do you want to show in the random term boxes, both in the main page and in the block? (Default: 150)");

define("_MI_WB_LINKTERMS", "13. Show links to other glossary terms in the definitions?");
define("_MI_WB_LINKTERMSDSC", "If set to 'yes', will automatically link in your definitions those terms you already have in your glossaries.");

// Names of admin menu items
define("_MI_WB_ADMENU1", "Index");
define("_MI_WB_ADMENU2", "Categories");
define("_MI_WB_ADMENU3", "Entries");
define("_MI_WB_ADMENU4", "Blocks");
define("_MI_WB_ADMENU5", "Go to module");
//mondarse
define("_MI_WB_ADMENU6", "Import");

//Names of Blocks and Block information
define("_MI_WB_ENTRIESNEW", "WB Newest Terms");
define("_MI_WB_ENTRIESTOP", "WB Most Read Terms");

// added in version 1.17
define("_MI_WB_ADMENU8", "Submissions");
define("_MI_WB_ADMENU10", "About");

// The name of this module
define('_MI_WB_NAME', _MI_WB_MD_NAME);
define('_MI_WB_DIRNAME', basename(dirname(dirname(__DIR__))));
define('_MI_WB_HELP_HEADER', __DIR__ . '/help/helpheader.html');
define('_MI_WB_BACK_2_ADMIN', 'Back to Administration of ');

//define('_MI_WB_HELP_DIR', __DIR__);

//help
define('_MI_WB_HELP_OVERVIEW', 'Overview');
define("_MI_WB_HELP", "Help");
