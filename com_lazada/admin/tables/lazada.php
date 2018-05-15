<?php
defined('_JEXEC');

class LazadaTableLazada extends JTable
{
	public function __construct(&$db)
	{
		parent::__construct('#__product_lazada', 'id', $db);
	}
}