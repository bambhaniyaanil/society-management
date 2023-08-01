<?php
include "thumbnail.php";
	function get_file_name($file_upload_name)
	{
		if(isset($_FILES[$file_upload_name]['name']))
		{
			$arr_file['filename']=$_FILES[$file_upload_name]['name'];		
			$arr_file['stat']="1";						  
			$arr_file['msg']="";	
		}else
		{
			$arr_file['filename']="";		
			$arr_file['stat']="0";
			$arr_file['msg']="please upload file";
		}
		return json_encode($arr_file);
	}
	
	function get_file_type($file_upload_name)
	{
		if(isset($_FILES[$file_upload_name]['name']))
		{
			$arr_file['filename']=$_FILES[$file_upload_name]['name'];		
			$arr_file['type']=$_FILES[$file_upload_name]['type'];		
			$arr_file['stat']="1";						  
			$arr_file['msg']="";	
		}else
		{
			$arr_file['filename']="";		
			$arr_file['type']="";		
			$arr_file['stat']="0";
			$arr_file['msg']="please upload file";
		}
		return json_encode($arr_file);
	}
	function get_image_size($file_upload_name)
	{
		if(isset($_FILES[$file_upload_name]['name']))
		{
			if( $_FILES[$file_upload_name]['name']!="")
			{
				$typ1 = $_FILES[$file_upload_name]['type'];
				if( $typ1 == "image/jpeg" || $typ1 =="image/jpg" ||  $typ1 =="image/png" ||  $typ1 =="image/vnd.microsoft.icon" ||  $typ1 =="image/bmp" )
				{
					$image_info = getimagesize($_FILES[$file_upload_name]["tmp_name"]);
					$image_width = $image_info[0];
					$image_height = $image_info[1];
					
					$arr_file['filename']=$_FILES[$file_upload_name]['name'];		
					$arr_file['width']=$image_width;		
					$arr_file['height']=$image_height;		
					$arr_file['stat']="1";
					$arr_file['msg']="";
					
				}else
				{
					$arr_file['filename']="";	
					$arr_file['width']="";		
					$arr_file['height']="";					
					$arr_file['stat']="0";
					$arr_file['msg']="Upload only image files like jpg,png,bmp,ico";	
				}
			}else
			{
				$arr_file['filename']="";
				$arr_file['width']="";		
				$arr_file['height']="";				
				$arr_file['stat']="0";
				$arr_file['msg']="please upload file";
			}
		}else
		{
			$arr_file['filename']="";	
			$arr_file['width']="";		
			$arr_file['height']="";	
			$arr_file['stat']="0";
			$arr_file['msg']="please upload file";
		}
		return json_encode($arr_file);
	}

	function upload_file($file_upload_name,$path_to_upload,$file_name="")
	{
		if(isset($_FILES[$file_upload_name]['name']))
		{
			if( $_FILES[$file_upload_name]['name']!="")
			{
					
				$upload_logo_dir = $path_to_upload;
				
				if($file_name!="")
				{
					$uploading_file_name=$file_name;
				}else
				{
					$uploading_file_name=rand(1000,100000)."-".$_FILES[$file_upload_name]['name'];
				}
				
				$uploadlogo = $upload_logo_dir.basename($uploading_file_name);
				  
				if(move_uploaded_file($_FILES[$file_upload_name]['tmp_name'], $uploadlogo))
				{
				  
					$arr_file['filename']=$uploading_file_name;		
					$arr_file['stat']="1";						  
					$arr_file['msg']="successfully uploaded";						  
				}
				else
				{	
					$arr_file['filename']="";		
					$arr_file['stat']="0";
					$arr_file['msg']="Error in uploading";	
				}
			}else
			{
				$arr_file['filename']="";		
				$arr_file['stat']="0";
				$arr_file['msg']="please upload file";	
			}
			//echo  $team_logo;
			//echo json_encode($arr_file);
		}else
		{
			$arr_file['filename']="";		
			$arr_file['stat']="0";
			$arr_file['msg']="please upload file";	
		}
		return json_encode($arr_file);
	}
	
	function upload_image($file_upload_name,$path_to_upload,$file_name="")
	{
		if(isset($_FILES[$file_upload_name]['name']))
		{
			if( $_FILES[$file_upload_name]['name']!="")
			{
				$typ1 = $_FILES[$file_upload_name]['type'];
				if( $typ1 == "image/jpeg" || $typ1 =="image/jpg" ||  $typ1 =="image/png" ||  $typ1 =="image/vnd.microsoft.icon" ||  $typ1 =="image/bmp" )
				{
					$upload_logo_dir = $path_to_upload;
					if($file_name!="")
					{
						$ext=explode(".",$_FILES[$file_upload_name]['name']);
						$count=count($ext);
						$extension=$ext[$count-1];
						$uploading_file_name=$file_name.".".$extension;
					}else
					{
						$uploading_file_name=rand(1000,100000)."-".$_FILES[$file_upload_name]['name'];
					}
					
					$uploadlogo = $upload_logo_dir.basename($uploading_file_name);
					  
					if(move_uploaded_file($_FILES[$file_upload_name]['tmp_name'], $uploadlogo))
					{
					  
						$arr_file['filename']=$uploading_file_name;		
						$arr_file['stat']="1";						  
						$arr_file['msg']="successfully uploaded";						  
					}
					else
					{	
						$arr_file['filename']="";		
						$arr_file['stat']="0";
						$arr_file['msg']="Error in uploading";	
					}
				}else
				{
					$arr_file['filename']="";		
					$arr_file['stat']="0";
					$arr_file['msg']="Upload only image files like jpg,png,bmp,ico";	
				}
				
			}else
			{
				$arr_file['filename']="";		
				$arr_file['stat']="0";
				$arr_file['msg']="please upload file";	
			}
			//echo  $team_logo;
			//echo json_encode($arr_file);
		}else
		{
			$arr_file['filename']="";		
			$arr_file['stat']="0";
			$arr_file['msg']="please upload file";	
		}
		return json_encode($arr_file);
	}
	
	function upload_image_size_check($file_upload_name,$path_to_upload,$height,$width,$file_name="",$more_less='0')
	{
		if(isset($_FILES[$file_upload_name]['name']))
		{
			if( $_FILES[$file_upload_name]['name']!="")
			{
				$typ1 = $_FILES[$file_upload_name]['type'];
				if( $typ1 == "image/jpeg" || $typ1 =="image/jpg" ||  $typ1 =="image/png" ||  $typ1 =="image/vnd.microsoft.icon" ||  $typ1 =="image/bmp" )
				{
					$upload_logo_dir = $path_to_upload;
					if($file_name!="")
					{
						$ext=explode(".",$_FILES[$file_upload_name]['name']);
						$count=count($ext);
						$extension=$ext[$count-1];
						$uploading_file_name=$file_name.".".$extension;
					}else
					{
						$uploading_file_name=rand(1000,100000)."-".$_FILES[$file_upload_name]['name'];
					}
	
					
					$uploadlogo = $upload_logo_dir.basename($uploading_file_name);
					  
					$image_info = getimagesize($_FILES[$file_upload_name]["tmp_name"]);
					$image_width = $image_info[0];
					$image_height = $image_info[1];
					if($more_less==1)
					{
						
						$condition=$image_width >= $width && $image_height >= $height;
						$errormsg="More than or equal to ";
					}else
					{
						 
						$condition=$image_width <= $width &&$image_height <= $height;
						$errormsg="Less than or equal to ";
					}
						
					if($condition)
					{
						if(move_uploaded_file($_FILES[$file_upload_name]['tmp_name'], $uploadlogo))
						{
						  
							$arr_file['filename']=$uploading_file_name;		
							$arr_file['stat']="1";						  
							$arr_file['msg']="successfully uploaded";						  
						}
						else
						{	
							$arr_file['filename']="";		
							$arr_file['stat']="0";
							$arr_file['msg']="Error in uploading";	
						}
					}else
					{
						echo "0";
						$arr_file['filename']="";		
						$arr_file['stat']="0";
						$arr_file['msg']="Upload image files must be ".$errormsg.$width." X ".$height;	
					}
					
					
					
				}else
				{
					$arr_file['filename']="";		
					$arr_file['stat']="0";
					$arr_file['msg']="Upload only image files like jpg,png,bmp,ico";	
				}
				
			}else
			{
				$arr_file['filename']="";		
				$arr_file['stat']="0";
				$arr_file['msg']="please upload file";	
			}
			//echo  $team_logo;
			//echo json_encode($arr_file);
		}else
		{
			$arr_file['filename']="";		
			$arr_file['stat']="0";
			$arr_file['msg']="please upload file";	
		}
		return json_encode($arr_file);
	}
	
	
	
	function upload_image_size_fix($file_upload_name,$path_to_upload,$height,$width,$file_name="")
	{
		if(isset($_FILES[$file_upload_name]['name']))
		{
			if( $_FILES[$file_upload_name]['name']!="")
			{
				$typ1 = $_FILES[$file_upload_name]['type'];
				if( $typ1 == "image/jpeg" || $typ1 =="image/jpg" ||  $typ1 =="image/png" ||  $typ1 =="image/vnd.microsoft.icon" ||  $typ1 =="image/bmp" )
				{
					$upload_logo_dir = $path_to_upload;
					if($file_name!="")
					{
						$ext=explode(".",$_FILES[$file_upload_name]['name']);
						$count=count($ext);
						$extension=$ext[$count-1];
						$uploading_file_name=$file_name.".".$extension;
					}else
					{
						$uploading_file_name=rand(1000,100000)."-".$_FILES[$file_upload_name]['name'];
					}
					
					
					$uploadlogo = $upload_logo_dir.basename($uploading_file_name);
					  
					$image_info = getimagesize($_FILES[$file_upload_name]["tmp_name"]);
					$image_width = $image_info[0];
					$image_height = $image_info[1];
					
					
					if($image_width == $width && $image_height==$height)
					{
						if(move_uploaded_file($_FILES[$file_upload_name]['tmp_name'], $uploadlogo))
						{
						  
							$arr_file['filename']=$uploading_file_name;		
							$arr_file['stat']="1";						  
							$arr_file['msg']="successfully uploaded";						  
						}
						else
						{	
							$arr_file['filename']="";		
							$arr_file['stat']="0";
							$arr_file['msg']="Error in uploading";	
						}
					}else
					{
						$arr_file['filename']="";		
						$arr_file['stat']="0";
						$arr_file['msg']="Upload image files must be ".$width." X ".$height;	
					}
					
					
					
				}else
				{
					$arr_file['filename']="";		
					$arr_file['stat']="0";
					$arr_file['msg']="Upload only image files like jpg,png,bmp,ico";	
				}
				
			}else
			{
				$arr_file['filename']="";		
				$arr_file['stat']="0";
				$arr_file['msg']="please upload file";	
			}
			//echo  $team_logo;
			//echo json_encode($arr_file);
		}else
		{
			$arr_file['filename']="";		
			$arr_file['stat']="0";
			$arr_file['msg']="please upload file";	
		}
		return json_encode($arr_file);
	}
	
	function upload_image_size_sign($file_upload_name,$path_to_upload,$height,$width,$file_name="",$sign='')
	{
		if(isset($_FILES[$file_upload_name]['name']))
		{
			if( $_FILES[$file_upload_name]['name']!="")
			{
				$typ1 = $_FILES[$file_upload_name]['type'];
				if( $typ1 == "image/jpeg" || $typ1 =="image/jpg" ||  $typ1 =="image/png" ||  $typ1 =="image/vnd.microsoft.icon" ||  $typ1 =="image/bmp" )
				{
					$upload_logo_dir = $path_to_upload;
					if($file_name!="")
					{
						$ext=explode(".",$_FILES[$file_upload_name]['name']);
						$count=count($ext);
						$extension=$ext[$count-1];
						$uploading_file_name=$file_name.".".$extension;
					}else
					{
						$uploading_file_name=rand(1000,100000)."-".$_FILES[$file_upload_name]['name'];
					}
	
					
					$uploadlogo = $upload_logo_dir.basename($uploading_file_name);
					  
					$image_info = getimagesize($_FILES[$file_upload_name]["tmp_name"]);
					$image_width = $image_info[0];
					$image_height = $image_info[1];
					$errormsg="";
					if($sign!='')
					{
						if($sign=="=" || $sign=="==")
						{
							
							$errormsg=" be ";
							$condition=$image_width == $width && $image_height == $height;
						}elseif($sign==">=" || $sign=="=>")
						{
							
							$errormsg=" be grater than or equal to ";
							$condition=$image_width >= $width && $image_height >= $height;
						}elseif($sign=="<=" || $sign=="=<")
						{
							
							$errormsg=" be less than or equal to ";
							$condition=$image_width <= $width && $image_height <= $height;
						}elseif($sign==">" )
						{
							
							$errormsg=" be grater than ";
							$condition=$image_width > $width && $image_height > $height;
						}elseif($sign=="<" )
						{
							
							$errormsg=" be less than  ";
							$condition=$image_width < $width && $image_height < $height;
						}	
						else
						{
							$sign="<=";
							$errormsg=" be less than or equal to ";
							$condition=$image_width <= $width && $image_height <= $height;
						}
						
					//	$condition=$image_width .$sign. $width && $image_height .$sign. $height;
						
						//$errormsg="More than or equal to ";
					}else
					{
						 
						$condition=$image_width <= $width &&$image_height <= $height;
						$errormsg=" be less than or equal to ";
					}
						//echo $condition;
					if($condition)
					{
						if(move_uploaded_file($_FILES[$file_upload_name]['tmp_name'], $uploadlogo))
						{
						  
							$arr_file['filename']=$uploading_file_name;		
							$arr_file['stat']="1";						  
							$arr_file['msg']="successfully uploaded";						  
						}
						else
						{	
							$arr_file['filename']="";		
							$arr_file['stat']="0";
							$arr_file['msg']="Error in uploading";	
						}
					}else
					{
						echo "0";
						$arr_file['filename']="";		
						$arr_file['stat']="0";
						$arr_file['msg']="Upload image files must ".$errormsg.$width." X ".$height;	
					}
					
					
					
				}else
				{
					$arr_file['filename']="";		
					$arr_file['stat']="0";
					$arr_file['msg']="Upload only image files like jpg,png,bmp,ico";	
				}
				
			}else
			{
				$arr_file['filename']="";		
				$arr_file['stat']="0";
				$arr_file['msg']="please upload file";	
			}
			//echo  $team_logo;
			//echo json_encode($arr_file);
		}else
		{
			$arr_file['filename']="";		
			$arr_file['stat']="0";
			$arr_file['msg']="please upload file";	
		}
		return json_encode($arr_file);
	}
	
	
	//dynamic upload
	function upload_file_dynamic($file_upload_name,$path_to_upload,$check_type=0,$file_type="",$file_name="",$add_extension="0")
	{
		$condition=0;
		if(isset($_FILES[$file_upload_name]['name']))
		{
			if( $_FILES[$file_upload_name]['name']!="")
			{
				$typ1 = $_FILES[$file_upload_name]['type'];
				if($file_type!="" && $check_type!='0')
				{
					$file_type=explode(",",$file_type);
					for($i=0;$i<count($file_type);$i++)
					{
						if($typ1==$file_type[$i])
						{
							$condition++;
						}
					}
				}else
				{
					$condition=1;
				}
				//echo $condition;
				if( $condition)
				{
					$upload_logo_dir = $path_to_upload;
					if($file_name!="")
					{
						$ext=explode(".",$_FILES[$file_upload_name]['name']);
						$count=count($ext);
						$extension=$ext[$count-1];
						if($add_extension!="0")
						{
							$uploading_file_name=$file_name;
						}else
						{
							$uploading_file_name=$file_name.".".$extension;
						}
					}else
					{
						$uploading_file_name=rand(1000,100000)."-".$_FILES[$file_upload_name]['name'];
					}
	
					
					$uploadlogo = $upload_logo_dir.basename($uploading_file_name);
					  
					
					if(move_uploaded_file($_FILES[$file_upload_name]['tmp_name'], $uploadlogo))
					{
					  
						$arr_file['filename']=$uploading_file_name;		
						$arr_file['stat']="1";						  
						$arr_file['msg']="successfully uploaded";						  
					}
					else
					{	
						$arr_file['filename']="";		
						$arr_file['stat']="0";
						$arr_file['msg']="Error in uploading";	
					}
					
				}else
				{
					$arr_file['filename']="";		
					$arr_file['stat']="0";
					$arr_file['msg']="Invalid file type";	
				}
				
			}else
			{
				$arr_file['filename']="";		
				$arr_file['stat']="0";
				$arr_file['msg']="please upload file";	
			}
			
		}else
		{
			$arr_file['filename']="";		
			$arr_file['stat']="0";
			$arr_file['msg']="please upload file";	
		}
		return json_encode($arr_file);
	}
	
	function upload_file_dynamic_size($file_upload_name,$path_to_upload,$size,$check_type=0,$file_type="",$file_name="")
	{
		$condition=0;
		if(isset($_FILES[$file_upload_name]['name']))
		{
			if( $_FILES[$file_upload_name]['name']!="")
			{
				
				$typ1 = $_FILES[$file_upload_name]['type'];
				if($file_type!="" && $check_type!='0')
				{
					$file_type=explode(",",$file_type);
					for($i=0;$i<count($file_type);$i++)
					{
						if($typ1==$file_type[$i])
						{
							$condition++;
						}
					}
				}else
				{
					$condition=1;
				}
				//echo $condition;
				if( $condition)
				{
					$file_size=$_FILES[$file_upload_name]['size'];
					if($file_size<=$size)
					{
					
						$upload_logo_dir = $path_to_upload;
						if($file_name!="")
						{
							$ext=explode(".",$_FILES[$file_upload_name]['name']);
							$count=count($ext);
							$extension=$ext[$count-1];
							$uploading_file_name=$file_name.".".$extension;
						}else
						{
							$uploading_file_name=rand(1000,100000)."-".$_FILES[$file_upload_name]['name'];
						}
		
						
						$uploadlogo = $upload_logo_dir.basename($uploading_file_name);
						  
						
						if(move_uploaded_file($_FILES[$file_upload_name]['tmp_name'], $uploadlogo))
						{
						  
							$arr_file['filename']=$uploading_file_name;		
							$arr_file['stat']="1";						  
							$arr_file['msg']="successfully uploaded";						  
						}
						else
						{	
							$arr_file['filename']="";		
							$arr_file['stat']="0";
							$arr_file['msg']="Error in uploading";	
						}
					}else
					{
						$arr_file['filename']="";		
						$arr_file['stat']="0";
						$arr_file['msg']="File is to big";
					}
					
				}else
				{
					$arr_file['filename']="";		
					$arr_file['stat']="0";
					$arr_file['msg']="Invalid file type";	
				}
				
			}else
			{
				$arr_file['filename']="";		
				$arr_file['stat']="0";
				$arr_file['msg']="please upload file";	
			}
			
		}else
		{
			$arr_file['filename']="";		
			$arr_file['stat']="0";
			$arr_file['msg']="please upload file";	
		}
		return json_encode($arr_file);
	}
	
	function upload_thumb_image($file_upload_name,$path_to_upload,$height,$width,$file_name="")
	{
		if(isset($_FILES[$file_upload_name]['name']))
		{
			if( $_FILES[$file_upload_name]['name']!="")
			{
				$typ1 = $_FILES[$file_upload_name]['type'];
				if( $typ1 == "image/jpeg" || $typ1 =="image/jpg" ||  $typ1 =="image/png" ||  $typ1 =="image/vnd.microsoft.icon" ||  $typ1 =="image/bmp" )
				{
					$upload_logo_dir = $path_to_upload;
					if($file_name!="")
					{
						$ext=explode(".",$_FILES[$file_upload_name]['name']);
						$count=count($ext);
						$extension=$ext[$count-1];
						$uploading_file_name=$file_name.".".$extension;
					}else
					{
						$uploading_file_name=rand(1000,100000)."-".$_FILES[$file_upload_name]['name'];
					}
					if($height=="" || $height<=0)
					{
						$height="200";
					}
					
					if($width=="" || $width<=0)
					{
						$width="200";
					}
					$uploadlogo = $upload_logo_dir.basename($uploading_file_name);
					  
					if(move_uploaded_file($_FILES[$file_upload_name]['tmp_name'], $uploadlogo))
					{
						makeThumbnails($upload_logo_dir, $uploading_file_name,$upload_logo_dir,$width,$height);
						
						$arr_file['filename']=$uploading_file_name;		
						$arr_file['stat']="1";						  
						$arr_file['msg']="successfully uploaded";						  
					}
					else
					{	
						$arr_file['filename']="";		
						$arr_file['stat']="0";
						$arr_file['msg']="Error in uploading";	
					}
				}else
				{
					$arr_file['filename']="";		
					$arr_file['stat']="0";
					$arr_file['msg']="Upload only image files like jpg,png,bmp,ico";	
				}
				
			}else
			{
				$arr_file['filename']="";		
				$arr_file['stat']="0";
				$arr_file['msg']="please upload file";	
			}
			//echo  $team_logo;
			//echo json_encode($arr_file);
		}else
		{
			$arr_file['filename']="";		
			$arr_file['stat']="0";
			$arr_file['msg']="please upload file";	
		}
		return json_encode($arr_file);
	}
	
	function upload_image_thumb($file_upload_name,$path_to_upload,$path_to_upload_thumb,$height,$width,$file_name="")
	{
		if(isset($_FILES[$file_upload_name]['name']))
		{
			if( $_FILES[$file_upload_name]['name']!="")
			{
				$typ1 = $_FILES[$file_upload_name]['type'];
				if( $typ1 == "image/jpeg" || $typ1 =="image/jpg" ||  $typ1 =="image/png" ||  $typ1 =="image/vnd.microsoft.icon" ||  $typ1 =="image/bmp" )
				{
					$upload_logo_dir = $path_to_upload;
					if($file_name!="")
					{
						$ext=explode(".",$_FILES[$file_upload_name]['name']);
						$count=count($ext);
						$extension=$ext[$count-1];
						$uploading_file_name=$file_name.".".$extension;
					}else
					{
						$uploading_file_name=rand(1000,100000)."-".$_FILES[$file_upload_name]['name'];
					}
					if($height=="" || $height<=0)
					{
						$height="200";
					}
					
					if($width=="" || $width<=0)
					{
						$width="200";
					}
					$uploadlogo = $upload_logo_dir.basename($uploading_file_name);
					  
					if(move_uploaded_file($_FILES[$file_upload_name]['tmp_name'], $uploadlogo))
					{
						makeThumbnails($upload_logo_dir, $uploading_file_name,$path_to_upload_thumb,$width,$height);
						
						$arr_file['filename']=$uploading_file_name;		
						$arr_file['stat']="1";						  
						$arr_file['msg']="successfully uploaded";						  
					}
					else
					{	
						$arr_file['filename']="";		
						$arr_file['stat']="0";
						$arr_file['msg']="Error in uploading";	
					}
				}else
				{
					$arr_file['filename']="";		
					$arr_file['stat']="0";
					$arr_file['msg']="Upload only image files like jpg,png,bmp,ico";	
				}
				
			}else
			{
				$arr_file['filename']="";		
				$arr_file['stat']="0";
				$arr_file['msg']="please upload file";	
			}
			//echo  $team_logo;
			//echo json_encode($arr_file);
		}else
		{
			$arr_file['filename']="";		
			$arr_file['stat']="0";
			$arr_file['msg']="please upload file";	
		}
		return json_encode($arr_file);
	}
	
?>
<!--<form method="post" enctype="multipart/form-data">
	<input type="file" name="upload_file" />
	<input type="submit" name="submit">
</form>-->
<?php
	if(isset($_POST['submit']))
	{
		$file_upload_name="upload_file";
		$path_to_upload="upload/";
		$path_to_upload_thumb="upload/thumb/";
		$file_name="image2";
		$height="300";
		$width="300";
		$more_less="0";
		$file_type="image/jpeg,image/jpg,image/png,application/pdf";
		$size="5242880";
		//$res=upload_file($file_upload_name,$path_to_upload);
		//$res=upload_image($file_upload_name,$path_to_upload);
		//$res=upload_image_size_fix($file_upload_name,$path_to_upload,$height,$width,$file_name);
		//$res=upload_image_size_check($file_upload_name,$path_to_upload,$height,$width,$file_name="",$more_less);
		//$res=upload_image_size_sign($file_upload_name,$path_to_upload,$height,$width,$file_name="",$sign='==');
		//$res=get_file_name($file_upload_name);
		//$res=get_file_type($file_upload_name);
		//$res=get_image_size($file_upload_name);
		//$res=upload_file_dynamic($file_upload_name,$path_to_upload,$check_type=0,$file_type,$file_name="");
		//$res=upload_thumb_image($file_upload_name,$path_to_upload,$height,$width,$file_name="");
		//$res=upload_image_thumb($file_upload_name,$path_to_upload,$path_to_upload_thumb,$height,$width,$file_name="");
		//$res=upload_file_dynamic_size($file_upload_name,$path_to_upload,$size,1,$file_type,$file_name="");
		
		//echo $res;
	}
?>