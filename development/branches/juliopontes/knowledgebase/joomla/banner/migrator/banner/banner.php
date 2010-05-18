<?php
class JoomlaBannerMigratorBanner extends JKnowledgeMigrator
{
	/**
	 * Generic convert proccess
	 * 
	 * register knowledge and try to find a method with same name to convert specific data.
	 * 
	 * @param JKnowledge $knowledge
	 * @param Array $params
	 */
	public function convert($knowledge,$params=null)
	{
		/**
		 * converting data from specific knowledge using a specialist class
		 * when convert data, can receive params arguments
		 * 
		 * @var JKnowledgeConversor Banner
		 */
		
		$conversorName = 'JoomlaBannerConversor'.ucfirst($knowledge->getName());
		$bannerConversor = JKnowledgeDataConversor::getInstance($conversorName);
		$this->_result = $bannerConversor->addKnowledge($knowledge)->convertData( $params );
		
		return $this;
	}
		
	/**
	 * Proccess to save on Banner table
	 */
	public function proccess()
	{
		if( !empty($this->_result) ){
			$bannerModel = JKnowledgeModel::getInstance('JoomlaBannerModelBanner');
			$bannerModel->setConnector( $this->_connect );
			return $bannerModel->save( $this->_result );
		}
		
		return false;
	}
}