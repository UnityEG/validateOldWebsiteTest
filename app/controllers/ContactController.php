<?php
use \Swift_Mailer;
use \Swift_SmtpTransport as SmtpTransport;

class ContactController extends \BaseController {

    public function index() {

		$rules = array(
		'inputName'     => 'required',
		'inputEmail'	=> 'required|email',
		'inputPhone' 	=> 'numeric',
		'inputMessage' 	=> 'required'
	);


		 $validation =Validator::make(Input::all(),$rules);

        if ($validation->passes()) {
		
		Mail::send('contactmessage',array(), function ($m) {
            $m->to('info@validate.co.nz','Validate')->subject('New Contact');
        });
		
		/*
		$backup = Mail::getSwiftMailer();
$transport = SmtpTransport::newInstance('smtp.gmail.com', 465 , 'ssl' );
$transport->setUsername('mahmoudhasanmahmoudhasan@gmail.com');
$transport->setPassword('123456a]');

$gmail = new Swift_Mailer($transport);

Mail::setSwiftMailer($gmail);

$message = Swift_Message::newInstance('New Contact')
  ->setFrom(array('contact@validate.co.nz' => 'Validate'))
  ->setTo(array('info@validate.co.nz' => 'Validate')); 
  		
Mail::setSwiftMailer($backup);		
*/
		


		return View::make('contact')->with(array('isSent'=>'yes'));
        }
				
		$data=array('name'=>Input::get('inputName'),'email'=>Input::get('inputEmail'),
						'phone'=>Input::get('inputPhone'),'inputMessage'=>Input::get('inputMessage'),
						'message'=>'Fix the errors below.');
        return Redirect::route('ContactController.index')
                        ->withInput()
                        ->withErrors($validation)
						->with($data); 						
    }
	
	    public function store()
    {
        // this is your NEW store method
        // put logic here to save the record to the database
    }
	
	

}
