<?php
namespace sql;
class price {
	public $id           = array('type'=> 'int@10', 'autoI', 'label' => 'price_id');
	public $users_id     = array('type'=> 'int@10', "label" => "users_id");
	public $date		 = array("type" => "int@8", "label" => "price_date");
	public $type         = array("type" => "enum@price_add,price_low" ,"label" => "price_type");
	public $value        = array('type'=> 'int@7', "label" => "price_value");
	public $pay_type     = array('type'=> 'enum@bank,pos,cash,rule', "label" => "price_pay_type");
	public $transactions = array('type'=> 'varchar@255', "label" => "price_transactions");
	public $description  = array('type'=> 'text@' , "label" => "description");
	
	public function id(){
		$this->validate("id");
	}

	public function users_id(){
		// $this->form("text")->name("users_id")->data_url("person/api/");
	}

	public function date() {
		$this->form("#date")->name("date")->label("date");
		$this->validate()->date()->form->date("date incorect");
	}

	public function type(){
		$this->form("radio")->name("type")->label("type");
		$this->setChild($this->form);
	}

	public function value(){
		$this->form("text")->name("value")->label("value")->pl("به ریال");
		$this->validate()->price()->form->price("price value is not valid");
	}

	public function pay_type(){
		$this->form("radio")->name("pay_type")->label("pay_type");
		$this->setChild($this->form);
	}

	public function transactions(){
		$this->form("text")->name("transactions")->label("transactions");
		$this->validate()->transactions()->form->transactions("transactions is not valid");
	}

	public function description() {
		$this->form("#text_desc")->name("description");
		$this->validate()->description()->form->description("description must be between 3 and 255 charset");
	}

	
}	
?>