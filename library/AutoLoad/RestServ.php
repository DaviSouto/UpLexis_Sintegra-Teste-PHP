<?php
/*****************************************************************************************************************
 * Davi Volpato Domingues Souto
 * 09/12/2015
 * 
 * Classe com a função do servidor REST para consulta via web-service
 * 
 ****************************************************************************************************************/
class AutoLoad_RestServ
{
	/*
	 * Método de autenticação simples que gera uma chave com algumas palavras, a data e a hora atual e criptografa.
	 */
	private function generateAuthKey()
	{
		$code = 'L0rem_' . date('d/m/Y-H') . '_1psuM';
		$code_sha1 = sha1($code);	
		
		return $code_sha1 . '_key';		
	}	
	
	/*
	 * Método público no web service usado para consultar o registro buscando pelo CNPJ
	 * $cnpj : string    Número do CNPJ
	 */
	function ConsultarRegistro($cnpj, $authKey)
	{		
		// Verificar CNPJ
		if (! empty($cnpj) && strlen($cnpj) == 18 && $authKey == $this->generateAuthKey())
		{
			$cnpj2 = '';
			$cnpj2 = preg_replace('/[-]/', '/', $cnpj, 1);
			
			// Evitar SQL Inject
			$cnpj = addslashes($cnpj);
			$cnpj2 = addslashes($cnpj2);
		
			// Fazer o select no banco
			$db = Zend_Registry::get('db');
			$sql = "SELECT * FROM sintegra WHERE cnpj like '$cnpj' OR cnpj like '$cnpj2'";
			
			// Retornar dados em json
			return json_encode($db->fetchAll($sql)); 
		}
	}
}
?>