<?php 
/**
 * 
 */
class view extends main_view  {
	public function config() {
		//------------------------------  global
		$this->global->page_title = _("پرونده تحصیلی") . " " . _("student");

		//------------------------------  set users_id
		$users_id  = $this->xuId();

			//----------------------- check banch
		$this->sql(".branch.users",$users_id);

		$classesid = $this->xuId("classesid");
		//------------------------------ get detail classes
		$this->classesDetail($classesid);
		$chart = $this->sql("#progress", $users_id, $classesid);
		$this->data->chart = $chart;

		$chart = $this->sql("#progress", $users_id, $classesid, "month");
		// exit();
		$this->data->chart_month = $chart;
		// var_dump($chart);exit();`
	}
} 
?>