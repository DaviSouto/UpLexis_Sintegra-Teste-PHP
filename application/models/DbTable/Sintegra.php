<?php

/*****************************************************************************************************************
 * Davi Volpato Domingues Souto
 * 09/12/2015
 * 
 * Model da tabela Sintegra responsável por salvar os dados requisitados pela aplicação no site do Sintegra.
 * 
 ****************************************************************************************************************/

 class Application_Model_DbTable_Sintegra extends Zend_Db_Table_Abstract
{
	protected $_name = "Sintegra";
	protected $_primary = "id";
	
	/*
	 * Verifica se já existe um registro com esse CNPJ no banco de dados
	 * $cnpj : string    Número do CNPJ a ser verificado
	 */
	private function registerExists($cnpj)
	{
		try
		{
			if (! empty($cnpj))
			{
				$select = $this->select();
				$select = $select->from($this, 'cnpj')->where('cnpj = ?', $cnpj);
			
				$rows = $this->fetchAll($select)->toArray();
				return (boolean) (count($rows) > 0);
			}
			else return false;	
		} catch(Zend_Db_Exception $e)
		{
			die ("Erro: " . $e->getMessage());
		}
	}
	
	/*
	 * Retorna o id do registro buscando pelo CNPJ
	 * $cnpj : string    Número do CNPJ
	 */
	private function getIdByCnpj($cnpj)
	{
		try
		{
			if (! empty($cnpj))
			{
				$select = $this->select();
				$select = $select->from($this, 'id')->where('cnpj = ?', $cnpj);
			
				$line = $this->fetchRow($select)->toArray();
	
				if (count($line) > 0) return $line['id'];
				else                  return "";
			}
			else return false;	
		} catch(Zend_Db_Exception $e)
		{
			die ("Erro: " . $e->getMessage());
		}		
	}
	
	/*
	 * Função que cria ou atualiza um registro
	 * $cnpj : string    Número do CNPJ
	 * $json : boolean   Definir true se os dados estiverem em JSON
	 */
	public function Register($data, $json = false)
	{
		if ($json) $data = json_decode($data, true);
		
		if (is_array($data))
		{
			try
			{
				$data['dtaInicioAtividade'] = $this->DateToSql($data['dtaInicioAtividade']);
				$data['dtaSitCadastral'] = $this->DateToSql($data['dtaSitCadastral']);
				$data['dtaEmitenteDesde'] = $this->DateToSql($data['dtaEmitenteDesde']);
				
				if (! $this->registerExists($data['cnpj'])) 
				{
					$insert = $this->insert($data);
				}
				else
				{
					$where = $this->getAdapter()->quoteInto('cnpj = ?', $data['cnpj']);
					$update = $this->update($data, $where);
				}
			} catch (Zend_Db_Exception $e)
			{
				die ("Erro ao criar/atualizar registro. Erro: " . $e->getMessage());
			}
		} else return false;
	}
	
	public function selectFromCnpj($cnpj)
	{
		try
		{
			if (! empty($cnpj))
			{
				$select = $this->select();
				$select = $select->from($this, '*')->where('cnpj = ?', $cnpj);
			
				$line = $this->fetchRow($select)->toArray();

				if (count($line) > 0) return $line;
				else                  return "";
			}
			else return false;	
		} catch(Zend_Db_Exception $e)
		{
			die ("Erro: " . $e->getMessage());
		}			
	}
	
	/*
	 * Método para converter a data ao formato SQL
	 * $date : string        Data a ser convertida
	 */
	private function DateToSql($date)
	{
		if (! empty(trim($date)))
		{
			$dateParts = explode('/', $date);
			return ($dateParts[2] . '-' . $dateParts[1] . '-' . $dateParts[0]);
		} else return $date;	
	}	
}

