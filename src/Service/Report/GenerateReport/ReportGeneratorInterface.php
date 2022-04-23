<?php

declare(strict_types=1);

namespace App\Service\Report\GenerateReport;

interface ReportGeneratorInterface
{
    public function generate(\DateTime $fromDate, \DateTime $toDate);
}