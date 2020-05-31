<?php

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 */
function hotel_theme_setup() {
	
	/* Load the primary menu. */
	remove_action( 'omega_before_header', 'omega_get_primary_menu' );	
	add_action( 'omega_header', 'omega_get_primary_menu' );
	add_action( 'omega_header', 'hotel_intro');
	add_filter( 'omega_site_description', 'hotel_site_description' );

	/* Add support for a custom header image. */
	add_theme_support(
		'custom-header',
		array( 'header-text' => false,
			'flex-width'    => true,
			'uploads'       => true,
			'default-image' => get_stylesheet_directory_uri() . '/images/header.png' ) );

	/* Custom background. */
	add_theme_support( 
		'custom-background',
		array( 'default-color' => 'e6dabb' )
	);

	add_action('init', 'hotel_init', 1);

}

add_action( 'after_setup_theme', 'hotel_theme_setup', 11 );

/* disable site description */

function hotel_site_description($desc) {
	$desc = "";
	return $desc;
}

function hotel_init() {
	if(!is_admin()){
		wp_enqueue_script("tinynav", get_stylesheet_directory_uri() . '/js/tinynav.js', array('jquery'));
	} 
}

/* display custom header image */

function hotel_intro() {
	echo "<div class='intro'>";
	if(is_front_page()) {					
		if (get_header_image()) {
			echo '<img class="header-image" src="' . esc_url( get_header_image() ) . '" alt="' . get_bloginfo( 'description' ) . '" />';
		}
	} else {		
		// get title		
		$id = get_option('page_for_posts');
		if ( is_day() || is_month() || is_year() || is_tag() || is_category() || is_singular('post' ) || is_home() ) {
			$the_title = get_the_title($id);
		} else {
			$the_title = get_the_title(); 
		}
		
		if (( 'posts' == get_option( 'show_on_front' )) && (is_day() || is_month() || is_year() || is_tag() || is_category() || is_singular('post' ) || is_home())) {
			echo '<img class="header-image" src="' . esc_url( get_header_image() ) . '" alt="' . $the_title . '" />';	
		} elseif(is_home() || is_singular('post' ) ) {
			if ( has_post_thumbnail($id) ) {
				echo get_the_post_thumbnail( $id, 'full' );
			} elseif (get_header_image()) {
				echo '<img class="header-image" src="' . esc_url( get_header_image() ) . '" alt="' . $the_title . '" />';	
			}
		} elseif ( has_post_thumbnail() && is_singular('page' ) ) {	
			the_post_thumbnail();
		} elseif (get_header_image()) {
			echo '<img class="header-image" src="' . esc_url( get_header_image() ) . '" alt="' . $the_title . '" />';	
		}
	}       
	echo "</div>";	
}




/* login api */
add_action( 'rest_api_init', 'register_api_hooks' );

function register_api_hooks() {
	register_rest_route(
		'custom-plugin', '/login/',
		array(
			'methods'  => 'GET',
			'callback' => 'login',
		)
	);
}

function login($request){
	session_start();
	$creds = array();
	$creds['user_login'] = $request["username"];
	$creds['user_password'] =  $request["password"];
	// $creds['remember'] = true;

	$_SESSION['se_login'] = $creds['user_login'];
	$_SESSION['se_password'] = $creds['user_password'];

	// echo $_SESSION['se_login'];
	// echo $_SESSION['se_password'];


	$user = wp_signon( $creds, false );

	if ( is_wp_error($user) )
		echo $user->get_error_message();
		// echo "hello";

	return $user;
}

// add_action( 'after_setup_theme', 'custom_login' );

function add_cors_http_header(){
    header("Access-Control-Allow-Origin: *");
}
add_action('init','add_cors_http_header');

add_filter('allowed_http_origins', 'add_allowed_origins');

function add_allowed_origins($origins) {
    $origins[] = 'https://www.yourdomain.com/';
    return $origins;
}
add_filter( 'something', 'regis_options' );











/* booking api */


add_action( 'rest_api_init', 'register_api_hooks_booking' );
function register_api_hooks_booking() {
	register_rest_route(
		'custom-plugin', '/hotel/',
		array(
			'methods'  => 'POST',
			'callback' => 'Hotel_booking',
		)
	);
}
function Hotel_booking($request){
	$nm=$_POST['post_title'];//hotel name
	$ph_no=$_POST['post_mime_type'];//user mobile_no
	$usern=$_POST['post_content'];//username
	$dt=$_POST['post_date'];
	$dtr=$_POST['post_date_gmt'];
	$adt=$_POST['post_parent'];
	$child=$_POST['post_password'];
	
	$pinged = $_POST['pinged'];

	
	if($ph_no == "" || !preg_match("/^[0-9]{10}$/", $ph_no))
	{

		$required = "BAD Request";
		http_response_code(400);
		echo $required;
		exit();
  // echo "All Fields Are Reqiuired";
	}
	else{

		$con = mysqli_connect('localhost','root','','tourism');

		$qy= "INSERT INTO wp_posts (post_title, post_mime_type, post_content, post_date,  post_date_gmt , post_parent , post_password , pinged, post_status ,post_type )
		VALUES ('$nm','$ph_no','$usern','$dt','$dtr','$adt','$child','$pinged', 'awebooking-confirmed', 'pending')";

		if ($con->query($qy) === TRUE) {
			$abc =mysqli_insert_id($con);

			echo $abc;
		} 
		else {
			echo "Error: " . $qy . "<br>" . $con->error;
		}
	}
	
}




/* logout api */

add_action( 'rest_api_init', 'logoutfn' );

function logoutfn() {
  register_rest_route(
    'custom-plugin', '/logout/',
    array(
      'methods'  => 'GET',
      'callback' => 'logout')
  );
}
function logout(){
	
 session_destroy();
}





/* display booking api */

add_action( 'rest_api_init', 'Book_hotel' );

function Book_hotel() {
  register_rest_route(
    'custom-plugin', '/book/',
    array(
      'methods'  => 'GET',
      'callback' => 'hotel_room'
  	)
  );
}

function hotel_room($request){
	$pinged = $_GET['pinged'];
	// $ID = $_GET['ID']; //ID
	// var_dump($ID);

	// var_dump($pinged);

	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "tourism";

	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$sql = "SELECT id,post_title, post_mime_type, post_content, post_date, post_date_gmt, post_parent, post_password, post_status FROM wp_posts WHERE pinged = $pinged";
	$result = mysqli_query($conn, $sql);
	$respnose = []; 


	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			// $sql = "SELECT ID, post_title, post_mime_type, post_content, post_date, post_date_gmt, post_parent, post_password, post_status FROM wp_posts WHERE pinged = $pinged";
			// // "ID: "  . $row["ID"].  "\n".
			// echo "Hotel Name: "  . $row["post_title"].  "\n". 
			// "Mobile no: "  . $row["post_mime_type"].  "\n". 
			// "Username:"   . $row["post_content"]. "\n".
			// "Date: "  . $row["post_date"].  "\n". 
			// "Return date:". $row["post_date_gmt"]."\n" ;
			// "Adult:". $row["post_parent"]."\n" ;
			// "Children:". $row["post_password"]."\n" ;

			// $json_response = json_encode($row);
			// echo $json_response
			// exit();
			array_push($respnose,$row);
		}
		echo json_encode($respnose);
		exit();
	}
	else 
	{
		echo "This User is not valid to see his bookings"; 
	}
	mysqli_close($conn);
}








// **Edit button click api **

add_action( 'rest_api_init', 'bookdata');

function bookdata() {
  register_rest_route(
    'custom-plugin', '/edit/',
    array(
      'methods'  => 'GET',
      'callback' => 'displaydata',
    )
  );
}
function displaydata($request){

$id = $_GET['ID'];
    // echo $log_id;

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "tourism";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
         $sql = "SELECT ID, post_title, post_mime_type, post_content, post_date, post_date_gmt, post_parent, post_password  FROM wp_posts WHERE ID = $id";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                        echo json_encode($row);

              $sql = "SELECT id, post_title, post_mime_type, post_content, post_date, post_date_gmt, post_parent, post_password  FROM wp_posts WHERE ID = $id";


                    // echo 
                    //      "ID: "   . $row["ID"]. "\n".
                    //      "Hotel Name: "   . $row["post_title"]. "\n".
                    //      "ph no:"   . $row["post_mime_type"]. "\n".
                    //      "username:"   . $row["post_content"]. "\n".
                    //      "date: "  . $row["post_date"].  "\n". 
                    //      "return date:"   . $row["post_date_gmt"]. "\n".
                    //      "adult:"   . $row["post_parent"]. "\n".
                    //      "children:". $row["post_password"]."\n" ;

                        // exit();
            }
        } else 
        {
            echo "This User is not valid to see his bookings"; 
        } 
}



/* update api */

add_action( 'rest_api_init', 'updatedata');

function updatedata() {
  register_rest_route(
    'custom-plugin', '/update/',
    array(
      'methods'  => 'PUT',
      'callback' => 'tabledata',
    )
  );
}

function tabledata($request){
	$ID = $_GET['ID'];
    var_dump($ID);



    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "tourism";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    parse_str(file_get_contents('php://input'),$_PUT);

	var_dump($_put);
 	 $json_array[] = $_PUT;
      $json = json_encode($json_array);
    echo $json;

 $sql = $conn->query("UPDATE wp_posts SET
      post_title='".$_PUT['post_title']."', post_mime_type='".$_PUT['post_mime_type']."',post_content='".$_PUT['post_content']."',post_date='".$_PUT['post_date']."',post_date_gmt='".$_PUT['post_date_gmt']."',post_parent='".$_PUT['post_parent']."',post_password='".$_PUT['post_password']."' WHERE ID= $ID");

    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}



/* Delete api */

add_action( 'rest_api_init', 'deletedata');

function deletedata() {
  register_rest_route(
    'custom-plugin', '/delete/',
    array(
      'methods'  => 'DELETE',
      'callback' => 'delete',
    )
  );
}

function delete(){
	$ID = $_GET['ID']; //ID

	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "tourism";

// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}



	$query=mysqli_query($conn, "DELETE FROM wp_posts WHERE ID = '$ID' ");

	if($query==true){ 
        echo "Records was delete successfully.";
        exit();
    } else{ 
        echo "ERROR:Records is not delete . " . $mysqli->error;
    } 
	// echo json_encode($query);
    // return $data;
}



/* Sign up api */

add_action( 'rest_api_init', 'register_api_hooks_signup' );

function register_api_hooks_signup() {
	register_rest_route(
		'custom-plugin', '/signup/',
		array(
			'methods'  => 'POST',
			'callback' => 'signup',
		)
	);
}
function signup($request){
	$un=$_POST['user_login'];//username
    $pass=$_POST['user_pass'];//user pass
    $md = wp_hash_password($pass);
    $email=$_POST['user_email'];//user email address
    $nn=$_POST['user_nicename'];//user nicename
	$d= date("Y-m-d h:i:sa");


    $con = mysqli_connect('localhost','root','','tourism');


    $qy= "INSERT INTO wp_users (user_login , user_pass , user_email , user_nicename , display_name, user_registered) 
    VALUES ('$un', '$md' , '$email' , '$nn' , '$nn' , '$d')";
    if ($con->query($qy) === TRUE) {
        // $abc =mysqli_insert_id($con);
        echo "new user create successfully";
        //     echo "ID:".$abc;
        exit;
    } 
    else {
        echo "Error: " . $qy . "<br>" . $con->error;
    }
}





/*  forgot  password api */

add_action( 'rest_api_init', 'register_api_hooks_forgotpass' );

function register_api_hooks_forgotpass() {
    register_rest_route(
        'custom-plugin', '/forgot/',
        array(
            'methods'  => 'PUT',
            'callback' => 'password',
        )
    );
}

function password(){
    $ID = $_GET['user_email'];
    // var_dump($ID);
    $up = $_PUT['user_pass'];
    // $md = md5($up);
    // $user_pass = wp_hash_password("$up");

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "tourism";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    parse_str(file_get_contents('php://input'),$_PUT);
    // print_r($_PUT);

    //
    $json_array[] = $_PUT;
    $json = json_encode($json_array);
    echo $json;
    $sql = mysqli_query($conn, "UPDATE wp_users SET  user_pass='".wp_hash_password($_PUT['user_pass'])."'  WHERE user_email = '$ID' " );

    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }
    $conn->close();
}

//////////////////////////////////////////*confirm button*//////////////////////////////////////////

add_action( 'rest_api_init', 'register_api_hooks_confirm' );

function register_api_hooks_confirm() {
	register_rest_route(
		'custom-plugin', '/confirm_btn/',
		array(
			'methods'  => 'PUT',
			'callback' => 'confirm_btn',
		)
	);
}

function confirm_btn(){
	$ID = $_GET['id'];
	var_dump($ID);

	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "tourism";

	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	parse_str(file_get_contents('php://input'),$_PUT);
    // print_r($_PUT);

	// $json_array[] = $_PUT;
	// $json = json_encode($json_array);
	// echo $json;

	$sql = mysqli_query($conn, "UPDATE wp_posts SET post_status = 'awebooking' WHERE id = '$ID' " );

	if ($conn->query($sql) === TRUE) {
		echo "Record updated successfully";
	} else {
		echo "Error updating record: " . $conn->error;
	}
	$conn->close();
}


//////////////////////////////////////////*cancel button*//////////////////////////////////////////

add_action( 'rest_api_init', 'register_api_hooks_cancel' );

function register_api_hooks_cancel() {
	register_rest_route(
		'custom-plugin', '/cancel_btn/',
		array(
			'methods'  => 'PUT',
			'callback' => 'cancel_btn',
		)
	);
}

function cancel_btn(){
	$ID = $_GET['id'];
	var_dump($ID);

	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "tourism";

	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	parse_str(file_get_contents('php://input'),$_PUT);
    // print_r($_PUT);

	// $json_array[] = $_PUT;
	// $json = json_encode($json_array);
	// echo $json;

	$sql = mysqli_query($conn, "UPDATE wp_posts SET post_status = 'awebooking-canceled' WHERE id = '$ID' " );

	if ($conn->query($sql) === TRUE) {
		echo "Record updated successfully";
	} else {
		echo "Error updating record: " . $conn->error;
	}
	$conn->close();
}

//////////////////////////////////////////*email sent api*//////////////////////////////////////////


add_action( 'rest_api_init', 'register_api_hooks_mail' );

function register_api_hooks_mail() {
	register_rest_route(
		'custom-plugin', '/emailpass/',
		array(
			'methods'  => 'POST',
			'callback' => 'email',
		)
	);
}

function email($request){

	include 'email/index.php';
}

/////////////////////////////////forgot with otp api//////////////////////////////////////


add_action( 'rest_api_init', 'register_api_hooks_forgototp' );

function register_api_hooks_forgototp() {
	register_rest_route(
		'custom-plugin', '/forgototp/',
		array(
			'methods'  => 'POST',
			'callback' => 'forgototp',
		)
	);
}

function forgototp($request){

 $success = "";
    $error_message = "";
    $conn = mysqli_connect("localhost","root","","tourism");
    $result = mysqli_query($conn,"SELECT * FROM wp_users WHERE user_email='" . $_POST["user_email"] . "'");
    var_dump($_POST["user_email"]);

	date_default_timezone_set("Asia/Kolkata");
    $count  = mysqli_num_rows($result);
    $otp = rand(100000,999999);

    $qy = "INSERT INTO otp (otp, user_email, otp_date) VALUES ('$otp','" . $_POST["user_email"] . "','" . date("Y-m-d H:i:s"). "')";
    if ($conn->query($qy) === TRUE) {
        echo "successfully";
    } 
    else {
        echo "Error: " . $qy . "<br>" . $con->error;
    }

    require('email/phpmailer/class.phpmailer.php');
    require('email/phpmailer/class.smtp.php');

    $message_body = "OTP code is:" . $otp;
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPDebug = 0;
    $mail->SMTPAuth = TRUE;
    $mail->SMTPSecure = 'ssl'; // tls or ssl
    $mail->Port     = 465;
    $mail->Username = 'jaybapodara.rao@gmail.com';                 // SMTP username
    $mail->Password = 'Rao@1234';
    $mail->Host     = "smtp.gmail.com";
    $mail->Mailer   = "smtp";
    $mail->SetFrom($email);
    $mail->AddAddress($_POST["user_email"]);
    $mail->Subject = "Change password OTP";
    $mail->MsgHTML($message_body);
    $mail->IsHTML(true);
    $result = $mail->Send();

    return $result;

}






/////////////////////////////////OTP compare//////////////////////////////////////


add_action( 'rest_api_init', 'register_api_hooks_otp_compare' );

function register_api_hooks_otp_compare() {
    register_rest_route(
        'custom-plugin', '/otpcompare/',
        array(
            'methods'  => 'GET',
            'callback' => 'otp_compare',
        )
    );
}
function otp_compare($request){

    $otp = $_GET['otp']; //otp
    $user_email = $_GET['user_email']; //email

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "tourism";


   

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


    

    $sql = "SELECT ID,user_id,user_email,otp,otp_date FROM otp 
    WHERE otp = '$otp' && user_email = '$user_email'
    AND NOW() <= DATE_ADD(otp_date, INTERVAL 300 SECOND)";

    $result = mysqli_query($conn, $sql);
    $respnose = []; 


    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            array_push($respnose,$row);
        }
        echo json_encode($respnose);
        exit();
    }
    else 
    {
        echo "This User is not valid to see his bookings"; 
    }
    mysqli_close($conn);
}