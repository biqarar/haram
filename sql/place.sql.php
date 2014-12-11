<?php
namespace sql;
class place {
	public $id          = array('type'=> 'int@10', 'autoI', 'label' => 'place_id');
	public $name        = array('type'=> 'varchar@32', 'label' => 'place_name');
	public $branch_id   = array('type'=> 'int@10', 'label' => 'branch_id');
	public $description = array('type'=> 'varchar@255', 'label' => 'place_description');

	public $unique = array("name");
	public $index = array("branch_id");

	public $foreign = array("branch_id" => "branch@id!name");

	public function id() {
		$this->validate("id");
	}
	
	public function name() {
		$this->form("#fatext")->name("name");
	}
	
	public function branch_id() {
		$this->form("select")->name("branch_id");
		$this->setChild();
	}
	
	public function description() {
		$this->form("#text_desc")->name("description");
	}
}
?>