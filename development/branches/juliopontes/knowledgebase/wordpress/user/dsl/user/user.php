<?php

/**
 * 
 * @author Julio Pontes
 * @display true
 * @namespace Catalog\Wordpress\
 */
class WordpressUserDslUser extends JKnowledgeDSL
{
	/**
     * create a instance of Model User
     *
     * @return Catalog_Model
     */
	protected function _factoryModel() {
        return JKnowledgeModel::getInstance('DSL_Model_User');
    }
    
    /**
     * 
     * @param $login
     * @return DSL_User
     */
    public function withLogin($login)
    {
    	$this->select()->where("{$this->_model->getTableName()}.user_login = '{$login}'");
 
        return $this;
    }
    
    /**
     * 
     * @param $email
     * @return DSL_User
     */
    public function withEmail($email)
    {
    	$this->select()->where("{$this->_model->getTableName()}.user_email = '{$email}'");
 
        return $this;
    }
    
    /**
     * 
     * @param $date
     * @return DSL_User
     */
    public function withRegistrationDate($date)
    {
    	$this->select()->where("{$this->_model->getTableName()}.user_registered = {$date}");
 
        return $this;
    }
    
    /**
     * 
     * @param $status
     * @return DSL_User
     */
    public function withStatus($status)
    {
    	$this->select()->where("{$this->_model->getTableName()}.user_status = {$status}");
 
        return $this;
    }
    
    /**
     * 
     * @param $name
     * @return DSL_User
     */
    public function withAliasName($name)
    {
    	$this->select()->where("{$this->_model->getTableName()}.display_name = '{$name}'");
 
        return $this;
    }
}