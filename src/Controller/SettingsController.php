<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\ReportType;
use App\Service\Report\GenerateReport\ReportGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SettingsController extends AbstractController
{
    public function __construct(private ReportGeneratorInterface $generateCSVReportService)
    {
    }

    #[Route('/settings', name: 'app_settings')]
    public function index(Request $request): Response
    {
        $reportForm = $this->createForm(ReportType::class);
        $reportForm->handleRequest($request);

        if ($reportForm->isSubmitted() && $reportForm->isValid()) {
            $fromDate = $reportForm->get('dateFrom')->getData();
            $toDate = $reportForm->get('dateTo')->getData();

            try {
                $report = $this->generateCSVReportService->generate($fromDate, $toDate);

            } catch (\Exception $exception) {
                $this->addFlash('błąd', 'Wystąpił błąd. Raport nie został wygenerowany.');
            }

            $response = new Response($report);
            $response->headers->set('Content-type', 'text/csv');
            $response->headers->set('Content-Disposition', 'attachment; filename="CSVReport.csv"');

            return $response;
        }

        return $this->renderForm('settings/index.html.twig', [
            'reportForm' => $reportForm,
        ]);
    }


}
