<?php include 'connection.php'?>

<?php
header('Content-Type: application/json; charset=UTF-8');
$action=$_GET['action'];
switch ($action){

case 'customer_registration':
	$fullname=trim(strtoupper($_POST['fullname']));
	$email=trim($_POST['email']);
	$phone=trim(strtoupper($_POST['phone']));
	$pwd=$_POST['password'];
	$password=md5($_POST['password']);
	
	
	if(($fullname=='')||($email=='')||($phone=='')||($pwd=='')){
		$response['status']=false; 
		$response['message']="Error: Some Fields may be empty"; 
	}else{
		if(filter_var($email, FILTER_VALIDATE_EMAIL)){
			
		/////////// confirm user exitence//////////////////////////////////
		$query=mysqli_query($conn,"SELECT * FROM customers_tab WHERE email='$email' AND status_id='A'")or die (mysqli_error($conn));
		$rows=mysqli_num_rows($query);
				if ($rows>0){
					$response['status']=false; 
					$response['message']="Error: Account Aready Exist. Please LogIn"; 
				}else{
		
					///////////////////////geting sequence//////////////////////////
					$sequence=$callclass->_get_sequence_count($conn, 'CUS');
					$array = json_decode($sequence, true);
					$no= $array[0]['no'];
					//$num= $array[0]['num'];
					$user_id='CUS'.date("Ymdhis").$no;
					  
					////////////////////// inserting to users_tab//////////////////////////
					mysqli_query($conn,"INSERT INTO `customers_tab`
					(`user_id`, `fullname`, `phone`, `email`, `password`, `status_id`, `reg_date`) VALUES
					('$user_id','$fullname','$phone','$email','$password','A',NOW())")or die (mysqli_error($conn));
					
					mysqli_query($conn,"DELETE FROM email_verification_tab WHERE email ='$email'")or die (mysqli_error($conn));
						/////////// get alert//////////////////////////////////
					  $alert_detail="Success Alert: Customer registration successful. Details: $fullname with ID: $user_id";
					$callclass->_alert_sequence_and_update($conn,$alert_detail,$user_id,$fullname,$ip_address,$sysname,0);
					
					$response['status']=true; 
					$response['message']="Registration Successful"; 
					$response['customer_id']=$user_id; 
				}

		}else{
        // invalid address
			$response['status']=false; 
			$response['message']="Error: $email is NOT an email address"; 
		}
	}
echo json_encode($response); 
break;





case 'login': // for user login
			$username=trim($_POST['username']);
			$password=trim(md5($_POST['password']));
				$query=mysqli_query($conn,"SELECT * FROM customers_tab WHERE email='$username' and password='$password'")or die (mysqli_error($conn));
				$usercount = mysqli_num_rows($query);
				if ($usercount>0){
					$usersel=mysqli_fetch_array($query);
					$status=$usersel['status_id'];
					if ($status=='A'){
							$fetch=mysqli_fetch_array($query);
							$customer_id=$usersel['user_id'];
							
							$access_key=md5($customer_id.date("Ymdhis"));
							//// update accesskey
							mysqli_query($conn,"UPDATE customers_tab SET access_key='$access_key' WHERE user_id='$customer_id'");
							
							$response['fetch']=true; 
							$response['message']="Login Successful"; 
							$response['customer_id']=$customer_id; 
							$response['access_key']=$access_key; 
					}else if($status=='S'){
							$response['status']=false; 
							$response['message']="Account Suspended"; 
					}else{
							$response['status']=false; 
							$response['message']="Account Under Review"; 
					}
				}else{
							$response['status']=false; 
							$response['message']="Error: Invalid Username and Password"; 
				}
			
echo json_encode($response); 
break;

case 'get_customer': // for user login
	$customer_id=trim($_POST['customer_id']);
	$access_key=trim($_POST['access_key']);
	
	///////////auth/////////////////////////////////////////
	  $fetch=$callclass->_validate_accesskey($conn, $customer_id,$access_key);
	  $array = json_decode($fetch, true);
	  $check=$array[0]['check'];
	////////////////////////////////////////////////////////
	if($check==0){
		$response['status']=false; 
		$response['message']='Invalid AccessToken. Please LogIn Again.'; 
	}else{
		$response['status']=true; 
		$query=mysqli_query($conn,"SELECT * FROM customers_tab WHERE user_id='$customer_id'")or die (mysqli_error($conn));
		  while($fetch_query=mysqli_fetch_assoc($query)){
			$response['data']=$fetch_query; 
		  }
	}
	
echo json_encode($response); 
break;

case 'get_all_customer': // for user login
	$query=mysqli_query($conn,"SELECT * FROM customers_tab")or die (mysqli_error($conn));
	  while($fetch_query=mysqli_fetch_assoc($query)){
		$response['data']=$fetch_query; 
	}
echo json_encode($response); 
break;


}
?>
