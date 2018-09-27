<?php

	class GeneralController extends BaseController{

		public function homePage(){			
			return View::make('index');
		}

		public function patientPage(){
			$patientInfo=getJSON(getData("SELECT P.patient_id,P.name,P.address,P.ph_no,dob,sex,DOC.name 
										  FROM patients P,diagnosis D,doctors DOC 
										  WHERE P.patient_id='00001' 
										  AND P.patient_id=D.patient_id 
										  AND D.doctor_id=DOC.doctor_id",
										1),array("patient_id","name","address","ph_no","dob","sex","doctor"))[0];	
			if($patientInfo['sex']=='m')
					$patientInfo['sex']='Male';
				else
					$patientInfo['sex']='Female';
			return View::make('patient')->with(array('patient' => $patientInfo));
		}
		public function staffPage(){
			return View::make('staff');
		}

		public function newpatientPage(){
			return View::make('newpatient');
		}
	}
?>