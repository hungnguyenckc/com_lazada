<?php
defined('_JEXEC');
class LazadaViewProduct_redshop extends JViewLegacy
{
	public function display($tpl=null)
	{
		// Get application
		$app = JFactory::getApplication();
		$context = "lazada.list.admin.lazada";
		//Get data from models
		$this->items 		= $this->get('items');
		$this->pagination 	= $this->get('Pagination');
		$this->state			= $this->get('State');
		$this->filter_order 	= $app->getUserStateFromRequest($context.'filter_order', 'filter_order', 'greeting', 'cmd');
		$this->filter_order_Dir = $app->getUserStateFromRequest($context.'filter_order_Dir', 'filter_order_Dir', 'asc', 'cmd');
		$this->filterForm    	= $this->get('FilterForm');
		$this->activeFilters 	= $this->get('ActiveFilters');
		//Check for Error
		if(count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br/>', $errors));
			return false;
		}

		// Set the submenu
		LazadaHelper::addSubmenu('product_redshop');

		//Set toolbar
		$this->addToolBar();

		//Display template
		parent::display($tpl);

		// Set the document
		$this->setDocument();

	}

	protected function addToolBar()
	{
		$link  = 'index.php?option=com_lazada&view=product_redshop&task=product_redshop.synctolazada';
		$title = JToolbarHelper::title(JText::_('COM_LAZADA_MANAGER_LAZADAS'));

		if($this->pagination->total){
			$title .= "<span style='font-size: 0.5em; vertical-align: middle;'>(" . $this->pagination->total . ")</span>";
		}
		JToolbarHelper::title($title, 'lazada');
		JToolbarHelper::addNew('lazada.add');
		JToolbarHelper::editList('lazada.edit');
		JToolbarHelper::deleteList('', 'lazadas.delete');
		JToolbarHelper::link(
			$link, 
			'synctolazada' 
		);
	}

	protected function setDocument() 
	{
		$document = JFactory::getDocument();
		$document->setTitle(JTexT::_('COM_LAZADA_ADMINISTRATOR'));
	}

}

