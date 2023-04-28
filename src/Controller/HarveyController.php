<?php

namespace Trexima\EuropeanCvBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Trexima\EuropeanCvBundle\Facade\Harvey;

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
        $results = $this->harvey->getClient()->searchIsco($term, null, null, [4], null, null, $page, $perPage);
        $results = \array_map(function ($value) {
            return [
                'id' => $value['code'],
                'text' => $value['title'],
            ];
        }, $results);

        $results = ['results' => $results];

        return $this->json($results);
    }

    #[Route(path: '/kov-autocomplete', name: 'trexima_european_cv_kov_autocomplete')]
    public function kovAutocomplete(Request $request): Response
    {
        $term = $request->get('term');
        $page = $request->get('page');
        $perPage = 50;

        $results = [];
        $results = $this->harvey->getClient()->searchKov($term, null, $page, $perPage);
        $results = \array_map(function ($value) {
            return [
                'id' => $value['code'],
                'text' => $value['title'],
            ];
        }, $results);

        $results = ['results' => $results];

        return $this->json($results);
    }
}