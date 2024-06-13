<?php

namespace App\Dto\Response\Transformer;

use App\Dto\Response\PatientDto;
use App\Entity\Patient;
use App\Security\DataChecker;

class PatientDtoTransformer extends AbstractResponseDtoTransformer
{
    public function __construct(private readonly DataChecker $dataChecker)
    {
    }

    /**
     * @param Patient $patient
     */
    public function transformFromObject($patient): PatientDto
    {
        return new PatientDto(
            $patient->getId(),
            $this->dataChecker->decrypt($patient->getPesel()),
            $patient->getFirstname(),
            $patient->getLastname(),
            $patient->getAddress(),
            $patient->getPostCode(),
            $patient->getCity(),
            $patient->getPhoneNumber(),
        );
    }
}
