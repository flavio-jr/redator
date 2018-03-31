<?php

namespace App\Repositories;

use Doctrine\ORM\EntityManager;
use App\Services\Persister;
use App\Exceptions\EntityNotFoundException;
use App\Services\HtmlSanitizer;
use App\Entities\Publication;

class PublicationRepository
{
    private $repository;
    private $persister;
    private $applicationRepository;
    private $categoryRepository;
    private $htmlSanitizer;

    public function __construct(
        EntityManager $em,
        Persister $persister,
        ApplicationRepository $applicationRepository,
        CategoryRepository $categoryRepository,
        HtmlSanitizer $htmlSanitizer
    ) {
        $this->repository = $em->getRepository('App\Entities\Publication');
        $this->persister = $persister;
        $this->applicationRepository = $applicationRepository;
        $this->categoryRepository = $categoryRepository;
        $this->htmlSanitizer = $htmlSanitizer;
    }

    public function create(array $data)
    {
        $application = $this->applicationRepository->find($data['application']);

        if (!$application) {
            throw new EntityNotFoundException('App\Entities\Application');
        }

        $data['application'] = $application;

        $category = $this->categoryRepository->find($data['category']);

        if (!$category) {
            throw new EntityNotFoundException('App\Entities\Category ');
        }

        $data['category'] = $category;

        $data['body'] = $this->htmlSanitizer->sanitize($data['body']);

        $publication = new Publication();
        $publication->fromArray($data);

        $this->persister->persist($publication);

        return $publication;
    }
}