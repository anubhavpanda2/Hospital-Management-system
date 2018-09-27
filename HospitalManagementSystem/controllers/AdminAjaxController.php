<?php

	class AdminAjaxController extends BaseController{

		public function adminLogin(){
			cleanVars('POST');
			$username=$_POST['username'];
			$password=$_POST['password'];
			$isAdmin=getData("SELECT COUNT(*) FROM users U,administrators A WHERE U.username='$username' AND A.uuid=U.uuid",0)[0];
			if($isAdmin==1){
				session_start();
				$_SESSION['admin']='admin';
			}
			$code=authLogin($username,$password,'users');
			die($isAdmin==1?'SUCCESSFUL':'FAILED');
		}			

		public function updateTag(){
			session_start();
			trimVars('POST');
			cleanVars('POST');
			if(!isset($_SESSION['admin'])){
				return "INSUFFICIENT_PREVILEGES";
			}
			else{
				$oldTag=$_POST['oldTag'];
				$newTag=$_POST['newTag'];
				$existsCount=getData("SELECT COUNT(*) FROM tags WHERE tagtext = '$newTag'",0)[0];
				if($existsCount==1){
					return "ALREADY_EXISTS";
				}
				$existsCount=getData("SELECT COUNT(*) FROM tags WHERE tagtext = '$oldTag'",0)[0];
				if($existsCount==1){
					updateValues('tagtext',$oldTag,array('tagtext' => $newTag),'tags');
					return "SUCCESSFUL";
				}
				else{
					return "ERROR";
				}
			}
		}

		public function addTag(){
			session_start();
			if(!isset($_SESSION['admin'])){
				return "INSUFFICIENT_PREVILEGES";
			}
			trimVars('POST');
			cleanVars('POST');
			$existsCount=getData("SELECT COUNT(*) FROM tags WHERE tagtext = '".$_POST['text']."'",0)[0];
			if($existsCount==1){
				return "ALREADY_EXISTS";
			}
			insertValues(array('tagtext' => $_POST['text'],'coverimageuid' => 'default'),'tags');
			return "SUCCESSFUL";
		}

		public function deleteTag(){
			session_start();
			if(!isset($_SESSION['admin'])){
				return "INSUFFICIENT_PREVILEGES";
			}
			$parts=explode('/',$_SERVER['HTTP_REFERER']);
			$tag=Helper::decodeTag($parts[4]);
			if($tag==='Default'){
				return "CANNOT_DELETE";
			}
			$existsCount=getData("SELECT COUNT(*) FROM tags WHERE tagtext = '".$tag."'",0)[0];
			if($existsCount===0){
				return "DOESNT_EXIST";
			}
			$tagID=getData("SELECT tagid FROM tags WHERE tagtext='".$tag."'",0)[0];
			$imagesWithThisTag=getJSON(getData("SELECT imid,imhash FROM tagmappings TM,images I WHERE tagid = ".$tagID." AND imid NOT IN (SELECT DISTINCT imid FROM tagmappings WHERE tagid != ".$tagID.") AND TM.imid = I.imid",1),array("imid","imhash"));
			foreach($imagesWithThisTag as $image){
				deleteRow('imid',$image['imid'],'images');
				unlink('content/'.$image['imhash'].'.jpg') or die('ERROR');
				deleteRow('imid',$image['imid'],'sliderimg');
			}
			deleteRow('tagID',$tag,'tags');
			return "SUCCESSFUL";
		}

		public function removeFromSlider(){
			trimVars('POST');
			cleanVars('POST');
			$count=getData("SELECT COUNT(imid) FROM sliderimg",0)[0];
			if($count==3){
				die("CANNOT_REMOVE");
			}
			else{
				$imid=getData("SELECT imid FROM images WHERE imhash='".$_POST['img']."'",0)[0];
				deleteRow('imid',$imid,'sliderimg');
				die("SUCCESSFUL");
			}
		}

		public function addToSlider(){
			session_start();
			if(!isset($_SESSION['admin'])){
				return "INSUFFICIENT_PREVILEGES";
			}
			trimVars('POST');
			cleanVars('POST');
			$imhash=$_POST['imhash'];
			$imid=getData("SELECT imid FROM images WHERE imhash='".$imhash."'",0)[0];
			$exists=getData("SELECT COUNT(*) FROM sliderimg WHERE imid=".$imid,0)[0];
			if($exists==1){
				die("EXISTS");
			}
			else{
				insertValues(array('imid' => $imid),'sliderimg');
				die("SUCCESSFUL");
			}
		}	

		public function approveComment(){
			session_start();
			if(!isset($_SESSION['admin'])){
				return "INSUFFICIENT_PREVILEGES";
			}
			trimVars('POST');
			cleanVars('POST');
			updateValues('comment_id',$_POST['comment_id'],array('approved' => 1),'comments');
		}

		public function deleteComment(){
			session_start();
			if(!isset($_SESSION['admin'])){
				return "INSUFFICIENT_PREVILEGES";
			}
			trimVars('POST');
			cleanVars('POST');
			updateValues('comment_id',$_POST['comment_id'],array('approved' => -1),'comments');
		}
	}

?>