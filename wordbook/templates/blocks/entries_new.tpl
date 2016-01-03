<ul>
  <{foreach item=newentries from=$block.newstuff}>
    <li><a href="<{$xoops_url}>/modules/<{$newentries.dir}>/entry.php?entryID=<{$newentries.id}>"><{$newentries.linktext}></a> [<{$newentries.date}>]</li>
  <{/foreach}>
</ul>