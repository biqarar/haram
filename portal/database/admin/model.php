<?php 
/**
 * @author reza mohitit rm.biqarar@gmail.com
 */
// include_once("databaseVersion.php");


class model extends main_model{

	public $start_time;
	public $end_time;
	public $all_tiem;
	public $i = 0;
	public $title = "";
	public $version;

	public function xecho($str = false) {echo "<pre><br>" . $str . "</pre>";}
	
	public function ready($version = "new version") {

		$this->xecho("In The Name Of Allah");
		if(!isset($_GET['password']) || $_GET['password'] != 'ali110') {
			$this->xecho("password incorect.");
			exit(); die();
		}else{

			$this->xecho("Checking password ....");
			$this->xecho("Password OK");
		
			set_time_limit(30000);
			ini_set('memory_limit', '-1');
			ini_set("max_execution_time", "-1");
		
			if (ob_get_level() == 0) ob_start();

			$this->xecho( "Starting ... ");
			$this->xecho( "Set time start as : " . time());

			$this->xecho( "Set version $version in mysql ");

			$this->start_time = time();
			$this->version = $version;
		}
	}

	public function count() {
		$sql = new dbconnection_lib;
		$sql::$resum_on_error = true;
		$sql->query("COMMIT");
		$this->i++;
	}

	public function title($title) {
		$this->xecho(" -------------------------------------------- $title ... ");
		$this->title = $title;
	}

	public function set_version_history($version = 0 , $query = false) {
		$sql = new dbconnection_lib;
		$sql::$resum_on_error = true;
		$s = $sql->query("INSERT INTO 
			`quran_hadith_log`.`database_version` 
			(`id`, `version`, `query`, `time`) 
			VALUES (NULL, '$version', '$query', CURRENT_TIMESTAMP)");
		$this->xecho( "Saved in History (table database_version)");
	}

	public function flush() {
		$this->set_version_history($this->version, $this->title . " ( " . $this->i . " record)");
		$this->xecho( "num = " . $this->i );
		$this->xecho(" -------------------------------------------- ");
		$this->i = 0;
		ob_flush();
		flush();
	}

	public function end() {
		$this->xecho( " --------------------------------------------  End :)   ");
		$this->end_time = time();
		$this->xecho(" End time : " . $this->end_time);
		$this->all_tiem = intval($this->end_time) - intval($this->start_time);

        $this->xecho( "<div style='background :green'><br><br> all perosses ended 
        	in :" . $this->all_tiem   .  "   sec <br><br><br></div></pre>");
		ob_end_flush();
		exit(); die();
	}

	public function sql_admin() {
		//---------------------------------------------------------------------------------------------------
		$this->ready(10);

		//----------------------------- new version function (database change)	
		$this->database_change();		
		
		//----------------------------- new version function (query on record)
		$this->query_on_record();
		
		//---------------------------------------------------------------------------------------------------
		$this->end();

	}

	public function query_on_record() {
		$new_database = array(
			"CREATE DATABASE `quran_hadith_log` DEFAULT CHARSET=utf8 COLLATE utf8_general_ci;",

			"DROP TRIGGER IF EXISTS `database_version_delete`",
			"DROP TRIGGER IF EXISTS `database_version_insert`",
			"DROP TRIGGER IF EXISTS `database_version_update`",
			"ALTER TABLE `quran_hadith`.`database_version` RENAME `quran_hadith_log`.`database_version`",

			"ALTER TABLE `quran_hadith`.`history` RENAME `quran_hadith_log`.`history`",

			"DROP TRIGGER IF EXISTS `update_log_delete`",
			"DROP TRIGGER IF EXISTS `update_log_insert`",
			"DROP TRIGGER IF EXISTS `update_log_update`",
			"ALTER TABLE `quran_hadith`.`update_log` RENAME `quran_hadith_log`.`update_log`",

			"DROP TRIGGER IF EXISTS `branch_cash_delete`",
			"DROP TRIGGER IF EXISTS `branch_cash_insert`",
			"DROP TRIGGER IF EXISTS `branch_cash_update`",
			"ALTER TABLE `quran_hadith`.`branch_cash` RENAME `quran_hadith_log`.`branch_cash`",
			

			"DROP TRIGGER IF EXISTS `oldcertification_delete`",
			"DROP TRIGGER IF EXISTS `oldcertification_insert`",
			"DROP TRIGGER IF EXISTS `oldcertification_update`",
			
			"DROP TRIGGER IF EXISTS `oldprice_delete`",
			"DROP TRIGGER IF EXISTS `oldprice_insert`",
			"DROP TRIGGER IF EXISTS `oldprice_update`",

			"DROP TRIGGER IF EXISTS `oldclassification_delete`",
			"DROP TRIGGER IF EXISTS `oldclassification_insert`",
			"DROP TRIGGER IF EXISTS `oldclassification_update`",

			"DROP TRIGGER IF EXISTS `student_delete`",
			"DROP TRIGGER IF EXISTS `student_insert`",
			"DROP TRIGGER IF EXISTS `student_update`",
			
			"DROP TRIGGER IF EXISTS `oldclasses_delete`",
			"DROP TRIGGER IF EXISTS `oldclasses_insert`",
			"DROP TRIGGER IF EXISTS `oldclasses_update`",

			"CREATE DATABASE `quran_hadith_old` DEFAULT CHARSET=utf8 COLLATE utf8_general_ci;",


			"ALTER TABLE `quran_hadith`.`oldprice` RENAME `quran_hadith_old`.`oldprice`",
			"ALTER TABLE `quran_hadith`.`oldclasses` RENAME `quran_hadith_old`.`oldclasses`",
			"ALTER TABLE `quran_hadith`.`oldclassification` RENAME `quran_hadith_old`.`oldclassification`",
			"ALTER TABLE `quran_hadith`.`oldcertification` RENAME `quran_hadith_old`.`oldcertification`",
			"ALTER TABLE `quran_hadith`.`student` RENAME `quran_hadith_old`.`student`",


			"DROP PROCEDURE IF EXISTS `setHistory`",

			"CREATE DEFINER=`root`@`localhost` PROCEDURE `setHistory`(IN `_Table` VARCHAR(64), IN `_operator` VARCHAR(10), IN `_id` INT(10))
			    NO SQL
			BEGIN
			INSERT INTO `quran_hadith_log`.`history` 
			(users_id,history.table,query_type,record_id,date,ip)
			VALUES
			(@users_id, _Table ,_operator ,_id,CURRENT_TIMESTAMP(),@ip_);
			END",


			);

		$this->run($new_database);

		// ---------------------------------------------------------------------------------------------------

	}

	public function database_change() {
		/**
		* database change
		* CREATE DATABASE `quran_hadith` DEFAULT CHARSET=utf8 COLLATE utf8_general_ci;
		* ALTER TABLE `quran_hadith`.`oldprice` RENAME `quran_hadith_old`.`oldprice`
		*/
				
		$sql = new dbconnection_lib;


		$database_change = array(
			

			"ALTER TABLE `plan`  ADD `branch_id` INT(10) NULL",
			"update plan as us 
			inner join branch_cash as br on us.id = br.record_id and br.table = 'plan' 
			set us.branch_id = br.branch_id",

			"ALTER TABLE `plan`  CHANGE `branch_id`  `branch_id` INT(10) NOT NULL",
			
			"ALTER TABLE `plan` ADD CONSTRAINT `plan_branch_id_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`)",


			
			"ALTER TABLE `course`  ADD `branch_id` INT(10) NULL",
			"update course as us 
			inner join branch_cash as br on us.id = br.record_id and br.table = 'course' 
			set us.branch_id = br.branch_id",

			"ALTER TABLE `course`  CHANGE `branch_id`  `branch_id` INT(10) NOT NULL",

			"ALTER TABLE `course` ADD CONSTRAINT `course_branch_id_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`)",




			"ALTER TABLE `place`  ADD `branch_id` INT(10) NULL",


			"update place as us 
			inner join branch_cash as br on us.id = br.record_id and br.table = 'place' 
			set us.branch_id = br.branch_id",

			"ALTER TABLE `place`  CHANGE `branch_id`  `branch_id` INT(10) NOT NULL",

			"ALTER TABLE `place` ADD CONSTRAINT `place_branch_id_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`)",

			"ALTER TABLE `group`  ADD `branch_id` INT(10) NULL",

			"update `group` as us 
			inner join branch_cash as br on us.id = br.record_id and br.table = 'group' 
			set us.branch_id = br.branch_id",


			"ALTER TABLE `group`  CHANGE `branch_id`  `branch_id` INT(10) NOT NULL",
			
			"ALTER TABLE `group` ADD CONSTRAINT `group_branch_id_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`)",
 			

 			"ALTER TABLE `permission`  ADD `users_branch_id` INT(10) NULL AFTER `users_id`",


			"update `permission` as perm 
			inner join users_branch as ub  on perm.users_id = ub.users_id
			set perm.users_branch_id = ub.id",


			"ALTER TABLE `permission`  CHANGE `users_branch_id`  `users_branch_id` INT(10) NOT NULL",
			"ALTER TABLE `permission` ADD CONSTRAINT `permission_branch_id_ibfk_1` FOREIGN KEY (`users_branch_id`) REFERENCES `users_branch` (`id`)",
			
			"ALTER TABLE `permission` DROP FOREIGN KEY `permission_ibfk_2`",

			"ALTER TABLE permission DROP INDEX users_id",

			"ALTER TABLE `permission` DROP INDEX `table`",
			
			"ALTER TABLE `permission` DROP `users_id`",

			"ALTER TABLE `permission` ADD UNIQUE (`users_branch_id`, `tables`)",


			"CREATE TABLE `logs` (
			  `id` bigint(20) UNSIGNED NOT NULL,
			  `user_id` int(10) UNSIGNED DEFAULT NULL,
			  `log_data` varchar(200) DEFAULT NULL,
			  `log_meta` mediumtext,
			  `log_status` enum('enable','disable','expire','deliver') DEFAULT NULL,
			  `log_createdate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  `date_modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;",

			"ALTER TABLE `logs`
			  ADD PRIMARY KEY (`id`),
			  ADD KEY `logs_users_id` (`user_id`) USING BTREE;",

  			"ALTER TABLE `logs`
  				MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;",

			"ALTER TABLE `group` DROP INDEX `name`, ADD UNIQUE `name` (`name`, `branch_id`) USING BTREE;",

			"ALTER TABLE `users_branch` ADD `type` ENUM('student','teacher','operator') NOT NULL DEFAULT 'student'",

			"update `users_branch` as br 
			inner join users as us on us.id = br.users_id  
			set br.type = us.type",

			"ALTER TABLE `users` DROP `type`",

			"ALTER TABLE `users_branch` ADD `status` ENUM('waiting','block','delete','enable') NOT NULL DEFAULT 'waiting'",
			"update `users_branch` as br 
			inner join users as us on us.id = br.users_id  
			set br.status = us.status",


			"ALTER TABLE `users` DROP `status`",
			
		


			"ALTER TABLE `place` ADD `status` ENUM('enable','disable') NOT NULL DEFAULT 'enable' AFTER `multiclass`",
			"ALTER TABLE `place` CHANGE `name` `name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL COMMENT 'مدرس'",

			"ALTER TABLE `users_branch` CHANGE `branch_id` `branch_id` INT(10) NULL",

			"INSERT INTO `group` (`id`, `name`, `branch_id`) VALUES (NULL, 'معارف قرآن و حدیث', '2');",
			"INSERT INTO `group` (`id`, `name`, `branch_id`) VALUES (NULL, 'علوم و فنون قرائات', '2');",
			"INSERT INTO `group` (`id`, `name`, `branch_id`) VALUES (NULL, 'حفظ قرآن و حدیث', '2');",
			"INSERT INTO `group` (`id`, `name`, `branch_id`) VALUES (NULL, 'تربیت مربی قرآن کریم', '2');",
			"INSERT INTO `group` (`id`, `name`, `branch_id`) VALUES (NULL, 'پرورشی', '2');",
			"update plan  
				inner join branch_cash on branch_cash.record_id = plan.id and
 				branch_cash.table ='plan' and
  				branch_cash.branch_id = 2
  				set group_id = (select `id` from `group` 
  				where group.branch_id = 2 and group.name = 'معارف قرآن و حدیث')
				WHERE plan.group_id = 1 ",

			"update plan  
				inner join branch_cash on branch_cash.record_id = plan.id and
 				branch_cash.table ='plan' and
  				branch_cash.branch_id = 2
  				set group_id = (select `id` from `group` 
  				where group.branch_id = 2 and group.name = 'علوم و فنون قرائات')
				WHERE plan.group_id = 2 ",
				
			"update plan  
				inner join branch_cash on branch_cash.record_id = plan.id and
 				branch_cash.table ='plan' and
  				branch_cash.branch_id = 2
  				set group_id = (select `id` from `group` 
  				where group.branch_id = 2 and group.name = 'حفظ قرآن و حدیث')
				WHERE plan.group_id = 3 ",
				
			"update plan  
				inner join branch_cash on branch_cash.record_id = plan.id and
 				branch_cash.table ='plan' and
  				branch_cash.branch_id = 2
  				set group_id = (select `id` from `group` 
  				where group.branch_id = 2 and group.name = 'تربیت مربی قرآن کریم')
				WHERE plan.group_id = 4 ",

			"update plan  
				inner join branch_cash on branch_cash.record_id = plan.id and
 				branch_cash.table ='plan' and
  				branch_cash.branch_id = 2
  				set group_id = (select `id` from `group` 
  				where group.branch_id = 2 and group.name = 'پرورشی')
				WHERE plan.group_id = 5 ",

				"UPDATE `price_change` SET `branch_id` = '1' WHERE `price_change`.`id` = 7",

				"ALTER TABLE `files` CHANGE `description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_persian_ci NULL",
				"ALTER TABLE `files` CHANGE `file_tag_id` `file_tag_id` INT(10) NULL",


				"ALTER TABLE `quran_hadith`.`users_branch` DROP INDEX `users`, ADD UNIQUE `users` (`users_id`, `branch_id`, `type`) USING BTREE",

				"ALTER TABLE `person` CHANGE `gender` `gender` ENUM('male','female') CHARACTER SET utf8 COLLATE utf8_persian_ci NULL DEFAULT NULL COMMENT 'جنسیت'",



				



			


		);
		$xname = array(
				1=> 'پرداخت شهریه',
				2=> 'انتقال از دوره قبل به دوره جدید',
				3=> 'تشویق',
				4=> 'جریمه',
				5=> 'شرکت در کلاس',
				6=> 'شرکت با هماهنگی',
				7=> 'خدمات آستان مقدس',
				8 => 'پرداخت دوره ای'
				);
		foreach ($xname as $f => $name) {

			$xbranch = [2,3,9,10];
			foreach ($xbranch as $key => $value) {
				if($name == "شرکت در کلاس" || $name == 'جریمه'){
					$x = "INSERT INTO `price_change`(`name`, `type`, `branch_id`) VALUES ('$name','price_low','$value')";
				}else{
					
					$x = "INSERT INTO `price_change`(`name`, `type`, `branch_id`) VALUES ('$name','price_add','$value')";
				}
				// $xchange = array()
			$y = "update price  
				inner join branch_cash on branch_cash.record_id = price.id and
 				branch_cash.table ='price' and
  				branch_cash.branch_id = $value
  				set title = (select `id` from `price_change` 
  				where price_change.branch_id = $value and price_change.name = '$name')
				WHERE price.title = $f ";
				array_push($database_change, $x);
				array_push($database_change, $y);
			}
			
		}
		$xdrop =array(
			"ALTER TABLE `course` DROP FOREIGN KEY `course_ibfk_2`",
			"ALTER TABLE `course` DROP `expert`",
			"DROP TABLE `dev`",
			"DROP TABLE `form_answer`",
			"DROP TABLE `form_group`",
			"DROP TABLE `form_group_item`",
			"DROP TABLE `form_questions`",
			"DROP TABLE `nezarat_program_item`",
			"DROP TABLE `nezarat_item`",
			"DROP TABLE `nezarat_program`",
			"DROP TABLE `branch_description`",
			"DROP TABLE `branch_description`",
			"DROP TABLE `consultation_list`",
			"DROP TABLE `consultation`",
			"DROP TABLE `course_description`",
			"DROP TABLE `sms_log`",
			"DROP TABLE `drafts`",
			"DROP TABLE `education_users`",
			"DROP TABLE `experiences`",
			"DROP TABLE `graduate_classes`",
			"DROP TABLE `graduate`",
			"DROP TABLE `pending_classes`",
			"DROP TABLE `users_group`",
			"DROP TABLE `group_list`",
			"DROP TABLE `group_expert`",
			"DROP TABLE `prerequisite`",
			"DROP TABLE `tables`",
			"DROP TABLE `position`",	
			"DROP TABLE `regulation`",
			"DROP TABLE `position`",
			"DROP TRIGGER IF EXISTS `class_delete`",
			"DROP TRIGGER IF EXISTS `class_insert`",
			"DROP TRIGGER IF EXISTS `class_update`",
			"DROP TRIGGER IF EXISTS `certification_unique`",
			"DROP TRIGGER IF EXISTS `gorup_update`",
			"DROP TRIGGER IF EXISTS `news_delete`",
			"DROP TRIGGER IF EXISTS `news_group_delete`",
			"DROP TRIGGER IF EXISTS `news_group_insert`",
			"DROP TRIGGER IF EXISTS `news_group_update`",
			"DROP TRIGGER IF EXISTS `news_insert`",
			"DROP TRIGGER IF EXISTS `news_tags_delete`",
			"DROP TRIGGER IF EXISTS `news_tags_insert`",
			"DROP TRIGGER IF EXISTS `news_tags_update`",
			"DROP TRIGGER IF EXISTS `news_update`",
			"DROP TRIGGER IF EXISTS `prerequsite_upate`",
			"DROP TRIGGER IF EXISTS `race_history_delete`",
			"DROP TRIGGER IF EXISTS `race_history_insert`",
			"DROP TRIGGER IF EXISTS `race_history_update`",
			"DROP TRIGGER IF EXISTS `requlation_delete`",
			"DROP TRIGGER IF EXISTS `usrers_group_delete`",
			);

		$this->run($xdrop);
		
		// echo "<pre>";
		// print_r($database_change);exit();
		$list_table = $sql->query("SELECT * FROM `information_schema`.`TABLES` WHERE `TABLE_SCHEMA` LIKE 'quran_hadith'");

		$list_table = $list_table->allAssoc('TABLE_NAME');

		foreach ($list_table as $key => $value) {
			if(
				$value == "history" || 
				$value == "branch_users_key" || 
				$value == "branch_cash" || 
				$value == "logs" 
				// $value == "city" || 
				// $value == "province" || 
				// $value == "country" || 
				// $value == "education" || 
				
				) {

			}else{
				$this->run($this->trigger($value));
			}
		}

		$this->run($database_change);


	}

	public function run($array =false) {
		
		$sql = new dbconnection_lib;

		$error = 0;
		$all = 0;

		foreach ($array as $key => $value) {
			$this->title($value);
			$s = $sql->query($value);
			$this->xecho( "<b>Result:</b>". $sql->result . "\n");
			if(!$sql->result){
				$this->xecho( "<div style='background :red'> -- Error-- ");
				$this->xecho( "<b>Error number:</b>". $sql::$connection->errno  . "\n");
				$this->xecho( "<b>String error:</b>".  $sql::$connection->error . "\n");
				$this->xecho( "<b> -- Error-- </b></div>");
				$error++;
			}
			$this->flush();
			$all++;
		}

			$this->xecho( "<div style='background :green'>done.  Database set on version ". $this->version ." 
			 all perosses =  <b> $all </b>    
			 by <b> $error </b> error </div></pre>");

	}


	public function trigger($table = false) {
			return  array(

			// "ALTER TABLE `$table` ADD `date_insert` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ",

			// "ALTER TABLE `$table` ADD `date_modified` TIMESTAMP on update CURRENT_TIMESTAMP  NULL ",
			
				//-----------------------------------------------------------------------------
			"DROP TRIGGER IF EXISTS `" . $table . "_insert`",
			//-----------------------------------------------------------------------------
			"CREATE TRIGGER `" . $table . "_insert` AFTER INSERT ON `$table`
			FOR EACH ROW BEGIN
			call setHistory('$table', 'insert', NEW.id);
			END",

					//-----------------------------------------------------------------------------
			"DROP TRIGGER IF EXISTS `" . $table . "_update`",
			
			//-----------------------------------------------------------------------------
			"CREATE TRIGGER `" . $table . "_update` AFTER UPDATE ON `$table`
			FOR EACH ROW BEGIN
			call setHistory('$table', 'update', OLD.id);
			END",

			//-----------------------------------------------------------------------------
			"DROP TRIGGER IF EXISTS `" . $table . "_delete`",
			
			// -----------------------------------------------------------------------------
			"CREATE TRIGGER `" . $table . "_delete` AFTER DELETE ON `$table`
			FOR EACH ROW BEGIN
			call setHistory('$table', 'delete', OLD.id);
			END"
			);
	} 


}
?>