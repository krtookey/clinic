/* Things to consider:
 * 1. How do we want to store sex, as an int, or as a char?
 * 2. How are we going to store doctors in Prescriptions and LabOrders, should their name be stored in a varchar(50) or 
 * should their id be stored as an int and refer to their name and other info which is stored in Users or a Practitioners table?
 * 3. Is MedicationList going to be updated by Pharmacy? Shouldn't it just pull from Prescriptions? We need to discuss this further.
 *	- I think the MedicationList table can be managed by Medical Records - this table would also include over the counter-meds and vitamins. - Elizabeth
 * 4. How should we store the list of lab_id to order as part of a LabOrder? Should we have a separate table storing the ids for a single order, 
 * which would have the laborder_id as a FK and labor
 * 5. What is int status? I stole it from your old prescription table, as I thought that it was there for a reason that I didn't know about. Do we actually need it?
 *	- From a Medical Records standpoint, I think we do.  We need to know if the lab has been ordered and if the lab results have come in, so that we can then pull the lab results into the note.
 *	On second thought, though, it really depends on how we are storing the returned lab results?
 */

CREATE DATABASE IF NOT EXISTS Clinic;
USE Clinic;

-- DROP DATABASE IF EXISTS Clinic;

DROP TABLE IF EXISTS Patient;

CREATE TABLE Patient (
	patient_id	int NOT NULL,
	first_name	varchar(30) NOT NULL,
	last_name	varchar(30) NOT NULL,
	middle_name	varchar(30),
	DOB	date NOT NULL,
	sex	CHAR(1) NOT NULL, 
	gender SMALLINT,
	primary_phone	int NOT NULL,
	secondary_phone	int,
	email	varchar(40),
	street_address	int, 
	billing_id	int,
	insurance_id	int,
	pharmacy_id	int,
	labdest_id int,
	medical_record_id int,
	minor boolean,
	--
	PRIMARY KEY (patient_id),
	FOREIGN KEY (street_address),
	FOREIGN KEY (billing_id),
	FOREIGN KEY (insurance_id),
	FOREIGN KEY (pharmacy_id),
	FOREIGN KEY (labdest_id),
	FOREIGN KEY (medical_record_id)
);


/*
 * Medical Records Stuff
 * 
 */
 
DROP TABLE IF EXISTS MedicalRecord;

CREATE TABLE MedicalRecord (
	medicalrecord_id	int NOT NULL,
	patient_id	int NOT NULL,
	note_id	int NOT NULL,
	problems_id	int NOT NULL,
	medications_id	int NOT NULL,
	--
	PRIMARY KEY (medicalrecord_id),
	FOREIGN KEY (patient_id),
	FOREIGN KEY (note_id),
	FOREIGN KEY (problems_id),
	FOREIGN KEY (medications_id) -- How ARE we storing medications FOR someone? would that be connected TO Prescriptions? Things TO consider #3

);

DROP TABLE IF EXISTS ProblemList;

CREATE TABLE ProblemList (
	problem_id	int NOT NULL,
	patient_id	int NOT NULL, 
	problem	varchar(30) NOT NULL,
	category	varchar(30),
	timeframe	varchar(12),
	--
	PRIMARY KEY (problem_id),
	FOREIGN KEY (patient_id)
);






/*
 * Pharmacy Stuff
 * 
 */


-- Pharmacy/Prescriptions

DROP TABLE IF EXISTS Pharmacy;

CREATE TABLE Pharmacy (
	pharmacy_id	int NOT NULL,
	patient_id	int NOT NULL,
	name	varchar(50),
	address_id	int,
	phone	int,
	email	varchar(50),
	--
	PRIMARY KEY (pharmacy_id),
	FOREIGN KEY (patient_id),
	FOREIGN KEY (address_id)
);

DROP TABLE IF EXISTS Prescriptions;

CREATE TABLE Prescriptions (
	prescription_id	int NOT NULL,
	patient_id	int NOT NULL,
	status	int NOT NULL, -- Things to Consider #5
	doctor	varchar(50) NOT NULL, -- Things to Consider #2
	doctor_id int NOT NULL,	-- Things to Consider #2
	pharmacy_id	int NOT NULL,
	medication_name	varchar(50) NOT NULL,
	dosage	varchar(50) NOT NULL,
	quantity_total	int NOT NULL,
	quantity_days	int NOT NULL,
	refills	int NOT NULL,
	general_notes	varchar(5000), 
	--
	PRIMARY KEY (prescription_id),
	FOREIGN KEY (patient_id),
	FOREIGN KEY (pharmacy_id),
	FOREIGN KEY (doctor_id)
);

-- Labs

DROP TABLE IF EXISTS LabDest;

CREATE TABLE LabDest (
	labdest_id	int NOT NULL,
	patient_id	int NOT NULL,
	name	varchar(50),
	address_id	int,
	phone	int,
	--
	PRIMARY KEY (labdest_id),
	FOREIGN KEY (patient_id),
	FOREIGN KEY (address_id)
);

DROP TABLE IF EXISTS LabOrders;

CREATE TABLE LabOrders (
	laborder_id	int NOT NULL,
	patient_id	int NOT NULL,
	doctor	varchar(50) NOT NULL,
	status	int NOT NULL,
	labdest_id	int NOT NULL,
	cc_recipients	varchar(70),
	labids_toorder	-- I have NO idea how TO store this correctly, maybe a seperate TABLE WITH laborder_id AS a FOREIGN KEY AND WITH lab_ids TO ORDER WITHIN it?
	--
	PRIMARY KEY (laborder_id),
	FOREIGN KEY (patient_id),
	FOREIGN KEY (labdest_id)
);
 
DROP TABLE IF EXISTS LabList;

CREATE TABLE LabList ( -- List OF ALL labs that can be ordered, pairs lab_id WITH the name OF the lab (similar to DrugList, except that druglist doesn't contain all drugs)
	lab_id	int NOT NULL,
	lab_name	varchar(50) NOT NULL,
	PRIMARY KEY (lab_id)
);
	


CREATE TABLE 


CREATE TABLE Course (
	Discipline	char(3),
	CourseNum   char(4),
	CourseName  varchar(80),
	credits	    int
);
