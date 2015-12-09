<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	/* Definir Doctype */
	protected function _initDoctype()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_STRICT');
    }
	
	/* Inicializar conexão com o banco de dados */
	protected function _initDbConnection()
	{
		try
		{
			$this->bootstrap('db');
			$resourceDb = $this->getResource('db');
	 
			Zend_Db_Table_Abstract::setDefaultAdapter($resourceDb);
			Zend_Registry::set('db', $resourceDb);
		} catch(Zend_Db_Exception $e)
		{
			die("Não foi possível realizar a conexão com o banco de dados. \n Erro: " . $e->getMessage());
	    }
	}
	
	/* Reportar possíveis erros */
	protected function _initShowErrors()
	{
		error_reporting(E_ALL - E_DEPRECATED);
		ini_set('display_errors', true);
	}

}

