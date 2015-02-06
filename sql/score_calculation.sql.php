<?php
/**
* @author reza mohiti
*/
namespace sql;
class score_calculation {

	public $id          = array('type'=> 'int@10', 'autoI', 'label' => 'score_calculation_id');
	public $plan_id     = array('type'=> 'int@10', 'label' => 'plan_id');
	public $calculation = array('type'=> 'text@', 'label' => 'calculation');
	public $status       = array('type'=> 'enum@active,desactive', 'label' => 'status');
	public $description = array('type'=> 'text@', 'label' => 'description');
	

	public $foreign = array("plan_id" => "plan@id!name");

	public function id() {
		$this->validate("id");
	}

	public function	plan_id(){
		$this->form("select")->name("plan_id")->addClass("select-plan-section")->addClass("notselect");
		$this->setChild(function($q){
			
		}, function($child, $value){
			$child->label($value['name'])->value($value['id']); 
		});
	}



	public function	calculation(){
		$this->form("#text_desc")->name("calculation")->label("calculation");
		$this->validate();
	}

	public function	status(){
		$this->form("select")->name("status")->label("status");
		$this->setChild($this->form);
	}

	public function	description(){
		$this->form("#text_desc")->name("description")->label("description");
	}


}

?>