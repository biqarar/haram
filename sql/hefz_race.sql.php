<?php
/**
* @author reza mohiti
*/
namespace sql;
class hefz_race {

	public $id = array("type" => "int@10", "label" => "hefz_race_id");
	public $type = array("type" => "enum@حذفی,دوره ای!حذفی", "label" => "hefz_race_type");
	public $hefz_team_id_1 = array("type" => "int@10", "label" => "hefz_race_hefz_team_id_1");
	public $hefz_team_id_2 = array("type" => "int@10", "label" => "hefz_race_hefz_team_id_2");
	public $manfi1 = array("type" => "float@", "lable"=> "hefz_race_result_value");
	public $manfi2 = array("type" => "float@", "lable"=> "hefz_race_result_value");
	
	public $name = array("type" => "varchar@255", "label" => "hefz_race_name");

	public $foreign = array("hefz_team_id_1"=> "hefz_teams@id!name", "hefz_team_id_2" => "hefz_teams@id!name");

	public function id() {

	}

	public function hefz_team_id_1() {
		$this->form("select")->name("hefz_team_id_1")->addClass("notselect")->required();
		$this->setChild(function($q){
			// var_dump($q);exit();	
			// if(!isset($_SESSION['supervisor'])){

				$list = isset($_SESSION['user']['branch']['selected']) ? 
							  $_SESSION['user']['branch']['selected'] : array();
				
				
				$q->joinHefz_ligs()->whereId("#hefz_teams.lig_id");
				$q->groupOpen();
				foreach ($list as $key => $value) {
					if($key == 0){
						$q->condition("where", "hefz_ligs.branch_id","=",$value);
					}else{
						$q->condition("or","hefz_ligs.branch_id","=",$value);
					}
				}	
				$q->groupClose();

		});
	}

	public function hefz_team_id_2() {
		$this->form("select")->name("hefz_team_id_2")->addClass("notselect")->required();
		$this->setChild(function($q){
			
			// if(!isset($_SESSION['supervisor'])){

				$list = isset($_SESSION['user']['branch']['selected']) ? 
							  $_SESSION['user']['branch']['selected'] : array();
				
				
				$q->joinHefz_ligs()->whereId("#hefz_teams.lig_id");

				$q->groupOpen();
				foreach ($list as $key => $value) {
					if($key == 0){
						$q->condition("where", "hefz_ligs.branch_id","=",$value);
					}else{
						$q->condition("or","hefz_ligs.branch_id","=",$value);
					}
				}	
				$q->groupClose();

		});
	}

	// public function place() {
	// 	$this->form("#fatext")->name("place");
	// 	$this->validate()->farsi()->form->farsi("teams name must be persian");
	// }

	public function type() {
		$this->form("select")->name("type")->addClass("notselect")->required();
		$this->setChild();
	}

	public function name() {
		$this->form("#fatext")->name("name")->label("توضیحات");
		$this->validate()->farsi()->form->farsi("teams name must be persian");
	}

	public function manfi1(){}
	public function manfi2(){}


}

?>