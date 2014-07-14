<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Mailer extends CWidget {
    public function sendRegistrationMail($to, $subject,$body, $view=null) {

        $this->sendMail($view, $to, $subject,$body, $cc = null, $bcc = null);
    }
   
    public function sendMail($view, $to,  $subject, $body = '', $cc = null, $bcc = null) {

        $message = new YiiMailMessage();
        $message->view = $view;
        $message->subject = $subject;
        $message->setBody($body, 'text/html');                
        $message->addTo($to);
        if($cc){
            $message->AddCC($cc);
        }
        if($bcc) {
            $message->AddBCC($bcc);
        }
        $message->from = Yii::app()->params['EMAIL_FROM'];
        Yii::app()->mail->send($message);
    }
}