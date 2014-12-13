<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class view extends main_view {

	public function config() {
		//------------------------------ globals
		$this->global->page_title = 'login';

		//------------------------------ load form
		$f = $this->form("@users");

		//------------------------------ set hidden value to get $_POST in model
		$f->hidden->value("login");
		
		//------------------------------ load captcha from
		if(isset($_SESSION['load_captcha']) && $_SESSION['load_captcha']) {
			$captcha = $this->form("captcha");
			$f->add("captcha", $captcha);
			$f->atEnd("submit");
		}

		if(isset($_SESSION['redirect']) && isset($_SESSION['users_id'])){
			$redirect = $_SESSION['redirect'];
			unset($_SESSION['redirect']);
			$this->redirect($redirect);
		}elseif(isset($_SESSION['users_id'])){
			$this->redirect("/profile");
			// header("Location: /profile");
		}
		//------------------------------ change caption of sumbit
		$f->submit->value("login");

		//------------------------------ remove email field 
		$f->remove("email,status,type");
	}
}
?>