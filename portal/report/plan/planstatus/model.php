<?php 

/**
* @auther reza mohiti
*/
class model extends main_model {
	public function sql_classes($start_date = false, $end_date = false) {
	

	$classes = $this->sql()->tableClasses()
			->condition("where", "#start_date" , "<=", $this->sql(".duplicateUsersClasses.convert_date",$end_date))
			->condition("and", "#end_date", ">=", $this->sql(".duplicateUsersClasses.convert_date",$start_date));
	$classes->joinPlan()->whereId("#classes.plan_id")->fieldName('planname')->fieldMax_person("maxp");

	$classes = $classes->select()->allAssoc();
		// var_dump($classes);exit();
		$return = array();
		// $return = array("طرح","تعداد کلاس های تشکیل شده","تعداد نفرات شرکت کننده","میانگین هر کلاس");
	foreach ($classes as $key => $value) {
		// var_dump($value);exit();
		$return[$value['plan_id']]['planname'] = $value['planname'];

		isset($return[$value['plan_id']]['classescount']) ? 
			  $return[$value['plan_id']]['classescount']++ :
			  $return[$value['plan_id']]['classescount'] = 1;

		isset($return[$value['plan_id']]['classificationcount']) ? 
			  $return[$value['plan_id']]['classificationcount'] += intval($value['count']) :
			  $return[$value['plan_id']]['classificationcount'] = intval($value['count']);

		// $return[$value['plan_id']]['max_person'] = $value['maxp'];
		$return[$value['plan_id']]['average_classes'] = intval($return[$value['plan_id']]['classificationcount']) /  intval( $return[$value['plan_id']]['classescount']);
	}
	// var_dump($return);exit();
		return $return;

	}
}
?>