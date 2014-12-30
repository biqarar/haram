<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class model extends main_model {

	public function sql_branch_name($branch_id) {
		return $this->sql()->tableBranch()->whereId($branch_id)->limit(1)->select()->assoc("name");
	}

	public function post_settings() {
		unset($_SESSION['branch_active']);
		$_SESSION['branch_active'] = array();
		foreach ($_POST as $key => $value) {
			if(preg_match("/^branch_(\d+)$/", $key, $branch_id)) {			
				$_SESSION['branch_active'][] = $branch_id[1];
			}	
		}
	}
}
?>