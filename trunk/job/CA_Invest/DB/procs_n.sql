DELIMITER //
-- -------------------------------------------------------------------------------------------------
-- -------------------------------------------------------------------------------------------------
 



-- REMARK. Min time is not used!!!
DROP PROCEDURE IF EXISTS `get_any_agenda_month`//

CREATE PROCEDURE `get_any_agenda_month`(IN `p_d_t_now` TIMESTAMP,
										IN `p_month` TINYINT,
										IN `p_year` SMALLINT,
										IN `p_app_type_id` INT,
										IN `p_ag_id` INT,
										IN `p_cat_id` INT,
										IN `p_org_code` VARCHAR(50),
										IN `p_debug` BOOLEAN )
	COMMENT 'gets any available agenda user for selected month and year'
	NOT DETERMINISTIC
BEGIN
	DECLARE `v_ag_id` INT;
	DECLARE	`v_ag_start_time`, `v_ag_end_time` TIME;
	DECLARE `v_first_date`, `v_last_date`, `v_prv_date`, `v_cur_date`, `v_nxt_date`, `v_anx_date`, `v_aax_date`, `del_date`, `v_app_type_last_date` DATE;
	DECLARE	`v_today` DATE DEFAULT DATE_FORMAT( `p_d_t_now`, '%Y-%m-%d' );
	DECLARE	`v_n_get_day` INT DEFAULT 3;

	DECLARE  `v_max_d_t` TIMESTAMP;

	DECLARE `v_result` VARCHAR(470) DEFAULT '';
	DECLARE `v_busy_items_tbl_name` VARCHAR(20) DEFAULT '';

	DECLARE `v_app_type_patt` INT  DEFAULT 127;
	DECLARE `v_app_type_start`, `v_app_type_end` TIME DEFAULT '00:00:00';
	DECLARE `v_is_multi` TINYINT(4) DEFAULT 0;

	DECLARE `v_n_dates` TINYINT(4) DEFAULT 0;
	DECLARE `v_n_month_days` TINYINT(4);


	DECLARE done INT DEFAULT 0;

 	DECLARE cursor_ag_single CURSOR FOR
		SELECT `CA_AGENDAS`.`AGENDA_ID`, `START_TIME`, `END_TIME`  FROM `CA_AGENDAS` WHERE `AGENDA_ID` = `p_ag_id`;

 	DECLARE cursor_ag_cat CURSOR FOR
		SELECT `CA_AGENDAS`.`AGENDA_ID`, `START_TIME`, `END_TIME` 
		FROM `CA_AGENDAS`
		LEFT JOIN `CA_AGENDAS_ASSIGNED_CATEGORIES` ON `CA_AGENDAS_ASSIGNED_CATEGORIES`.`AGENDA_ID` = `CA_AGENDAS`.`AGENDA_ID`
		WHERE `CA_AGENDAS_ASSIGNED_CATEGORIES`.`AGE_CAT_ID` = `p_cat_id`;

 	DECLARE cursor_ag_org CURSOR FOR
		SELECT `CA_AGENDAS`.`AGENDA_ID`, `START_TIME`, `END_TIME`  FROM `CA_AGENDAS` WHERE `ORG_CODE` = `p_org_code`;

	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
		
###	Declarations end ####################


	###	App type parameters
	SELECT 
		`TIME`,
		`v_today` + INTERVAL `MAX_TIME` DAY,
		`IS_MULTY`,
		`AT_PERIOD_START_TIME`,
		`AT_PERIOD_END_TIME`,
		`AT_PERIOD_DAY`
	INTO 
		@app_typ_dur,
		`v_app_type_last_date`,
		`v_is_multi`,
		`v_app_type_start`,
		`v_app_type_end`,
		`v_app_type_patt`
	FROM `CA_APPOINTMENT_TYPES` WHERE `ID` = `p_app_type_id` LIMIT 1;


	SET `v_busy_items_tbl_name`	= CONCAT( 'tmp_', `get_marker`() );

	SET @busy_items	= CONCAT( 'CREATE TABLE ', `v_busy_items_tbl_name`, '( `d_t_start` TIMESTAMP, `d_t_end` TIMESTAMP ) ENGINE = MEMORY' );
	PREPARE busy_items_stmt FROM @busy_items;
	EXECUTE busy_items_stmt;


	SET @get_busy_items	= CONCAT(
'INSERT INTO ', `v_busy_items_tbl_name`, ' ( `d_t_start`, `d_t_end`  ) 
( SELECT `d_t_start`, `d_t_end`
FROM (
	SELECT 
		CONCAT( ?, \' \', `START_TIME` ) AS `d_t_start`, 

		IF( ( `START_TIME` > `END_TIME`), 
			CONCAT( DATE_ADD( ?, INTERVAL 1 DAY ), \' \', `END_TIME` ), 
			CONCAT( ?, \' \', `END_TIME` ) ) AS `d_t_end`

		FROM `CA_APPOINTMENTS` 
		LEFT JOIN `CA_APP_ASSIGNED_AGENDA` ON `CA_APP_ASSIGNED_AGENDA`.`APP_ID` = `CA_APPOINTMENTS`.`APP_ID` 
		LEFT JOIN `CA_DAYSOFF_PATTERN` ON `CA_DAYSOFF_PATTERN`.`ID` = `CA_APPOINTMENTS`.`PATT_ID` 
		WHERE ( ? BETWEEN `START_DATE` AND `END_DATE` ) AND 
		NOT ( `END_DATE` = ? AND `START_TIME` > `END_TIME` ) AND 
		`CA_APP_ASSIGNED_AGENDA`.`AGENDA_ID` = ? AND 
		`isDateValidByPattern`( ?, `START_DATE`, IFNULL( `CYCLE`, 0 ), IFNULL( `PERIOD`, 0 ), IFNULL( `WEEK_DAYS`, 0 ) ) 

		UNION ALL 

	SELECT CONCAT( ?, \' \', `START_TIME` ) AS `d_t_start`, 
		IF( ( `START_TIME` > `END_TIME`), 
			CONCAT( DATE_ADD( ?, INTERVAL 1 DAY ), \' \', `END_TIME` ), 
			CONCAT( ?, \' \', `END_TIME` ) ) AS `d_t_end`

		FROM `CA_FREE_TIMES` 
		LEFT JOIN `CA_DAYSOFF_PATTERN` ON `CA_DAYSOFF_PATTERN`.`ID` = `CA_FREE_TIMES`.`PATT_ID`
		WHERE ( ? BETWEEN `START_DATE` AND `END_DATE` ) AND 
		NOT ( `END_DATE` = ? AND `START_TIME` > `END_TIME` ) AND 
		`AGENDA_ID` = ? AND 
		`isDateValidByPattern`( ?, `START_DATE`, IFNULL( `CYCLE`, 0 ), IFNULL( `PERIOD`, 0 ), IFNULL( `WEEK_DAYS`, 0 ) ) 

) AS `ins_tbl`)' );
	PREPARE get_busy_items_stmt FROM @get_busy_items;


--	DEBUG ZONE					(Initialization)
--	------------------------------------------------------------------------------------------------
--	------------------------------------------------------------------------------------------------
--	------------------------------------------------------------------------------------------------
IF `p_debug` THEN
-- 	DROP TABLE IF EXISTS `tst_tbl`;

	CREATE TABLE IF NOT EXISTS `tst_tbl` 
		( `d_t_start` TIMESTAMP DEFAULT '0000-00-00 00:00:00', `d_t_end` TIMESTAMP DEFAULT '0000-00-00 00:00:00', `info` VARCHAR(1000) DEFAULT '' ) ENGINE = MEMORY;
	

	DELETE FROM `tst_tbl`;

ELSE
	DROP TABLE IF EXISTS `tst_tbl`;
END IF;
--	DEBUG ZONE (end)
--	------------------------------------------------------------------------------------------------
--	------------------------------------------------------------------------------------------------
--	------------------------------------------------------------------------------------------------




	SET `v_first_date`	= CONCAT( `p_year`, '-', `p_month`, '-01' );
	SET `v_last_date`		= LAST_DAY( `v_first_date` );


###	App type pattern, Today and max time dates rendering
	SET `v_n_month_days` = DATE_FORMAT( LAST_DAY( CONCAT( `p_year`, '-', `p_month`, '-01') ), '%e' );
	SET `v_cur_date`	= `v_first_date`;
	REPEAT
		IF  ( `v_cur_date` < `v_today` ) OR
			( ( `v_app_type_patt` & `get_week_day_mask`( `v_cur_date` ) ) = 0 ) OR
			IF( `v_app_type_last_date` IS NULL, FALSE, ( `v_cur_date` > `v_app_type_last_date` ) )
		THEN
			SET `v_result` = CONCAT( `v_result`, '"', `v_cur_date`, '":0,' );
			SET `v_n_dates` = `v_n_dates` + 1;
		END IF;

		SET `v_cur_date`	= DATE_ADD( `v_cur_date`, INTERVAL 1 DAY );
	UNTIL `v_cur_date` > `v_last_date` END REPEAT; 

	IF( `p_ag_id` != -2 ) THEN
		OPEN cursor_ag_single;
	ELSEIF( `p_ag_id` = -2 AND `p_cat_id` = -2 )THEN
		OPEN cursor_ag_org;
	ELSE
		OPEN cursor_ag_cat;
	END IF;


	###	Prepared queries
	SET @off_items	=
'SELECT TRUE INTO @is_day_off FROM `CA_DAYSOFF` 
WHERE EXISTS(
	SELECT `DAY_ID` FROM `CA_DAYSOFF`
	LEFT JOIN `CA_DAYSOFF_PATTERN` ON `CA_DAYSOFF_PATTERN`.`ID` = `CA_DAYSOFF`.`PATT_ID`
	WHERE `AGENDA_ID` = ? AND ( ? BETWEEN `START_DATE` AND `END_DATE` ) AND `isDateValidByPattern`( ?, `START_DATE`, IFNULL( `CYCLE`, 0 ), IFNULL( `PERIOD`, 0 ), IFNULL( `WEEK_DAYS`, 0 ) )
) LIMIT 1';
	PREPARE off_items_stmt FROM @off_items;
-- ---------------------------

	SET @ins_busy	= CONCAT( 'INSERT INTO ', `v_busy_items_tbl_name`, '( `d_t_start`, `d_t_end`  )VALUES( ?, ? )' );
	PREPARE ins_busy_stmt FROM @ins_busy;
-- ---------------------------

	SET @seek_interval = CONCAT(
	'SELECT `is_exists` INTO  @is_exists 
	 FROM 
		(SELECT 
			1 AS `is_exists`, ',
			'IF( ? > @free_d_t_end, ?, @free_d_t_end  ) AS `fr_d_t_start`, ',
			'@free_d_t_end := `d_t_end`, 
			`d_t_start` AS `fr_d_t_end`
		FROM ','( SELECT `d_t_start`, `d_t_end` FROM ', `v_busy_items_tbl_name`, ' ORDER BY `d_t_start` ) AS `ordered` ',
	') AS result_intervals 
	WHERE 
		`fr_d_t_start` < `fr_d_t_end` AND `fr_d_t_start` < ? AND `fr_d_t_end` > ? AND
		( ( UNIX_TIMESTAMP( `fr_d_t_end` ) - UNIX_TIMESTAMP( `fr_d_t_start` ) ) / 60 ) >= ? LIMIT 1' );
	PREPARE seek_interval_stmt FROM @seek_interval;
-- ---------------------------

	SET @del_absorbed	= CONCAT( 'DELETE `intervals` FROM ', `v_busy_items_tbl_name`, ' AS `intervals`,
		( SELECT `oi1`.`d_t_start`, `oi1`.`d_t_end` 
			FROM ',
				`v_busy_items_tbl_name`, ' AS `oi1`,',
				`v_busy_items_tbl_name`, ' AS `oi2`
			WHERE (
				( `oi1`.`d_t_start` >= `oi2`.`d_t_start` AND `oi1`.`d_t_end` <  `oi2`.`d_t_end` ) OR 
				( `oi1`.`d_t_start` >  `oi2`.`d_t_start` AND `oi1`.`d_t_end` <= `oi2`.`d_t_end` )
			)
		) AS `absorbed`
	WHERE `intervals`.`d_t_start` = `absorbed`.`d_t_start` AND `intervals`.`d_t_end` = `absorbed`.`d_t_end`' );
	PREPARE del_absorbed_stmt FROM @del_absorbed;
-- ---------------------------

	SET @del_busy_items2	=
	CONCAT(
'DELETE FROM ', `v_busy_items_tbl_name`, ' WHERE `d_t_end` <= ? OR `d_t_start` = `d_t_end`' );
	PREPARE del_busy_items_stmt2 FROM @del_busy_items2;
-- ---------------------------


	###	Timetables (Agendas) analizing loop
	REPEAT
		IF `p_ag_id` != -2 THEN
			FETCH cursor_ag_single INTO `v_ag_id`, `v_ag_start_time`, `v_ag_end_time`;
		ELSEIF `p_ag_id` = -2 AND `p_cat_id` = -2 THEN
			FETCH cursor_ag_org INTO `v_ag_id`, `v_ag_start_time`, `v_ag_end_time`;
		ELSE
			FETCH cursor_ag_cat INTO `v_ag_id`, `v_ag_start_time`, `v_ag_end_time`;
		END IF;

		IF NOT done AND `v_n_dates` < `v_n_month_days` THEN
			SET @ag_id = `v_ag_id`;
	
			SET @del_busy_items1	= CONCAT( 'DELETE FROM ', `v_busy_items_tbl_name` );
			PREPARE del_busy_items_stmt1 FROM @del_busy_items1;
			EXECUTE del_busy_items_stmt1;
	
	###	All Days analizing loop
			SET `v_cur_date`	= `v_first_date`;
-- 			REPEAT
			WHILE( `v_cur_date` <= `v_last_date` AND `v_n_dates` < `v_n_month_days` )DO
				SET `v_prv_date`	= `v_cur_date` - INTERVAL 1 DAY;
				SET `v_nxt_date`	= `v_cur_date` + INTERVAL 1 DAY;
				SET `v_anx_date`	= `v_cur_date` + INTERVAL 2 DAY;
				SET `v_aax_date`	= `v_cur_date` + INTERVAL 3 DAY;
	
				SET @pv_ag_d_t_start	= CONCAT( `v_cur_date`, ' ', `v_ag_start_time` );
	
				IF `v_ag_start_time` < `v_ag_end_time` THEN
					SET @pv_ag_d_t_end	= CONCAT( `v_cur_date`, ' ', `v_ag_end_time` );
				ELSE
					SET @pv_ag_d_t_end	= CONCAT( `v_nxt_date`, ' ', `v_ag_end_time` );
				END IF;

				SET @is_day_off = FALSE;
				SET @udate = `v_cur_date`;
				EXECUTE off_items_stmt USING @ag_id, @udate, @udate;
	
	### Single Day analizing.
				IF( NOT @is_day_off AND
					( LOCATE(`v_cur_date`, `v_result`, 1 ) = 0 ) AND 
					( 
						( `v_ag_start_time` = `v_ag_end_time` ) OR
						( ( ( UNIX_TIMESTAMP(@pv_ag_d_t_end) - UNIX_TIMESTAMP(`p_d_t_now`) ) / 60 ) >= @app_typ_dur )
						
					)
				) THEN

					IF `v_n_get_day` = 3 THEN
						SET `v_n_get_day` = `v_n_get_day` - 1;
						SET @udate		= `v_prv_date`;
						EXECUTE get_busy_items_stmt USING	@udate, @udate, @udate, @udate, @udate, @ag_id, @udate,
															@udate, @udate, @udate, @udate, @udate, @ag_id, @udate;

						IF( `v_ag_start_time` < `v_ag_end_time` )THEN
							SET @d_t_start_busy	= CONCAT( `v_prv_date`, ' ', `v_ag_end_time` );
							SET @d_t_end_busy	= CONCAT( `v_cur_date`, ' ', `v_ag_start_time` );
							EXECUTE ins_busy_stmt USING @d_t_start_busy, @d_t_end_busy;
						ELSEIF(`v_ag_start_time` > `v_ag_end_time` )THEN
							SET @d_t_start_busy	= CONCAT( `v_prv_date`, ' ', `v_ag_end_time` );
							SET @d_t_end_busy	= CONCAT( `v_prv_date`, ' ', `v_ag_start_time` );
							EXECUTE ins_busy_stmt USING @d_t_start_busy, @d_t_end_busy;
						END IF;

						IF( `v_app_type_start` < `v_app_type_end` )THEN
							SET @d_t_start_busy	= CONCAT( `v_prv_date`, ' ', `v_app_type_end` );
							SET @d_t_end_busy	= CONCAT( `v_cur_date`, ' ', `v_app_type_start` );
							EXECUTE ins_busy_stmt USING @d_t_start_busy, @d_t_end_busy;
						ELSEIF `v_app_type_start` > `v_app_type_end` THEN
							SET @d_t_start_busy	= CONCAT( `v_prv_date`, ' ', `v_app_type_end` );
							SET @d_t_end_busy	= CONCAT( `v_prv_date`, ' ', `v_app_type_start` );
							EXECUTE ins_busy_stmt USING @d_t_start_busy, @d_t_end_busy;
						END IF;
					END IF;
	
					IF `v_n_get_day` = 2 THEN
						SET `v_n_get_day` = `v_n_get_day` - 1;
						SET @udate	= `v_cur_date`;
						EXECUTE get_busy_items_stmt USING	@udate, @udate, @udate, @udate, @udate, @ag_id, @udate,
															@udate, @udate, @udate, @udate, @udate, @ag_id, @udate;

						IF( `v_ag_start_time` < `v_ag_end_time` )THEN
							SET @d_t_start_busy	= CONCAT( `v_cur_date`, ' ', `v_ag_end_time` );
							SET @d_t_end_busy	= CONCAT( `v_nxt_date`, ' ', `v_ag_start_time` );
							EXECUTE ins_busy_stmt USING @d_t_start_busy, @d_t_end_busy;
						ELSEIF(`v_ag_start_time` > `v_ag_end_time` )THEN
							SET @d_t_start_busy	= CONCAT( `v_cur_date`, ' ', `v_ag_end_time` );
							SET @d_t_end_busy	= CONCAT( `v_cur_date`, ' ', `v_ag_start_time` );
							EXECUTE ins_busy_stmt USING @d_t_start_busy, @d_t_end_busy;
						END IF;

						IF( `v_app_type_start` < `v_app_type_end` )THEN
							SET @d_t_start_busy	= CONCAT( `v_cur_date`, ' ', `v_app_type_end` );
							SET @d_t_end_busy	= CONCAT( `v_nxt_date`, ' ', `v_app_type_start` );
							EXECUTE ins_busy_stmt USING @d_t_start_busy, @d_t_end_busy;
						ELSEIF `v_app_type_start` > `v_app_type_end` THEN
							SET @d_t_start_busy	= CONCAT( `v_cur_date`, ' ', `v_app_type_end` );
							SET @d_t_end_busy	= CONCAT( `v_cur_date`, ' ', `v_app_type_start` );
							EXECUTE ins_busy_stmt USING @d_t_start_busy, @d_t_end_busy;
						END IF;
					END IF;
	
					IF `v_n_get_day` = 1 THEN
						SET `v_n_get_day` = `v_n_get_day` - 1;
						SET @udate	= `v_nxt_date`;
						EXECUTE get_busy_items_stmt USING	@udate, @udate, @udate, @udate, @udate, @ag_id, @udate,
															@udate, @udate, @udate, @udate, @udate, @ag_id, @udate;

						IF( `v_ag_start_time` < `v_ag_end_time` )THEN
							SET @d_t_start_busy	= CONCAT( `v_nxt_date`, ' ', `v_ag_end_time` );
							SET @d_t_end_busy	= CONCAT( `v_anx_date`, ' ', `v_ag_start_time` );
							EXECUTE ins_busy_stmt USING @d_t_start_busy, @d_t_end_busy;
						ELSEIF(`v_ag_start_time` > `v_ag_end_time` )THEN
							SET @d_t_start_busy	= CONCAT( `v_nxt_date`, ' ', `v_ag_end_time` );
							SET @d_t_end_busy	= CONCAT( `v_nxt_date`, ' ', `v_ag_start_time` );
							EXECUTE ins_busy_stmt USING @d_t_start_busy, @d_t_end_busy;
						END IF;

						IF( `v_app_type_start` < `v_app_type_end` )THEN
							SET @d_t_start_busy	= CONCAT( `v_nxt_date`, ' ', `v_app_type_end` );
							SET @d_t_end_busy	= CONCAT( `v_anx_date`, ' ', `v_app_type_start` );
							EXECUTE ins_busy_stmt USING @d_t_start_busy, @d_t_end_busy;
						ELSEIF `v_app_type_start` > `v_app_type_end` THEN
							SET @d_t_start_busy	= CONCAT( `v_nxt_date`, ' ', `v_app_type_end` );
							SET @d_t_end_busy	= CONCAT( `v_nxt_date`, ' ', `v_app_type_start` );
							EXECUTE ins_busy_stmt USING @d_t_start_busy, @d_t_end_busy;
						END IF;
					END IF;
	
					SET @udate	= `v_anx_date`;
					EXECUTE get_busy_items_stmt USING	@udate, @udate, @udate, @udate, @udate, @ag_id, @udate,
														@udate, @udate, @udate, @udate, @udate, @ag_id, @udate;

					IF( `v_ag_start_time` < `v_ag_end_time` )THEN
						SET @d_t_start_busy	= CONCAT( `v_anx_date`, ' ', `v_ag_end_time` );
						SET @d_t_end_busy	= CONCAT( `v_aax_date`, ' ', `v_ag_start_time` );
						EXECUTE ins_busy_stmt USING @d_t_start_busy, @d_t_end_busy;
					ELSEIF(`v_ag_start_time` > `v_ag_end_time` )THEN
						SET @d_t_start_busy	= CONCAT( `v_anx_date`, ' ', `v_ag_end_time` );
						SET @d_t_end_busy	= CONCAT( `v_anx_date`, ' ', `v_ag_start_time` );
						EXECUTE ins_busy_stmt USING @d_t_start_busy, @d_t_end_busy;
					END IF;
	
					IF( `v_app_type_start` < `v_app_type_end` )THEN
						SET @d_t_start_busy	= CONCAT( `v_anx_date`, ' ', `v_app_type_end` );
						SET @d_t_end_busy	= CONCAT( `v_aax_date`, ' ', `v_app_type_start` );
						EXECUTE ins_busy_stmt USING @d_t_start_busy, @d_t_end_busy;
					ELSEIF `v_app_type_start` > `v_app_type_end` THEN
						SET @d_t_start_busy	= CONCAT( `v_anx_date`, ' ', `v_app_type_end` );
						SET @d_t_end_busy	= CONCAT( `v_anx_date`, ' ', `v_app_type_start` );
						EXECUTE ins_busy_stmt USING @d_t_start_busy, @d_t_end_busy;
					END IF;

	### Before agenda and boundary items of timetable deletion.
					EXECUTE del_busy_items_stmt2 USING @pv_ag_d_t_start;

					IF( `v_ag_start_time` = `v_ag_end_time` )THEN
						SET `v_max_d_t`	= CONCAT( `v_aax_date`, ' ', `v_ag_end_time` );

						SET @d_t_start_busy	= CONCAT( `v_cur_date`, ' ', ' 00:00:00' );
						SET @d_t_end_busy	= @pv_ag_d_t_start;
						EXECUTE ins_busy_stmt USING @d_t_start_busy, @d_t_end_busy;


						SET @d_t_start_busy	= `v_max_d_t`;
						SET @d_t_end_busy	= `v_max_d_t`;
						EXECUTE ins_busy_stmt USING @d_t_start_busy, @d_t_end_busy;
					END IF;
	
--	DEBUG ZONE (exapmpe)
--	------------------------------------------------------------------------------------------------
--	------------------------------------------------------------------------------------------------
--	------------------------------------------------------------------------------------------------
IF `p_debug` AND `v_cur_date` = '2010-01-10' THEN
						SET @seek_interval_dbg = CONCAT(
	'INSERT INTO `tst_tbl` ( `d_t_start`,  `d_t_end`, `info` )
	SELECT `d_t_start`, `d_t_end`, \'InFo\' FROM ', `v_busy_items_tbl_name`
						);

					PREPARE seek_interval_stmt_dbg1 FROM @seek_interval_dbg;
					EXECUTE seek_interval_stmt_dbg1;

-- 						SET @seek_interval_dbg = CONCAT(
-- 	'INSERT INTO `tst_tbl` ( `d_t_start`,  `d_t_end`, `info` )
-- 	VALUES ( )'
-- 						);
-- 
-- 					PREPARE seek_interval_stmt_dbg1 FROM @seek_interval_dbg;
-- 					EXECUTE seek_interval_stmt_dbg1;


END IF;
--	DEBUG ZONE (end)
--	------------------------------------------------------------------------------------------------
--	------------------------------------------------------------------------------------------------
--	------------------------------------------------------------------------------------------------


	### Delete inner busy elements
					EXECUTE del_absorbed_stmt;

	###	Empty timeslots seeking
					SET @free_d_t_end	= CONCAT( `v_cur_date`, ' 00:00:00');
					SET @is_exists	= 0;
	

					SET @pp_d_t_now	= `p_d_t_now`;
					EXECUTE seek_interval_stmt USING @pp_d_t_now, @pp_d_t_now, @pv_ag_d_t_end, @pv_ag_d_t_start, @app_typ_dur;
	
					IF @is_exists > 0 THEN
						SET `v_result` = CONCAT( `v_result`, '"', `v_cur_date`, '":1,');
						SET `v_n_dates` = `v_n_dates` + 1;
					END IF;
				ELSEIF ( `v_n_get_day` < 3 ) THEN			# Single Day anilizing end
					EXECUTE del_busy_items_stmt2 USING @pv_ag_d_t_start;
					SET `v_n_get_day`	= `v_n_get_day` + 1;
				END IF;
	
				IF NOT( `v_n_dates` < `v_n_month_days` )THEN
					SET done	= 1;
				END IF;

				SET `v_cur_date`	= `v_nxt_date`;
			END WHILE;						### All Days analizing loop end
		ELSE
			SET done	= 1;
		END IF;
	UNTIL done END REPEAT;

	SET @busy_items	= CONCAT( 'DROP TABLE IF EXISTS ', `v_busy_items_tbl_name` );
	PREPARE drop_stmt FROM @busy_items;
	EXECUTE drop_stmt;


### Absent Days analizing loop
	SET `v_cur_date`	= `v_first_date`;

-- 	REPEAT
-- 		IF ( LOCATE(`v_cur_date`, `v_result`, 1 ) = 0 ) THEN
-- 			SET `v_result`	= CONCAT( `v_result`, '"', `v_cur_date`, '":0,');
-- 			SET `v_n_dates`	= `v_n_dates` + 1;
-- 		END IF;
-- 
-- 		SET `v_cur_date`	= `v_cur_date` + INTERVAL 1 DAY;
-- 	UNTIL `v_n_dates` >= `v_n_month_days`  END REPEAT;	### Absent Days analizing loop end
  
	WHILE( `v_n_dates` < `v_n_month_days` )DO
		IF ( LOCATE(`v_cur_date`, `v_result`, 1 ) = 0 ) THEN
			SET `v_result`	= CONCAT( `v_result`, '"', `v_cur_date`, '":0,');
			SET `v_n_dates`	= `v_n_dates` + 1;
		END IF;

		SET `v_cur_date`	= `v_cur_date` + INTERVAL 1 DAY;
	END WHILE;




	SET `v_result`	= SUBSTRING( `v_result`, 1, ( LENGTH( `v_result` ) - 1 ) );
	SET `v_result`	= CONCAT( '{', `v_result`, '}' );

	SELECT `v_result` AS `res`;

END//




-- -------------------------------------------------------------------------------------------------
-- -------------------------------------------------------------------------------------------------
-- -------------------------------------------------------------------------------------------------
-- -------------------------------------------------------------------------------------------------
-- -------------------------------------------------------------------------------------------------




-- -------------------------------------------------------------------------------------------------
-- -------------------------------------------------------------------------------------------------
DELIMITER ;


