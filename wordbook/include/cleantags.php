<?php
/* This file comes from a post by tREXX [www.trexx.ch] in http://www.php.net/manual/en/function.strip-tags.php */
/**
 * Allow these tags
 */
//$allowedTags = '<h1><b><i><a><ul><li><pre><hr><blockquote>';
$allowedTags = '<a><acronym><address><b><br><blockquote><cite><code><div><dd><del><dl><dt><em><h1><h2><h3><h4><h5><h6><hr><i><img><li><ol><p><pre><s><span><strong><sub><table><tr><td><th><u><ul>';

/**
 * Disallow these attributes/prefix within a tag
 */
//$stripAttrib = '';
$stripAttrib = 'javascript|onclick|ondblclick|onmousedown|onmouseup|onmouseover|'.
               'onmousemove|onmouseout|onkeypress|onkeydown|onkeyup|vbscript|about';

/**
 * @return string
 * @param string
 * @desc Strip forbidden tags and delegate tag-source check to cleanAttributes()
 */
function wb_cleanTags($source)
{
   global $allowedTags;
   $source = strip_tags($source, $allowedTags);
   $source = preg_replace('/<(.*?)>/ie', "'<'.cleanAttributes('\\1').'>'", $source);
   return $source;
}

/**
 * @return string
 * @param string
 * @desc Strip forbidden attributes from a tag
 */
function cleanAttributes($tagSource)
{
   global $stripAttrib;
   return stripslashes(preg_replace("/$stripAttrib/i", '', $tagSource));
}

// Will output: <a href="forbiddenalert(1);" target="_blank" forbidden =" alert(1)">test</a>
// echo wb_cleanTags('<a href="javascript:alert(1);" target="_blank" onMouseOver = "alert(1)">test</a>');
?>
