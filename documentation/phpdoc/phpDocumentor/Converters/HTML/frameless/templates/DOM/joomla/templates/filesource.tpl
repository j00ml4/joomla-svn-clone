{capture name="tutle"}File source for {$name}{/capture}
{include file="header.tpl" title=$smarty.capture.tutle}
<div class="contentheading">Source code for file {$source_loc}</div>
<p>Documentation is available at {$docs}</p>
<div class="src-code">
{$source}
</div>
{include file="footer.tpl"}
