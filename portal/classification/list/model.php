<?php
class model extends main_model {
	public function post_api() {
		$dtable = $this->dtable->table("classification")
			
			->fields(
			"username users.username",
			"name person.name",
			"family person.family",
			"date_entry",
			"date_delete",
			"because",
			"id edit")
			
			->search_fields("username", "name", "family")
			->query(function($q){

				$q->andClasses_id($this->xuId("classesid"));
				$q->joinPerson()->whereUsers_id("#classification.users_id")->fieldName("name")->fieldFamily("family");
				$q->joinUsers()->whereId("#classification.users_id")->fieldUsername("username");
			
			})
			->search_result(function($result){
				$vsearch = $_GET['search']['value'];
				$vsearch = str_replace(" ", "_", $vsearch);
				$result->condition("and", "##concat(person.name, person.family)", "LIKE", "%$vsearch%");
			})
			->result(function($r){
				$r->edit = $this->tag("a")->addClass("icoedit")->href("classification/status=edit/id=". $r->edit)->render();
			});
			$this->sql(".dataTable", $dtable);
	}
}
?>