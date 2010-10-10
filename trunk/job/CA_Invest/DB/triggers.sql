DELIMITER //
-- -------------------------------------------------------------------------------------------------
-- -------------------------------------------------------------------------------------------------

DROP TRIGGER IF EXISTS `add_app_before`//
CREATE TRIGGER `add_app_before`
	BEFORE INSERT ON `CA_APPOINTMENTS` FOR EACH ROW
BEGIN
	DECLARE `duration` INT DEFAULT 0;
	DECLARE `d_t` DATETIME;

	SELECT `CA_APPOINTMENT_TYPES`.`TIME`
	FROM  `CA_APPOINTMENT_TYPES`
	WHERE `CA_APPOINTMENT_TYPES`.`ID` = new.`APPTYPE_ID` LIMIT 1 INTO `duration`;


	SET `d_t` = CONCAT_WS( ' ', new.`START_DATE`, new.`START_TIME` ) + INTERVAL `duration` MINUTE;

	SET new.`END_DATE` = SUBSTR( `d_t`, 1, 10 );
	SET new.`END_TIME` = SUBSTR( `d_t`, 12, 8 );
END//
-- -------------------------------------------------------------------------------------------------

DROP TRIGGER IF EXISTS `upd_app_before`//
CREATE TRIGGER `upd_app_before`
	BEFORE UPDATE ON `CA_APPOINTMENTS` FOR EACH ROW
BEGIN
	DECLARE `duration` INT DEFAULT 0;
	DECLARE `d_t` DATETIME;

	SELECT `CA_APPOINTMENT_TYPES`.`TIME`
	FROM  `CA_APPOINTMENT_TYPES`
	WHERE `CA_APPOINTMENT_TYPES`.`ID` = new.`APPTYPE_ID` LIMIT 1 INTO `duration`;


	SET `d_t` = CONCAT_WS( ' ', new.`START_DATE`, new.`START_TIME` ) + INTERVAL `duration` MINUTE;

	SET new.`END_DATE` = SUBSTR( `d_t`, 1, 10 );
	SET new.`END_TIME` = SUBSTR( `d_t`, 12, 8 );
END//
-- -------------------------------------------------------------------------------------------------

DROP TRIGGER IF EXISTS `del_dayoff_before`//
CREATE TRIGGER `del_dayoff_before`
	BEFORE DELETE ON `CA_DAYSOFF` FOR EACH ROW
BEGIN
	DELETE FROM `CA_DAYSOFF_PATTERN` WHERE `CA_DAYSOFF_PATTERN`.`ID` = old.`PATT_ID`;
END//
-- -------------------------------------------------------------------------------------------------

DROP TRIGGER IF EXISTS `del_free_time_before`//
CREATE TRIGGER `del_free_time_before`
	BEFORE DELETE ON `CA_FREE_TIMES` FOR EACH ROW
BEGIN
	DELETE FROM `CA_DAYSOFF_PATTERN` WHERE `CA_DAYSOFF_PATTERN`.`ID` = old.`PATT_ID`;
END//
-- -------------------------------------------------------------------------------------------------


-- -------------------------------------------------------------------------------------------------
-- -------------------------------------------------------------------------------------------------
DELIMITER ;