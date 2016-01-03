<table id="moduleheader">
<tr>
<td width="100%"><span class="leftheader"><a href="<{$xoops_url}>"><{$smarty.const._MD_WB_HOME}></a> > <a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/index.php"><{$lang_modulename}></a> > <a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/letter.php?init=<{$thisterm.init}>"><{$thisterm.init}></a> > <{$thisterm.term}></span></td>
<td width="100"><span class="rightheader"><{$lang_modulename}></span></td>
</tr>
</table>


<{* New Alphabet block *}>
<div class="toprow"><fieldset>
<legend><{$smarty.const._MD_WB_BROWSELETTER}></legend>
<div class="letters">
          <{foreach item=letterlinks from=$alpha.initial}>
          <{if $letterlinks.total > 0}> <a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/letter.php?init=<{$letterlinks.id}>" title="[ <{$letterlinks.total}> ]" ><{/if}><{$letterlinks.linktext}>
          <{if $letterlinks.total > 0}></a><{/if}> |<{/foreach}>
          <{if $totalother > 0}><a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/letter.php?init=<{$smarty.const._MD_WB_OTHER}>" title="[ <{$totalother}> ]"><{/if}><{$smarty.const._MD_WB_OTHER}><{if $totalother > 0}></a><{/if}> | <a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/letter.php" title="[ <{$publishedwords}> ]"><{$smarty.const._MD_WB_ALL}></a>
      </div>
   </fieldset>
</div>

<{* Category block *}>
<{if $multicats == 1}>
<div class="clearer">
    <fieldset class="item" style="border:1px solid #778;margin:1em 0;text-align:left;background-color:transparent;">
    <legend><{$smarty.const._MD_WB_BROWSECAT}></legend>
        <div class="letters" style="margin:1em 0;width:100%;padding:0;text-align:center;line-height:1.3em;">
           <{foreach item=catlinks from=$block0.categories}>
           <{if $catlinks.total > 0}><a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/category.php?categoryID=<{$catlinks.id}>" title="[<{$catlinks.total}>]"><{/if}><{$catlinks.linktext}>
           <{if $catlinks.total > 0}></a> <{/if}>[<{$catlinks.total}>] | <{/foreach}>
             <{if $publishedwords}><a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/category.php" title="[<{$publishedwords}>]"><{/if}>
          <a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/category.php"><{$smarty.const._MD_WB_ALLCATS}></a> [<{$publishedwords}>]
        </div>
    </fieldset>
  </div>
<{/if}>

<{if $multicats == 1}>
<div class="catback"><b><{$smarty.const._MD_WB_ENTRYCATEGORY}></b>
<a href="<{$xoops_url}>/modules/<{$thisterm.dir}>/category.php?categoryID=<{$thisterm.categoryID}>"><{$thisterm.catname}></a>
</div>
<{/if}>

<h4 class="term"><{$microlinks}><{$thisterm.term}></h4>
<b><{$smarty.const._MD_WB_ENTRYDEFINITION}></b>
<div class="small"><{$thisterm.definition}></div>

<{if $thisterm.ref}>
<div class="small"><b><{$smarty.const._MD_WB_ENTRYREFERENCE}></b><{$thisterm.ref}></div>
<{/if}>

<{if $thisterm.url}>
<div class="xsmall"><b><{$smarty.const._MD_WB_ENTRYRELATEDURL}></b><{$thisterm.url}></div>
<{/if}>
<br />
<div class="xxsmall"><{$submitted}> | <{$counter}></div>

<!-- start comments -->
<div style="text-align: center; padding: 3px; margin: 3px;">
  <{$commentsnav}>
  <{$lang_notice}>
</div>

<div style="margin: 3px; padding: 3px;">
<!-- start comments loop -->
<{if $comment_mode == "flat"}>
  <{include file="db:system_comments_flat.html"}>
<{elseif $comment_mode == "thread"}>
  <{include file="db:system_comments_thread.html"}>
<{elseif $comment_mode == "nest"}>
  <{include file="db:system_comments_nest.html"}>
<{/if}>
<!-- end comments loop -->
<!-- end comments -->
</div>
