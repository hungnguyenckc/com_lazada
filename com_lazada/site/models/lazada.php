<?php
defined('_JEXEC') or die('Restrited access');
class LazadaModelLazada extends JModelItem
{
	protected $message;

	public function getTable($type = 'lazada', $prefix = 'LazadaTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getMsg($id=1) 
	{
		if(!is_array($this->message)){
			$this->message = array();
		}

		if(!isset($this->message['id'])) {
			$input = JFactory::getApplication()->input;
			$id = $input->get('id', 1, 'INT');

			$table = $this->getTable();
			$table->load($id);
			$this->message['id'] = $table->name;
		}



		return $this->message['id'];
	}
}