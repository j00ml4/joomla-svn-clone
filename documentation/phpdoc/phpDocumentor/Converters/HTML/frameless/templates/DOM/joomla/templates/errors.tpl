{include file="header.tpl"}
<a name="top"></a>
<div class="contentheading">phpDocumentor Error Log</div>
<br />
<p>Warnings and errors were detected in the following files:</p>
<ul>
{section name=files loop=$files}
<li><a href="#{$files[files].file}">{$files[files].file}</a></li>
{/section}
</ul>
{foreach key=file item=issues from=$all}
<a name="{$file}"></a>
<div class="error-section">
	<div style="float: left" class="error-title">{$file}</div>
        <div style="float: right"><a href="#top">top</a></div>
        <div style="clear: both"></div>
</div>
{if count($issues.warnings)}
{section name=warnings loop=$issues.warnings}
<b>{$issues.warnings[warnings].name}</b> - {$issues.warnings[warnings].listing}<br />
{/section}
<br />
{/if}
{if count($issues.errors)}
{section name=errors loop=$issues.errors}
<b>{$issues.errors[errors].name}</b> - {$issues.errors[errors].listing}<br />
{/section}
<br />
{/if}
{/foreach}
{include file="footer.tpl"}
