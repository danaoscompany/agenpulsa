<?php

class User extends CI_Controller {

	public function purchase() {
		$refID = $this->input->post('ref_id');
		$code = $this->input->post('code');
		$phone = $this->input->post('phone');
		$price = intval($this->input->post('price'));
		$balance = intval($this->input->post('balance'));
		$transactionID = intval($this->input->post('transaction_id'));
		$this->db->insert('transactions', array(
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
		$fcmToken = $this->db->get_where('users', array(
		    'id' => $userID
		  ))->row_array()['fcm_token'];
		$server_key = 'AAAAon_cfQo:APA91bEL0BldzQJ3z8yJl4ePybkpyvARvRXsyw4tSMJj9ffDWjkzlzBWTZsJnbx3c9hKaaagjC8gIHsFfPeDMK29L70yIwAJtlMuMHHKphUNLc4yHWUoaCZmnuPTG8hAZfZPp1VKN-PX';
    $url = "https://fcm.googleapis.com/fcm/send";
    $token = $fcmToken;
    $serverKey = $server_key;
    $title = "Informasi Pembayaran";
    $body = "Ketuk untuk info lebih lanjut";
    $notification = array('title' =>$title , 'body' => $body, 'sound' => 'default', 'badge' => '1');
    $arrayToSend = array('to' => $token, 'notification' => $notification,'priority'=>'high');
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
    curl_close($ch);
	}
}
