<table id="moduleheader">
<tr>
<td width="100%"><span class="leftheader"><a href="<{$xoops_url}>"><{$smarty.const._MD_WB_HOME}></a> > <{$lang_modulename}></span></td>
<td width="100"><span class="rightheader"><{$lang_modulename}></span></td>
</tr>
</table>

<{if $empty == 1}><div class="empty"><{$smarty.const._MD_WB_STILLNOTHINGHERE}></div><{/if}>

<div class="toprow">
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
<input class="btnDefault" type="button" value="<{$smarty.const._MD_WB_REQUESTDEF}>" onclick="location.href = 'request.php' " />
</div>
</div>

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

<div class="float30">
<fieldset>
<legend><{$smarty.const._MD_WB_RECENTENT}></legend>
<ul>
<{foreach item=newentries from=$block.newstuff}>
<li><a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/entry.php?entryID=<{$newentries.id}>"><{$newentries.linktext}></a> <span style="font-size: xx-small; color: #456;">[<{$newentries.date}>]</span></li>
<{/foreach}>
</ul>
</fieldset>
</div>

<div class="float30">
<fieldset>
<legend><{$smarty.const._MD_WB_POPULARENT}></legend>
<ul>
<{foreach item=popentries from=$block2.popstuff}>
<li><a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/entry.php?entryID=<{$popentries.id}>"><{$popentries.linktext}></a> <span style="font-size: xx-small; color: #456;">[<{$popentries.counter}>]</span></li>
<{/foreach}>
</ul>
</fieldset>
</div>

<div>
<fieldset>
<legend><{$smarty.const._MD_WB_RANDOMTERM}></legend>
<{if $multicats == 1}>
<{if $empty != 1}>
<div class="catname"><a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/category.php?categoryID=<{$random.categoryID}>"><{$random.categoryname}></a></div>
<{/if}>
<{/if}>
<div class="pad4">
    <h5 class="term"><{$microlinks}><a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/entry.php?entryID=<{$random.entryID}>"><{$random.term}></a></h5>
    <div class="nopadding"><{$random.definition}></div>
</div>
</fieldset>
</div>

<{if $userisadmin == 1}>
<div class="clearer2">
<fieldset>
<legend><{$smarty.const._MD_WB_SUBANDREQ}></legend>

<div class="submission">
<b><{$smarty.const._MD_WB_SUB}></b>
<{if $wehavesubs == '0'}><{$smarty.const._MD_WB_NOSUB}><{/if}>
<{foreach item=subentries from=$blockS.substuff}>
<a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/admin/entry.php?op=mod&entryID=<{$subentries.id}>"><{$subentries.linktext}></a>&nbsp;
<{/foreach}>
</div>

<div class="request">
<b><{$smarty.const._MD_WB_REQ}></b>
<{if $wehavereqs == '0'}><{$smarty.const._MD_WB_NOREQ}><{/if}>
<{foreach item=reqentries from=$blockR.reqstuff}>
<a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/admin/entry.php?op=mod&entryID=<{$reqentries.id}>"><{$reqentries.linktext}></a>&nbsp;
<{/foreach}>
</div>
</fieldset>
<div>
<{else}>
<div class="clearer2">
<fieldset>
<legend><{$smarty.const._MD_WB_REQ}></legend>

<div class="request">
<b><{$smarty.const._MD_WB_REQ}></b>
<{if $wehavereqs == '0'}><{$smarty.const._MD_WB_NOREQ}>
<{else}>
<br /><span style="font-size:80%;"><{$smarty.const._MD_WB_REQUESTSUGGEST}></span><br />
<{/if}>
<{foreach item=reqentries from=$blockR.reqstuff}>
<a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/submit.php?suggest=<{$reqentries.id}>"><{$reqentries.linktext}></a>&nbsp;
<{/foreach}>
</div>
</fieldset>
<div>
<{/if}>
