<?PHP
/**
 * $Id: index.php v 1.0 8 May 2004 hsalazar Exp $
 * Module: Wordbook - a multicategory glossary
 * Version: v 1.00
 * Release Date: 8 May 2004
 * Author: hsalazar
 * Licence: GNU
 */
$xoopsOption['pagetype'] = "search";

include("header.php");
$xoopsOption['template_main'] = 'wb_search.html';
include(XOOPS_ROOT_PATH."/header.php");
global $xoTheme, $xoopsDB, $xoopsModule, $xoopsModuleConfig, $searchtype;
$myts =& MyTextSanitizer::getInstance();

include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
// obsolete
//include_once XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/include/cleantags.php";

// Check if search is enabled site-wide
$config_handler =& xoops_gethandler('config');
$xoopsConfigSearch =& $config_handler->getConfigsByCat(XOOPS_CONF_SEARCH);
if ($xoopsConfigSearch['enable_search'] != 1) {
	header('Location: '.XOOPS_URL.'/index.php');
	exit();
}

extract($_GET);
extract($_POST, EXTR_OVERWRITE);

$action = isset($action) ? trim($action) : "search";
$query = isset($term) ? trim($term) : "";
$start = isset($start) ? intval($start) : 0;
$categoryID = isset($categoryID) ? intval($categoryID) : 0;
$type = isset($type) ? intval($type) : 3;
$queries = array();


if ($xoopsModuleConfig['multicats'] == 1)
	{
	$xoopsTpl->assign('multicats', 1);
	}
else
	{
	$xoopsTpl->assign('multicats', 0);
	}

// Configure search parameters according to selector
$query = stripslashes($query);
if ($type == "1") { $searchtype = "w.term LIKE '%$query%' "; }
if ($type == "2") { $searchtype = "definition LIKE '%$query%' "; }
//if ($type == "3") { $searchtype = "term LIKE '%$query%' OR definition LIKE '%$query%' "; }
// v.1.17 search function extended by reference
if ($type == "3") { $searchtype = "term LIKE '%$query%' OR definition LIKE '%$query%' OR ref LIKE '%$query%' "; }


if ($xoopsModuleConfig['multicats'] == 1)
	{
	// If the look is in a particular category
	if ($categoryID > 0 ) { $andcatid = "AND categoryID='$categoryID'"; }
	else { $andcatid = ""; }
	}
else
	{
	$andcatid="";
	}

// Counter
$totalcats = countCats();
$publishedwords = countWords();
$xoopsTpl->assign('totalcats', $totalcats);
$xoopsTpl->assign('publishedwords', $publishedwords);

// If there's no term here (calling directly search page)
if (!$query)
	// Display message saying there's no term and explaining how to search
	{
	$xoopsTpl -> assign ('intro', _MD_WB_NOSEARCHTERM );
	// Display search form
	$searchform = showSearchForm();
	$xoopsTpl->assign('searchform', $searchform);
	}
else
	{
	// IF there IS term, count number of results
	//$searchquery = $xoopsDB -> query ("SELECT * FROM ".$xoopsDB->prefix ("wbentries")." WHERE $searchtype AND submit ='0' AND offline='0' ".$andcatid." ORDER BY term");
	$searchquery = $xoopsDB -> query ("SELECT COUNT(*) as nrows FROM ".$xoopsDB->prefix ("wbentries")." w WHERE $searchtype AND submit ='0' AND offline='0' ".$andcatid." ORDER BY term");
	list($results) = $xoopsDB->fetchRow($searchquery);
	//$results = $xoopsDB -> getRowsNum ( $searchquery );

	if ($results == 0)
		{
		// There's been no correspondences with the searched terms
		$xoopsTpl -> assign ('intro', _MD_WB_NORESULTS );
		// Display search form
		$searchform = showSearchForm();
		$xoopsTpl->assign('searchform', $searchform);
		}
	else	// $results > 0 -> there were search results
		{
		// Show paginated list of results
		// We'll put the results in an array
		$resultset = array();

		// How many results will we show in this page?
		//$queryA = "SELECT * FROM " . $xoopsDB -> prefix( 'wbentries' ) . " WHERE ".$searchtype." AND submit = '0' AND offline = '0' ".$andcatid." ORDER BY term";
  		// v 1.17 include reference
		$queryA = "SELECT w.entryID, w.categoryID, w.term, w.init, w.definition, w.ref, c.name AS catname FROM ".$xoopsDB -> prefix( 'wbentries' )." w LEFT JOIN ".$xoopsDB -> prefix( 'wbcategories' )." c ON w.categoryID = c.categoryID WHERE ".$searchtype." AND w.submit = '0' AND w.offline = '0' ORDER BY w.term ASC";
		//$queryA = "SELECT w.entryID, w.categoryID, w.term, w.init, w.definition, c.name AS catname FROM ".$xoopsDB -> prefix( 'wbentries' )." w LEFT JOIN ".$xoopsDB -> prefix( 'wbcategories' )." c ON w.categoryID = c.categoryID WHERE ".$searchtype." AND w.submit = '0' AND w.offline = '0' ORDER BY w.term ASC";
		$resultA = $xoopsDB -> query ($queryA, $xoopsModuleConfig['indexperpage'], $start );
		
		//while (list( $entryID, $categoryID, $term, $init, $definition ) = $xoopsDB->fetchRow($resultA))
		//while (list( $entryID, $categoryID, $term, $init, $definition, $catname ) = $xoopsDB->fetchRow($resultA))
   		while (list( $entryID, $categoryID, $term, $init, $definition, $ref, $catname ) = $xoopsDB->fetchRow($resultA))
			{
			$eachresult = array();
			$xoopsModule = XoopsModule::getByDirname("wordbook");
			$eachresult['dir'] = $xoopsModule->dirname();
			$eachresult['entryID'] = $entryID;
			$eachresult['categoryID'] = $categoryID;
			$eachresult['term'] = ucfirst($myts -> makeTboxData4Show( $term ));
   			$eachresult['ref'] = $myts -> htmlSpecialChars( $ref );
			$eachresult['catname'] = $myts -> makeTboxData4Show( $catname );
			//$tempdef = wb_cleanTags( $definition );
			$tempdef = $myts -> displayTarea ( $definition );
			$tempdef = wb_html2text($tempdef); // v 1.17 special tags
			$eachresult['definition'] = getHTMLHighlight( $query, $tempdef, '<b style="background-color: yellow; ">', '</b>' );
			//$eachresult['definition'] = $myts->addslashes( $definition );//show HTML but np highlighter

			// Functional links
			//$microlinks = serviceLinks ( $eachresult ); // erroneous
			// 1.17
	 		$microlinks = serviceLinks ( $eachresult['entryID'] );// erroneous as well but better ;-)
			$eachresult['microlinks'] = $microlinks;
			$resultset['match'][] = $eachresult;
			}
		// Msg: there's # results
		$xoopsTpl -> assign ('intro', sprintf( _MD_WB_THEREWERE, $results, $query ) );
		
		$linkstring = "term=".$query."&start";
		$pagenav = new XoopsPageNav( $results, $xoopsModuleConfig['indexperpage'], $start, $linkstring );
		$resultset['navbar'] = '<div style="text-align:right;">' . $pagenav -> renderNav() . '</div>';

		$xoopsTpl -> assign ('resultset', $resultset);

		// Display search form
		$searchform = showSearchForm();
		$xoopsTpl->assign('searchform', $searchform);
		}
	}
// Assign variables and close
$xoopsTpl -> assign ( 'lang_modulename', $xoopsModule->name() );
$xoopsTpl -> assign ( 'lang_moduledirname', $xoopsModule->dirname() );
// v.1.17
$xoopsTpl->assign('xoops_pagetitle', $myts->htmlSpecialChars($xoopsModule->name()). ' - ' ._MD_WB_SEARCHENTRY);

$xoopsTpl->assign("xoops_module_header", '<link rel="stylesheet" type="text/css" href="style.css" />');

include(XOOPS_ROOT_PATH."/footer.php");
?>
