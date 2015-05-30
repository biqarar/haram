<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class model extends main_model {
	
	public function post_setrunning(){
		$classes_id = $this->xuId("classesid");

		if($this->sql()->tableClasses()->whereId($classes_id)->limit(1)->select()->assoc("status") == "running"){
			debug_lib::fatal("این کلاس هم اکنون در حال اجرا می باشد");
		}

		// $scoreCalculation = $this->sql()->tableClasses()->whereId($classes_id);
		// $scoreCalculation->joinScore_calculation()->wherePlan_id("#classes.plan_id");
		// $scoreCalculation = $scoreCalculation->limit(1)->select()->assoc();
		
		// if(!$scoreCalculation) {
		// 	debug_lib::fatal("روش محاسبه امتیاز نهایی ثبت نشده است");
		// }

		// $this->score($classes_id);

		$this->sql(".price.runningClasses", $classes_id);

		// // $classification = $this->sql()->tableClassification()->whereClasses_id($classes_id);
		// // $classification = $this->classification_finde_active_list($classification);
		// // $classification->setDate_delete($this->dateNow())->setBecause("running")->update();

		// $classification = $this->sql()->tableClassification()
		// ->whereClasses_id($classes_id)
		// ->groupOpen()
		// ->andBecause("absence")
		// ->orBecause("cansel")
		// ->orBecause("error_in_insert")
		// ->groupClose()
		// ->setMark(0)->update();
		
		// $this->sql(".classesCount", $classes_id);
		
		$this->sql()->tableClasses()->whereId($classes_id)->setStatus("running")->update();

		debug_lib::true("فعال سازی مجدد کلاس انجام شد");

	}

	public function score($classes_id = false) {
		$result = $this->sql(".scoreCalculation.score_classes", $classes_id);

		if($result && is_array($result)){

			foreach ($result as $key => $value) {
				$this->sql()->tableClassification()->whereUsers_id($key)->andClasses_id($classes_id)
					->setMark(
						($value['result'] && 
						$value['result'] != "" && 
						$value['result'] != null) ? $value['result'] : 0)->update();

			}

		}

	}
		
}
?>