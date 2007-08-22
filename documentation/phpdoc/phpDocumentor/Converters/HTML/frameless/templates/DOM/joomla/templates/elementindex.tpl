{include file="header.tpl"}
<a name="top"></a>
<div class="contentheading">Element index for all packages</div>
<br />
<h3>Individual package element indexes</h3>
<ul>
{section name=p loop=$packageindex}
	<li><a href="elementindex_{$packageindex[p].title}.html">{$packageindex[p].title}</a></li>
{/section}
</ul>
<br />
{include file="basicindex.tpl" indexname="elementindex"}
{include file="footer.tpl"}
