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
}
