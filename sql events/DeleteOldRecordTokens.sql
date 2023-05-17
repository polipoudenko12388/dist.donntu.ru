DELIMITER $$
CREATE EVENT DeleteOldRecordTokens
ON SCHEDULE EVERY 1 WEEK
DO
BEGIN
	DELETE FROM `dbwebsite_university`.`tokens_authorization` WHERE `id` in
	(SELECT id FROM 
	(SELECT id FROM `dbwebsite_university`.`tokens_authorization`
	WHERE TIMESTAMPDIFF(MONTH, `tokens_authorization`.`data_save_note`, CURDATE())>=1)temptable) 
    LIMIT 100;
END$$
DELIMITER ;