
<?php
/**
 * @author reza mohiti rm.biqarar@gmail.com
 */
class view extends main_view {

	public function config(){

		//------------------------------ globals
		$this->global->page_title ="teachinghistory";

		//------------------------------ url
		$this->global->url = ($this->xuId("status") == "add") ? 
				"status=add/usersid=" . $this->xuId("usersid") :
				"status=edit/id=" . $this->xuId();
				
		//------------------------------  load form		
		$f = $this->form("@teachinghistory", $this->urlStatus());

		//------------------------------  edit form
		$this->sql(".edit", "teachinghistory", $this->xuId(), $f);
	}
}
?>