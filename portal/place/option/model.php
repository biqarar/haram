<?php
/**
 * @author reza mohiti rm.biqarar@gmail.com
 */
class model extends main_model{
	
	public function makeQuery() {
		//------------------------------ make object sql
		return $this->sql()->tablePlace()
				->setName(post::name())
				->setBranch_id(post::branch_id())
				->setDescription(post::description());
	}

	public function post_add_place() {

		//------------------------------ check duplicate entry
		$duplicate = $this->sql()->tablePlace()->whereName(post::name())->andBranch_id(post::branch_id())->select()->num();

		if($duplicate >= 1){
			$this->rollback(function() {
				debug_lib::fatal("[[duplicate entry for name]]");
			});
		}else{
			//------------------------------ insert place
			$sql = $this->makeQuery()->insert();
		}

		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[insert place successful]]");
		});

		//------------------------------ rollback code
		$this->rollback(function() {
			debug_lib::fatal("[[insert place failed]]");
		});
	}

	public function post_edit_place() {

		//------------------------------ update place
		$sql = $this->makeQuery()->whereId($this->xuId())->update();

		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[update place successful]]");
		});

		//------------------------------ rollback code
		$this->rollback(function() {
			debug_lib::fatal("[[update place failed]]");
		});
	}
}
?>