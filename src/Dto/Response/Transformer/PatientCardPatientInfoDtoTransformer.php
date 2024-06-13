<?php

namespace App\Dto\Response\Transformer;

use App\Dto\Response\PatientCardPatientInfoDto;
use App\Entity\Patient;
use App\Security\DataChecker;

class PatientCardPatientInfoDtoTransformer extends AbstractResponseDtoTransformer
{
    public function __construct(private readonly DataChecker $dataChecker)
    {
    }

    /**
     * @param Patient $patient
     */
    public function transformFromObject($patient): PatientCardPatientInfoDto
    {
        return new PatientCardPatientInfoDto(
            $patient->getId(),
            $this->dataChecker->decrypt($patient->getPesel()),
            $patient->getFirstname(),
            $patient->getLastname()
        );
    }
}
