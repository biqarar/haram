<?php 

/**
* @auther reza mohiti
*/
class model extends main_model {
		public function post_api(){

		$dtable = $this->dtable->table('person')
			->fields(
				'username users.username',
				'name',
				'family',
				'father',
				'birthday',
				'gender',
				'nationalcode',
				'code',
				// 'marriage',
				// 'education_id',
				'users_id detail',
				'users_id learn')
			->search_fields('name', 'family', 'father' , "username users.username" , "nationalcode person.nationalcode")
			->query(function($q){
				$q->joinUsers()->whereId("#person.users_id")->fieldUsername("username");
				$q->joinUsers_branch()->whereUsers_id("#users.id")->andType(($this->xuId("type") == "teacher") ? "teacher" : "operator");
					//---------- get branch id in the list
				$q->groupOpen();
				foreach ($this->branch() as $key => $value) {
					if($key == 0){
						$q->condition("and", "users_branch.branch_id","=",$value);
					}else{
						$q->condition("or","users_branch.branch_id","=",$value);
					}
				}
				$q->groupClose();
			})
			->search_result(function($result){
				$search = array('name', 'family', 'father' , "username users.username" , "nationalcode person.nationalcode");
				
				foreach ($search as $key => $value) {
					if(preg_match("/^[^\s]*\s(.*)$/", $value, $nvalue)){
						$search[$key] = $nvalue[1];
					}
				}
				$vsearch = $_POST['search']['value'];
				$ssearch = preg_split("[ ]", $vsearch);
				$vsearch = str_replace(" ", "_", $vsearch);
				$csearch = $search;
				foreach ($search as $key => $value) {
					$search[$key] = "IFNULL($value, '')";
				}
				$search  = join($search, ', ');
				$result->groupOpen();
				$result->condition("and", "##concat($search)", "LIKE", "%$vsearch%");
				foreach ($csearch as $key => $value) {
					if(isset($ssearch[$key])){
						$sssearch = $ssearch[$key];
						if($key === 0){
							$result->condition("OR", "##$value", "LIKE", "%$sssearch%");
						}else{
							$result->condition("AND", "##$value", "LIKE", "%$sssearch%");
						}
					}
				}
				$result->groupClose();
			})
			->result(function($r){
				$r->learn = $this->tag("a")->addClass("icoallteacher")->href('users/learn/id='. $r->learn)->render();
				$r->detail = $this->tag("a")->addClass("icomore")->href("users/status=detail/id=". $r->detail)->render();
			});
		$this->sql(".dataTable", $dtable);
	}	
}
?>