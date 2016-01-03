<?php
include("admin_header.php"); 

function import2db($text) {
	return preg_replace(array("/'/i"), array("\'"), $text);
}

function DefinitionImport($delete) {
	global $xoopsConfig, $xoopsDB, $xoopsModule;
	$sqlquery = $xoopsDB->query("SELECT count(id) as count FROM ".$xoopsDB->prefix("dictionary"));
	list( $count ) = $xoopsDB->fetchRow( $sqlquery ) ;
	if( $count < 1 ) {
		redirect_header("index.php",1,"Database for import missing or empty!");
		exit();
	}

	xoops_cp_header();
	echo "<B>Wordbook Entries Import Script</B><p>";
	OpenTable();
	echo "<BR><B>Import from Dictionary Version 0.92</B><P>";
	
	$glocounter = 0;
	$errorcounter = 0;

	/*if ($delete) {
	  	//get all entries
		$result3=$xoopsDB->query("select entryID from ".$xoopsDB->prefix("wbentries")."");
		//now for each entry, delete the coments
		while ( list($entryID)=$xoopsDB->fetchRow($result3) ) {
				xoops_comment_delete(
				$xoopsModule->getVar('mid'), $entryID);
			}		 
		$sqlquery=$xoopsDB->queryF("delete from ".$xoopsDB->prefix("wbentries"));
	}*/
	$sqlquery=$xoopsDB->queryF("truncate table ".$xoopsDB->prefix("wbentries"));
	
	$sqlquery=$xoopsDB->query("SELECT id, letter, name, definition, state, comments from ".$xoopsDB->prefix("dictionary"));
	$fecha = time()-1;
	while ($sqlfetch=$xoopsDB->fetchArray($sqlquery)) {
		$glo = array();
		$glo['id'] = $sqlfetch["id"];
		$glo['letter'] = $sqlfetch["letter"];
		$glo['name'] = import2db($sqlfetch["name"]);
		$glo['definition'] = import2db($sqlfetch["definition"]);
		$glo['datesub'] = $fecha++;
		$estado = $sqlfetch["state"];
		if ($estado == 'O') 
			{ $glo['state'] = 0; } else {$glo['state'] = 1;}
		if ($estado == 'D') 
			{ $glo['submit'] = 1; } else {$glo['submit'] = 0;}
		$glo['comments'] = $sqlfetch["comments"];
		$glocounter = $glocounter + 1;
		
		$insert = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("wbentries")." (entryID, init, term, definition, url, submit, datesub, offline, comments) VALUES ('','".$glo['letter']."','".$glo['name']."','".$glo['definition']."','','".$glo['submit']."','".$glo['datesub']."','".$glo['state']."','".$glo['comments']."')");
		if (!$insert) {
			$errorcounter = $errorcounter + 1;
			echo "<font color='red'>Error: #".$glo['id']."</font><br>".$glo['nom']."<br>";
			echo $glo['definition']."<br><br>";
		}
	}
	
	$sqlquery=$xoopsDB->query("SELECT mid from ".$xoopsDB->prefix("modules")." WHERE dirname = 'dictionary'");
	list( $dicID ) = $xoopsDB->fetchRow( $sqlquery ) ;
	echo "<p>Dictionary Module ID: ".$dicID."</p>";
	echo "<p>Wordbook Module ID: ".$xoopsModule->getVar('mid')."</p>";

	$comentario = $xoopsDB->queryF("UPDATE ".$xoopsDB->prefix("xoopscomments")." SET com_modid = '".$xoopsModule->getVar('mid')."' WHERE  com_modid = '".$dicID."'");
	if (!$comentario) {
		echo "<font color='red'>Error while moving Comments from Dictionary to Wordbook module.<br><br>";
	} else { echo "<p>Comments successfully moved from Dictionary to Wordbook</p>"; }

	echo "<p><font color='red'>Incorrectly: ".$errorcounter."</font></p>";
	echo "<p>Processed: ".$glocounter."</p>";
	CloseTable();
	echo "<br /><B><a href='index.php'>Back to Admin</a></B><p>";
	xoops_cp_footer();
}

function FormImport() {
	global $xoopsConfig, $xoopsDB;
	xoops_cp_header();
	echo "<B>Wordbook Entries Import Script</B><p>";
	OpenTable();
	echo "<B>Import from Dictionary Version 0.92</B><P>";
	echo "<BR><B><font color='red'>"._AM_WB_IMPORTWARN."</font></B><P>";
	echo "<FORM ACTION='importdictionary091.php?op=import' METHOD=POST>
	<TABLE BORDER=0 CELLPADDING=2 CELLSPACING=3>";
	/*<TR>
  	  <TD width='50%'>Is to be deleted the existing Dictionary entries before the import? </TD>
  	  <TD><input type='radio' name='delete' value='1'>&nbsp;"._YES."&nbsp;&nbsp;<input type='radio' name='delete' value='0' checked='checked'>&nbsp;"._NO."</TD>
	</TR>*/
	echo "
	<tr>
	  <td width='100%' colspan='2' align='center'><br><input type='submit' name='button' id='import' value='"._AM_WB_IMPORT."'>&nbsp;<input type='button' name='cancel' value='"._CANCEL."' onclick='javascript:history.go(-1);'></td>
	<tr> 
	</TABLE>";
	CloseTable();
	xoops_cp_footer();



}

if(!isset($HTTP_POST_VARS['op'])) {
	$op = isset($HTTP_GET_VARS['op']) ? $HTTP_GET_VARS['op'] : 'main';
} else {
	$op = $HTTP_POST_VARS['op'];
}

if(!isset($HTTP_POST_VARS['delete'])) {
	$delete = isset($HTTP_GET_VARS['delete']) ? $HTTP_GET_VARS['delete'] : 'main';
} else {
	$delete = $HTTP_POST_VARS['delete'];
}

switch($op) {
    	case "import":
		DefinitionImport($delete);
		break;
	case 'main':
	default:
		FormImport();
		break;
}
?>