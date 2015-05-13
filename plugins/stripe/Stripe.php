<?php
/**
 * Created by PhpStorm.
 * User: jakemarvin05
 * Date: 3/11/2015
 * Time: 5:15 AM
 */

namespace Plugin;

use \Plugin\Plugin;
use \Stripe\Stripe;

require_once ROOT_DIR . "/plugins/Plugin.php";
require_once ROOT_DIR . "/includes/config/globalConfig.php";
require_once ROOT_DIR . "/vendor/stripe/stripe-php-master/init.php";

class StripePlugin extends \Plugin\Plugin
{

	/**
	 * @functionName
	 * __construct
	 * @description
	 * Executed every time an instance is made
	 **/
	function __construct()
	{
		/* Sets the secret key
		 See your keys here https://dashboard.stripe.com/account*/
		\Stripe\Stripe::setApiKey(STRIPE_API_KEY);
		parent::__construct(__CLASS__);
	}

	/**
	 * @functionName
	 * retrieveCustomer
	 * @description
	 * Subscribe a customer to the designated plan
	 * @params
	 * $custumerID - string, customer ID gained from the stripe token
	 * $plan - string, The identifier of the plan to subscribe the customer to.
	 **/
	public function  createSubsciption($customerID, $plan)
	{   
		$customer = false;
		try {
			$customer = $this->retrieveCustomer($customerID);
			$customer->subscriptions->create(array("plan" => $plan));
		} catch (\Stripe\Error\Card $e) {
			// The card has been declined
			echo 'The Card has been declined!';
		}
		return $customer;
	}

	/**
	 * @functionName
	 * retrieveCustomer
	 * @description
	 * Retrieves a costumer if exist and create new if none
	 * @params
	 * $customerID - string, customer ID gained from the stripe token
	 **/
	private function retrieveCustomer($customerID)
	{

		$patron = \Stripe\Customer::retrieve($customerID);
		if (!empty($patron)) {
			$customerID = $patron->id;
		} else {
			$customerID = $customerID;
			// Save the customer ID in your database so you can use it later
			saveStripeCustomerId($user, $customerID);
		}
		return $customerID;
	}

	/**
	 * @functionName
	 * createCharge
	 * @description
	 * Charges a costumer
	 * @params
	 * $amount - integer, the value of the amount to be charge where 100 = 1 unit
	 * $currency - string, the currency that will be used
	 * $token - string, the unique identifier of the current session, achieved with stripe.js
	 * $email - string, the email address of the customer
	 **/
	public function createCharge($amount, $currency, $token, $email,$description=null)
	{
		/* Get the credit card details submitted by the form
		 Create the charge on Stripe's servers - this will charge the user's card*/
		$charge = false;
		$description =(null?$email:$description);
		
		try {
			$customer = \Stripe\Customer::create(array(
					"source" => $token,
					"description" => $email)
			);

			$customerId = $this->retrieveCustomer($customer->id);
			$charge = \Stripe\Charge::create(array(
					"amount" => $amount, # amount in cents, again
					"currency" => $currency,
					"customer" => $customerId,
					"description" => "This is the Product")
			);

			
		} catch (\Stripe\Error\Card $e) {
			/*The card has been declined*/
			echo 'The Card has been declined!';
		}
		return $charge;
	}

	/**
	 * @functionName
	 * retrieveCharge
	 * @description
	 * Retrieves the details of a charge that has previously been created.
	 * @params
	 * $chargeID - string, The identifier of the charge to be retrieved.
	 **/
	public function retrieveCharge($chargeID)
	{
		$charge = false;
		try {
			$charge = \Stripe\Charge::retrieve($chargeID);
			
		} catch (\Stripe\Error\Card $e) {
			/* The card has been declined*/
			echo 'The Card has been declined!';
		}
		return $charge;
	}

	/**
	 * @functionName
	 * updateCharge
	 * @description
	 * Updates the specified charge by setting the values of the parameters passed.
	 * @params
	 * $chargeID - string, The identifier of the charge to be updated.
	 * $info - string, The informations that will updated
	 **/
	public function updateCharge($chargeID, $info)
	{
		$charge = false;
		try {
			$charge = \Stripe\Charge::retrieve($chargeID);
			foreach ($info as $key => $value) {
				$charge->$key = $value;
			}
			$charge->save();

		} catch (\Stripe\Error\Card $e) {
			/* The card has been declined */
			echo 'The Card has been declined!';
		}
		return $charge;
	}
	
	/**
	 * @functionName
	 * captureCharge
	 * @description
	 	* Capture the payment of an existing, uncaptured, charge. 
		* This is the second half of the two-step payment flow, 
		* where first you created a charge with the capture option set to false.
	 * @params
	 * $chargeID - string, The identifier of the charge to be updated.
	 * $info - string, The informations that will updated, please refer to Stripe API for the list of keys https://stripe.com/docs/api
	 **/
	public function captureCharge($chargeID, $info)
	{
		$charge = false;
		try {
			$charge = \Stripe\Charge::retrieve($chargeID);
			foreach ($info as $key => $value) {
				$charge->$key = $value;
			}
			$charge->capture();

		} catch (\Stripe\Error\Card $e) {
			/* The card has been declined */
			echo 'The Card has been declined!';
		}
		return $charge;
		
	}
	
	/**
	 * @functionName
	 * listCharges
	 * @description
	 * Returns a list of charges you've previously created. * @params
	 **/
	public function listCharges()
	{
		$charge = false;
		try {
		 $charge =	\Stripe\Charge::all(array("limit" => 3));

		} catch (\Stripe\Error\InvalidRequest $e) {
			/* Request is invalid */
			echo 'The process was cancelled due to invalid request!';
		}
		return $charge;
		
	}
	
	/**
	 * end    \Plugin\StripePlugin
	 **/
}