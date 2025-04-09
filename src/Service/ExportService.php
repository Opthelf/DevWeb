<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Dompdf\Dompdf;

class ExportService
{
    public function exportToCsv(array $data, string $filename): Response
    {
        $response = new StreamedResponse(function () use ($data) {
            $handle = fopen('php://output', 'w');
            foreach ($data as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }

    public function exportToPdf(array $data, string $filename): Response
    {
        $html = '<h1>Export PDF</h1><table border="1">';
        foreach ($data as $row) {
            $html .= '<tr><td>' . implode('</td><td>', $row) . '</td></tr>';
        }
        $html .= '</table>';

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();

        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
