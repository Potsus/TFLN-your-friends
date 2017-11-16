<?php



require_once "/var/www/library/PHPMailer/class.phpmailer.php";
require_once "/var/www/library/functions.php";

class Mail {
    
    private $mailer;
    private $login;
    
    function __construct() {
        $this->login = json_decode(file_get_contents("/var/private/mail_login.json"), true);
        $this->createMailer();
        
        if(isset($_POST['to']) && isset($_POST['subject']) && isset($_POST['message'])){
            $this->to       = $_POST['to'];
            $this->from     = $_POST['from'] ?: 'figbot@fastfig.com';
            $this->fromName = $_POST['fromName'] ?: 'FastFig';
            $this->subject  = $_POST['subject'];
            $this->message  = $_POST['message'];
        }
        else {
            die('a required field "to", "subject", or "message" was left unset');
        }
        
    }
    
    private function createMailer() {
        $this->mailer  = new PHPMailer();
        $this->mailer->IsSMTP();
        
        //Mandrill SMTP config
        $this->mailer->SMTPAuth   = true;               
        $this->mailer->SMTPSecure = "ssl";
        $this->mailer->Host       = $this->login['Host'];    
        $this->mailer->Port       = $this->login['Port'];                 
        $this->mailer->Username   = $this->login['Username'];
        $this->mailer->Password   = $this->login['Password'];
    }
    
    function send() {
        
        $this->mailer->From = $this->from;
        $this->mailer->FromName   = $this->fromName;
        $this->mailer->Subject = $this->subject;
        $this->mailer->MsgHTML($this->message);
        $this->mailer->AltBody = $this->message;

        $this->mailer->AddAddress($this->to, "name to");
        $this->mailer->IsHTML(false);
        
        $send = $this->mailer->Send();
        
        //Overwrite the existing Mail
        $this->createMailer();
        
        error_log(json_encode($send));
        return $send;
        
    }
}
    
$mail = new Mail;
error_log($mail->send());

?>
