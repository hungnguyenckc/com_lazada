<?php
defined('_JEXEC');
class LazadaControllerProduct_redshop extends JControllerAdmin
{
	public function getModel($name = 'Lazada', $prefix = 'LazadaModel', $config=array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}

	private function checkImage($image, $access_token)
	{	
		$dir 		= JPATH_ROOT . "/components/com_redshop/assets/images/product/";
		if(filter_var($image, FILTER_VALIDATE_URL))
		{
			$image = $image;
		}
		else 
		{
			$image 		= $dir . $image;
		}
		$infoImage = getimagesize($image);
		list($weight, $height) = array($infoImage[0], $infoImage[1]);

		if(($weight < 500 || $height < 500 ) || ($weight > 2000  || $height > 2000))
		{
			$ext 		= explode("/", $image);
			$file_name 	= end($ext); 
		    $file_ext 	= end(explode(".", $file_name));
			$thumbnail 	= $dir."thumb/" . time() .".".$file_ext    ;//lưu file đã cắt ở thư mục thumb
				list($width,$height) = array($infoImage[0], $infoImage[1]);
				$thumb_create = imagecreatetruecolor("600","650");
				switch($file_ext)
				{
					case 'jpg':
						$source = imagecreatefromjpeg($image); // tạo ảnh mới từ file này
						break;
					case 'jpeg':
						$source = imagecreatefromjpeg($image);
						break;
					case 'png':
						$source = imagecreatefrompng($image);
						break;
					case 'gif':
						$source = imagecreatefromgif($image);
						 break;
					default:
						$source = imagecreatefromjpeg($image);
				}

			    imagecopyresized($thumb_create, $source, 0, 0, 0, 0, "600","650", $width,$height);
            switch($file_ext)
				{
					case 'jpg' || 'jpeg':

						imagejpeg($thumb_create,$thumbnail,100);

						break;
					case 'png':
						imagepng($thumb_create,$thumbnail,100);
						break;
					case 'gif':
						imagegif($thumb_create,$thumbnail,100);
						 break;
					default:
						imagejpeg($thumb_create,$thumbnail,100);
				}
				$result = $thumbnail;
		}else{
			$result = $image;
		}

		require_once (JPATH_ROOT.'/media/com_lazada/lazada/LazopSdk.php');
		$url	 		= "https://api.lazada.vn/rest";
		$appkey 		= "101621";
		$appSecret 	= "sp6LzWE3LX7UG6XKJK0RKKd5Ss9cHOzc";
		$c = new LazopClient($url,$appkey,$appSecret);
		$request = new LazopRequest('/image/upload');
		$request->addFileParam('image',file_get_contents($result));
		$data = json_decode($c->execute($request, $access_token));
		unlink($thumbnail);	
		return $data->data->image->url;
	}

	protected function getSkuLazada($access_token)
	{
		require_once (JPATH_ROOT.'/media/com_lazada/lazada/LazopSdk.php');
		 $url	 		= "https://api.lazada.vn/rest";
		 $appkey 		= "101621";
		 $appSecret 	= "sp6LzWE3LX7UG6XKJK0RKKd5Ss9cHOzc";
		 //$accessToken	= "50000501f17eelbrAorviHjJJxmww15944bd5DdWGtShwtxQPvXzBscKfVL5Y";


		$c = new LazopClient($url,$appkey,$appSecret);
		$request = new LazopRequest('/products/get','GET');
		$request->addApiParam('filter','all');

		$request->addApiParam('limit','100');
		$request->addApiParam('options','1');

		$data = json_decode($c->execute($request, $access_token));
		foreach ($data as $key => $value) 
		{
			foreach ($value->products as $keys => $row) 
			{
				$sku[]  = $row->skus[0]->SellerSku;
			}
		}
		return $sku;
	}

	public function getProductNumber()
	{
		$db 	= JFactory::getDbo();
		$query 	= $db->getQuery(true);
		$query
				->select('product_number')
				->from($db->qn('#__redshop_product'));
		$db->setQuery($query);
		return  $db->loadColumn();
	}

	public function getattr($primaryCategory, $access_token)
	{
		require_once (JPATH_ROOT.'/media/com_lazada/lazada/LazopSdk.php');
		 $url	 		= "https://api.lazada.vn/rest";
		 $appkey 		= "101621";
		 $appSecret 	= "sp6LzWE3LX7UG6XKJK0RKKd5Ss9cHOzc";
		//$accessToken 	= "50000501523a6pueotZtuEcbDrCqvxdFlOK1736ca392GvukG5ksveKs0fD9D";
		$c = new LazopClient($url,$appkey,$appSecret);
		$request = new LazopRequest('/category/attributes/get','GET');
		$request->addApiParam('primary_category_id',$primaryCategory);
		$data = json_decode($c->execute($request, $access_token));
		return $data;
	}

	public function createProcuctLazada($payload, $access_token, $array)
	{
		require_once (JPATH_ROOT.'/media/com_lazada/lazada/LazopSdk.php');
		$url	 		= "https://api.lazada.vn/rest";
		$appkey 		= "101621";
		$appSecret 	= "sp6LzWE3LX7UG6XKJK0RKKd5Ss9cHOzc";
		$c = new LazopClient($url,$appkey,$appSecret);
		$request = new LazopRequest('/product/create');
		$request->addApiParam('payload', $payload);
			
		echo "<pre>";
		print_r($c->execute($request, $access_token));
		echo "</pre>";
	}

	Public function synctolazada()
	{

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query
				->select('*')
				->from('#__access_token');
		$db->setQuery($query);
		$info_token = $db->loadAssocList();
		$access_token = $info_token[0]['access_token'];

		$product_number = $this->getProductNumber();
		
		$sku_lazada 	= $this->getSkuLazada($access_token);
		foreach ($product_number as $key => $value) {
			if(!in_array($value, $sku_lazada)){
				$arrSku[] = "'".$value."'";
			}
		}
		if(!empty($arrSku)){
			$db 	= JFactory::getDbo();
			$query 	= $db->getQuery(true);
			$query
					->select(array(
							'p.product_number',
							'p.product_name',
							'p.product_s_desc',
							'p.product_desc',
							'p.product_price',
							'p.discount_price',
							'p.product_length',
							'p.product_height',
							'p.product_width',
							'p.product_full_image',
							'l.primaryCategory',
							'l.color_family',
							'l.brand',
							'l.quantity',
							'l.special_from_date',
							'l.special_to_date',
						))
					->from($db->qn('#__redshop_product', 'p'))
					->join('LEFT', $db->qn('#__product_lazada', 'l') . ' ON ('.$db->qn('p.product_number'). ' = ' .$db->qn('l.sellerSku') . ')')
					->where( $db->qn('product_number') . 'IN('.implode(',', $arrSku).')' );
			$db->setQuery($query);
			$data = $db->loadAssocList();
			$arrProduct = array();
			foreach($data as $key => $value){
				$Product['PrimaryCategory'] 	= empty($value['primaryCategory']) ? '6623' : $value['primaryCategory'];
				$Product['SellerSku'] 			= $value['product_number'];
				$Product['name']				= $value['product_name'];
				$Product['short_description']	= empty($value['product_s_desc']) ? 'short_description' : $value['product_s_desc'];
				$Product['description']			= empty($value['product_desc']) ? 'description' : $value['product_desc'];
				$Product['price']			= ($value['product_price'] <= 1000) ? $value['product_price']. '000' : $value['product_price'];
				$Product['special_price']			= ($value['discount_price'] == 0) ? '' : $value['discount_price'];
				$Product['color_family']		= empty($value['color_family']) ? '' : $value['color_family'];
				$Product['size']				= '';
				$Product['brand']				= empty($value['brand']) ? 'AGAMA' : $value['brand'];
				$Product['model']				= 'asdf';
				$Product['special_from_date']						= 'asdf';
				$Product['package_weight']		= 2;
				$Product['package_length']		= $value['product_length'];
				$Product['package_width']		= $value['product_width'];
				$Product['package_height']		= $value['product_height'];
				$Product['quantity']			= empty($value['quantity']) ? '' : $value['quantity'];
				$Product['special_from_date']		= empty($value['special_from_date']) ? '2018-05-15 00:00:00' : $value['special_from_date'];
				$Product['special_to_date']		= empty($value['special_to_date']) ? '2018-05-15 00:00:00' : $value['special_to_date'];
				$Product['Image']				= $value['product_full_image'];

				array_push($arrProduct, $Product);
			}
			$i = 1;
			foreach ($arrProduct as $key => $value_product) 
			{
				$xml = new DOMDocument('1.0', 'UTF-8');
				$request 	= $xml->createElement('Request');
				$product 	= $xml->createElement('Product');
				$primaryCategory	= $xml->createElement('PrimaryCategory', $value_product['PrimaryCategory']);
				$attributes = $xml->createElement('Attributes');
				$skus = $xml->createElement('Skus');
				$sku  =$xml->createElement('Sku');
				$images = $xml->createElement('Images');
				$image = $xml->createElement('Image', $this->checkImage($value_product['Image'], $access_token));

				$xml->appendChild($request);
				$request->appendChild($product);
				$product->appendChild($primaryCategory);
				$product->appendChild($attributes);
				$product->appendChild($skus);
				$skus->appendChild($sku);
				$attr = $this->getattr($value_product['PrimaryCategory'], $access_token);
				foreach ($attr->data as $key_attr => $value_attr) 
				{

					//$value_attr->options[0]->name
					foreach ($value_product as $key_pr => $value) 
					{
						if($value_attr->name)
						{
							if(($value_product[$value_attr->name] == null || !isset($value_product[$value_attr->name])) && $value_attr->is_mandatory == 1 && count($value_attr->options) > 0)
							{
								if(($value_attr->attribute_type == 'normal'))
								{
									$attributes->appendChild($xml->createElement($value_attr->name, $value_attr->options[0]->name));break;
								}elseif($value_attr->attribute_type == 'sku'){
									$sku->appendChild($xml->createElement($value_attr->name, $value_attr->options[0]->name));break;
								}
							}
							else 
							{
								if(($value_attr->attribute_type == 'normal'))
								{
									$attributes->appendChild($xml->createElement($value_attr->name, $value_product[$value_attr->name]));break;
								}elseif($value_attr->attribute_type == 'sku'){
									$sku->appendChild($xml->createElement($value_attr->name,$value_product[$value_attr->name]));break;
								}
							}		
						}
					}
				}
				$sku->appendChild($images);
				$images->appendChild($image);
				$xml->formatOutput = true;
				$xml->save(JPATH_ROOT . "/administrator/components/com_lazada/views/file/payload_".$i.".xml") or die('error');
				$i++;
			}
			for ($i = 1; $i <= count($arrProduct); $i++) 
			{
				$payload = file_get_contents(JPATH_ROOT . "/administrator/components/com_lazada/views/file/payload_".$i.".xml");

				$this->createProcuctLazada($payload, $access_token, $arrProduct);

				unlink(JPATH_ROOT . "/administrator/components/com_lazada/views/file/payload_".$i.".xml");
			}
				echo '<script>alert("Synchronization '.count($arrProduct).' success");document.location="'.JURI::base() .'index.php?option=com_lazada&view=product_redshop"</script>';
		}
		else
		{
			echo '<script>alert("No synchronization product");document.location="'.JURI::base().'index.php?option=com_lazada&view=product_redshop"</script>';
		}
		
	}
}