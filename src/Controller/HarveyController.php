<?php

namespace Trexima\EuropeanCvBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Trexima\EuropeanCvBundle\Facade\Harvey;
use Trexima\EuropeanCvBundle\Manager\EuropeanCvManager;

#[Route(path: '/trexima-european-cv-bundle-harvey')]
class HarveyController extends AbstractController
{
    public function __construct(private readonly Harvey $harvey)
    {
    }

    #[Route(path: '/isco-autocomplete', name: 'trexima_european_cv_isco_autocomplete')]
    public function iscoAutocomplete(Request $request): Response
    {
        $term = $request->get('term');
        $page = $request->get('page');
        $perPage = 50;

        $results = [];
        $data = $this->harvey->getClient()->searchIsco($term, null, null, [7], null, null, $page, $perPage);
        foreach ($data as $isco) {
            $results[] = [
                'id' => $isco['code'],
                'text' => sprintf('%s %s', $isco['code'], $isco['title'])
            ];
        }

        return $this->json([
            'results' => $results,
            'page' => $page,
            'perPage' => $perPage,
            'total' => count($results) !== 0 ? ($page * $perPage) + 1 : ($page * $perPage) // Load new while result set is not empty
        ]);
    }
}