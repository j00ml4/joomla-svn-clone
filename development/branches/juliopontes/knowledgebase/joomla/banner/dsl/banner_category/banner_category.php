<?php
//include required knowledges
JKnowledgeDSL::requireKnowledge( 'JoomlaCategoryDslCategory' );

/**
 * Banner Category Knowledge
 * 
 * @author Julio Pontes
 * @package Joomla Knowledge Banner
 * @version 1.6
 */
class JoomlaBannerDslBanner_Category extends JoomlaCategoryDslCategory
{
	/**
	 * @return JoomlaBannerDslBanner
	 */
	public function andBanner()
	{
		return $this->_refrence('JoomlaBannerDslBanner');
	}
}