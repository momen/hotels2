<?php namespace App\Http\Controllers;
Use Redirect; 
Use View; 
Use Session;  
use Config;
Use Illuminate\Http\Request;  

class HotelController extends Controller { 

	public function __construct()
	{	 
		View::share ( 'purl', Config::get('app.presenturl') );
	}

	/* Coding for the Landing page START */
	public function hotelinfo()
	{ 
		$dataArr = array(); 
		$hitApi = $this->getDatafromApi();  	 
		$hotelData = Session::get('PRESENT_DATA'); 
		 return view('hotel.hotel_info' , [ 
									'hotelData' 	=> $hotelData 
								]);
	}
	/* Coding for the Landing page END */


	/* Coding for ordering name,price START */
	public function changeListing($mode, $status)
	{   
		$getHotelData = Session::get('PRESENT_DATA'); 
		
		if($mode == "name")
		{
			if($status==1)
			{
				foreach ($getHotelData as $key => $hotelval) 
				{ 
					$hotelname[$key] = $hotelval['name'];
				}
				@@array_multisort($hotelname, SORT_ASC, $getHotelData);
			}
			else if($status==2)
			{
				foreach ($getHotelData as $key => $hotelval) 
				{ 
					$hotelname[$key] = $hotelval['name'];
				}
				@@array_multisort($hotelname, SORT_DESC, $getHotelData);
			}
		} 

		if($mode == "price")
		{
			if($status==1)
			{
				foreach ($getHotelData as $key => $hotelval) 
				{ 
					$hotelname[$key] = $hotelval['price'];
				}
				@@array_multisort($hotelname, SORT_ASC, $getHotelData);
			}
			else if($status==2)
			{
				foreach ($getHotelData as $key => $hotelval) 
				{ 
					$hotelname[$key] = $hotelval['price'];
				}
				@@array_multisort($hotelname, SORT_DESC, $getHotelData);
			}
		}

		 
		$hotelData = $getHotelData;  
		return response()->json([
			'view' => view('hotel.table', compact('hotelData'))->render() 
		]);
	}
	/* Coding for ordering name,price END */


	/* Coding for Search START */
	public function searchbykey($searchkeyword)
	{ 
		$fetchhotelData = Session::get('PRESENT_DATA'); 
		$searchword = trim($searchkeyword); 
		$getHotelData = $this->searchArrange($fetchhotelData); 

		if($searchkeyword!=1)
		{
			if (stripos($searchword, ":") !== FALSE)
			{
				$splitArr = explode(":",$searchword);
				if (stripos($searchword, "$") !== FALSE)
				{
   					/* Code for Price range */
   					$checkPriceRange = $this->checkPriceRange($splitArr);
   					$finalKey = $checkPriceRange;
				}
				else
				{
					/* Code for Date range */
					$checkDateRange = $this->checkDateRange($splitArr);
					$finalKey = $checkDateRange;
				}
			}
			else
			{ 
				$checkName 			= $this->getarraykey($getHotelData, "name", $searchword);
				$checkPrice 		= $this->getarraykey($getHotelData, "price", $searchword);
				$checkCity 			= $this->getarraykey($getHotelData, "city", $searchword); 
				$checkAvailability 	= $this->getarraykey($getHotelData, "availability", $searchword); 
			 	$finalKey = array_merge($checkName,$checkPrice,$checkCity,$checkAvailability); 
			}

			$dataArr = array();
			if(is_array($fetchhotelData) && count($fetchhotelData)>0) 
			{
				$counter=0;
				foreach($fetchhotelData as $key=>$hotelval)
				{ 
					if (in_array($key, $finalKey))
					{
						$dataArr[$counter]['name']  		= $hotelval['name'];
						$dataArr[$counter]['price'] 		= $hotelval['price'];
						$dataArr[$counter]['city']  		= $hotelval['city'];
						$dataArr[$counter]['availability']  = $hotelval['availability'];
						$counter++;
					}
				}
			}
			$hotelData = $dataArr;   
		}
		else
		{
			$hotelData = $fetchhotelData;
		}
		return response()->json([
			'view' => view('hotel.table', compact('hotelData'))->render() 
		]);
	} 
	/* Coding for Search END */


	/* Coding for price range Search START */
	function checkPriceRange($pricedata)
	{
		$fetchhotelData = Session::get('PRESENT_DATA'); 
		$from = trim($pricedata[0],"$");
		$to = trim($pricedata[1],"$");


		$returnArr= array();
		foreach($fetchhotelData as $key => $hotelval)
		{ 
			if ($hotelval['price']>=$from &&  $hotelval['price']<=$to)
			{
				$returnArr[] = $key;
			} 
		} 
		return $returnArr;	 
	}
	/* Coding for price range Search END */


	/* Coding for date range Search START */
	function checkDateRange($datedata)
	{
		$fetchhotelData = Session::get('PRESENT_DATA'); 
		$getFrom = trim($datedata[0]);
		$getTo = trim($datedata[1]);   
		$from = strtotime($getFrom);
		$to =	strtotime($getTo);	


		$returnArr= array(); 
		foreach($fetchhotelData as $key => $hotelval)
		{ 
			$getDateRange = $hotelval['availabilityinfo'];  
			$alldate = explode(",",$getDateRange);  
			foreach($alldate as $kay => $dateval)
			{ 
				$presentdate = explode(":",$dateval);  
				$fromDate = strtotime($presentdate[0]);
				$toDate = strtotime($presentdate[1]);	  
				if($from>=$fromDate && $to<=$toDate )
				{
					$returnArr[] = $key;
				} 
			}

		}  
		return $returnArr;
	}
	/* Coding for date range Search END */


	/* Coding for date rearrange Search START */
	function searchArrange($getHotelData)
	{
		$dataArr = array();
		if(is_array($getHotelData) && count($getHotelData)>0) 
		{
			$counter=0;
			foreach($getHotelData as $key=>$hotelval)
			{  
					$dataArr[$counter]['name']  		= $hotelval['name'];
					$dataArr[$counter]['price'] 		= "$".$hotelval['price'];
					$dataArr[$counter]['city']  		= $hotelval['city'];
					$dataArr[$counter]['availability']  = $hotelval['availability'];
					$counter++;
				 
			}
		}
		return $dataArr;
	} 
	/* Coding for date rearrange Search END */

	/* Coding for text Search START */
	function getarraykey($mainArr, $field, $value)
	{
		$returnArr= array();
		foreach($mainArr as $key => $arrval)
		{ 
			if (stripos($arrval[$field], $value) !== FALSE)
			{
				$returnArr[] = $key;
			} 
		} 
		return $returnArr;
	}
	/* Coding for text Search END */


	/* Coding for data retrival from API START */
	public function getDatafromApi()
	{ 

		$curl = curl_init(); 
		curl_setopt_array($curl, array(
												CURLOPT_RETURNTRANSFER => 1,
												CURLOPT_URL => 'https://api.myjson.com/bins/tl0bp' 
									  )); 
		$resp = curl_exec($curl); 
		curl_close($curl);  

		if($resp)
		{ 
			 $getReturnArr = json_decode($resp);
			 $gethotelArr = $getReturnArr->hotels;
			 $returnArr = $this->getRearrangeData($gethotelArr);   
			 Session::put('PRESENT_DATA', $returnArr);
		}
		else
		{
			$returnArr = array();
			Session::put('PRESENT_DATA', $returnArr);
		} 
		return $returnArr;
	}
	/* Coding for data retrival from API END */


	/* Coding for data rearrange START */
	public function getRearrangeData($oldArr)
	{
		$dataArr = array();
		if(is_array($oldArr) && count($oldArr)>0) 
		{  
			$counter=0; 
			foreach($oldArr as $key=>$oldval)
			{ 
				if(is_array($oldval->availability) && count($oldval->availability)>0) 
				{ 
					$availabilityArr= array(); 
					foreach($oldval->availability as $kby=>$availval)
					{
						$availabilityArr[] = $availval->from.":".$availval->to; 
					}
					$availability = implode("<br>",$availabilityArr);
					$availabilityinfo = implode(",",$availabilityArr);
				}
				else
				{
					$availability = "";
					$availabilityinfo = ""; 				
				}
				$dataArr[$counter]['name']  = $oldval->name;
				$dataArr[$counter]['price'] = $oldval->price;
				$dataArr[$counter]['city']  = $oldval->city;
				$dataArr[$counter]['availability']  = $availability;
				$dataArr[$counter]['availabilityinfo']  = $availabilityinfo; 
				$counter++;
			}
		}
		return  $dataArr;
	}	
	/* Coding for data rearrange END */

}