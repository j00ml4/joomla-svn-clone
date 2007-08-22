<div class="index-letter-menu">
{section name=letter loop=$letters}
	<a class="index-letter" href="{$indexname}.html#{$letters[letter].letter}">{$letters[letter].letter}</a>
{/section}
</div>

{section name=index loop=$index}
	<a name="{$index[index].letter}"></a>
	<div class="index-letter-section">
		<div style="float: left" class="index-letter-title">{$index[index].letter}</div>
		<div style="float: right"><a href="#top">top</a></div>
		<div style="clear: both"></div>
	</div>
	<dl>
	{section name=contents loop=$index[index].index}
		<dt class="field">
		    {if ($index[index].index[contents].title == "Variable")}
			<img src="{$subdir}media/images/{if $index[index].index[contents].access == 'private'}Private{/if}{$index[index].index[contents].title}.png" alt="{if $index[index].index[contents].access == 'private'}Private variable{else}Variable{/if}" title="{if $index[index].index[contents].access == 'private'}Private variable{else}Variable{/if}" />
			<span class="var-title">{$index[index].index[contents].name}</span>
		    {elseif ($index[index].index[contents].title == "Global")}
			<img src="{$subdir}media/images/Global.png" alt="Global" title="Global" />
			<span class="var-title">{$index[index].index[contents].name}</span>
		    {elseif ($index[index].index[contents].title == "Method")}
			<img src="{$subdir}media/images/{if $index[index].index[contents].constructor}Constructor{elseif $index[index].index[contents].destructor}Destructor{elseif $index[index].index[contents].access == 'private'}{if $index[index].index[contents].abstract}Abstract{/if}PrivateMethod{else}{if $index[index].index[contents].abstract}Abstract{/if}Method{/if}.png"
				alt="{if $index[index].index[contents].constructor}Constructor{elseif $index[index].index[contents].destructor}Destructor{elseif $index[index].index[contents].abstract}Abstract {if $index[index].index[contents].access == 'private'}private {/if}method{elseif $index[index].index[contents].access == 'private'}Private method{else}Method{/if}"
				title="{if $index[index].index[contents].constructor}Constructor{elseif $index[index].index[contents].destructor}Destructor{elseif $index[index].index[contents].abstract}Abstract {if $index[index].index[contents].access == 'private'}private {/if}method{elseif $index[index].index[contents].access == 'private'}Private method{else}Method{/if}"/>
			<span class="method-title">{$index[index].index[contents].name}</span>
		    {elseif ($index[index].index[contents].title == "Function")}
			<img src="{$subdir}media/images/Function.png" alt="Function" title="Function" />
			<span class="method-title">{$index[index].index[contents].name}</span>
		    {elseif ($index[index].index[contents].title == "Constant")}
			<img src="{$subdir}media/images/Constant.png" alt="Constant" title="Constant" />
			<span class="const-title">{$index[index].index[contents].name}</span>
		    {elseif ($index[index].index[contents].title == "Page") || ($index[index].index[contents].title == "Include")}
			<img src="{$subdir}media/images/{$index[index].index[contents].title}.png" alt="File" title="File" />
			<span class="include-title">{$index[index].index[contents].name}</span>
		    {elseif ($index[index].index[contents].title == "Class")}
			<img src="{$subdir}media/images/{if $index[index].index[contents].abstract}Abstract{/if}{if $index[index].index[contents].access == 'private'}Private{/if}Class.png" alt="{if $index[index].index[contents].abstract}Abstract {if $index[index].index[contents].access == 'private'}private {/if}c{else}{if $index[index].index[contents].access == 'private'}Private c{else}C{/if}{/if}lass" title="{if $index[index].index[contents].abstract}Abstract {if $index[index].index[contents].access == 'private'}private {/if}c{else}{if $index[index].index[contents].access == 'private'}Private c{else}C{/if}{/if}lass" />
			{$index[index].index[contents].name}
		    {else}
			<img src="{$subdir}media/images/{$index[index].index[contents].title}.png" alt="{$index[index].index[contents].title}" title="{$index[index].index[contents].title}" />
			{$index[index].index[contents].name}
		    {/if}
		</dt>
		<dd class="index-item-body">
			<div class="index-item-details">{$index[index].index[contents].link} in {$index[index].index[contents].file_name}</div>
			{if $index[index].index[contents].description}
				<div class="index-item-description">{$index[index].index[contents].description}</div>
			{/if}
		</dd>
	{/section}
	</dl>
{/section}

<div class="index-letter-menu">
{section name=letter loop=$letters}
	<a class="index-letter" href="{$indexname}.html#{$letters[letter].letter}">{$letters[letter].letter}</a>
{/section}
</div>
