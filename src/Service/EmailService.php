<?php

namespace App\Service;

use App\Entity\Appointment;
use JetBrains\PhpStorm\ArrayShape;
use Swift_Mailer;
use Swift_Message;
use Twig\Environment;

class EmailService
{
    public function __construct(
        private Swift_Mailer $mailer,
        private readonly Environment $twig,
    ) {
    }

    #[ArrayShape(['message' => 'string'])]
    public function sendEmailChangePassword(string $email, string $plainPassword)
    {
        $message = (new Swift_Message('[Infomed] Change password'))
            ->setFrom('infomed@company.com')
            ->setTo($email)
            ->setBody(
                $this->twig->render(
                    'emails/change-password.html.twig',
                    [
                        'email' => $email,
                        'password' => $plainPassword,
                    ]
                ),
                'text/html'
            );
        try {
            $this->mailer->send($message);
        } catch (\Exception $exception) {
            return sprintf('Email has not been sent');
        }
    }

    #[ArrayShape(['message' => 'string'])]
    public function sendEmailNewUser(string $email, string $plainPassword)
    {
        $message = (new Swift_Message('[Infomed] new user registered'))
            ->setFrom('infomed@company.com')
            ->setTo($email)
            ->setBody(
                $this->twig->render(
                    'emails/new-doctor-registred.html.twig',
                    [
                        'email' => $email,
                        'password' => $plainPassword,
                    ]
                ),
                'text/html'
            );
        try {
            $this->mailer->send($message);
        } catch (\Exception $exception) {
            return sprintf('Email has not been sent');
        }
    }

    #[ArrayShape(['message' => 'string'])]
    public function sendEmailPatientAppointmentPDF(string $email, string $pdf, string $fileName, Appointment $appointment)
    {
        $message = (new Swift_Message('[Infomed] Information about patient prescription'))
            ->setFrom('infomed@company.com')
            ->setTo($email)
            ->setBody(
                $this->twig->render(
                    'emails/information-about-patient-prescription.html.twig',
                    [
                        'appointment' => $appointment,
                    ]
                ),
                'text/html'
            )->attach(
                (new \Swift_Attachment($pdf))
                    ->setFilename($fileName)
                    ->setContentType('application/csv')
            );
        try {
            $this->mailer->send($message);
        } catch (\Exception $exception) {
            return sprintf('Email has not been sent');
        }
    }

    #[ArrayShape(['message' => 'string'])]
    public function sendEmailPatientCardPDF(string $email, string $pdf, string $fileName, Appointment $appointment)
    {
        $message = (new Swift_Message('[Infomed] Information about patient card'))
            ->setFrom('infomed@company.com')
            ->setTo($email)
            ->setBody(
                $this->twig->render(
                    'emails/information-about-patient-card.html.twig',
                    [
                        'appointment' => $appointment,
                    ]
                ),
                'text/html'
            )->attach(
                (new \Swift_Attachment($pdf))
                    ->setFilename($fileName)
                    ->setContentType('application/csv')
            );
        try {
            $this->mailer->send($message);
        } catch (\Exception $exception) {
            return sprintf('Email has not been sent');
        }
    }
}
