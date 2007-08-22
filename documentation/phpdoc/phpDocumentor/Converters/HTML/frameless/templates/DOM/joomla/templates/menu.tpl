webFXTreeConfig.rootIcon        = '{$subdir}media/images/empty.png';
webFXTreeConfig.openRootIcon    = '{$subdir}media/images/empty.png';
webFXTreeConfig.folderIcon      = '{$subdir}media/images/empty.png';
webFXTreeConfig.openFolderIcon  = '{$subdir}media/images/empty.png';
webFXTreeConfig.fileIcon        = '{$subdir}media/images/empty.png';
webFXTreeConfig.iIcon           = '{$subdir}media/images/I.png';
webFXTreeConfig.lIcon           = '{$subdir}media/images/L.png';
webFXTreeConfig.lMinusIcon      = '{$subdir}media/images/Lminus.png';
webFXTreeConfig.lPlusIcon       = '{$subdir}media/images/Lplus.png';
webFXTreeConfig.tIcon           = '{$subdir}media/images/T.png';
webFXTreeConfig.tMinusIcon      = '{$subdir}media/images/Tminus.png';
webFXTreeConfig.tPlusIcon       = '{$subdir}media/images/Tplus.png';
webFXTreeConfig.blankIcon       = '{$subdir}media/images/blank.png';

if (document.getElementById) {ldelim}
{section name=p loop=$info}
        {if $info[p].subpackage == ""}
                var tree = new WebFXTree('<span class="package">{$info.0.package}</span>');
                tree.setBehavior('classic');
                tree.openIcon = '{$subdir}media/images/package_folder.png';
                tree.icon = '{$subdir}media/images/package_folder.png';
		tree.targetWindow = '';

                var class_trees = new WebFXTreeItem('Class trees', '{$subdir}{$classtreepage}.html');
                class_trees.openIcon = '{$subdir}media/images/Index.png';
                class_trees.icon = '{$subdir}media/images/Index.png';
		class_trees.targetWindow = '';
                tree.add(class_trees);

                var elements = new WebFXTreeItem('Element index', '{$subdir}{$elementindex}.html');
                elements.openIcon = '{$subdir}media/images/Index.png';
                elements.icon = '{$subdir}media/images/Index.png';
		elements.targetWindow = '';
                tree.add(elements);

                var parent_node;

		{if $info[p].tutorials}
                        var tree_tutorial = new WebFXTreeItem('Tutorials/Manuals', '');
                        tree_tutorial.openIcon = '{$subdir}media/images/tutorial_folder.png';
                        tree_tutorial.icon = '{$subdir}media/images/tutorial_folder.png';
			tree_tutorial.targetWindow = '';
                        tree.add(tree_tutorial);

                        {if $info[p].tutorials.pkg}
                                var tree_inner_tutorial = new WebFXTreeItem('Package-level', '');
                                tree_inner_tutorial.openIcon = '{$subdir}media/images/package_folder.png';
                                tree_inner_tutorial.icon = '{$subdir}media/images/package_folder.png';
				tree_inner_tutorial.targetWindow = '';
                                tree_tutorial.add(tree_inner_tutorial);

                                parent_node = tree_inner_tutorial;
                                {section name=ext loop=$info[p].tutorials.pkg}
                                        {$info[p].tutorials.pkg[ext]}
                                {/section}
                        {/if}

                        {if $info[p].tutorials.cls}
                                var tree_inner_tutorial = new WebFXTreeItem('Class-level', '');
                                tree_inner_tutorial.openIcon = '{$subdir}media/images/class_folder.png';
                                tree_inner_tutorial.icon = '{$subdir}media/images/class_folder.png';
				tree_inner_tutorial.targetWindow = '';
                                tree_tutorial.add(tree_inner_tutorial);

                                parent_node = tree_inner_tutorial;
                                {section name=ext loop=$info[p].tutorials.cls}
                                        {$info[p].tutorials.cls[ext]}
                                {/section}
                        {/if}

                        {if $info[p].tutorials.proc}
                                var tree_inner_tutorial = new WebFXTreeItem('Function-level', '');
                                tree_inner_tutorial.openIcon = '{$subdir}media/images/function_folder.png';
                                tree_inner_tutorial.icon = '{$subdir}media/images/function_folder.png';
				tree_inner_tutorial.targetWindow = '';
                                tree_tutorial.add(tree_inner_tutorial);

                                parent_node = tree_inner_tutorial;
                                {section name=ext loop=$info[p].tutorials.proc}
                                        {$info[p].tutorials.proc[ext]}
                                {/section}
                        {/if}
                {/if}

                {if $info[p].classes}
                        var tree_classe = new WebFXTreeItem('Classes', '{$packagedoc}');
                        tree_classe.openIcon = '{$subdir}media/images/class_folder.png';
                        tree_classe.icon = '{$subdir}media/images/class_folder.png';
			tree_classe.targetWindow = '';

                        {section name=class loop=$info[p].classes}
                                var classe = new WebFXTreeItem('{$info[p].classes[class].title}', '{$info[p].classes[class].link}');
                                classe.openIcon = '{$subdir}media/images/{if $info[p].classes[class].abstract}Abstract{/if}{if $info[p].classes[class].access == 'private'}Private{/if}Class.png';
                                classe.icon = '{$subdir}media/images/{if $info[p].classes[class].abstract}Abstract{/if}{if $info[p].classes[class].access == 'private'}Private{/if}Class.png';
				classe.targetWindow = '';
                                tree_classe.add(classe);
                        {/section}

                        tree.add(tree_classe);
                {/if}

                {if $info[p].functions}
                        var tree_function = new WebFXTreeItem('Functions', '{$packagedoc}');
                        tree_function.openIcon = '{$subdir}media/images/function_folder.png';
                        tree_function.icon = '{$subdir}media/images/function_folder.png';
			tree_function.targetWindow = '';

                        {section name=nonclass loop=$info[p].functions}
                                var fic = new WebFXTreeItem('{$info[p].functions[nonclass].title}', '{$info[p].functions[nonclass].link}');
                                fic.openIcon = '{$subdir}media/images/Function.png';
                                fic.icon = '{$subdir}media/images/Function.png';
				fic.targetWindow = '';
                                tree_function.add(fic);
                        {/section}

                        tree.add(tree_function);
                {/if}

                {if $info[p].files}
                        var tree_file = new WebFXTreeItem('Files', '{$packagedoc}');
                        tree_file.openIcon = '{$subdir}media/images/folder.png';
                        tree_file.icon = '{$subdir}media/images/folder.png';
			tree_file.targetWindow = '';

                        {section name=nonclass loop=$info[p].files}
                                var file = new WebFXTreeItem('{$info[p].files[nonclass].title}', '{$info[p].files[nonclass].link}');
                                file.openIcon = '{$subdir}media/images/Page.png';
                                file.icon = '{$subdir}media/images/Page.png';
				file.targetWindow = '';
                                tree_file.add(file);
                        {/section}

                        tree.add(tree_file);
                {/if}

        {else}
                {if $info[p].subpackagetutorial}
                        var subpackagetree = new WebFXTreeItem('{$info[p].subpackagetutorialtitle|strip_tags}', '{$info[p].subpackagetutorialnoa}');
                {else}
                        var subpackagetree = new WebFXTreeItem('{$info[p].subpackage}', '{$packagedoc}');
                {/if}

                subpackagetree.openIcon = '{$subdir}media/images/package.png';
                subpackagetree.icon = '{$subdir}media/images/package.png';
		subpackagetree.targetWindow = '';

                {if $info[p].tutorials}
                        var tree_tutorial = new WebFXTreeItem('Tutorials/Manuals', '');
                        tree_tutorial.openIcon = '{$subdir}media/images/tutorial_folder.png';
                        tree_tutorial.icon = '{$subdir}media/images/tutorial_folder.png';
			tree_tutorial.targetWindow = '';
                        tree.add(tree_tutorial);

                        {if $info[p].tutorials.pkg}
                                var tree_inner_tutorial = new WebFXTreeItem('Package-level', '');
                                tree_inner_tutorial.openIcon = '{$subdir}media/images/package_folder.png';
                                tree_inner_tutorial.icon = '{$subdir}media/images/package_folder.png';
				tree_inner_tutorial.targetWindow = '';
                                tree_tutorial.add(tree_inner_tutorial);

                                parent_node = tree_inner_tutorial;
                                {section name=ext loop=$info[p].tutorials.pkg}
                                        {$info[p].tutorials.pkg[ext]}
                                {/section}
                        {/if}

                        {if $info[p].tutorials.cls}
                                var tree_inner_tutorial = new WebFXTreeItem('Class-level', '');
                                tree_inner_tutorial.openIcon = '{$subdir}media/images/class_folder.png';
                                tree_inner_tutorial.icon = '{$subdir}media/images/class_folder.png';
				tree_inner_tutorial.targetWindow = '';
                                tree_tutorial.add(tree_inner_tutorial);

                                parent_node = tree_inner_tutorial;
                                {section name=ext loop=$info[p].tutorials.cls}
                                        {$info[p].tutorials.cls[ext]}
                                {/section}
                        {/if}

                        {if $info[p].tutorials.proc}
                                var tree_inner_tutorial = new WebFXTreeItem('Function-level', '');
                                tree_inner_tutorial.openIcon = '{$subdir}media/images/function_folder.png';
                                tree_inner_tutorial.icon = '{$subdir}media/images/function_folder.png';
				tree_inner_tutorial.targetWindow = '';
                                tree_tutorial.add(tree_inner_tutorial);

                                parent_node = tree_inner_tutorial;
                                {section name=ext loop=$info[p].tutorials.proc}
                                        {$info[p].tutorials.proc[ext]}
                                {/section}
                        {/if}
                {/if}

                {if $info[p].classes}
                        var subpackagetree_classe = new WebFXTreeItem('Classes', '{$packagedoc}');
                        subpackagetree_classe.openIcon = '{$subdir}media/images/class_folder.png';
                        subpackagetree_classe.icon = '{$subdir}media/images/class_folder.png';
			subpackagetree_classe.targetWindow = '';

                        {section name=class loop=$info[p].classes}
                                var classe = new WebFXTreeItem('{$info[p].classes[class].title}', '{$info[p].classes[class].link}');
                                classe.openIcon = '{$subdir}media/images/{if $info[p].classes[class].abstract}Abstract{/if}{if $info[p].classes[class].access == 'private'}Private{/if}Class.png';
                                classe.icon = '{$subdir}media/images/{if $info[p].classes[class].abstract}Abstract{/if}{if $info[p].classes[class].access == 'private'}Private{/if}Class.png';
				classe.targetWindow = '';
                                subpackagetree_classe.add(classe);
                        {/section}

                        subpackagetree.add(subpackagetree_classe);
                {/if}

                {if $info[p].functions}
                        var subpackagetree_function = new WebFXTreeItem('Functions', '{$packagedoc}');
                        subpackagetree_function.openIcon = '{$subdir}media/images/function_folder.png';
                        subpackagetree_function.icon = '{$subdir}media/images/function_folder.png';
			subpackagetree_function.targetWindow = '';

                        {section name=nonclass loop=$info[p].functions}
                                var fic = new WebFXTreeItem('{$info[p].functions[nonclass].title}', '{$info[p].functions[nonclass].link}');
                                fic.openIcon = '{$subdir}media/images/Function.png';
                                fic.icon = '{$subdir}media/images/Function.png';
				fic.targetWindow = '';
                                subpackagetree_function.add(fic);
                        {/section}

                        subpackagetree.add(subpackagetree_function);
                {/if}

                {if $info[p].files}
                        var subpackagetree_file = new WebFXTreeItem('Files', '{$packagedoc}');
                        subpackagetree_file.openIcon = '{$subdir}media/images/folder.png';
                        subpackagetree_file.icon = '{$subdir}media/images/folder.png';
			subpackagetree_file.targetWindow = '';

                        {section name=nonclass loop=$info[p].files}
                                var file = new WebFXTreeItem('{$info[p].files[nonclass].title}', '{$info[p].files[nonclass].link}');
                                file.openIcon = '{$subdir}media/images/Page.png';
                                file.icon = '{$subdir}media/images/Page.png';
				file.targetWindow = '';
                                subpackagetree_file.add(file);
                        {/section}

                        subpackagetree.add(subpackagetree_file);
                {/if}

          tree.add(subpackagetree);
        {/if}
{/section}

document.write(tree);
{rdelim}

