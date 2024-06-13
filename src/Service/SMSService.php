<?php

namespace App\Service;

use App\Entity\Appointment;
use App\Exception\AppointmentSMSReminderException;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\TexterInterface;
use Twig\Environment;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;
use Twilio\Rest\Verify\V2\ServiceContext;

class SMSService
{
    private ServiceContext $twilio;

    /**
     * @throws ConfigurationException
     */
    public function __construct(
        private readonly TexterInterface $texter,
        private readonly Environment $twig,
        private readonly string $twilioSID,
        private readonly string $twilioToken,
        private readonly string $secondTwilioVerificationSID
    ) {
        $client = new Client($this->twilioSID, $this->twilioToken);
        $this->twilio = $client->verify->v2->services($this->secondTwilioVerificationSID);
    }

    #[ArrayShape(['sms' => 'string'])]
    public function sendSMSReminderToPatient(string $phoneNumber, array $appointment): void
    {
        $sms = new SmsMessage(
            $phoneNumber,
            $this->twig->render(
                'sms/sms-reminder.html.twig',
                [
                    'appointments' => $appointment,
                ]
            )
        );
        try {
            $this->texter->send($sms);
        } catch (\Exception $exception) {
            throw AppointmentSMSReminderException::problemWithSendSMSReminder();
        }
    }

    #[ArrayShape(['sms' => 'string'])]
    public function sendSMSNewUserRegistred(string $phoneNumber, string $email, string $password): void
    {
        $sms = new SmsMessage(
            $phoneNumber,
            $this->twig->render(
                'sms/sms-new-doctor-registred.html.twig',
                [
                    'email' => $email,
                    'password' => $password,
                ]
            )
        );
        try {
            $this->texter->send($sms);
        } catch (\Exception $exception) {
            throw AppointmentSMSReminderException::problemWithSendSMSReminder();
        }
    }

    #[ArrayShape(['sms' => 'string'])]
    public function sendSMSCancelAppointmentToPatient(string $phoneNumber, Appointment $appointment): void
    {
        $sms = new SmsMessage(
            $phoneNumber,
            $this->twig->render(
                'sms/sms-reminder-cancel-appointment.html.twig',
                [
                    'appointment' => $appointment,
                ]
            )
        );
        try {
            $this->texter->send($sms);
        } catch (\Exception $exception) {
            throw AppointmentSMSReminderException::problemWithSendSMSReminder();
        }
    }

    #[ArrayShape(['sms' => 'string'])]
    public function sendSMSEditAppointmentToPatient(string $phoneNumber, Appointment $appointment): void
    {
        $sms = new SmsMessage(
            $phoneNumber,
            $this->twig->render(
                'sms/sms-reminder-edit-appointment.html.twig',
                [
                    'appointment' => $appointment,
                ]
            )
        );
        try {
            $this->texter->send($sms);
        } catch (\Exception $exception) {
            throw AppointmentSMSReminderException::problemWithSendSMSReminder();
        }
    }

}
