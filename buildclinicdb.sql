/* Things to consider:
 * 1. How do we want to store sex, as an int, or as a char?
 * 2. How are we going to store doctors in Prescriptions and LabOrders, should their name be stored in a varchar(50) or 
 * should their id be stored as an int and refer to their name and other info which is stored in Users or a Practitioners table?
 * 3. Is MedicationList going to be updated by Pharmacy? Shouldn't it just pull from Prescriptions? We need to discuss this further.
 *	- I think the MedicationList table can be managed by Medical Records - this table would also include over the counter-meds and vitamins. - EN
 * 4. How should we store the list of lab_id to order as part of a LabOrder? Should we have a separate table storing the ids for a single order, 
 * which would have the laborder_id as a FK and labor
 * 5. What is int status? I stole it from your old prescription table, as I thought that it was there for a reason that I didn't know about. Do we actually need it?
 *	- From a Medical Records standpoint, I think we do.  We need to know if the lab has been ordered and if the lab results have come in, so that we can then pull the lab results into the note.
 *	On second thought, though, it really depends on how we are storing the returned lab results? - EN
 */

-- $$ The comments I leave start with "$$" - Nick
-- EN comments from Elizabeth

CREATE DATABASE IF NOT EXISTS Clinic;
USE Clinic;

-- DROP DATABASE IF EXISTS Clinic;

DROP TABLE IF EXISTS Patient;

CREATE TABLE Patient (
	patient_id	int NOT NULL AUTO_INCREMENT,
	first_name	varchar(30) NOT NULL,
	last_name	varchar(30) NOT NULL,
	middle_name	varchar(30),
	DOB	date NOT NULL,
	sex	CHAR(1) NOT NULL, 
	gender TINYINT,
	primary_phone varchar(20) NOT NULL,
	secondary_phone	varchar(20),
	email varchar(40),
	address_id int, 
	insurance_id int,
	pharmacy_id	int,
	labdest_id int,
	minor boolean,
	guardian int,
	pcp_id int, 
	prev_note_id int NOT NULL,
	emergency_contact1 int NOT NULL,
	emergency_contact2 int,
	-- 
	PRIMARY KEY (patient_id),
	FOREIGN KEY (address_id),
	FOREIGN KEY (insurance_id),
	FOREIGN KEY (pharmacy_id),
	FOREIGN KEY (labdest_id),
	FOREIGN KEY (guardian),
	FOREIGN KEY (pcp_id),
	FOREIGN KEY (prev_note_id),
	FOREIGN KEY (emergency_contact1),
	FOREIGN KEY (emergency_contact2)
);

-- // Sample Data
-- INSERT INTO Patient (first_name, last_name, middle_name, DOB, sex, gender, primary_phone, secondary_phone, email, address_id, billing_id, insurance_id, pharmacy_id, lab_destid, minor, guardian, pcp_id, prev_note_id, emergency_contact1, emergency_contact2) 
-- VALUES ('Nick', 'Danger', 'Does', '1999-07-22', 'M', '1', '18027678888', '18023497898', 'nickdangeriscool@gmail.com', '1', '1', '1', '1', '0', '0', '1', '1', '2', '10', '11');
INSERT INTO Patient VALUES ('Nick', 'Danger', 'Does', '1999-07-22', 'M', '1', '18027678888', '18023497898', 'nickdangeriscool@gmail.com', '1', '1', '1', '1', '0', '0', '1', '1', '2', '10', '11');
INSERT INTO Patient VALUES 	('John', 'Doe', 'Bob', '1945-06-04', 'M', '1', '12809993300', '18025553333', 'catsrule@gmail.com', '3', '3', '3', '1', '1', '0', '0', '1', '1', '0'),
				('Jane', 'Dough', 'Jill', '1964-12-09', 'F', '2', '13014448998', '12409994444', 'dogsdrool@gmail.com', '4', '4', '4', '2', '2', '0', '0', '2', '0', '2', '0');
				 
DROP TABLE IF EXISTS Users;

CREATE TABLE Users (
	user_id	int NOT NULL AUTO_INCREMENT,
	user_name	varchar(50) NOT NULL,
	permission	TINYINT NOT NULL,
	job_title	varchar(50),
	phone	varchar(20),
	email	varchar(40),
	first_name	varchar(40),
	last_name	varchar(40),
	pwd	varchar(40),
	-- 
	PRIMARY KEY (user_id)
);

-- // Sample Data
-- INSERT INTO Users (user_name, permission, job_title, phone, email, first_name, last_name, pwd) VALUES ('JoeyDanger', '1', 'Pediatrician', '18024457689', 'joeydanger@uppervalleyhealth.org', 'Joey', 'Danger');
INSERT INTO Users VALUES 	('JoeyDanger', '1', 'Pediatrician', '18024457689', 'joeydanger@uppervalleyhealth.org', 'Joey', 'Danger'),
				('CBrown', '1', 'NP', '18025556767', 'snoopyrules@aol.com', 'Charlie', 'Brown');

DROP TABLE IF EXISTS Addresses;

CREATE TABLE Addresses (
	address_id	int NOT NULL AUTO_INCREMENT,
	street	varchar(40) NOT NULL,
	city	varchar(30) NOT NULL,
	state_abbr	char(2) NOT NULL,
	zip	int NOT NULL,
	-- 
	PRIMARY KEY (address_id)
);

-- // Sample Data
-- INSERT INTO Addresses (street, city, state_abbr, zip) VALUES ('1379 Maple St', 'Vergennes', 'VT', '05491');
INSERT INTO Addresses VALUES ('12 North Main St', 'Randolph', 'VT', '05060');
INSERT INTO Addresses VALUES 	('1390 Monti Rd', 'Northfield', 'VT', '05663'),
				('3 Mt Philo Rd', 'Charlotte', 'VT', '05444'),
				('26 South Rd', 'Williston', 'VT', '05495');


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
	problem_id	int NOT NULL AUTO_INCREMENT,
	patient_id	int NOT NULL, 
	problem	varchar(30) NOT NULL,
	category	varchar(30),
	timeframe	varchar(12),
	--  
	PRIMARY KEY (problem_id),
	FOREIGN KEY (patient_id)
);

CREATE INDEX patient_id_idx ON ProblemList (patient_id);

INSERT INTO ProblemList VALUES	('2', 'Cold Urticaria', 'allergy', 'present'), -- EN not sure if this is the right use of the timeframe field?
				('2', 'Covid-19', 'virus', 'past'),
				('3', 'Hypertension', 'cardiovascular', 'present');

DROP TABLE IF EXISTS MedicationList;

CREATE TABLE MedicationList (
	medlist_id	int NOT NULL AUTO_INCREMENT,
	patient_id	int NOT NULL,
	medication_id	int NOT NULL,
	dosage	varchar(50),
	status	boolean NOT NULL,
	--  
	PRIMARY KEY (medlist_id),
	FOREIGN KEY (patient_id),
	FOREIGN KEY (medication_id)
);

CREATE INDEX patient_id_idx ON MedicationList (patient_id);

INSERT INTO MedicationList VALUES 	('3', '8', '50 ml', '1'),
					('2', '4', '20 ml', '1');

DROP TABLE IF EXISTS Note;

CREATE TABLE Note (
	note_id	int NOT NULL AUTO_INCREMENT,
	patient_id	int NOT NULL,
	appointment_id	int,
	cc varchar(1000),
	hist_illness varchar(60000),
	ros_id int,
	med_profile_id int,
	social_hist	varchar(60000),
	med_hist varchar(60000),
	psych_hist varchar(60000),
	assessment varchar(60000),
	plan varchar(60000),
	laborder_id	int,
	labdest_id int, -- $$ Is this meant to be labdist? -- EN I checked the diagram, and I think we actually want it to be lab_id, so I went ahead and changed it.  Rational being that we are storing both lab_id and laborder_id in Note so that we can access the OrderedLabs table.  We probably need another work around for multiple labs, in which case we might be storing both as a varchar instead of an int.  
	demographics  varchar(60000),
	comments varchar(60000),
	-- 
	PRIMARY KEY (note_id), 
	FOREIGN KEY (patient_id)
	FOREIGN KEY (appointment_id),
	FOREIGN KEY (ros_id),
	FOREIGN KEY (med_profile_id),
	FOREIGN KEY (laborder_id),
	FOREIGN KEY (lab_dest_id)
);

-- CREATE INDEX patient_id_idx ON Note (patient_id);   $$ Do we want patient_id to be an index within Note? -- EN Yes.

INSERT INTO Note VALUES ('2', '1', 'Headache', 'History of Illness: Has been getting bad headaches for a few weeks now.', '1', '1', 'Social History: former smoker.  Drink 8 cups of coffee a day.', 'Medical History: ...blah, blah, blah', 'Phych History: suffers from anxiety', 'Assessment: drinks too much coffee.', 'Plan: Reduce caffine intake. Return in a month if headaches have not subsided', '0', '0', 'Demographic: Married', 'Comments: Blah...'),
			('1', '2', 'Annual Physical', 'Feels fine.', '2', '2', 'Social History: drinks socially. 2-3 beers a week', 'Medical History: partial fracture to left tibia in June 2016', 'Phych History: Blah', 'Assessment: Healthy for age, but blah, blah, blah', 'Plan: Eat health and get at least 8 hours of sleep a night. Annual lab tests ordered.', '0', '1', 'Demographic: Blah', 'Comments: view lab results.');

/*DROP TABLE IF EXISTS Demographics;

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
*/

DROP TABLE IF EXISTS FamilyHistory;

CREATE TABLE FamilyHistory (
	patient_id int NOT NULL,
	relationship varchar(30) NOT NULL,
	condition varchar(60000),
	-- 
	FOREIGN KEY (patient_id)
);

-- CREATE INDEX patient_id_idx ON FamilyHistory (patient_id);  -- EN I think we want to index the patient_id on this one too.

INSERT INTO FamilyHistory VALUES 	('2', 'Father', 'Hypertension'),
					('2', 'Mother', 'Type 2 Diabetes');

DROP TABLE IF EXISTS EmergencyContact;

CREATE TABLE EmergencyContact (
	contact_id	int NOT NULL AUTO_INCREMENT,
	contact_name 	varchar(50) NOT NULL,
	relationship	varchar(30) NOT NULL,
	phone	varchar(20) NOT NULL,
	-- 
	PRIMARY KEY (contact_id)

);

DROP TABLE IF EXISTS MedicalProfile;

CREATE TABLE MedicalProfile (
	med_profile_id	int NOT NULL AUTO_INCREMENT,
	bmi	float,
	p_weight float,
	height float,
	blood_pressure varchar(10),
	pulse int,
	pulse_ox int,
	appointment_id	int NOT NULL,
	-- 
	PRIMARY KEY (med_profile_id),
	FOREIGN KEY (appointment_id)
);


DROP TABLE IF EXISTS ReviewOfSystem;

CREATE TABLE ReviewOfSystem (
	ros_id	int NOT NULL AUTO_INCREMENT,
	comments varchar(20000),
	rashes	boolean	NOT NULL,
	itching	boolean	NOT NULL,
	hair_nails	boolean	NOT NULL,
	headaches	boolean	NOT NULL,
	head_injury	boolean	NOT NULL,
	-- Vision Stuff
	glasses	boolean	NOT NULL,
	change_vision	boolean	NOT NULL,
	eye_pain	boolean	NOT NULL,
	double_vision	boolean	NOT NULL,
	flash_lgt	boolean	NOT NULL,
	glaucoma	boolean	NOT NULL,
	last_eye date,
	-- Ear Stuff
	hearing	boolean	NOT NULL,
	ear_pain	boolean	NOT NULL,
	ear_disch	boolean	NOT NULL,
	ringing	boolean	NOT NULL,
	dizziness	boolean	NOT NULL,
	-- Nose/Sinus Stuff
	nose_bld	boolean	NOT NULL,
	stuffiness	boolean	NOT NULL,
	freq_colds	boolean	NOT NULL,
	hives	boolean	NOT NULL,
	swell_lip	boolean	NOT NULL,
	hay_fever	boolean	NOT NULL,
	asthma	boolean	NOT NULL,
	eczema	boolean	NOT NULL,
	sens_drg_food	boolean	NOT NULL,
	-- Mouth Stuff
	bld_gums	boolean	NOT NULL,
	sore_tongue	boolean	NOT NULL,
	sore_throat	boolean	NOT NULL,
	hoarseness	boolean	NOT NULL,
	lumps	boolean	NOT NULL,
	swoll_glands	boolean	NOT NULL,
	goiter	boolean	NOT NULL,
	-- Neck and Breasts
	neck_stiffness	boolean	NOT NULL,
	breast_lumps	boolean	NOT NULL,
	breast_pain	boolean	NOT NULL,
	nipple_discharge	boolean	NOT NULL,
	-- Cardiovascular/Lungs
	bse	boolean	NOT NULL,
	short_of_brth	boolean	NOT NULL,
	cough	boolean	NOT NULL,
	phlem	boolean	NOT NULL,
	wheezing	boolean	NOT NULL,
	chough_bld	boolean	NOT NULL,
	chest_pain	boolean	NOT NULL,
	fever	boolean	NOT NULL,
	night_sweats	boolean	NOT NULL,
	swell_hands	boolean	NOT NULL,
	blue_toes	boolean	NOT NULL,
	high_blood	boolean	NOT NULL,
	skipping_heart	boolean	NOT NULL,
	heart_murmur	boolean	NOT NULL,
	hx_of_heart_med	boolean	NOT NULL,
	bronchitis	boolean	NOT NULL,
	rheumatic_heart_dis	boolean	NOT NULL,
	-- Digestive
	appetite	boolean	NOT NULL,
	swallowing	boolean	NOT NULL,
	nausea	boolean	NOT NULL,
	heartburn	boolean	NOT NULL,
	vomiting	boolean	NOT NULL,
	vomit_blood	boolean	NOT NULL,
	constipation	boolean	NOT NULL,
	diarrhea	boolean	NOT NULL,
	bowels	boolean	NOT NULL,
	abdominal_pain	boolean	NOT NULL,
	burping	boolean	NOT NULL,
	farting	boolean	NOT NULL,
	yellow_skin	boolean	NOT NULL,
	food_intol	boolean	NOT NULL,
	rectal_bleed	boolean	NOT NULL,
	unination	boolean	NOT NULL,
	urin_pain	boolean	NOT NULL,
	freq_urin	boolean	NOT NULL,
	urgent_urin	boolean	NOT NULL,
	incontinence_urin	boolean	NOT NULL,
	dribble	boolean	NOT NULL,
	urin_stream	boolean	NOT NULL,
	urin_blood	boolean	NOT NULL,
	uti_stones	boolean	NOT NULL,
	-- Circulation
	leg_cramp	boolean	NOT NULL,
	varicose_vein	boolean	NOT NULL,
	clot_vein	boolean	NOT NULL,
	musc_pain	boolean	NOT NULL,
	musc_swelling	boolean	NOT NULL,
	musc_stiffness	boolean	NOT NULL,
	joint_motion	boolean	NOT NULL,
	broken_bone	boolean	NOT NULL,
	sprains	boolean	NOT NULL,
	arthritis	boolean	NOT NULL,
	gout	boolean	NOT NULL, 
	-- Neurological (kind of)
	--headache duplicate
	seizures	boolean	NOT NULL,
	fainting	boolean	NOT NULL,
	paralysis	boolean	NOT NULL,
	weakness	boolean	NOT NULL,
	muscle_size	boolean	NOT NULL,
	muscle_spasm	boolean	NOT NULL,
	tremor	boolean	NOT NULL,
	invol_move	boolean	NOT NULL,
	incoordination	boolean	NOT NULL,
	numbness	boolean	NOT NULL,
	pins_needles	boolean	NOT NULL,
	anemia	boolean	NOT NULL,
	bruising_bleed	boolean	NOT NULL,
	transfusions	boolean	NOT NULL,
	growth	boolean	NOT NULL,
	appetite	boolean	NOT NULL,
	thirst	boolean	NOT NULL,
	incre_urin	boolean	NOT NULL,
	thyroid	boolean	NOT NULL,
	head_cold	boolean	NOT NULL,
	sweating	boolean	NOT NULL,
	diabetes	boolean	NOT NULL,
	anxiety	boolean	NOT NULL,
	depression	boolean	NOT NULL,
	memory	boolean	NOT NULL,
	unusual_prob	boolean	NOT NULL,
	sleep	boolean	NOT NULL,
	psychiatrist	boolean	NOT NULL,
	mood	boolean	NOT NULL
	-- 
	PRIMARY KEY (ros_id)
);

CREATE INDEX ros_id_idx ON ReviewOfSystem (ros_id);

/*
 * Appointments Stuff
 *
 */

DROP TABLE IF EXISTS Appointment;

CREATE TABLE Appointment (
	appointment_id	int NOT NULL AUTO_INCREMENT,
	patient_id	int NOT NULL,
	date_time 	datetime NOT NULL,
	duration	int NOT NULL,
	status	TINYINT NOT NULL,
	doctor_id	int NOT NULL,
	doctor_last_name	varchar(30) NOT NULL, -- $$ Don't we want to store the doctors first and last name in the Users table, and just refer to them with doctor_id?
	doctor_first_name 	varchar(30) NOT NULL,
	-- 
	PRIMARY KEY (appointment_id),
	FOREIGN KEY (patient_id),
	FOREIGN KEY (doctor_id)
);

DROP TABLE IF EXISTS Billing;

CREATE TABLE Billing (
	billing_id	int NOT NULL AUTO_INCREMENT,
	patient_id	int NOT NULL,
	appointment_id	int NOT NULL,
	note_id	int NOT NULL,
	bill_statement	varchar(60000) NOT NULL,
	amount_due	float,
	paid	boolean NOT NULL,
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
	pharmacy_id	int NOT NULL AUTO_INCREMENT,
	pharmacy_name	varchar(50),
	address_id	int,
	phone	varchar(20),
	email	varchar(50),
	-- 
	PRIMARY KEY (pharmacy_id),
	FOREIGN KEY (address_id)
);

-- // Sample Data
INSERT INTO Pharmacy VALUES ('Rite Aid Randolph', '2', '18027283722', '05060@riteaid.com');


DROP TABLE IF EXISTS Prescriptions;

CREATE TABLE Prescriptions (
	prescription_id	int NOT NULL AUTO_INCREMENT,
	patient_id	int NOT NULL,
    doctor_id   int NOT NULL,	-- Things to Consider #2
    pharmacy_id int NOT NULL,
    medication_id	int NOT NULL,
    dosage  varchar(50) NOT NULL,
    route   varchar(20) NOT NULL,
	usage_details  varchar(40) NOT NULL,
	quantity	int NOT NULL,
	refills	int NOT NULL,
	general_notes	varchar(5000),
    status	int NOT NULL, -- Things to Consider #5
    -- 
	PRIMARY KEY (prescription_id),
	FOREIGN KEY (patient_id),
    FOREIGN KEY (doctor_id),
    FOREIGN KEY (pharmacy_id),
	FOREIGN KEY (medication_id)
);

-- Labs

DROP TABLE IF EXISTS LabDest;

CREATE TABLE LabDest (
	labdest_id	int NOT NULL AUTO_INCREMENT,
	labdest_name	varchar(50),
	address_id	int,
	phone	varchar(20),
	-- 
	PRIMARY KEY (labdest_id),
	FOREIGN KEY (address_id)
);

-- // Sample Data
INSERT INTO LabDest VALUES ('A Shack In The Woods', '3', '18024853788');

DROP TABLE IF EXISTS LabOrders;

CREATE TABLE LabOrders (
	laborder_id	int NOT NULL AUTO_INCREMENT,
	patient_id	int NOT NULL,
	doctor_id	int NOT NULL,
	status	TINYINT NOT NULL,
	labdest_id	int NOT NULL,
	cc_recipients	varchar(70), --$$ Should this instead be a list of IDs stored in a seperate table that contains the ID's of the practitioners who were CC'd, or just keep it as a list of names?
	-- 
	PRIMARY KEY (laborder_id),
	FOREIGN KEY (patient_id),
	FOREIGN KEY (doctor_id),
	FOREIGN KEY (labdest_id)
);

-- // Sample Data 
 
DROP TABLE IF EXISTS LabList;

CREATE TABLE LabList ( -- List OF ALL labs that can be ordered, pairs lab_id WITH the name OF the lab (similar to DrugList, except that druglist doesn't contain all drugs)
	lab_id	int NOT NULL AUTO_INCREMENT,
	lab_name	varchar(50) NOT NULL,
	-- 
	PRIMARY KEY (lab_id)
);

INSERT INTO LabList VALUES ('1', 'CBC');
INSERT INTO LabList VALUES ('2', 'CMP');
INSERT INTO LabList VALUES ('3', 'TSH');
INSERT INTO LabList VALUES ('4', 'Free_T4');
INSERT INTO LabList VALUES ('5', 'Hemoglobin_A1C');
INSERT INTO LabList VALUES ('6', 'Lipids');
INSERT INTO LabList VALUES ('7', 'Ferritin');
INSERT INTO LabList VALUES ('8', 'Iron Sat');
INSERT INTO LabList VALUES ('9', 'CRP');
INSERT INTO LabList VALUES ('10', 'Vitamin D');
INSERT INTO LabList VALUES ('11', 'Vitamin B12');
INSERT INTO LabList VALUES ('12', 'Vitamin B1');
INSERT INTO LabList VALUES ('13', 'Vitamin B2');
INSERT INTO LabList VALUES ('14', 'EKG');
INSERT INTO LabList VALUES ('15', 'Gonorrhea');
INSERT INTO LabList VALUES ('16', 'Chlamydia');
INSERT INTO LabList VALUES ('17', 'HIV');
INSERT INTO LabList VALUES ('18', 'Syphilis');


DROP TABLE IF EXISTS OrderedLabs;

CREATE TABLE OrderedLabs (
	laborder_id	int NOT NULL,
	lab_id	int NOT NULL,
	results	varchar(5000),
	-- 
	FOREIGN KEY (laborder_id),
	FOREIGN KEY (lab_id)
);

DROP TABLE IF EXISTS DrugList;

CREATE TABLE DrugList (
	medication_id	int NOT NULL AUTO_INCREMENT,
	medication_name	varchar(50) NOT NULL,
	generic_name 	varchar(50) NOT NULL,
	-- 
	PRIMARY KEY (medication_id)
);

-- // Sample Data
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Aspirin', 'acetylsalicylic acid');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Synthroid', 'Levothyroxine');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Vicodin', 'Hydrocodone/APAP');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Amoxil', 'Amoxicillin');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Prinivil',  'Lisinopril');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Nexium', 'Esomeprazole');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Lipitor', 'Atorvastatin');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Zocor', 'Simvastatin');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Plavix', 'Clopidogrel');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Singulair', 'Montelukast');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Crestor', 'Rosuvastatin');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Lopressor', 'Metoprolol');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Lexapro', 'Escitalopram');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Zithroma', 'Azithromycin');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('ProAir HF',  'Albuterol');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('HCTZ', 'Hydrochlorothiazide');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Glucophage', 'Metformin');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Zoloft', 'Sertraline');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Advil', 'Ibuprofen');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Ambien', 'Zolpidem');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Lasix', 'Furosemide');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Prilosec', 'Omeprazole');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Desyrel', 'Trazodone');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Diovan', 'Valsartan');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Ultram', 'Tramadol');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Cymbalta', 'Duloxetine');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Coumadin', 'Warfarin');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Norvasc', 'Amlodipine');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Percocet', 'Oxycodone/APAP');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Seroquel', 'Quetiapine');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Phenergan', 'Promethazine');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Flonase', 'Fluticasone');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Xanax', 'Alprazolam');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Klonopin', 'Clonazepam');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Lotensin', 'Benazepril');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Mobic', 'Meloxicam');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Celexa', 'Citalopram');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Keflex', 'Cephalexin');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Spiriva', 'Tiotropium');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Neurontin', 'Gabapentin');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Abilify', 'Aripiprazole');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('K-Tab,' 'Potassium');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Flexeril', 'Cyclobenzaprine');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Medrol', 'Methylprednisolone');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Concerta', 'Methylphenidate');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Claritin', 'Loratadine');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Coreg', 'Carvedilol');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Soma', 'Carisoprodol');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Lanoxin', 'Digoxin');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Namenda', 'Memantine');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Tenormin', 'Atenolol');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Valium', 'Diazepam');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('OxyContin', 'Oxycodone');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Actonel', 'Risedronate');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Folvite', 'Folic Acid');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Hyzaar', 'Losartan+HCTZ');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Deltasone', 'Prednisone');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Omnipred', 'Prednisolone');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Fosamax', 'Alendronate');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Protonix', 'Pantoprazole');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Flomax', 'Tamsulosin');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Dyazide', 'Triamterene+HCTZ');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Paxil', 'Paroxetine');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Suboxone', 'Buprenorphine+Naloxone');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Vasotec', 'Enalapril');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Mevacor', 'Lovastatin');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Actos', 'Pioglitazone');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Pravachol', 'Pravastatin');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Prozac', 'Fluoxetine');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Levemir', 'Insulin Detemir');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Diflucan', 'Fluconazole');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Levaquin', 'Levofloxacin');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Xarelto', 'Rivaroxaban');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Celebrex', 'Celecoxib');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Tylenol#2', 'Codeine/APAP' );
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Nasonex', 'Mometasone');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Cipro', 'Ciprofloxacin');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Lyrica', 'Pregabalin');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Novolog', 'Insulin Aspart');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Effexor', 'Venlafaxine');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Ativan', 'Lorazepam');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Zetia', 'Ezetimibe');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Premarin', 'Estrogen');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Zyloprim', 'Allopurinol');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Pen VK,' 'Penicillin' );
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Januvia', 'Sitagliptin');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Elavil', 'Amitriptyline');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Catapres', 'Clonidine');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Xalatan', 'Latanoprost');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Vyvanse', 'Lisdexamfetamine');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Advair', 'Fluticasone+Salmeterol');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Symbicort', 'Budesonide+Formoterol');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Dexilant', 'Dexlansoprazole');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Diabeta', 'Glyburide');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Zyprexa', 'Olanzapine');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Detrol', 'Tolterodine');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Zantac', 'Ranitidine');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Pepcid', 'Famotidine');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Cardizem', 'Diltiazem');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Lantus', 'Insulin Glargine');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Prinizide', 'Lisinopril+HCTZ');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Wellbutrin®', 'Bupropion');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Zyrtec®', 'Cetirizine');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Topamax®', 'Topiramate');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Valtrex®', 'Valacyclovir');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Lunesta®', 'Eszopiclone');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Zovirax®', 'Acyclovir');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Omnicef®', 'Cefdinir');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Cleocin®', 'Clindamycin');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Keppra®', 'Levetiracetam');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Lopid®', 'Gemfibrozil');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Robitussin®', 'Guaifenesin');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Glucotrol®', 'Glipizide');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Avapro®', 'Irbesartan');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Reglan®', 'Metoclopramide');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Cozaar®', 'Losartan');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Dramamine®', 'Meclizine');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Flagyl®', 'Metronidazole');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Caltrate®', 'Vitamin D');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('AndroGel®', 'Testosterone');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Requip®', 'Ropinirole');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Risperdal®', 'Risperidone');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Patanol®', 'Olopatadine');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Aricept®', 'Donepezil');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Focalin®', 'Dexmethylphenidate');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Lovenox®', 'Enoxaparin');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Duragesic®', 'Fentanyl');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Bentyl®', 'Dicyclomine');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Xopenex®', 'Levalbuterol');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Strattera®', 'Atomoxetine');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Altace®', 'Ramipril');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Restoril®', 'Temazepam');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Adipex® P', 'Phentermine');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Accupril®', 'Quinapril');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Viagra®	', 'Sildenafil');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Zofran®', 'Ondansetron');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Tamiflu®', 'Oseltamivir');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Rheumatrex®', 'Methotrexate');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Pradaxa®', 'Dabigatran');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Uceris®', 'Budesonide');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Cardura®', 'Doxazosin');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Pristiq®', 'Desvenlafaxine');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Humalog®', 'Insulin Lispro');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Biaxin®', 'Clarithromycin');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Buspar®', 'Buspirone');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Proscar®', 'Finasteride');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Nizoral®', 'Ketoconazole');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('VESIcare®', 'Solifenacin');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Dolophine®', 'Methadone');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Minocin®', 'Minocycline');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Pyridium®', 'Phenazopyridine');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Aldactone®', 'Spironolactone');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Levitra®', 'Vardenafil');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Clovate®', 'Clobetasol');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Tessalon®', 'Benzonatate');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Depakote®', 'Divalproex');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Avodart®', 'Dutasteride');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Uloric®', 'Febuxostat');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Lamictal®', 'Lamotrigine');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Pamelor®', 'Nortriptyline');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Amaryl®', 'Glimepiride');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Aciphex®', 'Rabeprazole');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Enbrel®', 'Etanercept');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Bystolic®', 'Nebivolol');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Relafen®', 'Nabumetone');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Procardia®', 'Nifedipine');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Macrobid®', 'Nitrofurantoin');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('NitroStat® SL', 'Nitroglycerine');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Ditropan®', 'Oxybutynin');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Cialis®', 'Tadalifil');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Kenalog®', 'Triamcinolone');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Exelon®', 'Rivastigmine');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Prevacid®', 'Lansoprazole');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Ceftin®', 'Cefuroxime');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Robaxin®', 'Methocarbamol');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Travatan®', 'Travoprost');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Latuda®', 'Lurasidone');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Hytrin®', 'Terazosin');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Imitrex®', 'Sumatriptan');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Evista®', 'Raloxifene');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Remeron® ', 'Mirtazepine');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Humira®', 'Adalimumab');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Cogentin®', 'Benztropine');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Gablofen®', 'Baclofen');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Apresoline®', 'Hydralazine');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Bactroban®', 'Mupirocin');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Inderal®', 'Propranolol');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Mycostatin®', 'Nystatin');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Verelan®', 'Verapamil');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Estrace®', 'Estradiol');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Dilantin®', 'Phenytoin');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Tricor®', 'Fenofibrate');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Victoza®', 'Liraglutide');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Brilinta®', 'Ticagrelor');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Voltaren®', 'Diclofenac');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Onglyza®', 'Saxagliptin');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Aleve®', 'Naproxen');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Zanaflex®', 'Tizanidine');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Amphetamine/Dextro-amphetamine',  'Adderall®');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Amoxicillin+Clavulanate', 'Augmentin®');
INSERT INTO DrugList (medication_name, generic_name) VALUES ('Ezetimibe+Simvastatin',   'Vytorin®');

/*
 * Insurance Stuff (this has no home apparantly, which is what it deserves!!)
 * 
 */
DROP TABLE IF EXISTS Insurance;

CREATE TABLE Insurance (
	insurance_id int NOT NULL AUTO_INCREMENT,
	-- 
	PRIMARY KEY (insurance_id)
);
