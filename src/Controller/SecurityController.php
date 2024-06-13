<?php

namespace App\Controller;

use App\Exception\ChangePasswordException;
use App\Exception\CreateDoctorException;
use App\Form\ChangePasswordForm;
use App\Form\ForgotPasswordForm;
use App\Repository\UserRepository;
use App\Security\UserChangePassword;
use App\Service\OTPService;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends AbstractApiController
{
    public function __construct(
        private readonly UserChangePassword $userChangePassword,
        private readonly UserRepository $userRepository,
        private readonly OTPService $OTPService,
        private readonly LoggerInterface $loggerMyApi,
    ) {
    }

    public function changePassword(Request $request): Response
    {
        try {
            $form = $this->buildForm(ChangePasswordForm::class);
            $form->handleRequest($request);
            if (!$form->isSubmitted() || !$form->isValid()) {
                return $this->respond($form, Response::HTTP_BAD_REQUEST);
            }
            $this->userChangePassword->changePassword($form->getData(), $this->getUser());
        } catch (ChangePasswordException $exception) {
            $this->loggerMyApi->error($exception);

            return new JsonResponse(
                ['error' => $exception->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }

        return new JsonResponse(['message' => 'The password has been successfully changed'], Response::HTTP_OK);
    }

    public function forgotPasswordCheckExistEmail(Request $request): Response
    {
        try {
            if (!$user = $this->userRepository->findOneBy(['email' => $request->get('email')])) {
                throw CreateDoctorException::doctorNotFound();
            }

            if (!empty($user->getPhoneNumber())) {
                $this->OTPService->generateOTP($user->getPhoneNumber());
            }
        } catch (\Exception $exception) {
            $this->loggerMyApi->error($exception);

            return new JsonResponse(
                ['error' => $exception->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }

         return new JsonResponse(['The email entered was found '], Response::HTTP_OK);
    }

    public function checkVerificationCode(Request $request): Response
    {
        try {
            $otp = $request->toArray()['otp'];
            if (!$user = $this->userRepository->findOneBy(['email' => $request->get('email')])) {
                throw CreateDoctorException::doctorNotFound();
            }
            $phoneNumber = $user->getPhoneNumber();
            if (!$this->OTPService->isValidOTP($otp, $phoneNumber)) {
                return $this->json([
                    'error' => 'Invalid OTP provided',
                ], Response::HTTP_BAD_REQUEST);
            }
        } catch (\Exception $exception) {
            $this->loggerMyApi->error($exception);

            return new JsonResponse(
                ['error' => $exception->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }

        return new JsonResponse(['The code was successfully verification'], Response::HTTP_CREATED);
    }

    public function setNewPassword(Request $request): Response
    {
        try {
            $email = $request->get('email');
            $form = $this->buildForm(ForgotPasswordForm::class);
            $form->handleRequest($request);
            if (!$form->isSubmitted() || !$form->isValid()) {
                return $this->respond($form, Response::HTTP_BAD_REQUEST);
            }
            $this->userChangePassword->setNewPassword($form->getData(), $email);
        } catch (ChangePasswordException $exception) {
            $this->loggerMyApi->error($exception);

            return new JsonResponse(
                ['error' => $exception->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }

        return new JsonResponse(['message' => 'The password has been successfully changed'], Response::HTTP_OK);
    }
}
