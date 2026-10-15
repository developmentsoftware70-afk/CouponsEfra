<?php

namespace App\Notify;
use App\Notify\NotifyProcess;
use Mailjet\Client;
use Mailjet\Resources;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use SendGrid;
use SendGrid\Mail\Mail;

class Email extends NotifyProcess{

    /**
    * Email of receiver
    *
    * @var string
    */
	public $email;

    /**
    * Assign value to properties
    *
    * @return void
    */
	public function __construct(){
		$this->statusField = 'email_status';
		$this->body = 'email_body';
		$this->globalTemplate = 'email_template';
		$this->notifyConfig = 'mail_config';
	}

    /**
    * Send notification
    *
    * @return void|bool
    */
	public function send(){

		//get message from parent
		$message = $this->getMessage();
		if ($this->setting->en && $message) {
			//Send mail
			$methodName = @$this->setting->mail_config->name ?: 'smtp';

			// Force/prefer SMTP on localhost Windows or when SMTP is configured
			if ($methodName == 'php') {
				$methodName = 'smtp';
			}

			$method = $this->mailMethods($methodName);
			try{
				$this->$method();
				$this->createLog('email');
			}catch(\Exception $e){
				$this->createErrorLog($e->getMessage());
				session()->flash('mail_error',$e->getMessage());
			}
		}

	}

    /**
    * Get the method name
    *
    * @return string
    */
	protected function mailMethods($name){
		$methods = [
			'php'=>'sendPhpMail',
			'smtp'=>'sendSmtpMail',
			'sendgrid'=>'sendSendGridMail',
			'mailjet'=>'sendMailjetMail',
		];
		return $methods[$name] ?? 'sendSmtpMail';
	}

	protected function sendPhpMail(){
		$general = $this->setting;
		$headers = "From: $general->site_name <$general->email_from> \r\n";
	    $headers .= "Reply-To: $general->site_name <$general->email_from> \r\n";
	    $headers .= "MIME-Version: 1.0\r\n";
	    $headers .= "Content-Type: text/html; charset=utf-8\r\n";
	    $sent = @mail($this->email, $this->subject, $this->finalMessage, $headers);
	    if (!$sent) {
	    	$this->sendSmtpMail();
	    }
	}

	protected function sendSmtpMail(){
		$mail = new PHPMailer(true);
		$config = $this->setting->mail_config;
		$general = $this->setting;

        $host = !empty($config->host) ? $config->host : env('MAIL_HOST', 'smtp.gmail.com');
        $port = !empty($config->port) ? (int)$config->port : (int)env('MAIL_PORT', 587);
        $username = !empty($config->username) ? $config->username : env('MAIL_USERNAME');
        $password = !empty($config->password) ? $config->password : env('MAIL_PASSWORD');
        $enc = !empty($config->enc) ? strtolower($config->enc) : strtolower(env('MAIL_ENCRYPTION', 'tls'));

        if (empty($username) || empty($password)) {
            throw new Exception("SMTP Username or Password is not configured. Please set your Gmail address and 16-character App Password in .env (MAIL_USERNAME / MAIL_PASSWORD) or in Admin Email Settings.");
        }

        //Server settings
        $mail->isSMTP();
        $mail->Host       = $host;
        $mail->SMTPAuth   = true;
        $mail->Username   = $username;
        $mail->Password   = $password;
        if ($enc == 'ssl') {
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        }else{
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        }
        $mail->Port       = $port;
        $mail->CharSet    = 'UTF-8';

        // Fix SSL certificate verify issues on Windows / localhost
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        //Recipients
        $fromEmail = !empty($username) ? $username : $general->email_from;
        $fromName  = $general->site_name ?: config('app.name');
        $mail->setFrom($fromEmail, $fromName);
        $mail->addAddress($this->email, $this->receiverName);
        $mail->addReplyTo($fromEmail, $fromName);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $this->subject;
        $mail->Body    = $this->finalMessage;
        $mail->send();
	}

	protected function sendSendGridMail(){
		$general = $this->setting;
		$sendgridMail = new Mail();
	    $sendgridMail->setFrom($general->email_from, $general->site_name);
	    $sendgridMail->setSubject($this->subject);
	    $sendgridMail->addTo($this->email, $this->receiverName);
	    $sendgridMail->addContent("text/html", $this->finalMessage);
	    $sendgrid = new SendGrid($general->mail_config->appkey);
	    $response = $sendgrid->send($sendgridMail);
	    if($response->statusCode() != 202){
	    	throw new Exception(json_decode($response->body())->errors[0]->message);

	    }
	}

	protected function sendMailjetMail()
	{
		$general = $this->setting;
	    $mj = new Client($general->mail_config->public_key, $general->mail_config->secret_key, true, ['version' => 'v3.1']);
	    $body = [
	        'Messages' => [
	            [
	                'From' => [
	                    'Email' => $general->email_from,
	                    'Name' => $general->site_name,
	                ],
	                'To' => [
	                    [
	                        'Email' => $this->email,
	                        'Name' => $this->receiverName,
	                    ]
	                ],
	                'Subject' => $this->subject,
	                'TextPart' => "",
	                'HTMLPart' => $this->finalMessage,
	            ]
	        ]
	    ];
	    $response = $mj->post(Resources::$Email, ['body' => $body]);
	}

    /**
    * Configure some properties
    *
    * @return void
    */
	protected function prevConfiguration(){
		if ($this->user) {
			$this->email = $this->user->email;
			$this->receiverName = $this->user->fullname;
		}
		$this->toAddress = $this->email;
	}
}
