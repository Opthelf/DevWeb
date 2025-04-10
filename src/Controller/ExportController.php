<?php

namespace App\Controller;

use App\Service\ExportService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ExportController extends AbstractController
{

    #[Route('/export/user/{format}', name: 'export_data_user')]
    public function export_user(string $format, ExportService $exportService, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les données de l'entité User
        $userRepository = $entityManager->getRepository('App\Entity\User');
        $users = $userRepository->findAll();

        // Transformer les données en tableau pour l'export
        $data = [['ID', 'Nom', 'Email']]; // En-têtes du fichier
        foreach ($users as $user) {
            $data[] = [
                $user->getId(),
                $user->getUsername(), // Remplacez par le nom de votre méthode getter
                $user->getPoints(), // Remplacez par le nom de votre méthode getter
            ];
        }

        $filename = 'users_export.' . $format;

        // Exporter en CSV ou PDF
        if ($format === 'csv') {
            return $exportService->exportToCsv($data, $filename);
        } elseif ($format === 'pdf') {
            return $exportService->exportToPdf($data, $filename);
        }

        throw $this->createNotFoundException('Format non supporté');
    }

    #[Route('/export/objet/{format}', name: 'export_data_objet')]
    public function export(string $format, ExportService $exportService, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les données de l'entité User
        $userRepository = $entityManager->getRepository('App\Entity\Objet');
        $users = $userRepository->findAll();

        // Transformer les données en tableau pour l'export
        $data = [['ID', 'Nom', 'Description','à supprimer','Actif']]; // En-têtes du fichier
        foreach ($users as $user) {
            $data[] = [
                $user->getId(),
                $user->getNom(), // Remplacez par le nom de votre méthode getter
                $user->getDescription(),
                $user->isStatus(),
                $user->isActif(), // Remplacez par le nom de votre méthode getter
            ];
        }

        $filename = 'objets_export.' . $format;

        // Exporter en CSV ou PDF
        if ($format === 'csv') {
            return $exportService->exportToCsv($data, $filename);
        } elseif ($format === 'pdf') {
            return $exportService->exportToPdf($data, $filename);
        }

        throw $this->createNotFoundException('Format non supporté');
    }
}
