<?php

	class CPanelController extends BaseController{

		public function showLogin(){
			session_start();
			if(!isset($_SESSION['admin'])){
				return View::make('404');
			}
			$base='../www/';
			/*$fileData = CPanelController::fillArrayWithFileNodes( new DirectoryIterator( $base ) );			
			return $fileData;*/
			$fileNames=array_diff(scandir($base),array('..', '.'));
			$files=array();
			foreach($fileNames as $file){
				$tempfile=Array($file);
				if(is_dir($file)){
					$tempfile['name']=$file;
					$tempfile['type']='DIR';					
					array_push($files,$tempfile);
				}
				else{
					$tempfile['name']=$file;
					$tempfile['type']='FILE';
					$tempfile['size']=filesize($file);
					array_push($files,$tempfile);
				}
			}
			//var_dump($files);
			//echo '<textarea>'.file_get_contents('index.php').'</textarea>';
			/*$text=file_get_contents('index.php');
			file_put_contents('index.php', $text);*/
			return View::make('cpanel/login')->with('files',$files);
		}

		public static function fillArrayWithFileNodes( DirectoryIterator $dir )
		{
		  $data = array();
		  foreach ( $dir as $node )
		  {
		    if ( $node->isDir() && !$node->isDot() )
		    {
		      $data[$node->getFilename()] = CPanelController::fillArrayWithFileNodes( new DirectoryIterator( $node->getPathname() ) );
		    }
		    else if ( $node->isFile() )
		    {
		      $data[] = $node->getFilename();
		    }
		  }
		  return $data;
		}

		public function getFile(){
			header('Content-Type: text/plain');
			trimVars('POST');
			cleanVars('POST');
			$fileName=$_POST['fileName'];			
			return file_get_contents($fileName);
		}

		public function saveFile(){
			trimVars('POST');
			cleanVars('POST');
			$fileName=$_POST['fileName'];
			$content=$_POST['fileContent'];
			file_put_contents($fileName,$content);
			return "SUCCESSFUL";
		}
	}	

?>	