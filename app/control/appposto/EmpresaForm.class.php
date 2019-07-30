<?php
/**
 * EmpresaForm Form
 * @author  Angelo Reis
 */
class EmpresaForm extends TPage
{
    protected $form; // form
    
    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_Empresa');
        $this->form->setFormTitle('Empresa');
        

        // create the form fields
        $id = new TEntry('id');
        $razao_social = new TEntry('razao_social');
        $fantasia = new TEntry('fantasia');
        $cnpj = new TEntry('cnpj');
        $endereco_completo = new TText('endereco_completo');
        $cep = new TEntry('cep');
        $bairro = new TEntry('bairro');
        $cidade = new TEntry('cidade');
        $uf = new TEntry('uf');
        $complemento = new TText('complemento');
        $email = new TText('email');


        // add the fields
        $this->form->addFields( [ new TLabel('Id') ], [ $id ] );
        $this->form->addFields( [ new TLabel('Razao Social') ], [ $razao_social ] );
        $this->form->addFields( [ new TLabel('Fantasia') ], [ $fantasia ] );
        $this->form->addFields( [ new TLabel('Cnpj') ], [ $cnpj ] );
        $this->form->addFields( [ new TLabel('Endereco Completo') ], [ $endereco_completo ] );
        $this->form->addFields( [ new TLabel('Cep') ], [ $cep ] );
        $this->form->addFields( [ new TLabel('Bairro') ], [ $bairro ] );
        $this->form->addFields( [ new TLabel('Cidade') ], [ $cidade ] );
        $this->form->addFields( [ new TLabel('Uf') ], [ $uf ] );
        $this->form->addFields( [ new TLabel('Complemento') ], [ $complemento ] );
        $this->form->addFields( [ new TLabel('Email') ], [ $email ] );

        $razao_social->addValidation('Razao Social', new TRequiredValidator);
        $cnpj->addValidation('Cnpj', new TRequiredValidator);


        // set sizes
        $id->setSize('100%');
        $razao_social->setSize('100%');
        $fantasia->setSize('100%');
        $cnpj->setSize('100%');
        $endereco_completo->setSize('100%');
        $cep->setSize('100%');
        $bairro->setSize('100%');
        $cidade->setSize('100%');
        $uf->setSize('100%');
        $complemento->setSize('100%');
        $email->setSize('100%');
        
        $cnpj->setMask('99.999.999/9999-99');



        if (!empty($id))
        {
            $id->setEditable(FALSE);
        }
        
        /** samples
         $fieldX->addValidation( 'Field X', new TRequiredValidator ); // add validation
         $fieldX->setSize( '100%' ); // set size
         **/
         
        // create the form actions
        $btn = $this->form->addAction(_t('Save'), new TAction([$this, 'onSave']), 'fa:floppy-o');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addAction(_t('New'),  new TAction([$this, 'onEdit']), 'fa:eraser red');
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 90%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        
        parent::add($container);
    }

    /**
     * Save form data
     * @param $param Request
     */
    public function onSave( $param )
    {
        try
        {
            TTransaction::open('meucombustivel'); // open a transaction
            
            /**
            // Enable Debug logger for SQL operations inside the transaction
            TTransaction::setLogger(new TLoggerSTD); // standard output
            TTransaction::setLogger(new TLoggerTXT('log.txt')); // file
            **/
            
            $this->form->validate(); // validate form data
            $data = $this->form->getData(); // get form data as array
            
            $object = new Empresa;  // create an empty object
            $object->fromArray( (array) $data); // load the object with data
            $object->cnpj = Empresa::removeCnpj($object->cnpj); 
            $object->store(); // save the object
            
            // get the generated id
            $data->id = $object->id;
            
            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction
            
            new TMessage('info', TAdiantiCoreTranslator::translate('Record saved'));
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    /**
     * Clear form data
     * @param $param Request
     */
    public function onClear( $param )
    {
        $this->form->clear(TRUE);
    }
    
    /**
     * Load object to form data
     * @param $param Request
     */
    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open('meucombustivel'); // open a transaction
                $object = new Empresa($key); // instantiates the Active Record

                $object->cnpj = Empresa::replaceCnpj($object->cnpj);
                
                $this->form->setData($object); // fill the form
                TTransaction::close(); // close the transaction
            }
            else
            {
                $this->form->clear(TRUE);
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
}