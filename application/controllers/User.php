<?php

class User extends CI_Controller {

	public function update_fcm_token() {
		$userID = intval($this->input->post('user_id'));
		$token = $this->input->post('token');
		$this->db->where('id', $userID);
		$this->db->update('users', array(
			'fcm_token' => $token
		));
	}

	public function purchase() {
		$userID = intval($this->input->post('user_id'));
		$refID = $this->input->post('ref_id');
		$code = $this->input->post('code');
		$phone = $this->input->post('phone');
		$price = intval($this->input->post('price'));
		$balance = intval($this->input->post('balance'));
		$transactionID = intval($this->input->post('transaction_id'));
		$this->db->insert('transactions', array(
			'user_id' => $userID,
			'ref_id' => $refID,
			'code' => $code,
			'phone' => $phone,
			'price' => $price,
			'balance' => $balance,
			'transaction_id' => $transactionID
		));
	}
	
	public function update_purchase_status() {
		$data = file_get_contents('php://input');
		$obj = json_decode($data, true);
		$status = intval($obj['data']['status']);
		$refID = $obj['data']['ref_id'];
		$transaction = $this->db->get_where('transactions', array(
		    'ref_id' => $refID
		  ))->row_array();
		$userID = intval($transaction['user_id']);
		$token = $this->db->get_where('users', array(
		    'id' => $userID
		  ))->row_array()['fcm_token'];
    $url = "https://fcm.googleapis.com/fcm/send";
    $serverKey = 'AAAAon_cfQo:APA91bEL0BldzQJ3z8yJl4ePybkpyvARvRXsyw4tSMJj9ffDWjkzlzBWTZsJnbx3c9hKaaagjC8gIHsFfPeDMK29L70yIwAJtlMuMHHKphUNLc4yHWUoaCZmnuPTG8hAZfZPp1VKN-PX';
    $title = "Notification title";
    $body = "Hello I am from Your php server";
    $notification = array('title' =>$title , 'body' => $body, 'sound' => 'default', 'badge' => '1');
    $arrayToSend = array('to' => $token, 'notification' => $notification,'priority'=>'high', 'data' => [
        'action' => 'com.prod.agenpulsa.PAYMENT_SUCCESS',
        'payment_status' => '' . $status,
        'ref_id' => $refID
      ]);
    $json = json_encode($arrayToSend);
    $headers = array();
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Authorization: key='. $serverKey;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
    //Send the request
    $response = curl_exec($ch);
    //Close request
    if ($response === FALSE) {
    die('FCM Send Error: ' . curl_error($ch));
    }
    curl_close($ch);
	}
	
	public function fcm_test() {
    $url = "https://fcm.googleapis.com/fcm/send";
    $token = "dXMZZSKXDtQ:APA91bFLR4i9LtNzppB2-yO_gCe-cyd2wxlI91lsk7JKRMEW3aWszOBaoOz3xXQXIfQSqgigHfvIxNSAUQUUu-ZUnOS6MFGs4qNjtGwA_aTCUzLirNGmpIaqasuCMNteW98EVn5f4BxS";
    $serverKey = 'AAAAon_cfQo:APA91bEL0BldzQJ3z8yJl4ePybkpyvARvRXsyw4tSMJj9ffDWjkzlzBWTZsJnbx3c9hKaaagjC8gIHsFfPeDMK29L70yIwAJtlMuMHHKphUNLc4yHWUoaCZmnuPTG8hAZfZPp1VKN-PX';
    $title = "Notification title";
    $body = "Hello I am from Your php server";
    $notification = array('title' =>$title , 'body' => $body, 'sound' => 'default', 'badge' => '1');
    $arrayToSend = array('to' => $token, 'notification' => $notification,'priority'=>'high', 'data' => [
        'name' => 'Dana'
      ]);
    $json = json_encode($arrayToSend);
    $headers = array();
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Authorization: key='. $serverKey;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
    //Send the request
    $response = curl_exec($ch);
    //Close request
    if ($response === FALSE) {
    die('FCM Send Error: ' . curl_error($ch));
    }
    curl_close($ch);
	}
}
