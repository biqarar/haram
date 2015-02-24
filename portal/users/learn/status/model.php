<?php 
/**
* 
*/
class model extends main_model {

	public function post_api(){
		$usersid = $this->xuId();

		$dtable = $this->dtable->table("classification")
		->fields("id", "plan", "teachername","teacherfamily","date_entry","date_delete","because", "id absence", "mark mark", "id certification")
		->search_fields("plan", "teacher")
		->query(function($q){
			$q->whereUsers_id($this->xuId());
			$q->joinClasses()->whereId("#classification.classes_id")->fieldId("classesid");
			$q->joinPlan()->whereId("#classes.plan_id")->fieldName("plan");
			$q->joinPerson()->whereUsers_id("#classes.teacher")->fieldName("teachername")->fieldFamily("teacherfamily");
		})
		->result(function($r) {
			$r->absence = $this->tag("a")->href("users/learn/absence/id=" . $this->xuId())->vtext($this->find_count_absence($r->absence))->render();
			$r->mark = $this->tag("a")->href("users/learn/score/id=". $this->xuId())->vtext($r->mark)->render();
			$r->certification = $this->tag("a")->href("users/learn/certification/id=" . 1)->vtext($this->find_status_certification($r->certification))->render();
		});
		$this->sql(".dataTable", $dtable);
	}

	public function find_status_certification($classificationid = false ) {

		$certification = $this->sql()->tableCertification()->whereClassification_id($classificationid)->limit(1)->select();
		return $certification->num();
		// if($certification->num() == 1) {
		// 	foreach ($certification->assoc() as $key => $value) {
		// 		print_r("key :" . $key . " val : " . $value . "\n");
		// 		if($value != "") {
		// 			// return $return;
		// 		}else{
		// 			$return =  ($key) . " : " . $value;
		// 		}
		// 	}
		// 	var_dump($return); exit();
		// }
	}
	public function find_count_absence($classificationid = false) {
		$absence = $this->sql()->tableAbsence()->whereClassification_id($classificationid)->select()->num();
		if($absence > 0) {
			return $absence;
		}
		return null;
	}


	public function sql_classification_list($usersid = false) {
		$return = array();
		$return['sum_active'] = 0;
		$return['sum_all'] = 0;
		$return['classes'] = array();
		$sql = $this->sql()->tableClassification()->whereUsers_id($usersid);
		$sql->joinClasses()->whereId("#classification.classes_id");
		$sql->joinPlan()->whereId("#classes.plan_id")->fieldName("planname");
		$sql->joinPlace()->whereId("#classes.place_id")->fieldName("placename");
		$sql->joinPerson()->whereUsers_id("#classes.teacher")->fieldName("teachername")->fieldFamily("teacherfamily");
		$x = $sql->select()->allAssoc();
		
		foreach ($x as $key => $value) {

			$return['sum_all']++;
			// if(empty($x[$key]['date_delete']) || $x[$key]['date_delete'] == ''){
				$return['sum_active']++;

				$return['classes'][$key]['string'] = $x[$key]['planname'] 		. '  ' .
								   _($x[$key]["age_range"]) 		. '  ' . 
								   $x[$key]['placename'] 		. ' ساعت ' .
								   $x[$key]['end_time']			. ' استاد ' .
								   $x[$key]["teachername"] 		. '  ' . 
								   $x[$key]['teacherfamily'];
				$return['classes'][$key]['id'] = $x[$key]["classes_id"];
				// }
								   
		}
		return $return;
	}
}
?>