<?php

defined('_JEXEC');

class LazadaModelLazada extends JModelAdmin
{
	public function getTable($type = "Lazada", $prefix = "LazadaTable", $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array(), $loadData = true)
	{
		//Get form 
		$form = $this->loadForm(
			'com_lazada.lazada', 
			'lazada',
			array(
				'control' => 'jform',
				'load_data' => $loadData
			)
		);

		if(empty($form)){
			return false;
		}		
		return $form;
	}

	protected function loadFormData()
	{
		//Check the session 
		$data = JFactory::getApplication()->getUserState(
			'com_lazada.edit.lazada.data',
			array()
		);

		if(empty($data)) {
			$data = $this->getItem();
		}
		echo "<pre>";
		print_r($data);
		echo "</pre>";

		return $data;

	}

	public function saveProduct($data)
	{
		echo "<pre>";
		print_r($data);
		echo "</pre>";
	}
}