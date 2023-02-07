<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_WARNING);
$ip_address=$_SERVER['REMOTE_ADDR']; //ip used
$sysname=gethostname();//computer used



//connection string with database  
///// for remote connection
$hostname = "23.94.30.18";  
$siteuser='afootec1';
$serverpass='.$AL@2022';

///// for local connection
//$hostname = "localhost";  
//$siteuser='root';
//$serverpass='';

$conn = mysqli_connect($hostname, $siteuser, $serverpass)or die("Unable to connect to MySQL");
mysqli_select_db($conn,"afootec1_test_api");
/////////////////////////////////////////////////////////////////
?>



<?php
class allClass{
	
/////////////////////////////////////////
function _validate_accesskey($conn,$customer_id,$access_key){
	$query=mysqli_query($conn,"SELECT * FROM customers_tab WHERE user_id='$customer_id' AND  access_key='$access_key'")or die (mysqli_error($conn));
		$count = mysqli_num_rows($query);
		if ($count>0){
			$response['check']=1; 
		}else{
			$response['check']=0; 
		}
	echo json_encode($response); 
}
	
/////////////////////////////////////////
function _get_setup_backend_settings_detail($conn, $backend_setting_id){
	$query=mysqli_query($conn,"SELECT * FROM setup_backend_settings_tab WHERE backend_setting_id='$backend_setting_id'")or die (mysqli_error($conn));
	$fetch=mysqli_fetch_array($query);
		$smtp_host=$fetch['smtp_host'];
		$smtp_username=$fetch['smtp_username'];
		$smtp_password=$fetch['smtp_password'];
		$smtp_port=$fetch['smtp_port'];
		$sender_name=$fetch['sender_name'];
		$support_email=$fetch['support_email'];
		$delivery_fee=$fetch['delivery_fee'];
		$bank_name=$fetch['bank_name'];
		$account_name=$fetch['account_name'];
		$account_number=$fetch['account_number'];
		$payment_key=$fetch['payment_key'];
		return '[{"smtp_host":"'.$smtp_host.'","smtp_username":"'.$smtp_username.'","smtp_password":"'.$smtp_password.'",
		"smtp_port":"'.$smtp_port.'","sender_name":"'.$sender_name.'","support_email":"'.$support_email.'","delivery_fee":"'.$delivery_fee.'","bank_name":"'.$bank_name.'","account_name":"'.$account_name.'",
		"account_number":"'.$account_number.'","payment_key":"'.$payment_key.'"}]';
}
	
/////////////////////////////////////////
function _get_sequence_count($conn, $item){
		 $count=mysqli_fetch_array(mysqli_query($conn,"SELECT mast_val FROM setup_masters_tab WHERE mast_id = '$item' FOR UPDATE"));
		  $num=$count[0]+1;
		  mysqli_query($conn,"UPDATE `setup_masters_tab` SET `mast_val` = '$num' WHERE mast_id = '$item'")or die (mysqli_error($conn));
		  if ($num<10){$no='00'.$num;}elseif($num>=10 && $num<100){$no='0'.$num;}else{$no=$num;}
		  return '[{"num":"'.$num.'","no":"'.$no.'"}]';
}

/////////////////////////////////////////
function _alert_sequence_and_update($conn,$alert_detail,$user_id,$user_name,$ip_address,$sysname,$role_id){
		$alertsele=mysqli_fetch_array(mysqli_query($conn,"SELECT mast_val FROM setup_masters_tab WHERE mast_id = 'ALT' FOR UPDATE"));
		$alertno=$alertsele[0]+1;
		$alertid='ALT'.$alertno;
		
		mysqli_query($conn,"INSERT INTO `alert_tab`
		(`alert_id`, `alert_detail`, `user_id`, `name`, `ipaddress`, `computer`, `role_id`, `seen_status`, `date`) VALUES
		('$alertid', '$alert_detail', '$user_id', '$user_name', '$ip_address', '$sysname', '$role_id', 0, NOW())")or die (mysqli_error($conn));
		
		mysqli_query($conn,"UPDATE setup_masters_tab SET mast_val='$alertno' WHERE mast_id = 'ALT'")or die (mysqli_error($conn));
}
	
/////////////////////////////////////////
function _get_setup_role_detail($conn, $role_id){
	$query=mysqli_query($conn,"SELECT * FROM setup_role_tab WHERE role_id = '$role_id'")or die (mysqli_error($conn));
	$fetch=mysqli_fetch_array($query);
		$role_name=$fetch['role_name'];
	return '[{"role_name":"'.$role_name.'"}]';
}

/////////////////////////////////////////
function _get_setup_status_detail($conn, $status_id){
	$query=mysqli_query($conn,"SELECT * FROM setup_status_tab WHERE status_id='$status_id'")or die (mysqli_error($conn));
	$fetch=mysqli_fetch_array($query);
		$status_name=$fetch['status_name'];
	return '[{"status_name":"'.$status_name.'"}]';
}

/////////////////////////////////////////
function _get_setup_category_detail($conn, $cat_id){
	$query=mysqli_query($conn,"SELECT * FROM setup_categories_tab WHERE cat_id='$cat_id'")or die (mysqli_error($conn));
	$fetch=mysqli_fetch_array($query);
		$cat_id=$fetch['cat_id'];
		$cat_name=$fetch['cat_desc'];
	return '[{"cat_id":"'.$cat_id.'","cat_name":"'.$cat_name.'"}]';
}
/////////////////////////////////////////
function _get_setup_page_category_detail($conn, $page_category_id){
	$query=mysqli_query($conn,"SELECT * FROM setup_page_categories_tab WHERE page_category_id='$page_category_id'")or die (mysqli_error($conn));
	$fetch=mysqli_fetch_array($query);
		$page_category_id=$fetch['page_category_id'];
		$page_category_name=$fetch['page_category_name'];
	return '[{"page_category_id":"'.$page_category_id.'","page_category_name":"'.$page_category_name.'"}]';
}

/////////////////////////////////////		
function _get_alert_detail($conn, $alert_id){
			$query=mysqli_query($conn,"SELECT * FROM alert_tab WHERE alert_id='$alert_id'");
			$fetch = mysqli_fetch_array($query); 
			$user_id = $fetch['user_id'];
			$name = $fetch['name'];
			$ipaddress = $fetch['ipaddress'];
			$computer = $fetch['computer'];
			$seen_status = $fetch['seen_status'];
			$date = $fetch['date'];
			return '[{"user_id":"'.$user_id.'", "name":"'.$name.'", "ipaddress":"'.$ipaddress.'", "computer":"'.$computer.'", "seen_status":"'.$seen_status.'", "date":"'.$date.'"}]';
}

/////////////////////////////////////////
function _get_setup_driver_endorsement_detail($conn, $endorsement_id){
	$query=mysqli_query($conn,"SELECT * FROM setup_driver_endorsement_tab WHERE endorsement_id='$endorsement_id'")or die (mysqli_error($conn));
	$fetch=mysqli_fetch_array($query);
		$endorsement_name=$fetch['endorsement_name'];
	return '[{"endorsement_name":"'.$endorsement_name.'"}]';
}

/////////////////////////////////////////
function _get_setup_country_detail($conn, $country_id){
	$query=mysqli_query($conn,"SELECT * FROM setup_countries_tab WHERE country_id='$country_id'")or die (mysqli_error($conn));
	$fetch=mysqli_fetch_array($query);
		$country_name=$fetch['country_name'];
	return '[{"country_name":"'.$country_name.'"}]';
}
/////////////////////////////////////////
function _get_setup_state_detail($conn, $country_id, $state_id ){
	$query=mysqli_query($conn,"SELECT * FROM setup_country_states_tab WHERE country_id='$country_id' AND state_id='$state_id'")or die (mysqli_error($conn));
	$fetch=mysqli_fetch_array($query);
		$state_name=$fetch['state_name'];
	return '[{"state_name":"'.$state_name.'"}]';
}





/////////////////////////////////////////
function _get_staff_detail($conn, $staff_id){
	$query=mysqli_query($conn,"SELECT * FROM staff_tab WHERE staff_id='$staff_id'")or die (mysqli_error($conn));
	$fetch=mysqli_fetch_array($query);
		$staff_id=$fetch['staff_id'];
		$fullname=$fetch['fullname'];
		$mobile=$fetch['mobile'];
		$email=$fetch['email'];
		$passport=$fetch['passport'];
		if ($passport==''){
			$passport='friends.png';
		}
		$otp=$fetch['otp'];
		$role_id=$fetch['role_id'];
		$status_id=$fetch['status_id'];
		$reg_date=$fetch['reg_date'];
		$last_login=$fetch['last_login_date'];
		
	return '[{"staff_id":"'.$staff_id.'","fullname":"'.$fullname.'","mobile":"'.$mobile.'","email":"'.$email.'","passport":"'.$passport.'","otp":"'.$otp.'",
	"role_id":"'.$role_id.'","status_id":"'.$status_id.'","reg_date":"'.$reg_date.'","last_login":"'.$last_login.'"}]';
}	
/////////////////////////////////////////
function _get_publish_detail($conn, $publish_id){
	$query=mysqli_query($conn,"SELECT * FROM publish_tab WHERE publish_id='$publish_id'")or die (mysqli_error($conn));
	$fetch=mysqli_fetch_array($query);
		$page_category_id=$fetch['page_category_id'];
		$publish_id=$fetch['publish_id'];
		$reg_name=$fetch['reg_name'];
		$reg_pix=$fetch['reg_pix'];
		if ($reg_pix==''){
			$reg_pix='sample.jpg';
		}
		$status_id=$fetch['status_id'];
		$staff_id=$fetch['last_updated_by'];
		$updated_date=$fetch['updated_date'];
		$date=$fetch['date'];
		$min_hour_of_hire=$fetch['min_hour_of_hire'];
		$promo_code=$fetch['promo_code'];
		$blog_cat_id=$fetch['blog_cat_id'];
		$blog_view=$fetch['blog_view'];
		$faq_cat_id=$fetch['faq_cat_id'];
		
	return '[{"page_category_id":"'.$page_category_id.'","publish_id":"'.$publish_id.'","reg_name":"'.$reg_name.'",
	"reg_pix":"'.$reg_pix.'","status_id":"'.$status_id.'",
	"staff_id":"'.$staff_id.'","updated_date":"'.$updated_date.'","date":"'.$date.'","min_hour_of_hire":"'.$min_hour_of_hire.'",
	"promo_code":"'.$promo_code.'","blog_cat_id":"'.$blog_cat_id.'","blog_view":"'.$blog_view.'","faq_cat_id":"'.$faq_cat_id.'"}]';
}	
/////////////////////////////////////////
function _get_page_detail($conn,$publish_id){
	$query=mysqli_query($conn,"SELECT * FROM pages_tab WHERE publish_id='$publish_id'")or die (mysqli_error($conn));
	$fetch=mysqli_fetch_array($query);
		$page_url=$fetch['page_url'];
		$page_title=$fetch['page_title'];
		$seo_flyer=$fetch['seo_flyer'];
		if ($seo_flyer==''){
			$seo_flyer='default.jpg';
		}
		$staff_id=$fetch['staff_id'];
		$updated_date=$fetch['updated_date'];
	return '[{"page_url":"'.$page_url.'","page_title":"'.$page_title.'","seo_flyer":"'.$seo_flyer.'","staff_id":"'.$staff_id.'","updated_date":"'.$updated_date.'"}]';
}


////////////////////////////////		
function _admin_title_pane($user_name){?>
	  <div class="page-title-div dashbord-title animated fadeInDown animated animated">
	  <div class="div-in">
		  <div class="left-div">
			  <span id="page-title"><i class="bi-speedometer2"></i> Admin Dashboard</span><br />
			  <div class="project-name"><?php echo ucwords(strtolower($user_name)); ?></div>
		  </div>
		  <div class="right-div">
			  Current Time<br />
			  <?php $this->_dateTimeText();?>
			  <?php echo date("l, d F Y");?>
		  </div>
	  </div>
	  </div>
	  
<?php }

////////////////////////////////		
function _dateTimeText(){?>
				<div class="datetime">
				 <span id="clock"><span id="digitalclock" class="styling"></span></span>
				</div>
<?php }









////////////////////////////////		
function _side_links($conn,$website){
	?>
		<div class="link-div" data-aos="fade-up" data-aos-duration="1000">
				 <div class="link-title"><div><i class="bi-link"></i></div> Hot Link</div>
					<a href="<?php echo $website?>/bookings" title="LookUp Rate">
					 <div class="link"><h2>Request A Quote</h2></div>
					</a>
				   <a href="<?php echo $website?>/airport-transportation-limousine-car-service" title="Our Services">
					 <div class="link"><h2> Our Services</h2></div>
					</a>
				   <a href="<?php echo $website?>/airport-transportation-limousine-car-service" title="Our Services">
					 <div class="link"><h2> Our Trucks</h2></div>
					</a>
				   <a href="<?php echo $website?>/airport-transportation-limousine-car-service" title="Our Services">
					 <div class="link"><h2> Career</h2></div>
					</a>
				   <a href="<?php echo $website?>/tour-special-package-deals" title="Package Deals">
					 <div class="link"><h2> Blog & Articles</h2></div>
					</a>
				   <a href="<?php echo $website?>/wine-country-region-of-northern-california" title="Wine Tour">
					 <div class="link"><h2> Frequently Asked Questions</h2></div>
					</a>
					 <div class="link" onclick="scrolltodiv('service_locations','235');"><h2> Service Locations</h2></div>
				   <a href="<?php echo $website?>/bookings" title="Book A Ride Now">
					 <div class="link"><h2>Contact Us</h2></div>
					</a>
			</div>
            
                       
            <div class="blog-link" data-aos="fade-in" data-aos-duration="1000">
                <div class="blog-link-in">
                
        <?php
			$query=mysqli_query($conn,"SELECT publish_id FROM publish_tab WHERE page_category_id='blog_category' ORDER BY updated_date DESC LIMIT 5 ")or die (mysqli_error($conn));
			while($fetch=mysqli_fetch_array($query)){
				$publish_id=$fetch['publish_id'];
		  $publish_array=$this->_get_publish_detail($conn, $publish_id);
		  $fetch_publish = json_decode($publish_array, true);
			$reg_name=$fetch_publish[0]['reg_name'];
			$updated_date=$fetch_publish[0]['updated_date'];
		  $updated_date=date('d M, Y', strtotime($fetch_publish[0]['updated_date']));
			  $page_array=$this->_get_page_detail($conn, $publish_id);
			  $fetch_page = json_decode($page_array, true);
				$page_url=$fetch_page[0]['page_url'];

		?>
                    
                    <div class="links">
                        <span> <?php echo $updated_date;?></span>
                        <a href="<?php echo $website;?>/blog/<?php echo $page_url;?>/" title="<?php echo $reg_name;?>">
                        <h2><?php echo $reg_name;?></h2></a>
                    </div>
		<?php }?>
                </div>
            </div>
            
            
            
            
<?php }





////////////////////////////////		
function _truck_slides($conn,$website){
	?>
	<?php
           $no=0;
          $query=mysqli_query($conn,"SELECT publish_id FROM publish_tab  WHERE page_category_id='truck_category'  AND status_id ='A'")or die (mysqli_error($conn));
          while($publish_sel=mysqli_fetch_array($query)){
          $no++;
              $publish_id=$publish_sel['publish_id'];
		  $publish_array=$this->_get_publish_detail($conn, $publish_id);
		  $fetch_publish = json_decode($publish_array, true);
			$page_category_id=$fetch_publish[0]['page_category_id'];
			$publish_id=$fetch_publish[0]['publish_id'];
			$reg_name=$fetch_publish[0]['reg_name'];
			$no_of_passengers=$fetch_publish[0]['no_of_passengers'];
			$hourly_rate=$fetch_publish[0]['hourly_rate'];
			$reg_pix=$fetch_publish[0]['reg_pix'];
				
			  $page_array=$this->_get_page_detail($conn, $publish_id);
			  $fetch_page = json_decode($page_array, true);
				$page_url=$fetch_page[0]['page_url'];
    ?>
                        
                        <div class="cg-carousel__slide js-carousel__slide" data-aos="fade-right" data-aos-duration="1000">
                            <a href="<?php echo $page_url;?>" title="<?php echo $reg_name;?>">
                            <div class="trucks-div">
                                <div class="status-div"><i class="bi-lightning-charge-fill"></i> AVAILABLE</div>
                                <div class="img-div"><img src="<?php echo $website?>/uploaded_files/publish-pix/<?php echo $reg_pix;?>" alt="<?php echo $reg_name;?>" /></div>
                                <div class="text-div">
                                    <div class="vote-div">
                                        <div class="text">Luxury <span>Equipped Truck</span></div>
                                        <div class="text no-border"><i class="bi-star-fill"></i><i class="bi-star-fill"></i><i class="bi-star-fill"></i><i class="bi-star-fill"></i><i class="bi-star-fill"></i></div>
                                    </div>
                                    <h2><?php echo $reg_name;?></h2><br clear="all" />
                                    <button class="btn">More Details</button>
                                </div>
                            </div></a>
                        </div>
    <?php }?>
<?php }





////////////////////////////////		
function _service_slides($conn,$website){
	?>
	<?php
           $no=0;
          $query=mysqli_query($conn,"SELECT publish_id FROM publish_tab  WHERE page_category_id='our_service_category' AND status_id ='A'")or die (mysqli_error($conn));
          while($publish_sel=mysqli_fetch_array($query)){
          $no++;
              $publish_id=$publish_sel['publish_id'];
		  $publish_array=$this->_get_publish_detail($conn, $publish_id);
		  $fetch_publish = json_decode($publish_array, true);
			$page_category_id=$fetch_publish[0]['page_category_id'];
			$publish_id=$fetch_publish[0]['publish_id'];
			$reg_name=$fetch_publish[0]['reg_name'];
			$hourly_rate=$fetch_publish[0]['hourly_rate'];
			$reg_pix=$fetch_publish[0]['reg_pix'];
			$min_hour_of_hire=$fetch_publish[0]['min_hour_of_hire'];
			$promo_code=$fetch_publish[0]['promo_code'];
				
			  $page_array=$this->_get_page_detail($conn, $publish_id);
			  $fetch_page = json_decode($page_array, true);
				$page_url=$fetch_page[0]['page_url'];

			$pages_query=mysqli_query($conn,"SELECT * FROM pages_tab WHERE publish_id='$publish_id'");
			$pages_fetch=mysqli_fetch_array($pages_query);
				$seo_description=$pages_fetch['seo_description'];
				$seo_description = substr($seo_description, 0, 120);
				if ($no==1){$class='active';}else{$class='';}
    ?>


                <div class="carousel-item <?php echo $class;?>">
                    <div class="text">
                        <div class="left-div"> <img src="<?php echo $website?>/uploaded_files/publish-pix/<?php echo $reg_pix;?>" alt="<?php echo $reg_name;?>" /> </div>
                        <div class="right-div">
                            <div class="info">
                                <div class="top-title">SERVICE <?php echo $no;?> <i class="bi-circle-fill"></i></div>
                                <h3><?php echo $reg_name;?></h3>
                                <p><?php echo $seo_description;?>...</p>
                                <a href="<?php echo $page_url;?>" title="<?php echo $reg_name;?>">
                                <button class="readmore-btn"> <i class="bi-check2-all"></i> READ MORE</button></a>
                            </div>
                        </div>
                    </div>
                </div>
    <?php }?>
<?php }






////////////////////////////////		
function _page_faq($conn,$publish_id){ ?>
                     <?php
						$query=mysqli_query($conn,"SELECT * FROM pages_faq_tab WHERE publish_id='$publish_id'");
						while($fetch=mysqli_fetch_array($query)){
							$sn=$fetch['sn'];
							$question=$fetch['question'];
							$answer=$fetch['answer'];
					?>
                       <div class="general-faq-div">
                            <div class="faq-title" onclick="_collapse('faq<?php echo $sn;?>')">
                                <h2><?php echo $question;?></h2>
                                <div class="expand-div" id="faq<?php echo $sn;?>num">&nbsp;<i class="bi-plus"></i>&nbsp;</div>
                                <br clear="all" />
                            </div>
                            <div class="faq-answer" id="faq<?php echo $sn;?>answer">
                                <?php echo $answer;?>
                            </div>
                        </div>
                     <?php }?>
            
<?php }












////////////////////////////////		
function _get_latest_blog($conn,$website){ ?>
        <div class="blog-body-div">
        
        <?php
			$query=mysqli_query($conn,"SELECT publish_id FROM publish_tab WHERE page_category_id='blog_category' ORDER BY updated_date DESC LIMIT 1 ")or die (mysqli_error($conn));
			$fetch=mysqli_fetch_array($query);
				$first_publish_id=$fetch['publish_id'];
		  $publish_array=$this->_get_publish_detail($conn, $first_publish_id);
		  $fetch_publish = json_decode($publish_array, true);
			$reg_name=$fetch_publish[0]['reg_name'];
			$reg_pix=$fetch_publish[0]['reg_pix'];
			$staff_id=$fetch_publish[0]['staff_id'];
			$updated_date=$fetch_publish[0]['updated_date'];
		  $updated_date=date('d M, Y', strtotime($fetch_publish[0]['updated_date']));
			$blog_cat_id=$fetch_publish[0]['blog_cat_id'];
			$blog_view=$fetch_publish[0]['blog_view'];
			$fetch_cat=$this->_get_setup_category_detail($conn, $blog_cat_id);
			$array = json_decode($fetch_cat, true);
			$blog_cat_name= $array[0]['cat_name'];
		
			$user_array=$this->_get_staff_detail($conn, $staff_id);
			$u_array = json_decode($user_array, true);
			$user_name= $u_array[0]['fullname'];
			$user_passport= $u_array[0]['passport'];
			  $page_array=$this->_get_page_detail($conn, $first_publish_id);
			  $fetch_page = json_decode($page_array, true);
				$page_url=$fetch_page[0]['page_url'];

		?>
        
        
            <div class="blog-summary" data-aos="zoom-in" data-aos-duration="1000">
                <div class="blog-pix"><img src="<?php echo $website;?>/uploaded_files/publish-pix/<?php echo $reg_pix;?>" alt="<?php echo $reg_name;?>" /></div>
                <div class="blog-title">
                    <div class="blog-title-in">
                        <div class="blog-cat">
                            <div class="cat-div"><?php echo $blog_cat_name;?></div>
                            <div class="cat-div no-border"><span><?php echo number_format($blog_view);?> Views</span></div>
                            <br clear="all"/>
                        </div>
                        <a href="<?php echo $website;?>/blog/<?php echo $page_url;?>/" title="<?php echo $reg_name;?>">
                        <h2><?php echo $reg_name;?></h2></a>
                        <div class="profile-div">
                            <div class="pix-div"><img src="<?php echo $website;?>/uploaded_files/staff_passport/<?php echo $user_passport;?>" alt="<?php echo $user_name;?>" /></div>
                            <div class="detail-div">
                                <div class="name"><?php echo ucwords(strtolower($user_name));?></div>
                                <?php echo $updated_date;?>
                            </div>
                            <br clear="all"/>
                        </div>
                    </div>
                </div>
            </div>

            <div class="blog-link" data-aos="fade-in" data-aos-duration="2600">
                <div class="blog-link-in">
                
                
        <?php
			$query=mysqli_query($conn,"SELECT publish_id FROM publish_tab WHERE page_category_id='blog_category' AND publish_id!='$first_publish_id' ORDER BY updated_date DESC LIMIT 3 ")or die (mysqli_error($conn));
			while($fetch=mysqli_fetch_array($query)){
				$publish_id=$fetch['publish_id'];
		  $publish_array=$this->_get_publish_detail($conn, $publish_id);
		  $fetch_publish = json_decode($publish_array, true);
			$reg_name=$fetch_publish[0]['reg_name'];
			$updated_date=$fetch_publish[0]['updated_date'];
		  $updated_date=date('d M, Y', strtotime($fetch_publish[0]['updated_date']));
			  $page_array=$this->_get_page_detail($conn, $publish_id);
			  $fetch_page = json_decode($page_array, true);
				$page_url=$fetch_page[0]['page_url'];

		?>
                    
                    <div class="links">
                        <span> <?php echo $updated_date;?></span>
                        <a href="<?php echo $website;?>/blog/<?php echo $page_url;?>/" title="<?php echo $reg_name;?>">
                        <h2><?php echo $reg_name;?></h2></a>
                    </div>
		<?php }?>
                </div>
            </div>
            <br clear="all"/>
        </div>

<?php }


////////////////////////////////		
function _get_driver_detail($conn, $driver_id){
	$query=mysqli_query($conn,"SELECT * FROM drivers_tab WHERE driver_id='$driver_id'")or die (mysqli_error($conn));
	$fetch=mysqli_fetch_array($query);
		$driver_id=$fetch['driver_id'];
		$job_id=$fetch['job_id'];
		$first_name=$fetch['first_name'];
		$last_name=$fetch['last_name'];
		$mobile_no=$fetch['mobile_no'];
		$dob=$fetch['dob'];
		$email=$fetch['email'];
		$passport=$fetch['passport'];
		if ($passport==''){
			$passport='friends.png';
		}
		$status_id=$fetch['status_id'];
		$date_updated=$fetch['date_updated'];
		$date=$fetch['date'];
		
	return '[{"driver_id":"'.$driver_id.'","job_id":"'.$job_id.'","first_name":"'.$first_name.'","last_name":"'.$last_name.'","mobile_no":"'.$mobile_no.'",
	"dob":"'.$dob.'","email":"'.$email.'","passport":"'.$passport.'","status_id":"'.$status_id.'","date_updated":"'.$date_updated.'","date":"'.$date.'"}]';
}

////////////////////////////////		
function _get_employment_history_detail($conn, $eh_id){
	$query=mysqli_query($conn,"SELECT * FROM employment_history_tab WHERE eh_id='$eh_id'")or die (mysqli_error($conn));
	$fetch=mysqli_fetch_array($query);
		$eh_id=$fetch['eh_id'];
		$driver_id=$fetch['driver_id'];
		$employer_name=$fetch['employer_name'];
		$city_state=$fetch['city_state'];
		$employer_mobile_no=$fetch['employer_mobile_no'];
		$position=$fetch['position'];
		$start_date=$fetch['start_date'];
		$end_date=$fetch['end_date'];
		$current_employer_question=$fetch['current_employer_question'];
		$contact_employer_answer=$fetch['contact_employer_answer'];
		$truck_id=$fetch['truck_id'];
		$state_driven=$fetch['state_driven'];
		$reason_for_leaving=$fetch['reason_for_leaving'];
		$date=$fetch['date'];
				
	return '[{"eh_id":"'.$eh_id.'","driver_id":"'.$driver_id.'","employer_name":"'.$employer_name.'","city_state":"'.$city_state.'","employer_mobile_no":"'.$employer_mobile_no.'","position":"'.$position.'",
	"start_date":"'.$start_date.'","end_date":"'.$end_date.'","current_employer_question":"'.$current_employer_question.'","contact_employer_answer":"'.$contact_employer_answer.'",
	"truck_id":"'.$truck_id.'","state_driven":"'.$state_driven.'","reason_for_leaving":"'.$reason_for_leaving.'","date":"'.$date.'"}]';
}

////////////////////////////////		
function _get_application_reference_detail($conn, $ar_id){
	$query=mysqli_query($conn,"SELECT * FROM application_reference_tab WHERE ar_id='$ar_id'")or die (mysqli_error($conn));
	$fetch=mysqli_fetch_array($query);
		$ar_id=$fetch['ar_id'];
		$driver_id=$fetch['driver_id'];
		$full_name=$fetch['full_name'];
		$reference_mobile_no=$fetch['reference_mobile_no'];
		$years_known=$fetch['years_known'];
		$street_address=$fetch['street_address'];
		$address_line_2=$fetch['address_line_2'];
		$city=$fetch['city'];
		$zip_code=$fetch['zip_code'];
		$reference_country_id=$fetch['reference_country_id'];
		$reference_state_id=$fetch['reference_state_id'];
		$date=$fetch['date'];
				
	return '[{"ar_id":"'.$ar_id.'","driver_id":"'.$driver_id.'","full_name":"'.$full_name.'","reference_mobile_no":"'.$reference_mobile_no.'","years_known":"'.$years_known.'",
	"street_address":"'.$street_address.'","address_line_2":"'.$address_line_2.'","city":"'.$city.'","zip_code":"'.$zip_code.'",
	"reference_country_id":"'.$reference_country_id.'","reference_state_id":"'.$reference_state_id.'","date":"'.$date.'"}]';
}


////////////////////////////////		
function _get_customer_detail($conn, $customer_id){
	$query=mysqli_query($conn,"SELECT * FROM customers_tab WHERE user_id='$customer_id'")or die (mysqli_error($conn));
	$fetch=mysqli_fetch_array($query);
		$customer_id=$fetch['user_id'];
		$fullname=$fetch['fullname'];
		$phone=$fetch['phone'];
		$email=$fetch['email'];
		$passport=$fetch['passport'];
		if ($passport==''){
			$passport='friends.png';
		}
		$status_id=$fetch['status_id'];
		$last_login_date=$fetch['last_login_date'];
		$reg_date=$fetch['reg_date'];
		
	return '[{"customer_id":"'.$customer_id.'","fullname":"'.$fullname.'","phone":"'.$phone.'","email":"'.$email.'","passport":"'.$passport.'","status_id":"'.$status_id.'","last_login_date":"'.$last_login_date.'","reg_date":"'.$reg_date.'"}]';
}





}//end of class
$callclass=new allClass();
?>

















