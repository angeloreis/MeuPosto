<?php
/**
 * Funcionario Active Record
 * @author  Angelo Reis
 */
class Funcionario extends TRecord
{
    const TABLENAME = 'funcionario';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    private $empresa;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome_completo');
        parent::addAttribute('data_nasc');
        parent::addAttribute('email');
        parent::addAttribute('cpf');
        parent::addAttribute('id_empresa');
    }

    
    /**
     * Method set_empresa
     * Sample of usage: $funcionario->empresa = $object;
     * @param $object Instance of Empresa
     */
    public function set_empresa(Empresa $object)
    {
        $this->empresa = $object;
        $this->empresa_id = $object->id;
    }
    
    /**
     * Method get_empresa
     * Sample of usage: $funcionario->empresa->attribute;
     * @returns Empresa instance
     */
    public function get_empresa()
    {
        // loads the associated object
        if (empty($this->empresa))
            $this->empresa = new Empresa($this->empresa_id);
    
        // returns the associated object
        return $this->empresa;
    }
    


}
