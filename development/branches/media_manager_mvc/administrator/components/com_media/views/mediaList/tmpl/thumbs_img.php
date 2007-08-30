<?php defined('_JEXEC') or die('Restricted access'); ?>
		<div class="imgOutline">
			<div class="imgTotal">
				<div align="center" class="imgBorder">
					<a class="img-preview" href="<?php echo $this->baseURL.'images/'.$this->_tmp_img->path_relative; ?>" title="<?php echo $this->_tmp_img->name; ?>" style="display: block; width: 100%; height: 100%">
						<div class="image">
							<img src="<?php echo $this->baseURL.'images/'.$this->_tmp_img->path_relative; ?>" width="<?php echo $this->_tmp_img->width_80; ?>" height="<?php echo $this->_tmp_img->height_80; ?>" alt="<?php echo $this->_tmp_img->name; ?> - <?php echo MediaHelper::parseSize($this->_tmp_img->size); ?>" border="0" />
						</div>
					</a>
				</div>
			</div>
			<div class="controls">
				<img src="components/com_media/images/remove.png" width="16" height="16" border="0" alt="<?php echo JText::_( 'Delete' ); ?>" onclick="confirmDeleteImage('<?php echo $this->_tmp_img->name; ?>');" />
				<input type="checkbox" name="rm[]" value="<?php echo $this->_tmp_img->name; ?>" />
			</div>
			<div class="imginfoBorder">
				<a href="<?php echo $this->baseURL.'images/'.$this->_tmp_img->path_relative; ?>" class="preview"><?php echo htmlspecialchars( substr( $this->_tmp_img->name, 0, 10 ) . ( strlen( $this->_tmp_img->name ) > 10 ? '...' : ''), ENT_QUOTES ); ?></a>
			</div>
		</div>
