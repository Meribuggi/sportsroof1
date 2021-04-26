<?php
/**
 * Plugin Name: Custom Registration Form
 * Plugin URI: http://localhost/sportroof/custom-regform
 * Description: Custom Registration Form
 * Version: 1.0
 * Author: Dav Ammy
 */
 
 // register jquery and style on initialization
add_action('init', 'cf_register_script');
function cf_register_script() {
    wp_register_style( 'new_style', plugins_url('/css/cf-style.css', __FILE__), false, '1.0.0', 'all');
}

// use the registered jquery and style above
add_action('wp_enqueue_scripts', 'cf_enqueue_style');

function cf_enqueue_style(){
   wp_enqueue_style( 'new_style' );
}

function registration_form( $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio ) {
   
    echo '
    <form action="' . $_SERVER['REQUEST_URI'] . '" method="post">
	<table class="custom_reg">
		<tr>
			<td><label for="username">Username <strong>*</strong></label></td>
			<td><input type="text" name="username" value="' . ( isset( $_POST['username'] ) ? $username : null ) . '"></td>
		</tr>
		<tr>
			<td><label for="password">Password <strong>*</strong></label></td>
			<td><input type="password" name="password" value="' . ( isset( $_POST['password'] ) ? $password : null ) . '"></td>
		</tr>     
		<tr>
			<td><label for="email">Email <strong>*</strong></label></td>
			<td><input type="text" name="email" value="' . ( isset( $_POST['email']) ? $email : null ) . '"></td>
		</tr>     
		<tr>
			<td><label for="website">Website</label></td>
			<td><input type="text" name="website" value="' . ( isset( $_POST['website']) ? $website : null ) . '"></td>
		</tr>     
		<tr>
			<td><label for="firstname">First Name</label></td>
			<td><input type="text" name="fname" value="' . ( isset( $_POST['fname']) ? $first_name : null ) . '"></td>
		</tr>
		<tr>
			<td><label for="website">Last Name</label></td>
			<td><input type="text" name="lname" value="' . ( isset( $_POST['lname']) ? $last_name : null ) . '"></td>
		</tr>
		 
		<tr>
			<td><label for="nickname">Nickname</label></td>
			<td><input type="text" name="nickname" value="' . ( isset( $_POST['nickname']) ? $nickname : null ) . '"></td>
		</tr>
		 
		<tr>
			<td><label for="bio">About / Bio</label></td>
			<td><textarea name="bio">' . ( isset( $_POST['bio']) ? $bio : null ) . '</textarea></td>
		</tr>
		<tr><td colspan="2"><input type="submit" name="submit" value="Register" class="cf_rgbtn"/></td></tr>
		</table>
    </form>
    ';
}


function registration_validation( $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio )  {

	global $reg_errors;
	$reg_errors = new WP_Error;
	
	if ( empty( $username ) || empty( $password ) || empty( $email ) ) {
		$reg_errors->add('field', 'Required form field is missing');
	}
	if ( 4 > strlen( $username ) ) {
		$reg_errors->add( 'username_length', 'Username too short. At least 4 characters is required' );
	}
	if ( username_exists( $username ) ){
		$reg_errors->add('user_name', 'Sorry, that username already exists!');
	}
	if ( ! validate_username( $username ) ) {
		$reg_errors->add( 'username_invalid', 'Sorry, the username you entered is not valid' );
	}
	if ( 5 > strlen( $password ) ) {
        $reg_errors->add( 'password', 'Password length must be greater than 5' );
    }
	if ( !is_email( $email ) ) {
    $reg_errors->add( 'email_invalid', 'Email is not valid' );
}
if ( email_exists( $email ) ) {
    $reg_errors->add( 'email', 'Email Already in use' );
}
if ( ! empty( $website ) ) {
    if ( ! filter_var( $website, FILTER_VALIDATE_URL ) ) {
        $reg_errors->add( 'website', 'Website is not a valid URL' );
    }
}

	
	
	
	
	
	if ( is_wp_error( $reg_errors ) ) {
 
		foreach ( $reg_errors->get_error_messages() as $error ) {
		 
			echo '<div>';
			echo '<strong>ERROR</strong>:';
			echo $error . '<br/>';
			echo '</div>';
			 
		}
	 
	}
}



function complete_registration() {
    global $reg_errors, $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio;
    if ( 1 > count( $reg_errors->get_error_messages() ) ) {
        $userdata = array(
        'user_login'    =>   $username,
        'user_email'    =>   $email,
        'user_pass'     =>   $password,
        'user_url'      =>   $website,
        'first_name'    =>   $first_name,
        'last_name'     =>   $last_name,
        'nickname'      =>   $nickname,
        'description'   =>   $bio,
        );
        $user = wp_insert_user( $userdata );
        echo 'Registration complete. Goto <a href="' . get_site_url() . '/my-account/">login page</a>.';   
    }
}


function custom_registration_function() {
    if ( isset($_POST['submit'] ) ) {
        registration_validation(
        $_POST['username'],
        $_POST['password'],
        $_POST['email'],
        $_POST['website'],
        $_POST['fname'],
        $_POST['lname'],
        $_POST['nickname'],
        $_POST['bio']
        );
         
        // sanitize user form input
        global $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio;
        $username   =   sanitize_user( $_POST['username'] );
        $password   =   esc_attr( $_POST['password'] );
        $email      =   sanitize_email( $_POST['email'] );
        $website    =   esc_url( $_POST['website'] );
        $first_name =   sanitize_text_field( $_POST['fname'] );
        $last_name  =   sanitize_text_field( $_POST['lname'] );
        $nickname   =   sanitize_text_field( $_POST['nickname'] );
        $bio        =   esc_textarea( $_POST['bio'] );
 
        // call @function complete_registration to create the user
        // only when no WP_error is found
        complete_registration(
        $username,
        $password,
        $email,
        $website,
        $first_name,
        $last_name,
        $nickname,
        $bio
        );
    }
 global $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio;
    registration_form(
        $username,
        $password,
        $email,
        $website,
        $first_name,
        $last_name,
        $nickname,
        $bio
        );
}
//https://wordpress.stackexchange.com/questions/196453/displaying-logged-in-user-name-in-wordpress-menu

// Register a new shortcode: [cr_custom_registration]
add_shortcode( 'cr_custom_registration', 'custom_registration_shortcode' );
 
// The callback function that will replace [book]
function custom_registration_shortcode() {
    ob_start();
	if (!is_user_logged_in()) {
		custom_registration_function();
	}
    return ob_get_clean();
}