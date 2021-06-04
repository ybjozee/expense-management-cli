<?php

namespace App\Service;

use SendGrid;
use SendGrid\Mail\Mail;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class SendgridMailer {

    private string $apiKey;
    private string $senderEmail;
    private string $senderName;

    public function __construct(ParameterBagInterface $parameterBag) {

        $this->apiKey = $parameterBag->get('sendgrid_api_key');
        $this->senderEmail = $parameterBag->get('sendgrid_sender_email');
        $this->senderName = $parameterBag->get('sendgrid_sender_name');
    }

    public function sendMail(
        string $recipientEmail,
        string $subject,
        string $htmlContent,
        ?string $attachmentPath
    ) {

        $email = new Mail();
        $email->setFrom($this->senderEmail, $this->senderName);
        $email->setSubject($subject);
        $email->addTo($recipientEmail);
        $email->addContent("text/html", $htmlContent);

        if (!is_null($attachmentPath)) {
            $attachment = base64_encode(file_get_contents($attachmentPath));
            $email->addAttachment(
                $attachment,
                "application/octet-stream",
                basename($attachmentPath),
                "attachment"
            );
        }
        $sendgrid = new SendGrid($this->apiKey);
        $sendgrid->send($email);
    }

}