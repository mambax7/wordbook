<ul>
  <{foreach item=popentries from=$block.popstuff}>
    <li><a href="<{$xoops_url}>/modules/<{$popentries.dir}>/entry.php?entryID=<{$popentries.id}>"><{$popentries.linktext}></a> [<{$popentries.counter}>]</li>
  <{/foreach}>
</ul>