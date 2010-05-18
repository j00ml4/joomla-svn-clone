<?php
/**
 * Translate Model for YQL
 * 
 * @author Julio Pontes
 * @package Google Knowledge Translate
 * @version 1.6
 */
class GoogleTranslateModelTranslate extends JKnowledgeModel
{
	protected $_fields = ' * ';
	protected $_table = 'google.translate';
	protected $_pk = 'q';
	
}