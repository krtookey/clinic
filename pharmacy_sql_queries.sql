-- GetBasicPatientInfo: From Patient table, given patient_id, get name and dob 
SELECT first_name, last_name, DOB FROM Patient 
WHERE patient_id=patient_id_to_get;

-- GetPatientInfo: From Patient table, given patient_id, get name, dob, address, sex, allergies, pharmacy_id, doctor_id, 
SELECT first_name, last_name, middle_name, DOB, address_id, sex FROM Patient
WHERE patient_id=patient_id_to_get;

--------Get Address From ID
SELECT street, city, state_abbr, zip FROM Addresses
WHERE address_id=address_id_to_get;


-- AddScripToRecord: To Prescriptions Table, add new row w/ patient_id, 
INSERT INTO Prescriptions (patient_id, status, general_notes, refills, quantity_days, quantity_total, dosage, medication_id, pharmacy_id, doctor_id)
VALUES ();

-- AddLabToRecord: 
INSERT INTO LabOrders (patient_id, doctor_id, status, labdest_id, cc_recipients)
VALUES ();

--------For each lab ordered as part of LabOrders, insert labs into OrderedLabs
INSERT INTO OrderedLabs (laborder_id, lab_id, results)
VALUES ();