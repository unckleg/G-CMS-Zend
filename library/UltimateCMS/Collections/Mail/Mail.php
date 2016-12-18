<?php

class UltimateCMS_Collections_Mail_Mail {
	
    public function sendemail($to_email, $subject, $htmlMessage) {
        $message = "";
        $sender = Zend_Registry::get('config')->sender;

        $mail = new Zend_Mail('UTF-8');
        $mail->setSubject($subject);
        $mail->addTo($to_email, $sender);
        $mail->setBodyHtml($htmlMessage);
        $mail->setBodyText($message);

        return $result = $mail->send();
    }
}
