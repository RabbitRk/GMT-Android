<?php
defined('BASEPATH') OR exit('No direct script access allowed');
		//get date in the server

class Android_ctrl extends CI_Controller {



	 public function __construct() {
        parent::__construct();         
        $this->load->model('Android_module');         
    }
 
	public function index()
	{
		$this->load->view('welcome_message');
	} 
	
	//add new user and get user id 
	public function addUser()
	{
		date_default_timezone_set("Asia/Kolkata");
		$startTime = date("Y-m-d h:i");
		
		if($_SERVER['REQUEST_METHOD']=='POST')
		{
	       $userName = $_POST['userName'];
	       $passWord = $_POST['passWord'];
	       $phoneNumber = $_POST['phoneNumber'];
	       $emailID = $_POST['emailID'];
       
       	   $otp = rand(1000, 9999);

			// $userName = 'dummy';
			// $passWord = 'dummy';
			// $phoneNumber = '9659314370';
			// $emailID = 'dua1mmy';
			// $otp = 'dumm1y';

			$user_data = array(
							'cus_name' => $userName,
							'cus_password' => $passWord,
							'cus_phone' => $phoneNumber,
							'cus_email' => $emailID,
							'otp' => $otp,
							'cus_join_date' => $startTime
						);

			$res = $this->Android_module->insert_new_user('customer_detail', $user_data);
  
			if ($res) {
				$this->sendOTP($otp,$phoneNumber);
 
				$user_id = $this->Android_module->select_user_id('customer_detail', $phoneNumber);

	            $products = array(); 

	            $temp = array();
	            $temp['id'] = $user_id; 
	            echo json_encode($products);

			}
			else {
				echo "failed";
			}

			}
	}

	// send OTP for the phone number confirmation
	public function sendOTP($otp, $phone)
	{
	    $username = "mahendrasnk@gmail.com";
	    $hash = "8e56247a6c68f42ae022de8c33402488bfad0c795e72c35f37d2c6f5617ba683";
	    
	    // Config variables. Consult http://api.textlocal.in/docs for more info.
	    $test = "0";

	    // Data for text message. This is the text message data.
	    $sender = "TXTLCL"; // This is who the message appears to be from.
	    $numbers = $phone;//single number or a comma-seperated list of numbers
	    //This is the sms text that will be sent via sms 
	    $message = "Welcome to Got My Trip: Your verification code is $otp .";
	    // 612 chars or less
	    // A single number or a comma-seperated list of numbers
	    $message = urlencode($message);
	    $data = "username=".$username."&hash=".$hash."&message=".$message."&sender=".$sender."&numbers=".$numbers."&test=".$test;
	    $ch = curl_init('http://api.textlocal.in/send/?');
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    $result = curl_exec($ch); // This is the result from the API
	    curl_close($ch); 
	}

//check the valid user otp
	function checkotp()
	{
		if($_SERVER['REQUEST_METHOD']=='POST'){

			//Getting the username and otp 
			// $cus_phone = '9659314370';
			// $otp = '5575';
			// $token = 'tokentokentokentoken'; 
			$cus_phone = $_POST['cus_phone'];
			$otp = $_POST['otp'];
			$token = $_POST['token'];
			
			//Creating an SQL to fetch the otp from the table  
			$real_otp = $this->Android_module->getOTP($cus_phone);
			 
			if($otp == $real_otp){ 

				$updated = $this->Android_module->update_user($token, $cus_phone);
				//If the table is updated 
				if($updated){
					//displaying success 
					echo 'success';
				}else{
					//displaying failure 
					echo 'failure'; 
				}
			}else{
				//displaying failure if otp given is not equal to the otp fetched from database  
				echo 'failure';
			}
		}
	}

//city booking module from app
	function cityBooking()
	{
		if($_SERVER['REQUEST_METHOD']=='POST')
		{
	       $cus_id = $_POST['CUS_ID'];
	       $book_date_time = date("d-m-Y h:i");
	       $origin = $_POST['ORIGIN'];
	       $destination = $_POST['DESTINATION'];
	       $base_fare = $_POST['BASE_FARE'];
	       $kmeter = $_POST['KMETER'];
	       $vehicle_id = $_POST['VEHICLE_ID']; 
	       
	       $city_book = array(
	       					'cus_id' => $cus_id,
							'starting_point' => $origin,
							'destination_point' => $destination,
							'kmeter' => $kmeter,
							'amount' => $base_fare,
							'v_type_id' => $vehicle_id,
							'b_time' => $book_date_time,
							'status' => '1',
							'extras' => 0
						);
 
	       $result = $this->Android_module->insert_city_booking('city_booking', $city_book);
	      
	      //displaying the result in json format 
	       if ($result) 
	       {
	          echo "success"; 
	       }
	        else 
	       { 
	          echo "failed";
	       } 
		}   
	}

//fetch user details
	function fetchUserDetails()
	{
		if($_SERVER['REQUEST_METHOD']=='POST')
		{ 
	   		$phoneNumber = $_POST['phoneNumber'];;
	   		// $phoneNumber = '9659314370';

            // getting id of the user      
            $stmt = $this->Android_module->fetch_user_details('customer_detail', $phoneNumber); 

            $products = array(); 
            $temp = array();
            foreach ($stmt as $rows) 
			{
				$temp['id'] = $rows["cus_id"]; 
	            $temp['name'] = $rows["cus_name"];   
	            $temp['email'] = $rows["cus_email"];  
            	array_push($products, $temp);
			} 
             
            // //displaying the result in json format 
            echo json_encode($products);
		} 
	}
//insert outstationBooking
	function outstationBooking()
	{
		if($_SERVER['REQUEST_METHOD']=='POST')
		{
	       $cus_id = $_POST['CUS_ID'];
	       $book_date_time = date("d-m-Y h:i");
	       $origin = $_POST['ORIGIN'];
	       $destination = $_POST['DESTINATION'];
	       $base_fare = $_POST['BASE_FARE'];
	       $kmeter = $_POST['KMETER'];
	       $vehicle_id = $_POST['VEHICLE_ID']; 
	       $hours = $_POST['HOURS'];
	       $return_time = $_POST['RETURN_DATE'];
        
	       $out_book = array(
	       					'cus_id' => $cus_id,
							'starting_point' => $origin,
							'destination_point' => $destination,
							'kmeter' => $kmeter,
							'amount' => $base_fare,
							'v_type_id' => $vehicle_id,
							'b_time' => $book_date_time,
							'return_date' => $return_time,
							'status' => '1',
							'extras' => 0
						);
 

	       $result = $this->Android_module->insert_out_booking('outstation_booking', $out_book);
	      
	      //displaying the result in json format 
	       if ($result) 
	       {
	          echo "success"; 
	       }
	        else 
	       { 
	          echo "failed";
	       } 
		}    
	}

	function rentalBooking()
	{
		if($_SERVER['REQUEST_METHOD']=='POST')
		{
			//GET SERVER SIDE datetime
			date_default_timezone_set("Asia/Kolkata"); 
			$book_date_time = date("Y-m-d h:i");
	       
	       $cus_id = $_POST['CUS_ID'];
	       $pick_date = $_POST['BOOK_TIME'];
	       $pick_date_time =  date('Y-m-d h:i', strtotime($pick_date));
	       $origin = $_POST['ORIGIN']; 
	       $travel_type = $_POST['TRAVEL_TYPE']; //prime or suv
	       $package_id = $_POST['PACKAGE_ID']; //package
	       $amount = $_POST['FARE'];
        
	       $out_book = array(
	       					'cus_id' => $cus_id,
							'starting_point' => $origin, 
							'package_id' => $package_id,
							'amount' => $amount,
							'p_time' => $pick_date_time,
							'v_type_id' => $travel_type,
							'b_time' => $book_date_time,
							'status' => '1',
						);
 

	       $result = $this->Android_module->insert_out_booking('outstation_booking', $out_book);
	       
	      //displaying the result in json format 
	       if ($result) 
	       {
	          echo "success"; 
	       }
	        else 
	       { 
	          echo "failed";
	       } 
		}    
	}

//get userDetails on user Login
	function userLogin()
	{
		if($_SERVER['REQUEST_METHOD']=='POST')
		{
		    $passWord = $_POST['passWord'];
		    $phoneNumber = $_POST['phoneNumber'];
  
			// $passWord = 'maluKann';
			// $phoneNumber = '9659314370';
		    
		    $result = $this->Android_module->getUserDetails('customer_detail',$passWord, $phoneNumber);
	 
	        $products = array(); 
	        $temp = array();
		    
		    if ($result != null) {
		    	foreach ($result as $rows) 
				{
					$temp['id'] = $rows["cus_id"]; 
		            $temp['name'] = $rows["cus_name"];   
		            $temp['email'] = $rows["cus_email"];  
	            	array_push($products, $temp);
				}
				echo json_encode($products);
		    }
		    else
		    {
		    	echo "failed";
		    } 
		} 
	}




}//end if