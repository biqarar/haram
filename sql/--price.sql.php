<?php
namespace sql;
class price {
	public $id           = array('type'=> 'int@10', 'autoI', 'label' => 'price_id');
	public $users_id     = array('type'=> 'int@10', "label" => "users_id");
	public $date		 = array("type" => "int@8", "label" => "date");
	public $title		 = array('type' => "int@10", "price_title");
	public $card         = array('type'=> 'int@7', "label" => "card");
	public $value        = array('type'=> 'int@7', "label" => "price_value");
	public $transactions = array('type'=> 'varchar@255', "label" => "price_transactions");
	public $pay_type     = array('type'=> 'enum@pos_mellat,pos_melli,rule', "label" => "pay_type");
	public $type    	 = array('type'=> 'enum@common,plan!commod', "label" => "type");
	public $plan_id    	 = array('type'=> 'int@10', "label" => "plan_id");
	public $description  = array('type'=> 'text@' , "label" => "description");
	public $status    	 = array('type'=> 'enum@active,void!active', "label" => "status");
	
	public $foreign = array("title" => "price_change@id!name", "plan_id" => "plan@id!name");
	
	public function id(){
		$this->validate("id");
	}

	public function users_id(){
		$this->validate("id");
	}

	public function date() {
		$this->form("#date")->name("date")->label("date");
		$this->validate()->date()->form->date("date incorect");
	}

	public function title(){
		$this->form("select")->name("title")->id("title")->addClass("select-title notselect")->label("type");
		$this->setChild(function($q){
			// var_dump($q);
		}, function($child, $value){
			$child->label($value['name'])->value($value['id']); 
		});
	}


	public function card(){
		$this->form("text")->name("card")->label("card")->pl("4 رقم آخر شماره کارت");
		$this->validate()->number()->form->number("price card is not valid");
	}

	public function value(){
		$this->form("text")->name("value")->label("مبلغ")->pl("به ریال");
		$this->validate()->price()->form->price("price value is not valid");
	}

	public function type(){
		$this->form("radio")->name("type")->label("type");
		$this->setChild($this->form);
	}

	public function plan_id(){
		$this->form("select")->name("plan_id")->label("plan_id")->class("notselect");
		$this->setChild();
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
		$this->validate()->description(-1)->form->description("description must be between 3 and 255 charset");
	}

	public function status(){
		$this->form("radio")->name("status")->label("status");
		$this->setChild($this->form);
	}

}	
?>