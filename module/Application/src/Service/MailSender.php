<?php
/**
 * User: Alice in wonderland
 * Date: 14.06.2017
 * Time: 15:39
 */

namespace Application\Service;


use Zend\Mail;
use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail as sendmail;

// This class is used to deliver an E-mail message to recipient.
class MailSender
{
    // Sends the mail message.
    public function sendMail($sender, $emailFrom, $subject, $message)
    {
        $result = false;
        try {

            // Create E-mail message
            $mail = new Message();
            $mail->setFrom($emailFrom);
            $mail->addTo($sender);
            $mail->setSubject($subject);
            $mail->setBody($message);

            // Send E-mail message
            $transport = new sendmail($sender);
            $transport->send($mail);
            $result = true;

        } catch(\Exception $e) {
            $result = false;
        }

        // Return status
        return $result;
    }
}