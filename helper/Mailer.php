<?php
/**
 * Created by PhpStorm.
 * User: jakemarvin05
 * Date: 5/7/2015
 * Time: 4:37 AM
 */

namespace Helper;


class Mailer {
	
	//The recipient of the email
	public  $to;
	
	//The Subject of the email	
	public $subject;

	//The Body of the email
	public $message;
	
	//The Reset Password Message
	static  $RESET_PASSWORD_MESSAGE;
	
	//The Subscriber Welcome Mesage
	static  $WELCOME_MESSAGE;
	
	
	public function sendEmail($purpose){
		$emailMessage = "";
		switch ($purpose){
		    case 'reset-password':
				$emailMessage = $this::$RESET_PASSWORD_MESSAGE;
				break;
			case 'welcome':
				$emailMessage = $this::$WELCOME_MESSAGE;
				break;
			default:
				$emailMessage = $this->message;
		}
			
		//TODO: Mail function
		
	}
	
	

} 