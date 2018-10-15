<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title></title>
<meta name="" content="">
<?php 
$alert="";
?>
	<style>
		input[type=text],input[type=file]{
			width:100%;
			padding:10px 5px;
			background: none;
			outline: none;
			border:3px solid #410f42;
			font-size:18px;
			margin-bottom: 10px;
		}
		input{
			margin-bottom:20px;
		}
		.btn{
			padding:15px 20px;
			background: #410f42;
			color:#fff;
			font-weight:bold;
			border:none;
		}
		label{
			width:80%;
			display: block;
			font-size:16px;
			font-weight:bold;
			color: #410f42;
		}
		form{
			background:#ffffff;border-radius:5px; padding:20px; width:80%;color: #410f42;margin: 0 auto;
		}
	</style>
</head>
<body>
<?php
if(isset($_POST['submit'])){
	$validate=uniqid(rand());
	$status="Not Active";
	$exam_id="N/A";
	$fullname=$_POST['fullname'];
	$email=$_POST['email'];
	$phone=$_POST['phone'];
	if($_POST['pass']==$_POST['cfpass']) {
		$password=$_POST['pass'];
				
	}
	else {
		$alert="Password does not match";
		
	}
	//gender
if(isset($_POST['gender'])){
			$ged=$_POST['gender'];
			
}

//courses
if(isset($_POST['course'])){
			$course=$_POST['course'];
			$course=implode(", ",$course);
}
	//notification
	if(isset($_POST['ntf'])){
			$ntf=$_POST['ntf'];
			$ntf=implode(", ",$ntf);
	}
	
	//file upload
	$files=$_FILES['upl']['name'];
	//collect type value
	$arr=explode(".", $files);
	$i=count($arr)-1;
	$type=$arr[$i];
	$files="img".rand().".".$type;
	//before we insert, we need to check for duplicate
	$link=mysqli_connect("localhost","root","","schoolpj");
	$select=mysqli_query($link,"select * from  studentsreg where email='$email' || phone_number='$phone'");
	
	$count=mysqli_num_rows($select);
	if($count==1) {
		$alert="Record Already Exist";
		
	}
	else { 
	$insert=mysqli_query($link,"insert into  studentreg(validate_code,fullname,gender,email,phone_number,password,courses,notification,exam_id,status,image)values('$validate','$fullname','$ged','$email','$phone','$password','$course','$ntf','$exam_id','$status','$files')");
		if($insert) {
			$body="Hi $fullname, \n\n Click on the link below \n\n\
http://localhost:8080/htmlfiles/activate.php?id=$validate to activate your account \n\n Thank you \n\n Site Administrator";
			$mail=mail($email,"Student Exam Registration Process", $body, "From:noreply");
			$move=move_uploaded_file($_FILES['upl']['tmp_name'],"passport/".$files);
			if($move && $mail) {
			$alert="Hi $fullname, Your Exam registration process has start, check your email to complete this process within 48hours. Thank You";				
			}
			else{
				$alert="either mail function didnt work or your move function didn't upload. however delete this later both are working";
			}
		}
	}
}
?>
	<div style="margin:30px auto 0;max-width:80%;background: #c15b3e;color:#fff;padding:20px 10px">
		<h1 style="margin:0 0 20px;text-align: center;padding:0;font-weight: 100; font-size:2em">Students Examination Register</h1>
		<h3 style="margin:0 0 20px;text-align: center;"><?php echo $alert ?></h3>		
		<form style="" method="post" action="" enctype="multipart/form-data">
		<input type="text" name="fullname" placeholder="Full Name">
		<input type="text" name="email" placeholder="Email Address">
		<input type="text" name="phone" placeholder="Phone Number">
		<input type="text" name="pass" placeholder="Password">
        <input type="text" name="cfpass" placeholder="Confirm Password"><br>
       <label>Gender:</label>
       <input type="radio" name="gender" value="Male">Male
       <input type="radio" name="gender" value="Female">Female
		
		<label>Courses:</label>
		<p><input type="checkbox" name="course[]" value="Maths" checked="" disabled="">Mathematics
		<input type="checkbox" name="course[]" value="English" checked="" disabled="">English
		<p>Select your courses:</p>
		<input type="checkbox" name="course[]" value="Biology">Biology
		<input type="checkbox" name="course[]" value="chemistry">Chemistry
		<input type="checkbox" name="course[]" value="BusAdmin">Bus. Admin<br>
		<input type="checkbox" name="course[]" value="Accounting">Accounting</p>
		
		
		<label>Notification:</label>
		<input type="checkbox" name="ntf[]" value="SMS">SMS
		<input type="checkbox" name="ntf[]" value="Email">EMAIL
		<label>Upload your Passport image</label>
		 <input type="file" name="upl">
		 <button name="submit" class="btn" type="submit">Submit</button>
				</form>
		
	</div>
	
</body>
</html>