<?php
defined('_JEXEC');
class LazadaModelLazadas extends JModelList
{
	public function __construct($config = array())
	{
		if(empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
					'id',
					'name',
					'published'
			);
		}
		parent::__construct($config);
	}


	protected function getListQuery() 
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('*')
				->from($db->quoteName('#__product_lazada'));

		// Filter like search
		$search = $this->getState('filter.search');
		if(!empty($search)) {
			$like = $db->quote('%' . $search . '%');
			$query->where('name LIKE' . $like);
		}

		// Filter by published state
		$query->where('(published IN(0,1))');

		// Add the list ordering clause
		$ordercol 	= $this->state->get('list.ordering', 'name');
		$orderDirn 	= $this->state->get('list.direction', 'asc');

		$query->order($db->escape($ordercol) . ' ' . $db->escape($orderDirn));
		return $query;
	}

}