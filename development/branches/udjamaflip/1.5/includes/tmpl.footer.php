
    </div>
        
    <div class="dock" id="dock">
        <div class="dock-container">
            <a class="dock-item" href="index.php?option=com_content" title="Article Manager" rel="950-550-10-10">
                <span>Article Manager</span><img src="templates/<?php echo  $this->template ?>/images/icons/Notes.png" alt="Article Manager" />
            </a>
			<a class="dock-item" href="index.php?option=com_menus" title="Menu Manager" rel="950-500-10-10">
                <span>Menu Manager</span><img src="templates/<?php echo  $this->template ?>/images/icons/StatusStyle.png" alt="Menu Manager" />
            </a>
			<a class="dock-item" href="index.php?option=com_massmail" title="Mass Mail" rel="950-500-10-10">
                <span>Mass Mail</span><img src="templates/<?php echo  $this->template ?>/images/icons/Mail.png" alt="Mass Mail" />
            </a>
			<a class="dock-item" href="index.php?option=com_media" title="Media Manager" rel="950-500-10-10">
                <span>Media Manager</span><img src="templates/<?php echo  $this->template ?>/images/icons/Categories.png" alt="Media Manager" />
            </a>
			<a class="dock-item" href="index.php?option=com_users&amp;task=view" title="User Manager" rel="950-500-10-10">
                <span>User Manager</span><img src="templates/<?php echo  $this->template ?>/images/icons/Contacts.png" alt="User Manager" />
            </a>
            <a class="dock-item" href="index.php?option=com_config" title="Global Configuration" rel="950-500-10-10">
                <span>Global Configuration</span><img src="templates/<?php echo  $this->template ?>/images/icons/SMBPrefs2.png" alt="Global Configuration" />
            </a>
            <a class="dock-item" href="index.php?option=com_modules" title="Module Manager" rel="950-500-10-10">
                <span>Module Manager</span><img src="templates/<?php echo  $this->template ?>/images/icons/iDigger.png" alt="Module Manager" />
            </a>

			<?php //THIS SPITS OUT ANY CUSTOM ADDED ITEMS
			$i = 1;
			while ($i <= 5)
			{
				if ($itemTitle = $this->params->get('customDockItem'.$i.'-title')
					&& $itemUrl = $this->params->get('customDockItem'.$i.'-url')
					&& $itemImage = $this->params->get('customDockItem'.$i.'-image'))
				{
					echo '<a class="dock-item" href="index.php?'.$this->params->get('customDockItem'.$i.'-url').'" title="'.$this->params->get('customDockItem'.$i.'-title').'" rel="950-500-10-10">
		                <span>'.$this->params->get('customDockItem'.$i.'-title').'</span><img src="templates/'.$this->template.'/images/icons/'.$this->params->get('customDockItem'.$i.'-image').'" alt="'.$this->params->get('customDockItem'.$i.'-title').'" />
		            </a>';
				}
				$i++;	
			}
			?>
            <div id="dynamic-icons"></div>
        </div> 
    </div>
    <jdoc:include type="modules" name="debug" />