<?php
	include_once('connect.php');
	$dbs = new database();
	$db=$dbs->connection();
	session_start();
	if(isset($_POST['submit']))
	{
		$data=$_POST;
		$editid = 0;
		if(isset($_GET['empedit']) && $_GET['empedit'] > 0){ 
			$editid = $_GET['empedit'];
		};
		$empid=mysqli_real_escape_string($db, $data['empid']);
		$img=$_FILES['pfimg']['name'];
		$gender=mysqli_real_escape_string($db, $data['gender']);
		$fname=mysqli_real_escape_string($db, $data['fname']);
		$mname=mysqli_real_escape_string($db, $data['mname']);
		$lname=mysqli_real_escape_string($db, $data['lname']);
		$bdate=mysqli_real_escape_string($db, $data['bdate']);
		$mnumber=mysqli_real_escape_string($db, $data['mnumber']);
		$email=mysqli_real_escape_string($db, $data['email']);
		$address1=mysqli_real_escape_string($db, $data['address1']);
		$address2=mysqli_real_escape_string($db, $data['address2']);
		$address3=mysqli_real_escape_string($db, $data['address3']);
		$city=mysqli_real_escape_string($db, $data['city']);
		$joindate=mysqli_real_escape_string($db, $data['joindate']);
		$leavedate=mysqli_real_escape_string($db, $data['leavedate']);
		$status=mysqli_real_escape_string($db, $data['status']);
		$role=mysqli_real_escape_string($db, $data['role']);
		$password=mysqli_real_escape_string($db, $data['password']);
		$marital=mysqli_real_escape_string($db, $data['marital']);
		$position=mysqli_real_escape_string($db, $data['position']);
		$imagefilename = mysqli_real_escape_string($db, $data['imagefilename']);
		$ImageComplete=false;

		if($editid==0){
			$sql = mysqli_query($db,"SELECT * FROM employee WHERE Email='$email'");
		}
		else{
			$sql = mysqli_query($db,"SELECT * FROM employee WHERE Email='$email' AND empid!=$editid");
		}
		
		if(mysqli_num_rows($sql) > 0)
		{
			header("location:../employeeadd.php?msg=Email address already exists!");exit;
		}
		else
		{
			if(!empty($_FILES['pfimg']['name']))
			{
				$name=$_FILES['pfimg']['name'];
				$temp=$_FILES['pfimg']['tmp_name'];
				$size=$_FILES['pfimg']['size'];
				$type=$_FILES['pfimg']['type'];
						
				if($type != "image/jpg" && $type != "image/png" && $type != "image/jpeg" && $type != "image/gif")
				{
					header("location:../employeeadd.php?msg=Invalid image format!");exit;
				}
				else
				{
					if($size > 1000000)
					{
						header("location:../employeeadd.php?msg=File size up to 1MB required!");exit;
					}
					else
					{	
						$ImageComplete=true;
					}				
				}
			}
			else
			{
				$in = $_POST["imagefilename"];
				
				if(file_exists("../image/".$in))
				{
					$ImageComplete=true;
				}
				else
				{
					header("location:../employeeadd.php?msg=Please select a profile image!");exit;	
				}
			}	
		}

		if($ImageComplete)
		{
			$roleid = $_SESSION['User']['RoleId'];
			date_default_timezone_set("Asia/Kolkata");
			$datetime = date("Y-m-d h:i:s");

			if($editid==0)
			{
				if(!empty($_FILES['pfimg']['name']))
				{
					$name = rand(222,333333).$name;
					move_uploaded_file($temp,"../image/".$name);
				}
				else
				{
					$name = $_POST["imagefilename"];
				}
				mysqli_query($db,"INSERT INTO employee (EmployeeId, FirstName, MiddleName, LastName, Birthdate, Gender, Address1, Address2, Address3, CityId, Mobile, Email, Password, MaritalStatus, PositionId, CreatedBy, CreatedDate, JoinDate, LeaveDate, StatusId, RoleId, ImageName)
					VALUES ('$empid', '$fname', '$mname', '$lname', '$bdate', '$gender', '$address1', '$address2', '$address3', '$city', '$mnumber', '$email', '$password', '$marital', '$position', '$roleid', '$datetime', '$joindate', '$leavedate', '$status', '$role', '$name')");

				header("location:../detailview.php?id=Successfully added.");exit;
			}
			else
			{
				if(!empty($_FILES['pfimg']['name']))
				{
					$name = rand(222,333333).$name;
					move_uploaded_file($temp,"../image/".$name);
				}
				else
				{
					$name = $_POST["imagefilename"];
				}
				mysqli_query($db,"UPDATE employee SET EmployeeId='$empid', FirstName='$fname', MiddleName='$mname', LastName='$lname', Birthdate='$bdate', Gender='$gender', Address1='$address1', Address2='$address2', Address3='$address3', CityId='$city', Mobile='$mnumber', Email='$email', Password='$password', MaritalStatus='$marital', PositionId='$position', ModifiedBy='$roleid', ModifiedDate='$datetime', JoinDate='$joindate', LeaveDate='$leavedate', StatusId='$status', RoleId='$role', ImageName='$name' WHERE EmpId='$editid'");

				header("location:../detailview.php?employeeid=$editid");exit;
			}
		}
	}
?>
