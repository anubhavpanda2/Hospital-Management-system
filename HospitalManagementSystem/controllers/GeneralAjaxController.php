<?php

	class GeneralAjaxController extends BaseController{

		public function login(){
			cleanVars('POST');
			$username=$_POST['username'];
			$password=$_POST['password'];
			$code=authLogin($username,$password,'users');
			if($code==1){
				session_start();
				$_SESSION['username']=$username;
			}
			die($code==1?'SUCCESSFUL':'FAILED');
		}

		public function register(){
			$echoData=array();
			$echoData['CODE']='BLANK';
			cleanVars('POST');
			trimVars('POST');
			$email=$_POST['register_email'];
			$name=$_POST['name'];
			if($email===''){
				$echoData['ERRORFIELD']='EMAIL';
				die(json_encode($echoData));
			}
			if(!is_email($email)){
				$echoData['CODE']='INVALID';
				$echoData['ERRORFIELD']='EMAIL';
				die(json_encode($echoData));
			}
			if($name===''){
				$echoData['CODE']='BLANK';
				$echoData['ERRORFIELD']='NAME';
				die(json_encode($echoData));	
			}
			else{
				$nameParts=explode(' ',$name);
				$firstName=$nameParts[0];
				if(sizeof($nameParts)==1)
					$lastName="";
				else
					$lastName=$nameParts[sizeof($nameParts)-1];
			}
			$password=$_POST['register_password'];
			if(strlen($password)<6){
				$echoData['ERRORFIELD']='PASSWORD';
				die(json_encode($echoData));
			}
			$captcha=$_POST['captcha'];
			session_start();
			if($captcha!=$_SESSION['captcha']){
				if($captcha!=='')
					$echoData['CODE']='INVALID';
				$echoData['ERRORFIELD']='CAPTCHA';
				die(json_encode($echoData));
			}
			$code=registerUser(array('username' => $email,'password' => $password,'firstname' => $firstName,'lastname' => $lastName),'users');
			if($code=='EXISTS'){
				$echoData['CODE']='EXISTS';
				$echoData['ERRORFIELD']='EMAIL';
				die(json_encode($echoData));
			}
			else{
				if(!isset($_SESSION))
					session_start();
				$_SESSION['username']=$email;
				die("SUCCESSFUL");
			}			
		}

		public function uploadImage(){
			trimVars('POST');
			cleanVars('POST');
			if(isset($_POST['imageTitle'])&&isset($_POST['description'])){
				session_start();
				var_dump($_POST);
				die();
				$userid=getData("SELECT uuid FROM users WHERE username='".$_SESSION['username']."'",0)[0];
				$pendingHash=getData("SELECT imhash FROM images WHERE uploaded_by=".$userid." AND status=-2",0)[0];
				updateValues('imhash',$pendingHash,array('title' => $_POST['imageTitle'],'description' => $_POST['description'],'uploaded_at' => date('Y-m-d H:i:s'),'status' => 0),'images');
				$imid=getData("SELECT imid FROM images WHERE imhash='".$pendingHash."'",0)[0];
				$tag=Helper::decodeTag($_POST['tagList']);
				$tagid=getData("SELECT tagid FROM tags WHERE tagtext='".$tag."'",0)[0];
				insertValues(array('imageid' => $imid,'tagid' => $tagid),'tagmappings');
				rename('../../temp/'.$pendingHash.'.jpg','../../content/'.$pendingHash.'.jpg');
				die($pendingHash);
			}
		}		

		public function postComment(){
			session_start();
			if(!isset($_SESSION['username'])){
				return "INVALID_SESSION";
			}
			trimVars('POST');
			cleanVars('POST');
			//Check for empty comment
			$comment=$_POST['comment'];
			if(trim($comment)===''){
				return "INVALID_COMMENT";
			}
			//Check for invalid photo
			$imhash=$_POST['imhash'];
			$exists=getData("SELECT COUNT(*) FROM images WHERE imhash='$imhash'",0)[0];
			if($exists!=1){
				return "INVALID_IMAGE";
			}
			$username=$_SESSION['username'];
			$userid=getData("SELECT uuid FROM users WHERE username='".$username."'",0)[0];
			list($imid,$status)=getData("SELECT imid,status FROM images WHERE imhash='".$imhash."'",0);
			if($status!=1){
				return "INVALID_IMAGE";
			}
			$comment=base64_encode(htmlspecialchars($_POST['comment']));
			insertValues(array(
				'comment_on' => $imid,
				'comment_by' => $userid,
				'text' => $comment,
				'time' => date('Y-m-d H:i:s')
			),'comments');
			return "SUCCESSFUL";
		}

		public function changePassword(){
			session_start();
			if(!isset($_SESSION['username'])){
				return 'INVALID_SESSION';
			}
			$username=$_SESSION['username'];
			$curPassword=$_POST['curPassword'];
			$newPassword=$_POST['newPassword'];
			$repeatPassword=$_POST['repeatPassword'];
			if($curPassword===$newPassword)
				return "SAME_OLD_PASSWORD";
			else if($newPassword!=$repeatPassword)
				return "PASSWORD_MISMATCH";
			return changePassword($username,$curPassword,$newPassword,'users');			
		}

	}	

?>