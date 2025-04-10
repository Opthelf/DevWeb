<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search')]
    public function search(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les paramètres de recherche et de filtres
        $searchTerm = $request->query->get('q'); // Terme de recherche
        $filter1 = $request->query->get('filter1'); // Premier filtre
        $filter2 = $request->query->get('filter2'); // Deuxième filtre

        // Construire une requête dynamique
        $queryBuilder = $entityManager->getRepository('App\Entity\User')->createQueryBuilder('u');

        if ($searchTerm) {
            $queryBuilder->andWhere('u.username LIKE :searchTerm OR u.points LIKE :searchTerm')
                ->setParameter('searchTerm', '%' . $searchTerm . '%');
        }

        if ($filter1) {
            $queryBuilder->andWhere('u.roles = :filter1')
                ->setParameter('filter1', $filter1);
        }

        /*if ($filter2) {
            $queryBuilder->andWhere('u.status = :filter2')
                ->setParameter('filter2', $filter2);
        }*/

        $results = $queryBuilder->getQuery()->getResult();

        return $this->render('search/results.html.twig', [
            'results' => $results,
            'searchTerm' => $searchTerm,
            'filter1' => $filter1,
            'filter2' => $filter2,
        ]);
    }
}
