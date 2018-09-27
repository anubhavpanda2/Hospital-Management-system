<?php

	class AdminController extends BaseController{
		public function showAdminLogin(){
			session_start();
			if(isset($_SESSION['admin'])){
				return Redirect::to('admin');
			}
			else if(isset($_SESSION['username'])){
				return Redirect::to('dashboard');
			}				
			else{
				return View::make('admin_login');
			}
		}

		public function showAdminDashboard(){
			session_start();
			if(!isset($_SESSION['admin']))
				return Redirect::to('admin/login')->with('error','You do not have sufficient privileges to view this page');				
			$pendingImages=getData("SELECT imhash,title,uploaded_at,IF(width>height,'h','w') AS orientation FROM images I WHERE status=0 LIMIT 8",1);
			$tasks['pendingImages']=getJSON($pendingImages,array('imhash','title','uploaded_at','orientation'));
			$tasks['totalPendingImages']=getData("SELECT COUNT(*) FROM images WHERE status=0",0)[0];

			$approvedImages=getData("SELECT imhash,title,uploaded_at,IF(width>height,'h','w') AS orientation FROM images I WHERE status=1 ORDER BY uploaded_at DESC LIMIT 8",1);
			$tasks['approvedImages']=getJSON($approvedImages,array('imhash','title','uploaded_at','orientation'));
			$tasks['totalApprovedImages']=getData("SELECT COUNT(*) FROM images WHERE status=1",0)[0];

			$disapprovedImages=getData("SELECT imhash,title,uploaded_at,IF(width>height,'h','w') AS orientation FROM images I WHERE status=-1 ORDER BY uploaded_at DESC LIMIT 8",1);
			$tasks['disapprovedImages']=getJSON($disapprovedImages,array('imhash','title','uploaded_at','orientation'));
			$tasks['totalDisapprovedImages']=getData("SELECT COUNT(*) FROM images WHERE status=-1",0)[0];

			$tagData=getJSON(getData("SELECT tagtext,SUM(IF(imcount='imcount',0,imcount)) AS
											imcount FROM (
												(	
													(SELECT tagtext,'imcount' FROM tags WHERE tagid!=0)
													UNION												
													(SELECT tagtext,COUNT(TM.imid) AS imcount FROM tags T,tagmappings TM,images I WHERE T.tagid = TM.tagid  AND T.tagid!=0 AND TM.imid = I.imid AND I.status=1 GROUP BY TM.tagid)
												)
											AS Z)
									        GROUP by tagtext",1),
									array('tagtext','imcount'));

			$l=sizeof($tasks['pendingImages']);			

			$sliderImages=getJSON(getData("SELECT imhash,title,IF(width>height,'h','w') AS orientation FROM sliderimg SI,images I WHERE SI.imid = I.imid",1),array('imhash','title','orientation'));
			$totalSliderImages=getData("SELECT COUNT(*) FROM sliderimg",0)[0];

			//Retrieve violations
			$activeViolations=getJSON(getData("SELECT report_id,uuid,reported_at,concat(firstname,\" \",lastname),imhash,IF(width>height,'h','w') AS orientation,title,cause FROM images I,users U,violation_reports R WHERE R.status=0 AND R.reporter = U.uuid AND R.imid = I.imid LIMIT 10",1),array('report_id','filed_by_uuid','filed_by','reported_at','imhash','orientation','title','cause'));

			//Retrieve violations for log table
			$pastViolations=getJSON(getData("SELECT report_id,username,reported_at,imhash,IF(width>height,'h','w') AS orientation,title,cause,R.status,feedback FROM images I,users U,violation_reports R WHERE R.status!=0 AND R.reporter = U.uuid AND R.imid = I.imid",1),array('report_id','filed_by','reported_at','imhash','orientation','title','cause','status','feedback'));

			//Retrieve top 10 unmoderated comments
			$comments=getJSON(getData("SELECT comment_id,imhash,title,uuid,concat(firstname,\" \",lastname),
									   text,time FROM comments C,images I,users U WHERE approved = 0 AND comment_on = I.imid 
									   AND comment_by = U.uuid LIMIT 10",1),
							  array(
							  		'comment_id','comment_on','image_title','commenter_user_id','commenter_shortname','comment_data','comment_timestamp'
							  ));

			$pastComments=getJSON(getData("SELECT comment_id,imhash,title,uuid,concat(firstname,\" \",lastname),
									   text,time,approved FROM comments C,images I,users U WHERE approved != 0 AND comment_on = I.imid 
									   AND comment_by = U.uuid LIMIT 10",1),
							  array(
							  		'comment_id','comment_on','image_title','commenter_user_id','commenter_shortname','comment_data','comment_timestamp','approved',
							  ));
			return View::make('admin_manage')->with(
				array
					('tasks' => $tasks,
					 'tagData' => $tagData,
					 'sliderImages' => $sliderImages,
					 'totalSliderImages' => $totalSliderImages,
					 'activeViolations' => $activeViolations,
					 'pastViolations' => $pastViolations,
					 'comments' => $comments,
					 'pastComments' => $pastComments
					)
				);
		}

		public function showAdminStats(){
			$tagData=getJSON(getData("SELECT tagtext,coverimageuid,SUM(IF(imcount='imcount',0,imcount)) AS
											imcount FROM (
												(	
													(SELECT tagtext,coverimageuid,'imcount' FROM tags WHERE tagid!=0)
													UNION												
													(SELECT tagtext,coverimageuid,COUNT(TM.imid) AS imcount FROM tags T,tagmappings TM,images I WHERE T.tagid = TM.tagid  AND T.tagid!=0 AND TM.imid = I.imid AND I.status=1 GROUP BY TM.tagid)
												)
											AS Z)
									        GROUP by tagtext",1),
									array('tagtext','coverhash','imcount'));
			$stats=getJSON(getData("SELECT T.tagtext,COUNT(TM.imid) AS cnt FROM tagmappings TM,(SELECT imid FROM images) I,tags T WHERE TM.imid=I.imid AND TM.tagid = T.tagid GROUP BY TM.tagid ORDER BY cnt DESC LIMIT 1",1),array('maxUploadTag','maxUploadTagCount'))[0];
			$stats['userCount']=getData("SELECT COUNT(uuid) FROM users",0)[0];
			list($stats['maxUploader_shortname'],$stats['maxUploader_user_id'],$stats['maxUploaderCount'])=getData("SELECT concat(firstname,\" \",lastname),uuid,imid,COUNT(*) as cnt FROM images I,users U WHERE U.uuid = I.uploaded_by GROUP BY uploaded_by ORDER BY cnt DESC LIMIT 1",0);
			list($stats['maxLikedHash'],$stats['maxLikedTitle'],$stats['maxLikedLikes'],$stats['maxLikedUploader_shortname'],$stats['maxLikedUploader_user_id'])=getData("SELECT imhash,title,likes,concat(firstname,\" \",lastname),uuid FROM images I,users U WHERE I.uploaded_by = U.uuid ORDER BY likes DESC LIMIT 1",0);
			list($stats['maxViewedHash'],$stats['maxViewedTitle'],$stats['maxViewedViews'],$stats['maxViewedUploader_shortname'],$stats['maxViewedUploader_user_id'])=getData("SELECT imhash,title,likes,concat(firstname,\" \",lastname),uuid FROM images I,users U WHERE I.uploaded_by = U.uuid ORDER BY views DESC LIMIT 1",0);
			return View::make('admin_statistics')->with(
				array(
					'stats' => $stats,
					'tagData' => $tagData
				)
			);
		}

		public function showAdminFlagged($criterion){
			session_start();
			if(!isset($_SESSION['admin']))
				return Redirect::to('admin/login')->with('error','You do not have sufficient privileges to view this page');
			$allowed_criterion=array('pending','archive');
			if(!in_array($criterion,$allowed_criterion)){
				App::abort('404');
			}
			$activeViolations=getJSON(getData("SELECT report_id,uuid,reported_at,concat(firstname,\" \",lastname),imhash,IF(width>height,'h','w') AS orientation,title,cause FROM images I,users U,violation_reports R WHERE R.status=0 AND R.reporter = U.uuid AND R.imid = I.imid LIMIT 10",1),array('report_id','filed_by_uuid','filed_by','reported_at','imhash','orientation','title','cause'));
			$pastViolations=getJSON(getData("SELECT report_id,username,reported_at,imhash,IF(width>height,'h','w') AS orientation,title,cause,R.status,feedback FROM images I,users U,violation_reports R WHERE R.status!=0 AND R.reporter = U.uuid AND R.imid = I.imid",1),array('report_id','filed_by','reported_at','imhash','orientation','title','cause','status','feedback'));
			$violations=getJSON(getData("SELECT report_id,uuid,concat(firstname,\" \",lastname),imhash,title,cause FROM images I,users U,violation_reports R WHERE R.status=0 AND R.reporter = U.uuid AND R.imid = I.imid",1),array('report_id','filed_by_uuid','filed_by','imhash','imtitle','cause'));
			$pastViolations=getJSON(getData("SELECT report_id,uuid,concat(firstname,\" \",lastname),imhash,title,cause,feedback FROM images I,users U,violation_reports R WHERE R.status!=0 AND R.reporter = U.uuid AND R.imid = I.imid",1),array('report_id','filed_by_uuid','filed_by','imhash','imtitle','cause','feedback'));
			return View::make('admin_violations')->with(
				array(
					'violations' => $violations,
					'pastViolations' => $pastViolations
				)
			);
		}

		public function showComments($criterion){
			session_start();
			if(!isset($_SESSION['admin']))
				return Redirect::to('admin/login')->with('error','You do not have sufficient privileges to view this page');
			$allowed_criterion=array('new','archive');
			if(!in_array($criterion,$allowed_criterion)){
				App::abort('404');
			}
			switch($criterion){
				case 'new':
					$comments=getJSON(getData("SELECT comment_id,imhash,title,uuid,concat(firstname,\" \",lastname),
									   text,time,approved FROM comments C,images I,users U WHERE approved = 0 AND comment_on = I.imid 
									   AND comment_by = U.uuid LIMIT 10",1),
							  array(
							  		'comment_id','comment_on','image_title','commenter_user_id','commenter_shortname','comment_data','comment_timestamp','status'
							  ));
					$title='New Comments';
					break;
				case 'archive':
					$comments=getJSON(getData("SELECT comment_id,imhash,title,uuid,concat(firstname,\" \",lastname),
									   text,time,approved FROM comments C,images I,users U WHERE approved != 0 AND comment_on = I.imid 
									   AND comment_by = U.uuid LIMIT 10",1),
							  array(
							  		'comment_id','comment_on','image_title','commenter_user_id','commenter_shortname','comment_data','comment_timestamp','status'							  		
							  ));
					$title='Old Comments';
					break;
			}
			
			return View::make('admin_comments')->with(
				array(
					'comments' => $comments,
					'title' => $title
				)
			);
		}

		public function showAdminImages($criterion){
			session_start();
			if(!isset($_SESSION['admin']))
				return Redirect::to('admin/login')->with('error','You do not have sufficient privileges to view this page');
			$allowed_criterion=array('pending','approved','disapproved','slider');
			if(!in_array($criterion,$allowed_criterion)){
				App::abort('404');
			}
			switch($criterion){
				case 'pending':
					$pendingImages=getData("SELECT imhash,title,uploaded_at,IF(width>height,'h','w') AS orientation FROM images I WHERE status=0 LIMIT 12",1);
					$tasks=getJSON($pendingImages,array('imhash','title','uploaded_at','orientation'));
					$title='Photos that need approval';
					break;

				case 'approved':
					$approvedImages=getData("SELECT imhash,title,uploaded_at,IF(width>height,'h','w') AS orientation FROM images I WHERE status=1 LIMIT 12",1);
					$tasks=getJSON($approvedImages,array('imhash','title','uploaded_at','orientation'));
					$title='Previously approved photos';
					break;

				case 'disapproved':
					$disapprovedImages=getData("SELECT imhash,title,uploaded_at,IF(width>height,'h','w') AS orientation FROM images I WHERE status=-1 LIMIT 12",1);
					$tasks=getJSON($disapprovedImages,array('imhash','title','uploaded_at','orientation'));
					$title='Previously disapproved photos';
					break;

				case 'slider':
					$tasks=getJSON(getData("SELECT imhash,title,uploaded_at,IF(width>height,'h','w') AS orientation FROM sliderimg SI,images I WHERE SI.imid = I.imid",1),array('imhash','title','uploaded_at','orientation'));
					$title='Home page slider';
					break;
			}
		
			return View::make('admin_images')->with(
				array(
					'title' => $title,
					'images' => $tasks

				)
			);
		}

		public function showAdminTags(){
			$tagData=getJSON(getData("SELECT tagtext,coverimageuid,SUM(IF(imcount='imcount',0,imcount)) AS
											imcount FROM (
												(	
													(SELECT tagtext,coverimageuid,'imcount' FROM tags WHERE tagid!=0)
													UNION												
													(SELECT tagtext,coverimageuid,COUNT(TM.imid) AS imcount FROM tags T,tagmappings TM,images I WHERE T.tagid = TM.tagid  AND T.tagid!=0 AND TM.imid = I.imid AND I.status=1 GROUP BY TM.tagid)
												)
											AS Z)
									        GROUP by tagtext",1),
									array('tagtext','imhash','imcount'));
			return View::make('admin_tags')->with(
				array(
					'tags' => $tagData
				)
			);
		}	

		public function manageImage($imhash){
			session_start();
			if(!isset($_SESSION['admin'])){
				return Redirect::to('admin/login')->with('error','You do not have sufficient privileges to view this page');
			}
			$status=getData("SELECT status FROM images WHERE imhash='".$imhash."'",0)[0];
			if($status==1){				
				return Redirect::to('view/'.$imhash)->with('approveMsg','This image has already been approved for public viewing');
			}
			else if($status==-1){
				return Redirect::to('view/'.$imhash)->with('cancelMsg','This image has already been restricted from public viewing');;	
			}
			$tags=getJSON(getData("SELECT tagtext FROM tags",1),array('tagtext'));			
			$image=getData("SELECT imid,imhash,title,description,CONCAT(SUBSTRING_INDEX(username,'@',1),
							DATE_FORMAT(date_and_time_regn,'%H%i')) AS username,U.username,concat(firstname,\" \",lastname),
							date_taken,uploaded_at,width,height,size 
							FROM images I,users U 
							WHERE imhash='".$imhash."' AND I.uploaded_by = U.uuid",1);
			if($image->rowCount()==0) 
				App::abort('404');
			$image=getJSON($image,array('imid','imhash','title','description','username','uploaded_by','uploader_shortname','date_taken','uploaded_at','width','height','size'))[0];
			$image['date_taken']=date("Y/m/d",strtotime($image['date_taken']));
			$imageTags=getJSON(getData("SELECT tagtext FROM tags T,tagmappings TM WHERE TM.tagid = T.tagid AND TM.imid = ".$image['imid'],1),array('tagtext'));
			$tagsAssigned=array();
			foreach($tags as $tag){
				$tagsAssigned[$tag['tagtext']]=0;
				if(in_array($tag,$imageTags)){
					$tagsAssigned[$tag['tagtext']]=1;
				}					
			}
			$tags=$tagsAssigned;
			//return $image;
			return View::make('approve')->with(array('tags' => $tags,'image' => $image));
		}

		public function approveImage(){
			session_start();
			if(!isset($_SESSION['admin'])){
				return Redirect::to('admin/login')->with('error','You do not have sufficient privileges to view this page');
			}
			trimVars('POST');
			cleanVars('POST');
			$imhash=$_POST['imhash'];
			$imid=getData("SELECT imid FROM images WHERE imhash='".$imhash."'",0)[0];
			$maxTags=getData("SELECT COUNT(tagid) FROM tags",0)[0];
			$allTags=getData("SELECT tagid,tagtext FROM tags",1);
			$keys=array();
			$tags=array();
			while($row=$allTags->fetch()){
				array_push($keys,$row[0]);
				array_push($tags,$row[1]);				
			}
			$imageTags=array();
			for($i=0;$i<$maxTags;$i++){				
				if(isset($_POST['tag'.$i])){
					$tag=$_POST['tag'.$i];
					echo array_search(Helper::decodeTag($tag),$tags);
					if(array_search(Helper::decodeTag($tag),$tags)!==FALSE){
						array_push($imageTags,$keys[array_search(Helper::decodeTag($tag),$tags)]);						
					}
				}
				else{
					break;
				}
			}
			deleteRow('imid',$imid,'tagmappings');
			foreach($imageTags as $tag){
				insertValues(array('imid' => $imid,'tagid' => $tag),'tagmappings');
			}
			$description=$_POST['imageDescription'];
			$dateTaken=(new DateTime($_POST['dateTaken']))->format('Y-m-d');
			updateValues('imid',$imid,array('status' => 1,'description' => $description,'title' => $_POST['imageTitle'],'date_taken' => $dateTaken),'images');
			return Redirect::to('view/'.$imhash)->with('approveMsg','This image has been approved for public viewing');
		}

		public function updateImage(){
			session_start();
			if(!isset($_SESSION['admin'])){
				return Redirect::to('admin/login')->with('error','You do not have sufficient privileges to view this page');
			}
			trimVars('POST');
			cleanVars('POST');
			$imhash=$_POST['imhash'];
			$imid=getData("SELECT imid FROM images WHERE imhash='".$imhash."'",0)[0];
			$maxTags=getData("SELECT COUNT(tagid) FROM tags",0)[0];
			$allTags=getData("SELECT tagid,tagtext FROM tags",1);
			$keys=array();
			$tags=array();
			while($row=$allTags->fetch()){
				array_push($keys,$row[0]);
				array_push($tags,$row[1]);				
			}
			$imageTags=array();
			for($i=0;$i<$maxTags;$i++){				
				if(isset($_POST['tag'.$i])){
					$tag=$_POST['tag'.$i];
					echo array_search(Helper::decodeTag($tag),$tags);
					if(array_search(Helper::decodeTag($tag),$tags)!==FALSE){
						array_push($imageTags,$keys[array_search(Helper::decodeTag($tag),$tags)]);						
					}
				}
				else{
					break;
				}
			}
			deleteRow('imid',$imid,'tagmappings');
			foreach($imageTags as $tag){
				insertValues(array('imid' => $imid,'tagid' => $tag),'tagmappings');
			}
			$description=$_POST['imageDescription'];
			$dateTaken=(new DateTime($_POST['dateTaken']))->format('Y-m-d');
			updateValues('imid',$imid,array('status' => 1,'description' => $description,'title' => $_POST['imageTitle'],'date_taken' => $dateTaken),'images');
			return Redirect::to('view/'.$imhash)->with('approveMsg','The details for this image have been updated');
		}

		public function editImage($imhash){
			session_start();
			if(!isset($_SESSION['admin'])){
				return Redirect::to('admin/login')->with('error','You do not have sufficient privileges to view this page');
			}
			$status=getData("SELECT status FROM images WHERE imhash='".$imhash."'",0)[0];
			if($status==0){
				return Redirect::to('manage/'.$imhash);
			}
			$tags=getJSON(getData("SELECT tagtext FROM tags",1),array('tagtext'));	
			$image=getData("SELECT imid,imhash,title,description,CONCAT(SUBSTRING_INDEX(username,'@',1),DATE_FORMAT(date_and_time_regn,'%H%i')) AS username,U.username,concat(firstname,\" \",lastname),date_taken,uploaded_at,width,height,size FROM images I,users U WHERE imhash='".$imhash."' AND I.uploaded_by = U.uuid",1);
			if($image->rowCount()==0) 
				return 0;
			$image=getJSON($image,array('imid','imhash','title','description','username','uploaded_by','uploader_shortname','date_taken','uploaded_at','width','height','size'))[0];
			$imageTags=getJSON(getData("SELECT tagtext FROM tags T,tagmappings TM WHERE TM.tagid = T.tagid AND TM.imid = ".$image['imid'],1),array('tagtext'));
			$tagsAssigned=array();
			foreach($tags as $tag){
				$tagsAssigned[$tag['tagtext']]=0;
				if(in_array($tag,$imageTags)){
					$tagsAssigned[$tag['tagtext']]=1;
				}					
			}
			$tags=$tagsAssigned;
			$image['tags']=getJSON(getData("SELECT tagtext FROM tags T,tagmappings TM WHERE TM.tagid = T.tagid AND TM.imid = ".$image['imid'],1),array('tagtext'));
			return View::make('edit_image')->with(array('tags' => $tags,'image' => $image));
		}

		public static function showAdminSettings(){
			$tags=getJSON(getData("SELECT tagtext,coverimageuid FROM tags",1),array('tagtext','coverhash'));
			$sliderImages=getJSON(getData("SELECT imhash FROM sliderimg SI,images I WHERE SI.imid = I.imid",1),array('imhash'));
			return View::make('admin_Settings')->with(array('tags' => $tags,'sliderImages' => $sliderImages));
		}
	}	

?>