====== {$class_name} ======
^ API ^ Package ^ phpDocumentor ^ Last reviewed ^ Doc status ^
| [[|##http://api.joomla.org/media/images/package.png qq}]] [[references:{$package}|{$package}]] | [[|##http://api.joomla.org/media/images/package.png qq}]] [[|{$subpackage}]] | [[http://api.joomla.org/{$package}/{$subpackage}/{$class_name}.html|{$class_name}]] | 28 Apr 2006 | Work in progress |

{if $sdesc}{$sdesc}{/if}
{if $desc}{$desc}{/if}

===== Methods =====
^ Method ^ Description ^
{section name=methods loop=$methods}
| [[{$class_name}-{$methods[methods].function_name}|##http://api.joomla.org/media/images/Method.png qq}]] [[{$class_name}-{$methods[methods].function_name}|{$methods[methods].function_name}]] | {if $methods[methods].sdesc}{$methods[methods].sdesc}{/if}
{if $methods[methods].desc}{$methods[methods].desc}{/if} |
{/section}
----

~~DISCUSSION~~



{section name=methods loop=$methods}
====== {$methods[methods].function_name} ======
^ API ^ Package ^ Class ^ phpDocumentor ^ Last reviewed ^ Doc status ^
| [[references:Joomla.Framework|##http://api.joomla.org/media/images/package.png qq]] [[references:{$package}|{$package}]] | [[|##http://api.joomla.org/media/images/package.png qq]] [[|{$subpackage}]] | [[{$class_name}|##http://api.joomla.org/media/images/Class.png qq]][[{$class_name}|{$class_name}]] | [[http://api.joomla.org/{$package}/{$subpackage}/{$class_name}.html|{$class_name}->{$method[methods].function_name}]] | 28 Apr 2006 | Work in progress |
{if $methods[methods].sdesc}{$methods[methods].sdesc}{/if}
{if $methods[methods].desc}{$methods[methods].desc}{/if}

===== Syntax =====
{$methods[methods].function_return} {if $methods[methods].ifunction_call.returnsref}&{/if}{$methods[methods].function_name}{if count($methods[methods].ifunction_call.params)}	( {section name=params loop=$methods[methods].ifunction_call.params}{if $smarty.section.params.iteration != 1}, {/if}**{$methods[methods].ifunction_call.params[params].name}**{/section} ){else} (){/if}
{section name=params loop=$methods[methods].params}| **{$methods[methods].params[params].var}** | {$methods[methods].params[params].datatype} | {$methods[methods].params[params].data} |
{/section}

===== Examples =====
<code php|Example>

</code>


----

~~DISCUSSION~~
{/section}

