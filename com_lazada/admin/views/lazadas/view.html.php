<?php
defined('_JEXEC');
use Joomla\Registry\Registry;
class LazadaViewLazadas extends JViewLegacy
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
		LazadaHelper::addSubmenu('lazada');

		//Set toolbar
		$this->addToolBar();

		$this->insertDataLazada();
		

		//Display template
		parent::display($tpl);

		// Set the document
		$this->setDocument();

	}

	protected function addToolBar()
	{
		$link  = 'index.php?option=com_lazada&view=lazadas&task=lazadas.synctoredshop';
		$getToken  = 'https://auth.lazada.com/oauth/authorize?response_type=code&force_auth=true&redirect_uri=https://hung.redweb.vn/callback/callback.php&client_id=101621';
		$title = JToolbarHelper::title(JText::_('COM_LAZADA_MANAGER_LAZADAS'));

		if($this->pagination->total){
			$title .= "<span style='font-size: 0.5em; vertical-align: middle;'>(" . $this->pagination->total . ")</span>";
		}
		JToolbarHelper::title($title, 'lazada');
		JToolbarHelper::addNew('lazada.add');
		JToolbarHelper::editList('lazada.edit');
		JToolbarHelper::deleteList('','lazadas.delete');
		JToolbarHelper::link(
			$link, 
			'synctoredshop' 
		);
		JToolbarHelper::link(
			$getToken, 
			'Get Token Lazada' 
		);
	}

	protected function setDocument() 
	{
		$document = JFactory::getDocument();
		$document->setTitle(JTexT::_('COM_LAZADA_ADMINISTRATOR'));
	}

	protected function getProductLazada($access_token)
	{
		$session = JFactory::getSession();
		require_once (JPATH_ROOT.'/media/com_lazada/lazada/LazopSdk.php');
		 $url	 		= "https://api.lazada.vn/rest";
		 $appkey 		= "101621";
		 $appSecret 	= "sp6LzWE3LX7UG6XKJK0RKKd5Ss9cHOzc";
		// $accessToken	= "50000501f17eelbrAorviHjJJxmww15944bd5DdWGtShwtxQPvXzBscKfVL5Y";


		$c = new LazopClient($url,$appkey,$appSecret);
		$request = new LazopRequest('/products/get','GET');
		$request->addApiParam('filter','all');

		$request->addApiParam('limit','100');
		$request->addApiParam('options','1');

		$data = json_decode($c->execute($request, $access_token));
		return $data;
	}

	protected function isExits($sellerSku)
	{

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query
			 	->select($db->qn('sellerSku'))
		   		->from($db->quoteName('#__product_lazada'))
		    	->where($db->quoteName('sellerSku') . ' = '. $db->quote($sellerSku));
		$db->setQuery($query);
		$db->execute();


		if($db->getNumRows() > 0) 
			return true;

		return false;
 	}  	

 	protected function arrSku()
	{

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query
			 	->select($db->qn('sellerSku'))
		   		->from($db->quoteName('#__product_lazada'));
		$db->setQuery($query);

		return $db->loadColumn();
 	}  	

	public static function refresh_token($refresh_token)
	{
		require_once (JPATH_ROOT.'/media/com_lazada/lazada/LazopSdk.php');

		$url	 		= "https://auth.lazada.com/rest";
		$appkey 		= "101621";
		$appSecret 		= "sp6LzWE3LX7UG6XKJK0RKKd5Ss9cHOzc";
		
		$c = new LazopClient($url,$appkey,$appSecret);
		$request = new LazopRequest('/auth/token/refresh');
		$request->addApiParam('refresh_token',$refresh_token);
		$data = json_decode($c->execute($request));
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$db->setQuery('TRUNCATE TABLE #__access_token')->execute();

		$columns = array('access_token', 'refresh_token', 'refresh_expires_in', 'expires_in');
		$values = array(
				$db->q($data->access_token),
				$db->q($data->refresh_token),
				$db->q(time() + $data->refresh_expires_in),
				$db->q(time() + $data->expires_in)
			);
		$query
			->clear()
		    ->insert($db->quoteName('#__access_token'))
		    ->columns($db->quoteName($columns))
		    ->values(implode(',', $values));
		$db->setQuery($query);
		$db->execute();
	}

	public function getToken()
	{
		$data = file_get_contents('http://hung.redweb.vn/callback/callback.txt');
		$data = json_decode($data);

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$db->setQuery('TRUNCATE TABLE #__access_token')->execute();
		$columns = array('access_token', 'refresh_token', 'refresh_expires_in', 'expires_in');
		$values = array(
				$db->q($data->access_token),
				$db->q($data->refresh_token),
				$db->q(time() + $data->refresh_expires_in),
				$db->q(time() + $data->expires_in)
			);
		$query
			->clear()
		    ->insert($db->quoteName('#__access_token'))
		    ->columns($db->quoteName($columns))
		    ->values(implode(',', $values));
		$db->setQuery($query);
		$db->execute();
	}

	public function insertDataLazada()
	{	
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query
				->select('*')
				->from('#__access_token');
		$db->setQuery($query);
		$info_token = $db->loadAssocList();
		if(empty($info_token) || ($info_token[0]['refresh_expires_in'] - time()) <= 0)
		{
 			$this->getToken();
		}
		elseif(($info_token[0]['refresh_expires_in'] - time()) < 94000)
		{
			$this->refresh_token($info_token[0]['refresh_token']);
		}
		//$this->refresh_token('50001501b19rh1pedfar9MxtFhmPkze1b9c07d2Q0EG3ltqBxxXtsqwz9e3ds');
		$data = $this->getProductLazada($info_token[0]['access_token']);
		$columns = array('primaryCategory', 'name','description', 'brand', 'model', 'warranty', 'warranty_type', 'color_family', 'sellerSku', 'price', 'quantity', 'special_price', 'special_from_date', 'special_to_date', 'package_height', 'package_length', 'package_weight', 'package_content', 'image');
		$values = array();
		foreach ($data as $key => $value) 
		{
			foreach ($value->products as $keys => $row) 
			{
				  $values[] = array($db->q($row->primary_category), 
				  $db->q($row->attributes->name), 
				  $db->q($row->attributes->description), 
				  $db->q($row->attributes->brand), 
				  $db->q($row->attributes->model), 
				  $db->q($row->attributes->warranty), 
				  $db->q($row->attributes->warranty_type), 
				  $db->q($row->skus[0]->color_family), 
				  $db->q($row->skus[0]->SellerSku), 
				  $db->q($row->skus[0]->price), 
				  $db->q($row->skus[0]->quantity), 
				  $db->q($row->skus[0]->special_price), 
				  empty($row->skus[0]->special_from_date) ? $db->q('1970-01-01 00:00:00') : $db->q($row->skus[0]->special_from_date), 
				   empty($row->skus[0]->special_from_date) ? $db->q('1970-01-01 00:00:00') : $db->q($row->skus[0]->special_from_date), 
				  $db->q($row->skus[0]->package_height), 
				  $db->q($row->skus[0]->package_length), 
				  $db->q($row->skus[0]->package_weight), 
				  $db->q($row->skus[0]->package_content), 
				  $db->q($row->skus[0]->MainImage)
				);
			}
		}		
		foreach ($values as $key => $value1) 
		{
			$sku = str_replace("'", "", $value1[8]);

			if($this->isExits($sku))
			{	
				$values_exist[] = $value1[8];
				continue;	
			}

			$valuess[] = implode(',', $value1);
		}
		$arrSku = $this->arrSku();
		foreach ($arrSku as $key => $value) {
			 $value = "'".$value."'";
			if(!in_array($value, $values_exist)){
				$value_del[] = $value;
			}
		}
		if(!empty($valuess))
		{
				$query
			    ->insert($db->qn('#__product_lazada'))
			    ->columns($db->qn($columns))
			    ->values($valuess);

			$db->setQuery($query);
			$db->execute();
		} 

		if(!empty($value_del))
		{
			$query->clear()
				  ->delete($db->qn('#__product_lazada'))
				  ->where($db->qn('sellerSku') . 'IN ('.implode(',', $value_del).')');
			$db->setQuery($query);
			$db->execute();
		}
	}

}

