<table id="moduleheader">
<tr>
<td width="100%"><span class="leftheader"><a href="<{$xoops_url}>"><{$smarty.const._MD_WB_HOME}></a> > <a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/index.php"><{$lang_modulename}></a> > <{$pageinitial}></span></td>
<td width="100"><span class="rightheader"><{$lang_modulename}></span></td>
</tr>
</table>
<br />

<{* New Alphabet block *}>
<div class="clearer">
<fieldset>
<legend><{$smarty.const._MD_WB_BROWSELETTER}></legend>
<div class="letters">
          <{foreach item=letterlinks from=$alpha.initial}>
          <{if $letterlinks.total > 0}> <a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/letter.php?init=<{$letterlinks.id}>" title="[ <{$letterlinks.total}> ]" ><{/if}><{$letterlinks.linktext}>
          <{if $letterlinks.total > 0}></a><{/if}> |<{/foreach}>
          <{if $totalother > 0}><a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/letter.php?init=<{$smarty.const._MD_WB_OTHER}>" title="[ <{$totalother}> ]"><{/if}><{$smarty.const._MD_WB_OTHER}><{if $totalother > 0}></a><{/if}> | <a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/letter.php" title="[ <{$publishedwords}> ]"><{$smarty.const._MD_WB_ALL}></a>
      </div>
   </fieldset>
</div>


<{if $multicats == 1}>
<div class="clearer">
<fieldset>
<legend><{$smarty.const._MD_WB_BROWSECAT}></legend>
<div class="letters">
<{foreach item=catlinks from=$block0.categories}> <a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/category.php?categoryID=<{$catlinks.id}>"><{$catlinks.linktext}>
<{if $catlinks.total > 0}><{/if}>
</a> [<{$catlinks.total}>] |
<{/foreach}>  <a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/category.php"><{$smarty.const._MD_WB_ALLCATS}></a> [<{$publishedwords}>]
</div>
</fieldset>
</div>
<{/if}>

<{if $pagetype == '0'}>
<h2 class="cat"><{$smarty.const._MD_WB_ALL}></h2>
<div class="letters"><{$smarty.const._MD_WB_WEHAVE}> <{$totalentries}> <{$smarty.const._MD_WB_INALLGLOSSARIES}></div>
<br />
<{foreach item=eachentry from=$entriesarray.single}>
<h4 class="term"><{$eachentry.microlinks}><a href="<{$xoops_url}>/modules/<{$eachentry.dir}>/entry.php?entryID=<{$eachentry.id}>"><{$eachentry.term}></a> <{if $multicats == 1}>[<{$eachentry.catname}>]<{/if}></h4>
<div class="definition"><{$eachentry.definition}></div>
<{$eachentry.comments}><br /><br />
<{/foreach}>
<div align = 'left'><{$entriesarray.navbar}></div>
<div class="letters"> [ <a href='javascript:history.go(-1)'><{$smarty.const._MD_WB_RETURN}></a><b> | </b><a href='./index.php'><{$smarty.const._MD_WB_RETURN2INDEX}></a> ] </div>

<{elseif $pagetype == '1'}>
<h2 class="cat"><{$firstletter}></h2>
<div class="letters"><{$smarty.const._MD_WB_WEHAVE}> <{$totalentries}> <{$smarty.const._MD_WB_BEGINWITHLETTER}></div>
<br />
<{foreach item=eachentry from=$entriesarray2.single}>
<h4 class="term"><{$eachentry.microlinks}><a href="<{$xoops_url}>/modules/<{$eachentry.dir}>/entry.php?entryID=<{$eachentry.id}>"><{$eachentry.term}></a> <{if $multicats == 1}>[<{$eachentry.catname}>]<{/if}></h4>
<div class="definition"><{$eachentry.definition}></div><br />
<{$eachentry.comments}><br /><br />
<{/foreach}>
<div align="left"><{$entriesarray2.navbar}></div>
<div class='letters'> [ <a href='javascript:history.go(-1)'><{$smarty.const._MD_WB_RETURN}></a><b> | </b><a href='./index.php'><{$smarty.const._MD_WB_RETURN2INDEX}></a> ] </div>

<{/if}>