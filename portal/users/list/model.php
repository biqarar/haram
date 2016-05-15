<?php 
/**
* 
*/
class model extends main_model {
	public function post_api(){
		$type = $this->xuId("type");
		
		// $this->branch();
		// $type = isset($type) && $type != "" ? $type : "learn";
			// var_dump($type);exit();
		$dtable = $this->dtable->table('person')
			->fields(
				'username',
				'name',
				'family',
				'father',
				'birthday',
				// 'gender',
				'nationalcode',
				'code',
				// 'marriage',
				// 'education_id',
				'users_id detail',
				'users_id learn',
				'users_id')
			->order(function($q, $n, $b){
				if($n === 'orderUsername'){
					$q->join->users->orderUsername($b);
				}else{
					return true;
					$q->orderId($b);
				}
			})
			->search_fields('name', 'family', 'father' , "username users.username" , "nationalcode person.nationalcode")
			->query(function($q){
				$q->joinUsers()->whereId("#person.users_id")->fieldUsername("username");
				$q->joinUsers_branch()->whereUsers_id("users.id");
				$q->groupOpen();
				//---------- get branch id in the list
				foreach ($this->branch() as $key => $value) {
					if($key == 0){
						$q->condition("and", "users_branch.branch_id","=",$value);
					}else{
						$q->condition("or","users_branch.branch_id","=",$value);
					}
				}
				$q->groupClose();
				// echo ($q->select()->string());exit();
			})
			// ->search_result(function($result){
			// 	$vsearch = $_GET['search']['value'];
			// 	$vsearch = str_replace(" ", "", $vsearch);
			// 	$result->groupOpen();
			// 	$result->condition("or", "##concat(person.name, person.family, person.father)", "LIKE", "%$vsearch%");
			// 	$result->groupClose();
			// 	$result->condition("or", "users.username", "LIKE", "%$vsearch%");
			// 	$result->condition("or", "person.nationalcode", "LIKE", "%$vsearch%");
			// 	// print_r($result);exit();
			// 	// $result->condition("or" "#person.s", "LIKE" "%$vsearch%");
			// })
			->result(function($r, $type){
				$r->learn = $this->type($type , $r->learn);
				$r->detail = $this->tag("a")->addClass("icomore")->href("users/status=detail/id=". $r->detail)->render();
			}, $type);
		$this->sql(".dataTable", $dtable);
	}	

	public function type($type = false, $id = false) {
		// var_dump($type, $id);exit();
		switch ($type) {
			case 'price':
				return $this->tag("a")
				->addClass("icoprice")->href('price/status=add/usersid='. $id)->render();
				break;
			case 'branch':
				return $this->tag("a")
				->addClass("icosettings")->href('branch/status=change/usersid='. $id)->render();
				break;
			
			default:
				return $this->tag("a")->addClass("icoshare")->href('users/learn/id='. $id)->render();
				break;
		}
	}
}
?>