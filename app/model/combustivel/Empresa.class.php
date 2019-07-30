<?php
/**
 * Empresa Active Record
 * @author  <Angelo Reis>
 */
class Empresa extends TRecord
{
    const TABLENAME = 'empresa';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('razao_social');
        parent::addAttribute('fantasia');
        parent::addAttribute('cnpj');
        parent::addAttribute('endereco_completo');
        parent::addAttribute('cep');
        parent::addAttribute('bairro');
        parent::addAttribute('cidade');
        parent::addAttribute('uf');
        parent::addAttribute('complemento');
        parent::addAttribute('email');
    }
    
    public static function replaceCnpj($CNPJ) : String
    {
         $cnpj_temp = $CNPJ;
         $retCNPJ = substr($cnpj_temp,0,2).'.'.substr($cnpj_temp,2,3).'.'.substr($cnpj_temp,3,3).'/'.substr($cnpj_temp,8,4).'-'.substr($cnpj_temp,12,2);
         return $retCNPJ;   
    }
    
    public static function removeCnpj($CNPJ) : String
    {
         $cnpj_temp = $CNPJ;
         $cnpj_temp = str_replace('-','',str_replace('/','',str_replace('.','',$CNPJ)));
         return $cnpj_temp;   
    }
    
}
