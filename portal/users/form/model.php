<?php
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class model extends main_model {
	public function post_form_questions() {
		$post = $_POST;
		// print_r($post);
		$i = 1;
		$question_id = array();
		$answer = array();
		foreach ($post as $key => $value) {
			if(preg_match("/^Q(\d+)$/", $key)) {
				preg_match("/^Q(\d+)$/", $key, $c);
				$question_id[$i] = $c[1];
				$answer[$i] = $value;
				$i++;
			}
		}
		$users_id = $this->xuId("usersid");
		$form_id = $this->xuId("formid");

		foreach ($answer as $key => $value) {
			// print_r($value);
		$this->sql()->tableForm_answer()
				->setUsers_id($users_id)
				->setForm_questions_id($question_id[$key])
				->setAnswer($value)
				->insert()->LAST_INSERT_ID();
		}
		$this->commit(function() {
			debug_lib::true("اطلاعات با موفقیت ثبت شد");
		});
	}
}
?>