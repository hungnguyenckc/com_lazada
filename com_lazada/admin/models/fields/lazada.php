<?php
defined('_JEXEC') or die('Restricted access');
JFormHelper::loadFieldClass('list');
class JFormFieldLazada extends JFormFieldList
{
	protected $type = 'lazada';

	protected function getOptions()
	{
		$db 	= JFactory::getDBO();
		$query 	= $db->getQuery(true);
		$query->select('id, name');
		$query->from('#__lazada_product');
		$db->setQuery((string) $query);
		$products = $db->loadObjectList();
		$options = array();

		if($products) {
			foreach ($products as $product) {
				$options[] = JHtml::_('select.option', $product->id, $product->name);
			}
		}
		$option = array_merge(parent::getOptions(), $options);
		return $options;
	}
}