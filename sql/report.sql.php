<?php
/**
 * @author Reza Mohiti
 */
namespace sql;

class report {
	
	public $id    = array('type'=> 'int@10', 'autoI', 'label' => 'report_id');
	public $tables = array('type'=> 'varchar@255', 'label' => 'table');
	public $name  = array('type'=> 'varchar@255', 'label' => 'name');
	public $url   = array('type'=> 'varchar@255', 'label' => 'url');

	public function id() {
		$this->validate("id");
	}

	public function tables() {
		$this->form("#fatext")->name("tables")->label("tables");
		$this->validate();
	}
	public function name() {
		$this->form("#fatext")->name("name")->label("name");
		$this->validate();


	}
	public function url() {
		$this->form("#fatext")->name("url")->label("url");
		$this->validate();

	}

}
