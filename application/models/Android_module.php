<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Android_module extends CI_Model {

  public function __construct()
  {
    $this->load->database();
  }
 // un-used function
 // 1. update_user
 // 2. update_token

// insert new user
  function insert_new_user($table1, $data1)
  {
  	$res = $this->db->insert($table1, $data1);
  	return $res;
  }

  //get id of the user
  public function select_user_id($table1, $data1){
	   
        $this->db->select('cus_id');
        $this->db->where('cus_phone',$data1);
        $q = $this->db->get($table1);
        $res = array();
        $res = $q->result_array();
        foreach($res as $rt)
        {
            $row = $rt['cus_id'];
        }
        return $row;
  }

  public function getOTP($data1)
  {
    // $sql = "SELECT otp FROM customer_detail WHERE cus_phone = '$cus_phone'";
        $this->db->select('otp');
        $this->db->where('cus_phone',$data1);
        $q = $this->db->get('customer_detail');
        $res = array();
        $res = $q->result_array();
        foreach($res as $rt)
        {
            $row = $rt['otp'];
        }
        return $row;
  }

  function update_user($token,$data_id)
  {
      $this->db->set('verified', 1); //value that used to update column  
      $this->db->set('token', $token); //value that used to update column  
      $this->db->where('cus_phone', $data_id); //which row want to upgrade  
      $this->db->update('customer_detail');
      if ($this->db->affected_rows() == '1')
      {
          return TRUE;
      }
      else
      {
          return FALSE;
      } 
  }

  function update_token($userToken,$userName)
  {
      $this->db->set('token', $userToken); //value that used to update column  
      $this->db->where('cus_phone', $userName); //which row want to upgrade  
      $this->db->update('customer_detail');
      if ($this->db->affected_rows() == '1')
      {
          return TRUE;
      }
      else
      {
          return FALSE;
      }
  }

// insert new city booking
  function insert_city_booking($table1, $data1)
  {
    $res = $this->db->insert($table1, $data1);
    return $res;
  }

//fetch user details
public function fetch_user_details($value, $phone)
{
    $this->db->select('cus_id, cus_name, cus_email');
    $this->db->where('cus_phone', $phone);
    $q = $this->db->get($value);
    $res = array();
    $res = $q->result_array();
    return $res;
}
     
//insert outstation booking details
  function insert_out_booking($table1, $data1)
  {
    $res = $this->db->insert($table1, $data1);
    return $res;
  }

  function getUserDetails($value, $pass, $phone)
  { 
    $this->db->select('cus_id, cus_name, cus_email');
    $this->db->where('cus_phone', $phone);
    $this->db->where('cus_password', $pass);
    $this->db->where('verified', 1);
    $q = $this->db->get($value);
    $res = array();
    $res = $q->result_array();
    return $res; 

  }
 
 

  
}