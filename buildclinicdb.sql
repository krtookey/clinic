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
 * 6. Maria
 */

-- $$ The comments I leave start with "$$" - Nick

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
	gender TINYINT,
	primary_phone	int NOT NULL,
	secondary_phone	int,
	email	varchar(40),
	address_id	int, 
	billing_id	int,
	insurance_id	int,
	pharmacy_id	int,
	labdest_id int,
	--medical_record_id int,
	minor boolean,
	guardian	int,
	pcp_id	int,
	problems_id	int NOT NULL,
	medlist_id	int NOT NULL,
	prev_note_id	int NOT NULL,
	--
	PRIMARY KEY (patient_id),
	FOREIGN KEY (street_address),
	FOREIGN KEY (billing_id),
	FOREIGN KEY (insurance_id),
	FOREIGN KEY (pharmacy_id),
	FOREIGN KEY (labdest_id),
	--FOREIGN KEY (medical_record_id),
	FOREIGN KEY (guardian),
	FOREIGN KEY (pcp_id),
	FOREIGN KEY (problem_id),
	FOREIGN KEY (medlist_id),
	FOREIGN KEY (prev_note_id)
);

DROP TABLE IF EXISTS Users;

CREATE TABLE Users (
	user_id	int NOT NULL,
	user_name	varchar(50) NOT NULL,
	permission	TINYINT NOT NULL,
	job_title	varchar(50),
	phone	int,
	email	varchar(40),
	first_name	varchar(40),
	last_name	varchar(40),
	pwd	varchar(),
	--
	PRIMARY KEY (user_id)
);

DROP TABLE IF EXISTS Addresses;

CREATE TABLE Addresses (
	address_id	int NOT NULL,
	street	varchar(40) NOT NULL,
	city	varchar(30) NOT NULL,
	state_abbr	char(2) NOT NULL,
	zip	int NOT NULL,
	--
	PRIMARY KEY (address_id)
);

/*
 * Medical Records Stuff
 * 
 */

/* 
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
*/

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

DROP TABLE IF EXISTS MedicationList;

CREATE TABLE MedicationList (
	medlist_id	int NOT NULL,
	patient_id	int NOT NULL,
	medication_id	int NOT NULL,
	status	boolean NOT NULL,
	--
	PRIMARY KEY (medlist_id),
	FOREIGN KEY (patient_id),
	FOREIGN KEY (medication_id)
);


DROP TABLE IF EXISTS Note;

CREATE TABLE Note (
	note_id	int NOT NULL,
	patient_id	int NOT NULL,
	appointment_id	int,
	cc varchar(1000),
	hist_illness	varchar(60000),
	ros_id	int,
	med_profile_id	int,
	social_hist	varchar(60000),
	med_hist	varchar(60000),
	psych_hist	varchar(60000),
	family_id	int,
	assessment	varchar(60000),
	plan	varchar(60000),
	laborder_id	int,
	lab_destid	int, -- $$ Is this meant to be labdist?
	demographics_id	int,
	comments	varchar(60000),
	--
	PRIMARY KEY (note_id),
	FOREIGN KEY (patient_id), 
	FOREIGN KEY (appointment_id),
	FOREIGN KEY (ros_id),
	FOREIGN KEY (med_profile_id),
	FOREIGN KEY (family_id), 
	FOREIGN KEY (laborder_id),
	FOREIGN KEY (lab_destid),
	FOREIGN KEY (demographics_id)
);

DROP TABLE IF EXISTS Demographics;

CREATE TABLE Demographics (
	demographics_id int NOT NULL,
	patient_id	int NOT NULL,
	emergency_contact1 int NOT NULL,
	emergency_contact2 int,
	comments varchar(60000),
	--
	PRIMARY KEY (demographics_id),
	FOREIGN KEY (patient_id),
	FOREIGN KEY (emergency_contact1),
	FOREIGN KEY (emergency_contact2),
);

DROP TABLE IF EXISTS FamilyHistory;

CREATE TABLE FamilyHistory (
	patient_id	int NOT NULL,
	relationship	varchar(30) NOT NULL,
	condition	varchar(60000),
	--
	FOREIGN KEY (patient_id)
);


DROP TABLE IF EXISTS EmergencyContact;

CREATE TABLE EmergencyContact (
	contact_id	int NOT NULL,
	contact_name 	varchar(50) NOT NULL,
	relationship	varchar(30) NOT NULL,
	phone	int NOT NULL,
	--
	PRIMARY KEY (contact_id)

);

DROP TABLE IF EXISTS MedicalProfile;

CREATE TABLE MedicalProfile (
	med_profile_id	int NOT NULL,
	bmi	float,
	p_weight	float,
	height	float,
	blood_pressure	varchar(10),
	pulse	int,
	pulse_ox	int,
	appointment_id	int NOT NULL,
	--
	PRIMARY KEY (med_profile_id),
	FOREIGN KEY (appointment_id)
);


DROP TABLE IF EXISTS ReviewOfSystem;

CREATE TABLE ReviewOfSystem (
	ros_id	int NOT NULL,
	condition1	boolean	NOT NULL,
	comments	varchar(20000),
	note_id	int NOT NULL,
	condition2	boolean	NOT NULL,
	condition3	boolean	NOT NULL,
	condition4	boolean	NOT NULL,
	condition5	boolean	NOT NULL,
	condition6	boolean	NOT NULL,
	condition7	boolean	NOT NULL,
	condition8	boolean	NOT NULL,
	condition9	boolean	NOT NULL,
	condition10	boolean	NOT NULL,
	condition11	boolean	NOT NULL,
	condition12	boolean	NOT NULL,
	condition13	boolean	NOT NULL,
	condition14	boolean	NOT NULL,
	condition15	boolean	NOT NULL,
	condition16	boolean	NOT NULL,
	condition17	boolean	NOT NULL,
	condition18	boolean	NOT NULL,
	condition19	boolean	NOT NULL,
	condition20	boolean	NOT NULL,
	condition21	boolean	NOT NULL,
	--
	PRIMARY KEY (ros_id)
);

/*
 * Appointments Stuff
 *
 */

DROP TABLE IF EXISTS Appointment;

CREATE TABLE Appointment (
	appointment_id	int NOT NULL,
	patient_id	int NOT NULL,
	date_time 	date NOT NULL,
	duration	int NOT NULL,
	status	TINYINT NOT NULL,
	doctor_id	int NOT NULL,
	doctor_last_name	varchar(30) NOT NULL, -- $$ Don't we want to store the doctors first and last name in the Users table, and just refer to them with doctor_id?
	doctor_first_name 	varcha(30) NOT NULL,
	--
	PRIMARY KEY (appointment_id),
	FOREIGN KEY (patient_id),
	FOREIGN KEY (doctor_id)
);

DROP TABLE IF EXISTS Billing;

CREATE TABLE Billing (
	billing_id	int NOT NULL,
	patient_id	int NOT NULL,
	appointment_id	int NOT NULL,
	note_id	int NOT NULL,
	bill_statement	varchar(60000) NOT NULL,
	amount_due	int,
	payed	boolean NOT NULL,
	--
	PRIMARY KEY (billing_id),
	FOREIGN KEY (patient_id),
	FOREIGN KEY (appointment_id),
	FOREIGN KEY (note_id)
);


/*
 * Pharmacy Stuff
 * 
 */


-- Pharmacy/Prescriptions

DROP TABLE IF EXISTS Pharmacy;

CREATE TABLE Pharmacy (
	pharmacy_id	int NOT NULL,
	pharmacy_name	varchar(50),
	address_id	int,
	phone	int,
	email	varchar(50),
	--
	PRIMARY KEY (pharmacy_id),
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
	medication_id	int NOT NULL,
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
	labdest_name	varchar(50),
	address_id	int,
	phone	int,
	--
	PRIMARY KEY (labdest_id),
	FOREIGN KEY (address_id)
);

DROP TABLE IF EXISTS LabOrders;

CREATE TABLE LabOrders (
	laborder_id	int NOT NULL,
	patient_id	int NOT NULL,
	doctor_id	int NOT NULL,
	status	TINYINT NOT NULL,
	labdest_id	int NOT NULL,
	cc_recipients	varchar(70),
	--
	PRIMARY KEY (laborder_id),
	FOREIGN KEY (patient_id),
	FOREIGN KEY (doctor_id),
	FOREIGN KEY (labdest_id)
);
 
DROP TABLE IF EXISTS LabList;

CREATE TABLE LabList ( -- List OF ALL labs that can be ordered, pairs lab_id WITH the name OF the lab (similar to DrugList, except that druglist doesn't contain all drugs)
	lab_id	int NOT NULL,
	lab_name	varchar(50) NOT NULL,
	--
	PRIMARY KEY (lab_id)
);

DROP TABLE IF EXISTS OrderedLabs;

CREATE TABLE OrderedLabs (
	laborder_id	int NOT NULL,
	lab_id	int NOT NULL,
	results	varchar(5000),
	-- 
	PRIMARY KEY (laborder_id), -- $$ Should this be a Primary or Foreign key??
	FOREIGN KEY (lab_id)
);

DROP TABLE IF EXISTS DrugList;

CREATE TABLE DrugList (
	medication_id	int NOT NULL,
	medication_name	varchar(50) NOT NULL,
	generic_name 	varchar(50) NOT NULL,
	--
	PRIMARY KEY (medication_id)
);

