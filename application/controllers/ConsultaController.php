<?php

/*****************************************************************************************************************
 * Davi Volpato Domingues Souto
 * 09/12/2015
 * 
 * Controller responsável fazer a autenticação ao web-service e exibir as informações
 * 
 ****************************************************************************************************************/

class ConsultaController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

	/*
	 * Action que chama o web service e exibe na tela os dados encontrados
	 */
    public function indexAction()
    {
    	$this->_helper->viewRenderer->setNoRender(false);
	  	$this->_helper->layout()->enableLayout(); 
	  
		if ($this->getRequest()->isPost())
		{
			if      ($this->_getParam('num_cnpj') == '') Zend_Layout::getMvcInstance()->assign('msg_error', 'Campo CNPJ é obrigatório !');
			else if (strlen($this->_getParam('num_cnpj')) < 18) Zend_Layout::getMvcInstance()->assign('msg_error', 'CNPJ está incorreto !');
			else 
			{
				$num_cnpj = $this->_getParam('num_cnpj', '');
				$this->view->assign('num_cnpj', $num_cnpj);
				
				// Incluir arquivo de cliente web service
				require_once('Zend/Rest/Client.php');
				
				// Criar classe da conexão com o web-service
				$clientRest = new Zend_Rest_Client('http://' . $_SERVER['HTTP_HOST'] . '/Consulta/sintegra');
				
				// Fazer requisição do registro
				$result = $clientRest->ConsultarRegistro($num_cnpj, $this->generateAuthKey())->get();			
				$result = json_decode($result);
				
				if (count($result) <= 0) Zend_Layout::getMvcInstance()->assign('msg_error', 'Não foi encontrado nenhum registro para o CNPJ ' . $num_cnpj);
				else                   
				{
					$result = get_object_vars($result[0]);
					
					Zend_Layout::getMvcInstance()->assign('msg_success', 'Exibindo dados do CNPJ ' . $num_cnpj);
					$this->view->assign('result', $result);
				}
			}
		}
    }
	
	/*
	 * Action do web service que retorna o registro buscado
	 */
	public function sintegraAction()
	{
		// Desabilitar renderização do layout e da view
		$this->_helper->viewRenderer->setNoRender(true);
	  	$this->_helper->layout()->disableLayout(); 
      
      	// Iniciar servidor web service
      	$restSrv = new Zend_Rest_Server();
	  	$restSrv->setClass('AutoLoad_RestServ');
      	$restSrv->handle();
	}
	
	/****************************************************************************************************************/
	
	/*
	 * Método de autenticação simples que gera uma chave com algumas palavras, a data e a hora atual e criptografa.
	 */
	private function generateAuthKey()
	{
		$code = 'L0rem_' . date('d/m/Y-H') . '_1psuM';
		$code_sha1 = sha1($code);	
		
		return $code_sha1 . '_key';		
	}	
}

