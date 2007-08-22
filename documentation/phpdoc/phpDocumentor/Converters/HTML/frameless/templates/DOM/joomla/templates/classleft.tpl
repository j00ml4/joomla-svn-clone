{foreach key=subpackage item=files from=$classleftindex}
  <div class="package">
	{if $subpackage != ""}{$subpackage}<br />{/if}
	<ul>
	{section name=files loop=$files}
		{if $files[files].link != ''}<li><a href="{$files[files].link}">{/if}{$files[files].title}{if $files[files].link != ''}</a></li>{/if}
	{/section}
	</ul>
  </div>
{/foreach}
