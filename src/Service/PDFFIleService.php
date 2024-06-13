<?php

namespace App\Service;

use App\Dto\Response\Transformer\PatientCardPatientAppointmentDtoTransformer;
use App\Entity\User;
use App\Exception\CreatePatientException;
use App\Exception\GeneratePDFException;
use App\Repository\AppointmentRepository;
use App\Repository\PatientRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Twig\Environment;

class PDFFIleService
{
    public function __construct(
        private readonly AppointmentRepository $appointmentRepository,
        private readonly Environment $twig,
        private readonly EmailService $emailService,
        private readonly SMSService $SMSService,
    ) {
    }

    /**
     * @throws GeneratePDFException
     */
    public function generateRecieptionPDF(int $appointmentId, User $user)
    {
        try {
            if (!$appointment = $this->appointmentRepository->findOneBy(['id' => $appointmentId, 'status' => AppointmentRepository::APPOINTMENT_TAKEN])) {
                throw GeneratePDFException::appontmentNotFound();
            }
            $pdfOptions = new Options();
            $pdfOptions->set('defaultFont', 'Arial');
            $dompdf = new Dompdf($pdfOptions);
            $html = $this->twig->render('emails/patient-appointment.html.twig', [
                'appointment' => $appointment,
            ]);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $output = $dompdf->output();

            $this->emailService->sendEmailPatientAppointmentPDF($user->getEmail(), $output, 'test.pdf', $appointment);
        } catch (\Exception $exception) {
            throw GeneratePDFException::problemWithGeneration();
        }
    }

    public function generatePatientCard(int $patientId, User $user)
    {
        if (!$appointment = $this->appointmentRepository->findOneBy(['idPatient' => $patientId, 'status' => AppointmentRepository::APPOINTMENT_TAKEN])) {
            throw GeneratePDFException::appontmentNotFound();
        }
        if (!$appointments = $this->appointmentRepository->findBy(['idPatient' => $patientId, 'status' => AppointmentRepository::APPOINTMENT_TAKEN])) {
            CreatePatientException::patientNotFound();
        }
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);
        $html = $this->twig->render('emails/patient-card.html.twig', [
            'appointmentInfo' => $appointment,
            'appointments' => $appointments,
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $output = $dompdf->output();
        $this->emailService->sendEmailPatientCardPDF($user->getEmail(), $output, 'testCard.pdf', $appointment);
    }
}
