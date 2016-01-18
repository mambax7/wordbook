<table id="moduleheader">
<tr>
<td width="100%"><span class="leftheader"><a href="<{$xoops_url}>"><{$smarty.const._MD_WB_HOME}></a> > <a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/index.php"><{$lang_modulename}></a> > <{$smarty.const._MD_WB_SEARCHHEAD}></span></td>
<td width="100"><span class="rightheader"><{$lang_modulename}></span></td>
</tr>
</table>

<h3 class="catsearch"><{$smarty.const._MD_WB_SEARCHHEAD}></h3>
<p class="intro"><{$intro}></p>
<div id="toprow">
<div id="search">
<fieldset>
<legend><{$smarty.const._MD_WB_SEARCHENTRY}></legend>
<{$searchform}>
</fieldset>
</div>

<div class="inventory">
<{$smarty.const._MD_WB_WEHAVE}><br />
<{$smarty.const._MD_WB_DEFS}><{$publishedwords}><br />

<{if $multicats == 1}><{$smarty.const._MD_WB_CATS}><{$totalcats}><br /><{/if}>

<input class="btnDefault" type="button" value="<{$smarty.const._MD_WB_SUBMITENTRY}>" onclick="location.href = 'submit.php'" /><br />
<input class="btnDefault" type="button" value="<{$smarty.const._MD_WB_REQUESTDEF}>"  onclick="location.href = 'request.php'" />
</div>
</div>

<div class="clearer2">
<{foreach item=eachresult from=$resultset.match}>
<img src="<{$xoops_url}>/modules/<{$eachresult.dir}>/assets/images/wb.png" />&nbsp;<{$eachresult.microlinks}>&nbsp;<a href="<{$xoops_url}>/modules/<{$eachresult.dir}>/entry.php?entryID=<{$eachresult.entryID}>"><{$eachresult.term}></a><{if $multicats == 1}> <a href="<{$xoops_url}>/modules/<{$eachresult.dir}>/category.php?categoryID=<{$eachresult.categoryID}>">[<{$eachresult.catname}>]</a><{/if}><br />
<div class="result"><{$eachresult.definition}></div>
<{/foreach}>
<div align = 'left'><{$resultset.navbar}></div>
</div>
