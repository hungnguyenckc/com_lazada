<?php
defined('_JEXEC');
class LazadaViewLazada extends JViewLegacy
{
	protected $form = null;

	public function display($tpl = null)
	{
		//Get the data
		$this->form = $this->get('Form');
		$this->item = $this->get('Item');

		//Check Error
		if(count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br/>', $errors));

			return fales;
		}

		//Set toolbar 
		$this->addToolBar();

		//Display the Template
		parent::display($tpl);
		
		$this->setDocument();
	}

	protected function addToolBar()
	{
		$input = JFactory::getApplication()->input;

		//Hide joomla administrator main menu
		$input->set('hidemainmenu', true);
		$isNew = ($this->item->id == 0);

		if($isNew) 
		{
			$title = JText::_('COM_LAZADA_MANAGER_LAZADA_NEW');
		}
		else
		{
			$title = JText::_('COM_LAZADA_MANAGER_LAZADA_EDIT');
		}

		JToolbarHelper::title($title, 'lazada');
		JToolbarHelper::save('lazada.save');
		JToolbarHelper::cancel(
			'lazada.cancel',
			$isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE'
		);
	}

	protected function setDocument()
	{
		$isNew	= ($this->item->id < 1);
		$document = JFactory::getDocument();
		$document->setTitle(
			$isNew ? JText::_('COM_LAZADA_LAZADA_CREATING') : JText::_('COM_LAZADA_LAZADA_EDITING')); 
	}
}