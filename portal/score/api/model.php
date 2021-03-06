<?php 
class model extends main_model {

	public function post_api(){


		$classificationid = $this->xuId("classificationid");
		$scoretypeid = $this->xuId("scoretypeid");
		$retest = $this->xuId("retest");

		

		//----------------- check branch
		$branch_score_type = $this->sql(".branch.score_type", $scoretypeid);
		$branch_classification_id = $this->sql(".branch.classification", $classificationid);

		if($branch_score_type != $branch_classification_id) {
			debug_lib::fatal("score_type and classification branch not match");
		}

		if($classificationid == 0 or $scoretypeid == 0 ) {
			debug_lib::fatal("خطا در اطلاعات");
		}

		$value = ($this->xuId("value"));


		$scoretype = $this->sql()->tableScore_type()->whereId($scoretypeid)->limit(1)->select()->assoc();

		if(intval($value) < intval($scoretype['min'])) {
			debug_lib::fatal("حد اقل امتیاز " . $scoretype['min']);
		}elseif(intval($value) > intval($scoretype['max'])) {
			debug_lib::fatal("حد اکثر امتیاز " . $scoretype['max']);
		}

		$check = $this->sql()->tableScore()
					->whereClassification_id($classificationid)
					->andScore_type_id($scoretypeid)->limit(1)->select();//->num();
		
		
		if($check->num() == 1) {
			$x = $this->sql()->tableScore()
							->whereClassification_id($classificationid)
							->andScore_type_id($scoretypeid)
							->setValue($value)
							->update();	

			if($retest == 'true'){
				$insert_log = $this->sql()->tableLogs()
								->setLog_data($check->assoc("value"))
								->setLog_meta("scoreretest/classificationid=$classificationid/scoretypeid=$scoretypeid")
								->setLog_status("enable")
								->insert();
								// var_dump($insert_log->string());exit();

			}
			

			$this->commit(function(){
				debug_lib::true("اطلاعات به روز رسانی شد");
			});
		}else{
			$x = $this->sql()->tableScore()
							->setClassification_id($classificationid)
							->setScore_type_id($scoretypeid)
							->setValue($value)
							->insert();	

			$this->commit(function(){
				debug_lib::true("اطلاعات ثبت شد");
			});
		}
	}
}
?>