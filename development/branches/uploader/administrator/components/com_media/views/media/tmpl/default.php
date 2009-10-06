
<?php defined('_JEXEC') or die; ?>
<table width="100%" cellspacing="0">
    <tr valign="top">
        <td width="200px">
            <fieldset id="treeview">
                <legend><?php echo JText::_('Folders'); ?></legend>
                <div id="media-tree_tree"></div>
                <?php echo $this->loadTemplate('folders'); ?>
            </fieldset>
        </td>
        <td>
            <?php if ($this->require_ftp): ?>
            <form action="index.php?option=com_media&amp;task=ftpValidate" name="ftpForm" id="ftpForm" method="post">
                <fieldset title="<?php echo JText::_('DESCFTPTITLE'); ?>">
                    <legend><?php echo JText::_('DESCFTPTITLE'); ?></legend>
                    <?php echo JText::_('DESCFTP'); ?>
                    <table class="adminform nospace">
                        <tbody>
                            <tr>
                                <td width="120">
                                    <label for="username"><?php echo JText::_('Username'); ?>:</label>
                                </td>
                                <td>
                                    <input type="text" id="username" name="username" class="input_box" size="70" value="" />
                                </td>
                            </tr>
                            <tr>
                                <td width="120">
                                    <label for="password"><?php echo JText::_('Password'); ?>:</label>
                                </td>
                                <td>
                                    <input type="password" id="password" name="password" class="input_box" size="70" value="" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>
            </form>
            <?php endif; ?>

            <form action="index.php?option=com_media" name="adminForm" id="mediamanager-form" method="post" enctype="multipart/form-data" >
                <input type="hidden" name="task" value="" />
                <input type="hidden" name="cb1" id="cb1" value="0" />
                <input class="update-folder" type="hidden" name="folder" id="folder" value="<?php echo $this->state->folder; ?>" />
            </form>

 			 <form action="index.php?option=com_media&amp;task=folder.create" name="folderForm" id="folderForm" method="post">
                <fieldset id="folderview">
                <div class="view">
                        <iframe src="index.php?option=com_media&amp;view=mediaList&amp;tmpl=component&amp;folder=<?php echo $this->state->folder;?>" id="folderframe" name="folderframe" width="100%" marginwidth="0" marginheight="0" scrolling="auto" frameborder="0"></iframe>
                    </div>
                    <legend><?php echo JText::_('Files'); ?></legend>
                    <div class="path">
                        <input class="inputbox" type="text" id="folderpath" readonly="readonly" />/
                        <input class="inputbox" type="text" id="foldername" name="foldername"  />
                        <input class="update-folder" type="hidden" name="folderbase" id="folderbase" value="<?php echo $this->state->folder; ?>" />
                        <button type="submit"><?php echo JText::_('Create Folder'); ?></button>
                    </div>

                </fieldset>
				<?php echo JHtml::_('form.token'); ?>
			</form>

            <!-- File Upload Form -->
            <form action="<?php echo JURI::base(); ?>index.php?option=com_media&amp;task=file.upload&amp;tmpl=component&amp;<?php echo $this->session->getName().'='.$this->session->getId(); ?>&amp;<?php echo JUtility::getToken();?>=1" id="uploadForm" method="post" enctype="multipart/form-data">
                <fieldset>
                    <legend><?php echo JText::_('UPLOAD_FILE'); ?> (<?php echo JText::_('MAXIMUM_SIZE'); ?>:&nbsp;<?php echo ($this->config->get('upload_maxsize') / 1000000); ?>MB)</legend>
                    <fieldset id="uploader-noflash" class="actions">
                        <input type="file" id="file-upload" name="Filedata" />
                        <input type="submit" id="file-upload-submit" value="<?php echo JText::_('START_UPLOAD'); ?>"/>
                        <span id="upload-clear"></span>
                    </fieldset>
                    <div id="uploader-flash" class="hide">
						<p>
							<a href="#" id="upload-browse">Browse Files</a> |
							<a href="#" id="upload-clear">Clear List</a> |
							<a href="#" id="action-upload">Start Upload</a>
						</p>
						<div>
							<strong class="overall-title"></strong><br />
							<img src="../../assets/progress-bar/bar.gif" class="progress overall-progress" />
						</div>
						<div>
							<strong class="current-title"></strong><br />
							<img src="../../assets/progress-bar/bar.gif" class="progress current-progress" />
						</div>
						<div class="current-text"></div>
					</div>
                    <ul class="upload-queue" id="upload-queue">
                        <li style="display: none" />
                    </ul>
                </fieldset>
                <input type="hidden" name="return-url" value="<?php echo base64_encode('index.php?option=com_media'); ?>" />
            </form>
        </td>
    </tr>
</table>
