
<?php
    /*
    Plugin Name: AWCG | Contact Form Plugin
    Plugin URI: http://example.com
    Description: WordPress Contact Form
    Version: 1.0
    Author: Tamil
    Author URI: http://thatguy.com
    */
 
 // TODO: Turn the function html_form_code() into a template PHP file
 
function html_form_code() {
    echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
    echo '<p>';
    echo 'Name <br/>';
    echo '<input type="text" name="cf-name" pattern="[a-zA-Z0-9 ]+" value="' . ( isset( $_POST["cf-name"] ) ? esc_attr( $_POST["cf-name"] ) : '' ) . '" size="64" />';
    echo '</p>';
    echo '<p>';
    echo 'Email <br/>';
    echo '<input type="email" name="cf-email" value="' . ( isset( $_POST["cf-email"] ) ? esc_attr( $_POST["cf-email"] ) : '' ) . '" size="64" />';
    echo '</p>';
    echo '<p>';
    echo 'Subject (required) <br/>';
    echo '<input type="text" name="cf-subject" pattern="[a-zA-Z ]+" value="' . ( isset( $_POST["cf-subject"] ) ? esc_attr( $_POST["cf-subject"] ) : '' ) . '" size="64" />';
    echo '</p>';
    echo '<p>';
    echo 'Message (required) <br/>';
    echo '<textarea rows="10" cols="20" name="cf-message">' . ( isset( $_POST["cf-message"] ) ? esc_attr( $_POST["cf-message"] ) : '' ) . '</textarea>';
    echo '</p>';
    echo '<p><input type="submit" name="cf-submitted" value="Send"></p>';
    echo '</form>';
}
 
function deliver_mail() {
 
    // click submit button to send
    if ( isset( $_POST['cf-submitted'] ) ) {
 
        // sanitize values
        $name    = sanitize_text_field( $_POST["cf-name"] );
        $email   = sanitize_email( $_POST["cf-email"] );
        $subject = sanitize_text_field( $_POST["cf-subject"] );
        $message = esc_textarea( $_POST["cf-message"] );
        
        $message = apply_filters('awcg_cf_message', $message);
 
        $to = $email;
 
        $headers = "From: $name <$email>" . "\r\n";
 
        // Submit message
        if ( wp_mail( $to, $subject, $message, $headers ) ) {
            echo '<div>';
            echo '<p>Your subject "<i>' . $subject . '</i>" has been sent successfully to <b>' . $name .'</b>. Expect an email soon.</p>';
            echo '</div>';
        } else {
            echo 'An unexpected error occurred';
        }
    }
}

// 
function contactForm_styles() {
    wp_register_style( 'contactFormStylesheet', plugins_url('cfstyles.css', __FILE__) );
    wp_enqueue_style( 'contactFormStylesheet' );
}

// see this as the main function 
function cf_shortcode() {
    ob_start();
    contactForm_styles();
    deliver_mail();
    html_form_code();
 
    return ob_get_clean();
}
 
add_shortcode( 'awcg_contactform', 'cf_shortcode' );

add_filter('awcg_cf_message', function($message) {
    return $message . ' Sent via the AWCG Contact Form';
});

 
?>