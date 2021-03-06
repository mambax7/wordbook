<table id="moduleheader">
<tr>
<td width="100%"><span class="leftheader"><a href="<{$xoops_url}>"><{$smarty.const._MD_WB_HOME}></a> > <a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/index.php"><{$lang_modulename}></a> > <{if $pagetype == '0'}><{$smarty.const._MD_WB_ALLCATS}><{elseif $pagetype == '1'}><{$singlecat.name}><{/if}></span></td>
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

<{if $pagetype == '0'}>
    <h2 class="cat"><{$smarty.const._MD_WB_ALLCATS}></h2>

    <{foreach item=eachcat from=$catsarray.single}>
    <h3 class="cat"><a href="<{$xoops_url}>/modules/<{$eachcat.dir}>/category.php?categoryID=<{$eachcat.id}>"><{$eachcat.name}></a></h3>
    <div class="introcen"><{$eachcat.description}><br /></div>
    <div class="letters"><{$smarty.const._MD_WB_WEHAVE}> <{$eachcat.total}> <{$smarty.const._MD_WB_ENTRIESINCAT}></div><br />
    <{/foreach}>

    <div align = 'left'><{$catsarray.navbar}></div>
    <p><div align = 'center'> [ <a href='javascript:history.go(-1)'><{$smarty.const._MD_WB_RETURN}></a><b> | </b><a href='./index.php'><{$smarty.const._MD_WB_RETURN2INDEX}></a> ] </div>

<{elseif $pagetype == '1'}>
    <h2 class="cat"><{$singlecat.name}></h2>
    <div class="introcen"><{$singlecat.description}></div>
    <div class="letters"><{$smarty.const._MD_WB_WEHAVE}> <{$singlecat.total}> <{$smarty.const._MD_WB_ENTRIESINCAT}></div><br />

    <{foreach item=eachentry from=$entriesarray.single}>
    <h4><{$eachentry.microlinks}><a href="<{$xoops_url}>/modules/<{$eachentry.dir}>/entry.php?entryID=<{$eachentry.id}>"><{$eachentry.term}></a></h4>
    <div class="definition"><{$eachentry.definition}></div>
    <br />
    <{/foreach}>

    <div align = 'left'><{$entriesarray.navbar}></div>
    <p><div align = 'center'> [ <a href='javascript:history.go(-1)'><{$smarty.const._MD_WB_RETURN}></a><b> | </b><a href='./index.php'><{$smarty.const._MD_WB_RETURN2INDEX}></a> ] </div>

<{/if}>
