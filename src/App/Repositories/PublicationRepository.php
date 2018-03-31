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

    public function create(array $data): Publication
    {
        $application = $this->applicationRepository->find($data['application']);

        if (!$application) {
            throw new EntityNotFoundException('App\Entities\Application');
        }

        $data['application'] = $application;

        $category = $this->categoryRepository->find($data['category']);

        if (!$category) {
            throw new EntityNotFoundException('App\Entities\Category');
        }

        $data['category'] = $category;

        $data['body'] = $this->htmlSanitizer->sanitize($data['body']);

        $publication = new Publication();
        $publication->fromArray($data);

        $this->persister->persist($publication);

        return $publication;
    }

    public function update(string $id, array $data): bool
    {
        $publication = $this->repository->find($id);

        if (!$publication) {
            return false;
        }

        if (!$this->applicationRepository->appBelongsToUser($publication->getApplication())) {
            return false;
        }

        $category = $publication->getCategory();

        if (isset($data['category'])) {
            $newCategory = $this->categoryRepository->find($data['category']);

            $category = $newCategory ?? $category;
        }

        $data['category'] = $category;

        if (isset($data['body'])) {
            $data['body'] = $this->htmlSanitizer->sanitize($data['body']);
        }

        $setters = Publication::getSetterMap();

        $allowedData = array_diff_key($data, [
            'application' => false
        ]);

        foreach ($allowedData as $field => $value) {
            $setter = $setters[$field];

            $publication->{$setter}($value);
        }

        $this->persister->persist($publication);

        return true;
    }

    public function destroy(string $id): bool
    {
        $publication = $this->repository->find($id);

        if (!$publication) {
            return false;
        }

        if (!$this->applicationRepository->appBelongsToUser($publication->getApplication())) {
            return false;
        }

        $this->persister->remove($publication);

        return true;
    }
}