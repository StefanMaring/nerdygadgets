USE nerdygadgets;

DELIMITER $$

CREATE TRIGGER UpdateColdRoomTemperatures AFTER UPDATE ON coldroomtemperatures FOR EACH ROW
BEGIN
    INSERT INTO coldroomtemperatures_archive (ColdRoomTemperatureID,ColdRoomSensorNumber,RecordedWhen,Temperature,ValidFrom,ValidTo)
    VALUES (old.ColdRoomTemperatureID, old.ColdRoomSensorNumber, old.RecordedWhen, old.Temperature, old.RecordedWhen, now());
END $$

DELIMITER ;





