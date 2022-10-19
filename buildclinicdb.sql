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

-- $$ The comments I leave start with "$$" - Nick

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
	primary_phone int NOT NULL,
	secondary_phone	int,
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

--// Sample Data
--INSERT INTO Patient (first_name, last_name, middle_name, DOB, sex, gender, primary_phone, secondary_phone, email, address_id, billing_id, insurance_id, pharmacy_id, lab_destid, minor, guardian, pcp_id, prev_note_id, emergency_contact1, emergency_contact2) 
--VALUES ('Nick', 'Danger', 'Does', '1999-07-22', 'M', '1', '18027678888', '18023497898', 'nickdangeriscool@gmail.com', '1', '1', '1', '1', '0', '0', '1', '1', '2', '10', '11');
INSERT INTO Patient VALUES ('Nick', 'Danger', 'Does', '1999-07-22', 'M', '1', '18027678888', '18023497898', 'nickdangeriscool@gmail.com', '1', '1', '1', '1', '0', '0', '1', '1', '2', '10', '11');


DROP TABLE IF EXISTS Users;

CREATE TABLE Users (
	user_id	int NOT NULL AUTO_INCREMENT,
	user_name	varchar(50) NOT NULL,
	permission	TINYINT NOT NULL,
	job_title	varchar(50),
	phone	int,
	email	varchar(40),
	first_name	varchar(40),
	last_name	varchar(40),
	pwd	varchar(40),
	--
	PRIMARY KEY (user_id)
);

--// Sample Data
--INSERT INTO Users (user_name, permission, job_title, phone, email, first_name, last_name, pwd) VALUES ('JoeyDanger', '1', 'Pediatrician', '18024457689', 'joeydanger@uppervalleyhealth.org', 'Joey', 'Danger');
INSERT INTO Users VALUES ('JoeyDanger', '1', 'Pediatrician', '18024457689', 'joeydanger@uppervalleyhealth.org', 'Joey', 'Danger');

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

--// Sample Data
--INSERT INTO Addresses (street, city, state_abbr, zip) VALUES ('1379 Maple St', 'Vergennes', 'VT', '05491');
INSERT INTO Addresses VALUES ('12 North Main St', 'Randolph', 'VT', '05060');
INSERT INTO Addresses VALUES ('1390 Monti Rd', 'Northfield', 'VT', '05663');


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
	lab_dest_id int, -- $$ Is this meant to be labdist?
	demographics  varchar(60000),
	comments varchar(60000),
	--
	PRIMARY KEY (note_id), 
	--FOREIGN KEY (patient_id)
	FOREIGN KEY (appointment_id),
	FOREIGN KEY (ros_id),
	FOREIGN KEY (med_profile_id),
	FOREIGN KEY (laborder_id),
	FOREIGN KEY (lab_dest_id)
);

-- CREATE INDEX patient_id_idx ON Note (patient_id);   $$ Do we want patient_id to be an index within Note?

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


DROP TABLE IF EXISTS EmergencyContact;

CREATE TABLE EmergencyContact (
	contact_id	int NOT NULL AUTO_INCREMENT,
	contact_name 	varchar(50) NOT NULL,
	relationship	varchar(30) NOT NULL,
	phone	int NOT NULL,
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
	phone	int,
	email	varchar(50),
	--
	PRIMARY KEY (pharmacy_id),
	FOREIGN KEY (address_id)
);

--// Sample Data
INSERT INTO Pharmacy VALUES ('Rite Aid Randolph', '2', '18027283722', '05060@riteaid.com');


DROP TABLE IF EXISTS Prescriptions;

CREATE TABLE Prescriptions (
	prescription_id	int NOT NULL AUTO_INCREMENT,
	patient_id	int NOT NULL,
	status	int NOT NULL, -- Things to Consider #5
	general_notes	varchar(5000),
	refills	int NOT NULL,
	quantity_days	int NOT NULL,
	quantity_total	int NOT NULL,
	dosage	varchar(50) NOT NULL,
	medication_id	int NOT NULL,
	pharmacy_id	int NOT NULL,
	doctor_id int NOT NULL,	-- Things to Consider #2
	--
	PRIMARY KEY (prescription_id),
	FOREIGN KEY (patient_id),
	FOREIGN KEY (medication_id),
	FOREIGN KEY (pharmacy_id),
	FOREIGN KEY (doctor_id)
);

-- Labs

DROP TABLE IF EXISTS LabDest;

CREATE TABLE LabDest (
	labdest_id	int NOT NULL AUTO_INCREMENT,
	labdest_name	varchar(50),
	address_id	int,
	phone	int,
	--
	PRIMARY KEY (labdest_id),
	FOREIGN KEY (address_id)
);

--// Sample Data
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

--// Sample Data 
 
DROP TABLE IF EXISTS LabList;

CREATE TABLE LabList ( -- List OF ALL labs that can be ordered, pairs lab_id WITH the name OF the lab (similar to DrugList, except that druglist doesn't contain all drugs)
	lab_id	int NOT NULL AUTO_INCREMENT,
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

--// Sample Data
INSERT INTO DrugList VALUES ('Aspirin', 'acetylsalicylic acid');
INSERT INTO DrugList Values ('Synthroid', 'Levothyroxine');
INSERT INTO DrugList Values ('Vicodin', 'Hydrocodone/APAP');
INSERT INTO DrugList Values ('Amoxil', 'Amoxicillin');
INSERT INTO DrugList Values ('Prinivil',  'Lisinopril');
INSERT INTO DrugList Values ('Nexium', 'Esomeprazole');
INSERT INTO DrugList Values ('Lipitor', 'Atorvastatin');
INSERT INTO DrugList Values ('Zocor', 'Simvastatin');
INSERT INTO DrugList Values ('Plavix', 'Clopidogrel');
INSERT INTO DrugList Values ('Singulair', 'Montelukast');
INSERT INTO DrugList Values ('Crestor', 'Rosuvastatin');
INSERT INTO DrugList Values ('Lopressor', 'Metoprolol');
INSERT INTO DrugList Values ('Lexapro', 'Escitalopram');
INSERT INTO DrugList Values ('Zithroma', 'Azithromycin');
INSERT INTO DrugList Values ('ProAir HF',  'Albuterol');
INSERT INTO DrugList Values ('HCTZ', 'Hydrochlorothiazide');
INSERT INTO DrugList Values ('Glucophage', 'Metformin');
INSERT INTO DrugList Values ('Zoloft', 'Sertraline');
INSERT INTO DrugList Values ('Advil', 'Ibuprofen');
INSERT INTO DrugList Values ('Ambien', 'Zolpidem');
INSERT INTO DrugList Values ('Lasix', 'Furosemide');
INSERT INTO DrugList Values ('Prilosec', 'Omeprazole');
INSERT INTO DrugList Values ('Desyrel', 'Trazodone');
INSERT INTO DrugList Values ('Diovan', 'Valsartan');
INSERT INTO DrugList Values ('Ultram', 'Tramadol');
INSERT INTO DrugList Values ('Cymbalta', 'Duloxetine');
INSERT INTO DrugList Values ('Coumadin', 'Warfarin');
INSERT INTO DrugList Values ('Norvasc', 'Amlodipine');
INSERT INTO DrugList Values ('Percocet', 'Oxycodone/APAP');
INSERT INTO DrugList Values ('Seroquel', 'Quetiapine');
INSERT INTO DrugList Values ('Phenergan', 'Promethazine');
INSERT INTO DrugList Values ('Flonase', 'Fluticasone');
INSERT INTO DrugList Values ('Xanax', 'Alprazolam');
INSERT INTO DrugList Values ('Klonopin', 'Clonazepam');
INSERT INTO DrugList Values ('Lotensin', 'Benazepril');
INSERT INTO DrugList Values ('Mobic', 'Meloxicam');
INSERT INTO DrugList Values ('Celexa', 'Citalopram');
INSERT INTO DrugList Values ('Keflex', 'Cephalexin');
INSERT INTO DrugList Values ('Spiriva', 'Tiotropium');
INSERT INTO DrugList Values ('Neurontin', 'Gabapentin');
INSERT INTO DrugList Values ('Abilify', 'Aripiprazole');
INSERT INTO DrugList Values ('K-Tab,' 'Potassium');
INSERT INTO DrugList Values ('Flexeril', 'Cyclobenzaprine');
INSERT INTO DrugList Values ('Medrol', 'Methylprednisolone');
INSERT INTO DrugList Values ('Concerta', 'Methylphenidate');
INSERT INTO DrugList Values ('Claritin', 'Loratadine');
INSERT INTO DrugList Values ('Coreg', 'Carvedilol');
INSERT INTO DrugList Values ('Soma', 'Carisoprodol');
INSERT INTO DrugList Values ('Lanoxin', 'Digoxin');
INSERT INTO DrugList Values ('Namenda', 'Memantine');
INSERT INTO DrugList Values ('Tenormin', 'Atenolol');
INSERT INTO DrugList Values ('Valium', 'Diazepam');
INSERT INTO DrugList Values ('OxyContin', 'Oxycodone');
INSERT INTO DrugList Values ('Actonel', 'Risedronate');
INSERT INTO DrugList Values ('Folvite', 'Folic Acid');
INSERT INTO DrugList Values ('Hyzaar', 'Losartan+HCTZ');
INSERT INTO DrugList Values ('Deltasone', 'Prednisone');
INSERT INTO DrugList Values ('Omnipred', 'Prednisolone');
INSERT INTO DrugList Values ('Fosamax', 'Alendronate');
INSERT INTO DrugList Values ('Protonix', 'Pantoprazole');
INSERT INTO DrugList Values ('Flomax', 'Tamsulosin');
INSERT INTO DrugList Values ('Dyazide', 'Triamterene+HCTZ');
INSERT INTO DrugList Values ('Paxil', 'Paroxetine');
INSERT INTO DrugList Values ('Suboxone', 'Buprenorphine+Naloxone');
INSERT INTO DrugList Values ('Vasotec', 'Enalapril');
INSERT INTO DrugList Values ('Mevacor', 'Lovastatin');
INSERT INTO DrugList Values ('Actos', 'Pioglitazone');
INSERT INTO DrugList Values ('Pravachol', 'Pravastatin');
INSERT INTO DrugList Values ('Prozac', 'Fluoxetine');
INSERT INTO DrugList Values ('Levemir', 'Insulin Detemir');
INSERT INTO DrugList Values ('Diflucan', 'Fluconazole');
INSERT INTO DrugList Values ('Levaquin', 'Levofloxacin');
INSERT INTO DrugList Values ('Xarelto', 'Rivaroxaban');
INSERT INTO DrugList Values ('Celebrex', 'Celecoxib');
INSERT INTO DrugList Values ('Tylenol#2', 'Codeine/APAP' );
INSERT INTO DrugList Values ('Nasonex', 'Mometasone');
INSERT INTO DrugList Values ('Cipro', 'Ciprofloxacin');
INSERT INTO DrugList Values ('Lyrica', 'Pregabalin');
INSERT INTO DrugList Values ('Novolog', 'Insulin Aspart');
INSERT INTO DrugList Values ('Effexor', 'Venlafaxine');
INSERT INTO DrugList Values ('Ativan', 'Lorazepam');
INSERT INTO DrugList Values ('Zetia', 'Ezetimibe');
INSERT INTO DrugList Values ('Premarin', 'Estrogen');
INSERT INTO DrugList Values ('Zyloprim', 'Allopurinol');
INSERT INTO DrugList Values ('Pen VK,' 'Penicillin' );
INSERT INTO DrugList Values ('Januvia', 'Sitagliptin');
INSERT INTO DrugList Values ('Elavil', 'Amitriptyline');
INSERT INTO DrugList Values ('Catapres', 'Clonidine');
INSERT INTO DrugList Values ('Xalatan', 'Latanoprost');
INSERT INTO DrugList Values ('Vyvanse', 'Lisdexamfetamine');
INSERT INTO DrugList Values ('Advair', 'Fluticasone+Salmeterol');
INSERT INTO DrugList Values ('Symbicort', 'Budesonide+Formoterol');
INSERT INTO DrugList Values ('Dexilant', 'Dexlansoprazole');
INSERT INTO DrugList Values ('Diabeta', 'Glyburide');
INSERT INTO DrugList Values ('Zyprexa', 'Olanzapine');
INSERT INTO DrugList Values ('Detrol', 'Tolterodine');
INSERT INTO DrugList Values ('Zantac', 'Ranitidine');
INSERT INTO DrugList Values ('Pepcid', 'Famotidine');
INSERT INTO DrugList Values ('Cardizem', 'Diltiazem');
INSERT INTO DrugList Values ('Lantus', 'Insulin Glargine');
INSERT INTO DrugList Values ('Prinizide', 'Lisinopril+HCTZ');
INSERT INTO DrugList Values ('Wellbutrin®', 'Bupropion');
INSERT INTO DrugList Values ('Zyrtec®', 'Cetirizine');
INSERT INTO DrugList Values ('Topamax®', 'Topiramate');
INSERT INTO DrugList Values ('Valtrex®', 'Valacyclovir');
INSERT INTO DrugList Values ('Lunesta®', 'Eszopiclone');
INSERT INTO DrugList Values ('Zovirax®', 'Acyclovir');
INSERT INTO DrugList Values ('Omnicef®', 'Cefdinir');
INSERT INTO DrugList Values ('Cleocin®', 'Clindamycin');
INSERT INTO DrugList Values ('Keppra®', 'Levetiracetam');
INSERT INTO DrugList Values ('Lopid®', 'Gemfibrozil');
INSERT INTO DrugList Values ('Robitussin®', 'Guaifenesin');
INSERT INTO DrugList Values ('Glucotrol®', 'Glipizide');
INSERT INTO DrugList Values ('Avapro®', 'Irbesartan');
INSERT INTO DrugList Values ('Reglan®', 'Metoclopramide');
INSERT INTO DrugList Values ('Cozaar®', 'Losartan');
INSERT INTO DrugList Values ('Dramamine®', 'Meclizine');
INSERT INTO DrugList Values ('Flagyl®', 'Metronidazole');
INSERT INTO DrugList Values ('Caltrate®', 'Vitamin D');
INSERT INTO DrugList Values ('AndroGel®', 'Testosterone');
INSERT INTO DrugList Values ('Requip®', 'Ropinirole');
INSERT INTO DrugList Values ('Risperdal®', 'Risperidone');
INSERT INTO DrugList Values ('Patanol®', 'Olopatadine');
INSERT INTO DrugList Values ('Aricept®', 'Donepezil');
INSERT INTO DrugList Values ('Focalin®', 'Dexmethylphenidate');
INSERT INTO DrugList Values ('Lovenox®', 'Enoxaparin');
INSERT INTO DrugList Values ('Duragesic®', 'Fentanyl');
INSERT INTO DrugList Values ('Bentyl®', 'Dicyclomine');
INSERT INTO DrugList Values ('Xopenex®', 'Levalbuterol');
INSERT INTO DrugList Values ('Strattera®', 'Atomoxetine');
INSERT INTO DrugList Values ('Altace®', 'Ramipril');
INSERT INTO DrugList Values ('Restoril®', 'Temazepam');
INSERT INTO DrugList Values ('Adipex® P', 'Phentermine');
INSERT INTO DrugList Values ('Accupril®', 'Quinapril');
INSERT INTO DrugList Values ('Viagra®	', 'Sildenafil');
INSERT INTO DrugList Values ('Zofran®', 'Ondansetron');
INSERT INTO DrugList Values ('Tamiflu®', 'Oseltamivir');
INSERT INTO DrugList Values ('Rheumatrex®', 'Methotrexate');
INSERT INTO DrugList Values ('Pradaxa®', 'Dabigatran');
INSERT INTO DrugList Values ('Uceris®', 'Budesonide');
INSERT INTO DrugList Values ('Cardura®', 'Doxazosin');
INSERT INTO DrugList Values ('Pristiq®', 'Desvenlafaxine');
INSERT INTO DrugList Values ('Humalog®', 'Insulin Lispro');
INSERT INTO DrugList Values ('Biaxin®', 'Clarithromycin');
INSERT INTO DrugList Values ('Buspar®', 'Buspirone');
INSERT INTO DrugList Values ('Proscar®', 'Finasteride');
INSERT INTO DrugList Values ('Nizoral®', 'Ketoconazole');
INSERT INTO DrugList Values ('VESIcare®', 'Solifenacin');
INSERT INTO DrugList Values ('Dolophine®', 'Methadone');
INSERT INTO DrugList Values ('Minocin®', 'Minocycline');
INSERT INTO DrugList Values ('Pyridium®', 'Phenazopyridine');
INSERT INTO DrugList Values ('Aldactone®', 'Spironolactone');
INSERT INTO DrugList Values ('Levitra®', 'Vardenafil');
INSERT INTO DrugList Values ('Clovate®', 'Clobetasol');
INSERT INTO DrugList Values ('Tessalon®', 'Benzonatate');
INSERT INTO DrugList Values ('Depakote®', 'Divalproex');
INSERT INTO DrugList Values ('Avodart®', 'Dutasteride');
INSERT INTO DrugList Values ('Uloric®', 'Febuxostat');
INSERT INTO DrugList Values ('Lamictal®', 'Lamotrigine');
INSERT INTO DrugList Values ('Pamelor®', 'Nortriptyline');
INSERT INTO DrugList Values ('Amaryl®', 'Glimepiride');
INSERT INTO DrugList Values ('Aciphex®', 'Rabeprazole');
INSERT INTO DrugList Values ('Enbrel®', 'Etanercept');
INSERT INTO DrugList Values ('Bystolic®', 'Nebivolol');
INSERT INTO DrugList Values ('Relafen®', 'Nabumetone');
INSERT INTO DrugList Values ('Procardia®', 'Nifedipine');
INSERT INTO DrugList Values ('Macrobid®', 'Nitrofurantoin');
INSERT INTO DrugList Values ('NitroStat® SL', 'Nitroglycerine');
INSERT INTO DrugList Values ('Ditropan®', 'Oxybutynin');
INSERT INTO DrugList Values ('Cialis®', 'Tadalifil');
INSERT INTO DrugList Values ('Kenalog®', 'Triamcinolone');
INSERT INTO DrugList Values ('Exelon®', 'Rivastigmine');
INSERT INTO DrugList Values ('Prevacid®', 'Lansoprazole');
INSERT INTO DrugList Values ('Ceftin®', 'Cefuroxime');
INSERT INTO DrugList Values ('Robaxin®', 'Methocarbamol');
INSERT INTO DrugList Values ('Travatan®', 'Travoprost');
INSERT INTO DrugList Values ('Latuda®', 'Lurasidone');
INSERT INTO DrugList Values ('Hytrin®', 'Terazosin');
INSERT INTO DrugList Values ('Imitrex®', 'Sumatriptan');
INSERT INTO DrugList Values ('Evista®', 'Raloxifene');
INSERT INTO DrugList Values ('Remeron® ', 'Mirtazepine');
INSERT INTO DrugList Values ('Humira®', 'Adalimumab');
INSERT INTO DrugList Values ('Cogentin®', 'Benztropine');
INSERT INTO DrugList Values ('Gablofen®', 'Baclofen');
INSERT INTO DrugList Values ('Apresoline®', 'Hydralazine');
INSERT INTO DrugList Values ('Bactroban®', 'Mupirocin');
INSERT INTO DrugList Values ('Inderal®', 'Propranolol');
INSERT INTO DrugList Values ('Mycostatin®', 'Nystatin');
INSERT INTO DrugList Values ('Verelan®', 'Verapamil');
INSERT INTO DrugList Values ('Estrace®', 'Estradiol');
INSERT INTO DrugList Values ('Dilantin®', 'Phenytoin');
INSERT INTO DrugList Values ('Tricor®', 'Fenofibrate');
INSERT INTO DrugList Values ('Victoza®', 'Liraglutide');
INSERT INTO DrugList Values ('Brilinta®', 'Ticagrelor');
INSERT INTO DrugList Values ('Voltaren®', 'Diclofenac');
INSERT INTO DrugList Values ('Onglyza®', 'Saxagliptin');
INSERT INTO DrugList Values ('Aleve®', 'Naproxen');
INSERT INTO DrugList Values ('Zanaflex®', 'Tizanidine');
INSERT INTO DrugList Values ('Amphetamine/Dextro-amphetamine',  'Adderall®');
INSERT INTO DrugList Values ('Amoxicillin+Clavulanate', 'Augmentin®');
INSERT INTO DrugList Values ('Ezetimibe+Simvastatin',   'Vytorin®');

/*
 * Insurance Stuff (this has no home apparantly, which is what it deserves!!)
 * 
 */

CREATE TABLE Insurance (
	insurance_id int NOT NULL AUTO_INCREMENT,
	--
	PRIMARY KEY (insurance_id)
);
