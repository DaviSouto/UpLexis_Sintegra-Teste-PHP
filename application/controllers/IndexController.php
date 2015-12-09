<?php

/*****************************************************************************************************************
 * Davi Volpato Domingues Souto
 * 08/12/2015
 * 
 * Controller responsável por fazer a requisição ao Sintegra e salvar os dados no banco.
 * 
 ****************************************************************************************************************/

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

	/*
	 * Action responsável por buscar os dados no Sintgra e salvar no banco de dados MySQL
	 */
    public function indexAction()
    {
		if ($this->getRequest()->isPost())
		{
			if      ($this->_getParam('num_cnpj') == '') Zend_Layout::getMvcInstance()->assign('msg_error', 'Campo CNPJ é obrigatório !');
			else if (strlen($this->_getParam('num_cnpj')) < 18) Zend_Layout::getMvcInstance()->assign('msg_error', 'CNPJ está incorreto !');
			else 
			{			
			    $num_cnpj = $this->_getParam('num_cnpj', '');

				$this->view->assign('num_cnpj', $num_cnpj);				
				$this->view->assign('proxy_name', $this->_getParam('proxy_name', ''));				
				$this->view->assign('use_proxy', $this->_getParam('use_proxy', ''));
				
				// Parsear proxy
				$proxy = "";
				if ($this->_getParam('use_proxy') && $this->_getParam('proxy_name'))
				{
					$proxy_name = $this->_getParam('proxy_name');
		
					$proxy['HOST'] = substr($proxy_name, 0, strpos($proxy_name, ':')); // Host
					$proxy['PORT'] = substr($proxy_name, strpos($proxy_name, ':')+1, strlen($proxy_name)); // Porta
					
					if (empty(trim($proxy['HOST'])) || empty(trim($proxy['PORT']))) $proxy = '';
				}
			
				$result = $this->getSintegraHtmlData($num_cnpj, $proxy);				
				$data = $this->parseSintegraHtmlData($result);
		
				if (! empty($data))
				{			
					$this->view->assign('result', $data);
					
					$sintegraModel = new Application_Model_DbTable_Sintegra();  
					
					$register = $sintegraModel->Register($data, true);
					
					Zend_Layout::getMvcInstance()->assign('msg_success', 'Registro encontrado e salvo no banco de dados !');
				} else Zend_Layout::getMvcInstance()->assign('msg_error', 'Não foi encontrado nenhum registro com esse CNPJ.');
								
			}
		}
    }
	
	/*
	 * Método para fazer a requisição dos dados no Sintegra (Spider)
	 * $num_cnpj : string    Número do CNPJ a ser buscado
	 * $proxy    : array     Deve ser nulo ou conter 2 chaves: HOST com o host da proxy e PORT com a porta
	 * $url      : string    Url de destino da requisição [DEFAULT url do Sintegra ES]
	 */
	private function getSintegraHtmlData($num_cnpj, $proxy = '', $url = 'http://www.sintegra.es.gov.br/resultado.php')
	{
		try
		{
			// Configurações da conexão HTTP com a página
			$zendHttpConfig = array(
				'adapter'       => 'Zend_Http_Client_Adapter_Socket',
				'ssltransport'  => 'tls',
				'timeout'       => 15
			);
			
			if (is_array($proxy))
			{
				$zendHttpConfig['proxy_host'] = $proxy['HOST'];
				$zendHttpConfig['proxy_port'] = $proxy['PORT'];
			}
			
			// Criar o objeto que fara a requisição HTTP
			$zendHttpClient = new Zend_Http_Client($url);
			$zendHttpClient->setConfig($zendHttpConfig);
			
			// Definir os parâmetros POST enviados a página
			// Obs: O parâmetro "botao" é obrigatório
			$zendHttpClient->setParameterPost('num_cnpj', $num_cnpj);
			$zendHttpClient->setParameterPost('botao', 'Consultar');
			
			// Fazer requisição da página (método POST)
			$response = $zendHttpClient->request(Zend_Http_Client::POST);
			
			// Retornar o corpo da página
			if ($response->isError()) throw new Exception($response->getStatus());
			else return $response->getBody();	
		} catch (Exception $e) {
			$erro = $e->message;			
			die("Erro ao buscar informações no Sintegra. Erro: " . $erro);
		}
	}

	/*
	 * Método para parsear os dados recebidos em HTML para um array ou json
	 * $data : string        HTML da consulta no Sintegra
	 * $json : bool          Se true converte para json [DEFAULT true]
	 */
	private function parseSintegraHtmlData($data, $json = true)
	{
		// Expressão regular para apagar as tabelas comentadas
		$ereg_delete = '/<!--.*?-->/ms';
		$data = preg_replace($ereg_delete, '', $data);

		// Expressão regular
		// Buscar no html pelo conteúdo das colunas da tabela que tenham a classe valor
		
		//  <td class="valor"   --> Buscar pela coluna com a classe valor
		//  .*?                 --> Pode ou não ter mais atributos no html da coluna
		//  (.*)                --> Criar um índice no array de qualquer informação que estiver dentro da coluna
		//  /i                  --> Expressão case insensitive
		$ereg  = '/<td.*?class="valor".*?>(.*)<\/td>/i';
		
		// Chaves do resultado da tabela
		// Obs: Devem estar na mesma ordem do resultado da expresão
		$keys = array('cnpj', 'inscEstadual', 'razaoSocial', 
		'logradouro', 'numero', 'complemento', 'bairro', 'municipio', 'uf', 'cep', 'telefone',
		'ativEconomica', 'dtaInicioAtividade', 'sitCadastral', 'dtaSitCadastral', 'regApuracao', 'dtaEmitenteDesde');
		
		// Executar a expressão regular e converter em JSON
		if (preg_match_all($ereg, $data, $matches))
		{
			// Retirar o último elemento encontrado 
			array_pop($matches[1]); 

			// Combinar as chaves com o resultado
			$result = array_combine($keys, $matches[1]); 

			// Remover o &nbsp; dos valores
			$result = str_ireplace('&nbsp;', '', $result);
			
			// Converter em JSON
			if ($json) $result = json_encode($result);
			
		} else $result = '';
		
		return $result;	
	}
}

