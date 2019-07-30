<?php
/**
 * EmpresaList Listing
 * @author  Angelo Reis
 */
class EmpresaList extends TPage
{
    protected $form;     // registration form
    protected $datagrid; // listing
    protected $pageNavigation;
    protected $formgrid;
    protected $deleteButton;
    
    use Adianti\base\AdiantiStandardListTrait;
    
    /**
     * Page constructor
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->setDatabase('meucombustivel');            // defines the database
        $this->setActiveRecord('Empresa');   // defines the active record
        $this->setDefaultOrder('id', 'asc');         // defines the default order
        // $this->setCriteria($criteria) // define a standard filter

        $this->addFilterField('id', '=', 'id'); // filterField, operator, formField
        $this->addFilterField('razao_social', 'like', 'razao_social'); // filterField, operator, formField
        $this->addFilterField('fantasia', 'like', 'fantasia'); // filterField, operator, formField
        $this->addFilterField('cnpj', 'like', 'cnpj'); // filterField, operator, formField
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_Empresa');
        $this->form->setFormTitle('Empresa');
        

        // create the form fields
        $id = new TEntry('id');
        $razao_social = new TEntry('razao_social');
        $fantasia = new TEntry('fantasia');
        $cnpj = new TEntry('cnpj');
       
        // add the fields
        $this->form->addFields( [ new TLabel('Id') ], [ $id ] );
        $this->form->addFields( [ new TLabel('Razao Social') ], [ $razao_social ] );
        $this->form->addFields( [ new TLabel('Fantasia') ], [ $fantasia ] );
        $this->form->addFields( [ new TLabel('Cnpj') ], [ $cnpj ] );
       

        // set sizes
        $id->setSize('100%');
        $razao_social->setSize('100%');
        $fantasia->setSize('100%');
        $cnpj->setSize('100%');
       
        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('Empresa_filter_data') );
        
        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction([$this, 'onSearch']), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink(_t('New'), new TAction(['EmpresaForm', 'onEdit']), 'fa:plus green');
        
        // creates a DataGrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        

        // creates the datagrid columns
        $column_id = new TDataGridColumn('id', 'Id', 'right');
        $column_razao_social = new TDataGridColumn('razao_social', 'Razao Social', 'left');
        $column_fantasia = new TDataGridColumn('fantasia', 'Fantasia', 'left');
        $column_cnpj = new TDataGridColumn('cnpj', 'Cnpj', 'left');
        $column_endereco_completo = new TDataGridColumn('endereco_completo', 'Endereco Completo', 'left');
        $column_cep = new TDataGridColumn('cep', 'Cep', 'left');
        $column_bairro = new TDataGridColumn('bairro', 'Bairro', 'left');
        $column_cidade = new TDataGridColumn('cidade', 'Cidade', 'left');
        $column_uf = new TDataGridColumn('uf', 'Uf', 'left');
        $column_complemento = new TDataGridColumn('complemento', 'Complemento', 'left');
        $column_email = new TDataGridColumn('email', 'Email', 'left');


        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_razao_social);
        $this->datagrid->addColumn($column_fantasia);
        $this->datagrid->addColumn($column_cnpj);
        $this->datagrid->addColumn($column_endereco_completo);
        $this->datagrid->addColumn($column_cep);
        $this->datagrid->addColumn($column_bairro);
        $this->datagrid->addColumn($column_cidade);
        $this->datagrid->addColumn($column_uf);
        $this->datagrid->addColumn($column_complemento);
        $this->datagrid->addColumn($column_email);

        
        // create EDIT action
        $action_edit = new TDataGridAction(['EmpresaForm', 'onEdit']);
        //$action_edit->setUseButton(TRUE);
        //$action_edit->setButtonClass('btn btn-default');
        $action_edit->setLabel(_t('Edit'));
        $action_edit->setImage('fa:pencil-square-o blue fa-lg');
        $action_edit->setField('id');
        $this->datagrid->addAction($action_edit);
        
        // create DELETE action
        $action_del = new TDataGridAction(array($this, 'onDelete'));
        //$action_del->setUseButton(TRUE);
        //$action_del->setButtonClass('btn btn-default');
        $action_del->setLabel(_t('Delete'));
        $action_del->setImage('fa:trash-o red fa-lg');
        $action_del->setField('id');
        $this->datagrid->addAction($action_del);
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // create the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction([$this, 'onReload']));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        


        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 90%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        $container->add(TPanelGroup::pack('', $this->datagrid, $this->pageNavigation));
        
        parent::add($container);
    }
}