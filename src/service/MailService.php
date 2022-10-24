<?php

namespace App\service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class MailService
{
    private VerifyEmailHelperInterface $verifyEmailHelper;
    private MailerInterface $mailer;

    public function __construct(VerifyEmailHelperInterface $helper, MailerInterface $mailer)
    {
        $this->verifyEmailHelper = $helper;
        $this->mailer = $mailer;
    }

    /**
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function sendEmail(
        string $to,
        string $htmlTemplate,
        array $context,
        string $from = 'tomasojeanroch@gmail.com'

    )
    {


        $email = new TemplatedEmail();
        $email->to($to);
        $email->htmlTemplate($htmlTemplate);
        $email->context($context);
        $email->from($from);

        $this->mailer->send($email);
    }
}