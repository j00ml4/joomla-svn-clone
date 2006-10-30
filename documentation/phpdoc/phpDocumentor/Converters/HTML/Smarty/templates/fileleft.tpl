{foreach key=subpackage item=files from=$fileleftindex}
	{if $subpackage != ""}subpackage <b>{$subpackage}</b><br>{/if}
  <div class="package">
	<ul>
	{section name=files loop=$files}
		<li>
		{if $files[files].link != ''}<a href="{$files[files].link}">{/if}
		{$files[files].title}
		{if $files[files].link != ''}</a>{/if}
		</li>
	{/section}
  </div><br />
{/foreach}
