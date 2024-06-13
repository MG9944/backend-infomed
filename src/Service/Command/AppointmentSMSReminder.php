<?php

namespace App\Service\Command;

use _PHPStan_52b7bec27\Nette\Utils\DateTime;
use App\Exception\AppointmentSMSReminderException;
use App\Repository\AppointmentRepository;
use App\Repository\PatientRepository;
use App\Service\SMSService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AppointmentSMSReminder extends Command
{
    protected static $defaultName = 'api:send-sms-reminder';

    public function __construct(
        private readonly AppointmentRepository $appointmentRepository,
        private readonly SMSService $smsService,
        private readonly PatientRepository $patientRepository,
        private readonly LoggerInterface $loggerMyApi,
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Send sms to patient about appointment');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('sending sms');
        $this->sendSMSToPatients();

        $io->info('Done!');

        return 0;
    }

    private function sendSMSToPatients()
    {
        try {
            $date = new DateTime();
            $patients = $this->patientRepository->getPatients();
            foreach ($patients as $patient) {
                $appointment = $this->appointmentRepository->getAppointmentsByPatientAndDate($patient, $date);
                if ($appointment) {
                    $this->smsService->sendSMSReminderToPatient($patient->getPhoneNumber(), $appointment);
                }
            }
        } catch (AppointmentSMSReminderException $exception) {
            $this->loggerMyApi->error($exception);
        }
    }
}
