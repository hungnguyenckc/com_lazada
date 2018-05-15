<?php
defined('_JEXEC');
class LazadaControllerLazadas extends JControllerAdmin
{
	private $data;
	public function getModel($name = 'Lazada', $prefix = 'LazadaModel', $config=array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}

	private function isExits($product_number)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query
			 	->select($db->qn('product_number'))
		   		->from($db->quoteName('#__redshop_product'))
		    	->where($db->quoteName('product_number') . ' = '. $db->quote($product_number));
		$db->setQuery($query);
		$db->execute();
		// $result = $db->loadRow();
		$num_row = $db->getNumRows();

		if($num_row > 0) return true;

		return false;
 	}  
 	
	public function synctoredshop()
	{	
		// Select product lazada
		$db 		= JFactory::getDbo();
		$query 		= $db->getQuery(true);
		$query->select('*')
				->from('#__product_lazada');
		$db->setQuery($query);
		$data = $db->loadAssocList();
		//Insert product to redshop
		$insert_query = $db->getQuery(true);
		$columns = array(
			'product_name', 
			'product_price', 
			'discount_price', 
			'product_number', 
			'published', 
			'product_full_image', 
			'product_template', 
			'product_type', 	
			'cat_in_sefurl', 
			'product_length',
			'product_height',
			'product_width',
			'update_date',
			'product_parent_id',
			'manufacturer_id',
			'supplier_id',
			'product_on_sale',
			'product_special',
			'product_download',
			'discount_stratdate',
			'discount_enddate',
			'product_s_desc',
			'product_desc',
			'product_volume',
			'product_tax_id',
			'product_thumb_image',
			'publish_date',
			'visited',
			'metakey',
			'metadesc',
			'metalanguage_setting',
			'metarobot_info',
			'pagetitle',
			'pageheading',
			'sef_url',
			'weight',
			'expired',
			'not_for_sale',
			'use_discount_calc',
			'discount_calc_method',
			'min_order_product_quantity',
			'attribute_set_id',
			'product_diameter',
			'product_availability_date',
			'use_range',
			'product_tax_group_id',
			'product_download_days',
			'product_download_limit',
			'product_download_clock',
			'product_download_clock_min',
			'accountgroup_id',
			'canonical_url',
			'minimum_per_product_total',
			'allow_decimal_piece',
			'quantity_selectbox_value',
			'checked_out',
			'checked_out_time',
			'max_order_product_quantity',
			'product_download_infinite',
			'product_back_full_image',
			'product_back_thumb_image',
			'product_preview_image',
			'product_preview_back_image',
			'preorder',
			'append_to_global_seo',
			'use_individual_payment_method'
		);

		$values = array();
		foreach ($data as $key => $value) {
			$values[] = array(
			$db->q($value['name']),
			$db->q($value['price']),
			$db->q($value['special_price']),
			$db->q($value['SellerSku']),
			$db->q($value['published']),
			$db->q($value['image']),
			empty($value['product_template']) ? 9 : $db->q($value['product_template']),
			empty($value['product_type']) ? "'product'" : $db->q($value['product_type']),
			empty($value['cat_in_sefurl']) ? '10' : $db->q($value['cat_in_sefurl']),
			$db->q($value['package_length']),
			$db->q($value['package_height']),
			$db->q($value['package_weight']),
			"'2015-01-12 12:58:09'",
			"'0'", "'1'", "'0'", "'0'",	"'0'", "'0'","'0'","'0'",
			empty($value['short_description']) ? "''" : $db->q($value['short_description']),
			empty($value['description']) ? "''" : $db->q($value['description']),
			"'0'","'0'","''","'2014-12-08 15:26:01'","'0'","''","''","''","''","''","''",
			"''","'0.000'","'0'","'0'","'0'","'0'","'0'","'0'","'0.00'","'0'","'0'","'0'","'0'",
			"'0'","'0'","'0'","'0'","''","'0'","'0'","''","'0'","'2014-12-08 15:26:01'",
			"'0'","'0'","''","''","''","''","'global'","'append'","'0'",
			);
		}

		foreach ($values as $key => $value1) 
		{
			$sku = str_replace("'", "", $value1[3]);
			if($this->isExits($sku))
			{
				continue;	
			}
			
			$valuess[] = implode(',', $value1);
			
		}

		// print_r($valuess);die;

		if(!empty($valuess))
		{
			$insert_query->clear()
						->insert($db->qn('#__redshop_product'))
						->columns($db->qn($columns))
						->values($valuess);	
			$db->setQuery($insert_query);

			if($db->execute()){
				echo '<script>alert("Synchronization '.count($valuess).' success");document.location="'.JURI::base().'index.php?option=com_lazada&view=lazadas"</script>';
			}
		}else {
			echo '<script>alert("No synchronization product");document.location="'.JURI::base().'index.php?option=com_lazada&view=lazadas"</script>';
		}
		
	}
}