<?php

namespace App\Controller;

use App\Service\ExportService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExportController extends AbstractController
{
    #[Route('/export/{format}', name: 'export_data')]
    public function export(string $format, ExportService $exportService): Response
    {
        // Exemple de données à exporter
        $data = [
            ['ID', 'Nom', 'Email'],
            [1, 'John Doe', 'john@example.com'],
            [2, 'Jane Doe', 'jane@example.com'],
        ];

        $filename = 'export.' . $format;

        if ($format === 'csv') {
            return $exportService->exportToCsv($data, $filename);
        } elseif ($format === 'pdf') {
            return $exportService->exportToPdf($data, $filename);
        }

        throw $this->createNotFoundException('Format non supporté');
    }
}
