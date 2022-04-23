<?php

declare(strict_types=1);

namespace App\Service\Report\GenerateReport;

use App\Repository\TodoRepository;
use Symfony\Component\HttpFoundation\Response;

class GenerateCSVReportService implements ReportGeneratorInterface
{
    public function __construct(private TodoRepository $todoRepository)
    {
    }

    public function generate(\DateTime $fromDate, \DateTime $toDate): string
    {

        $fp = fopen('php://temp', 'r+');
        fputcsv($fp, $this->prepareDataHeaders());

        $todos = $this->todoRepository->findByDateInterval($fromDate, $toDate);
        $data = $this->prepareData($todos);

        foreach ($data as $dataRow) {
            fputcsv($fp, $dataRow);
        }
        rewind($fp);
        
        return stream_get_contents($fp);
    }

    private function prepareDataHeaders(): array
    {
        return array('nazwa', 'data', 'opis', 'status');
    }

    private function prepareData(array $todos): array
    {
        $data = array();
        foreach ($todos as $todo) {
            $todoStatus = $todo->getIsCompleted() ? 'Wykonane' : 'Niewykonane';
            $todoData = [$todo->getName(), $todo->getDate()->format('d-m-Y'), $todo->getDescription(), $todoStatus];

            $data[] = $todoData;
        }

        return $data;
    }
}