{include file="header.tpl"}
<div class="contentheading">{$title}</div>
{if $interfaces}
<hr />
{section name=classtrees loop=$interfaces}
Root interface {$interfaces[classtrees].class}
{$interfaces[classtrees].class_tree}
{/section}
{/if}
{if $classtrees}
<hr />
{section name=classtrees loop=$classtrees}
Root class {$classtrees[classtrees].class}
{$classtrees[classtrees].class_tree}
{/section}
{/if}
{include file="footer.tpl"}
