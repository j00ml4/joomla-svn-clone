====== {$class_name} [WIP] ======

{if $sdesc}{$sdesc}{/if}
{if $desc}{$desc}{/if}
{if $methods || $imethods}


===== Methods =====

{section name=methods loop=$methods}
==== {$methods[methods].function_name} ====
{if $methods[methods].sdesc}{$methods[methods].sdesc}{/if}
{if $methods[methods].desc}{$methods[methods].desc}{/if}

=== Syntax ===
{$methods[methods].function_return} {if $methods[methods].ifunction_call.returnsref}&{/if}{$methods[methods].function_name}{if count($methods[methods].ifunction_call.params)}	( {section name=params loop=$methods[methods].ifunction_call.params}{if $smarty.section.params.iteration != 1}, {/if}{if $methods[methods].ifunction_call.params[params].default}[{/if}{$methods[methods].ifunction_call.params[params].type} {$methods[methods].ifunction_call.params[params].name}{if $methods[methods].ifunction_call.params[params].default} = {$methods[methods].ifunction_call.params[params].default}]{/if}{/section} ){else} (){/if}


{section name=params loop=$methods[methods].params}
{$methods[methods].params[params].var}
{if $methods[methods].params[params].data}  * {$methods[methods].params[params].data}{/if}

{/section}

=== Examples ===

{/section}{/if}
