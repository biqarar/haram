<?php
/**
* @author reza mohiti
*/
namespace sql;
class score_type {

	public $id          = array('type'=> 'int@10', 'autoI', 'label' => 'score_type_id');
	public $plan_id     = array('type'=> 'int@10', 'label' => 'plan_id');
	public $title       = array('type'=> 'varchar@255', 'label' => 'title');
	public $min         = array('type'=> 'int@3', 'label' => 'min');
	public $max         = array('type'=> 'int@3', 'label' => 'max');
	public $description = array('type'=> 'text@', 'label' => 'description');
	
	public $foreign = array("plan_id" => "plan@id!name");

	public function id() {
		$this->validate("id");
	}

	public function	plan_id(){
		$this->form("select")->name("plan_id")->addClass("select-plan-section")->addClass("notselect");
		$this->setChild(function($q){
			$list = isset($_SESSION['user']['branch']['selected']) ? 
						  $_SESSION['user']['branch']['selected'] : array();
			$q->groupOpen();
			foreach ($list as $key => $value) {
				if($key == 0){
					$q->condition("where", "plan.branch_id","=",$value);
				}else{
					$q->condition("or","plan.branch_id","=",$value);
				}
			}	
			$q->groupClose();
			
		}, function($child, $value){
			$child->label($value['name'])->value($value['id']); 
		});
	}

	public function	title(){
		$this->form("text")->name("title")->label("title")->required();
		$this->validate()->farsi()->form->farsi("title is not valid");
	}

	public function	min(){
		$this->form("text")->name("min")->label("min")->required();
		$this->validate()->float()->form->float("number is not valid");
	}

	public function	max(){
		$this->form("text")->name("max")->label("max")->required();
		$this->validate()->float()->form->float("number is not valid");
	}

	public function	description(){
		// $this->form("#text_desc")->name("description")->label("description");
	}


}

?>