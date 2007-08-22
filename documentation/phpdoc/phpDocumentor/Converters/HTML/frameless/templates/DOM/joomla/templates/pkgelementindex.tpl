{include file="header.tpl"}
<a name="top"></a>
<div class="contentheading">Element index for package {$package}</div>
<br />
{if count($packageindex) > 1}
	<h3>Other package element indexes</h3>
	<ul>
	{section name=p loop=$packageindex}
	{if $packageindex[p].title != $package}
		<li><a href="elementindex_{$packageindex[p].title}.html">{$packageindex[p].title}</a></li>
	{/if}
	{/section}
	<li><a href="elementindex.html">Element index (all packages)</a></li>
	</ul>
{/if}
<br />
{include file="basicindex.tpl" indexname=elementindex_$package}
{include file="footer.tpl"}
