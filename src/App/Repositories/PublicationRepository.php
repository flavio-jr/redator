<?php

namespace App\Repositories;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use App\Services\Persister;
use App\Exceptions\EntityNotFoundException;
use App\Services\HtmlSanitizer;
use App\Entities\Publication;
use Doctrine\ORM\QueryBuilder;

class PublicationRepository
{
    /**
     * The publication entity
     * @var Publication
     */
    private $publication;

    /**
     * The publication repository
     * @var EntityRepository
     */
    private $repository;

    /**
     * The service for persisting entities
     * @var Persister
     */
    private $persister;

    /**
     * The repository for Application entity
     * @var ApplicationRepository
     */
    private $applicationRepository;
    
    /**
     * The repository for Category entity
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * The service responsable for cleaning html input
     * @var HtmlSanitizer
     */
    private $htmlSanitizer;

    public function __construct(
        Publication $publication,
        EntityManager $em,
        Persister $persister,
        ApplicationRepository $applicationRepository,
        CategoryRepository $categoryRepository,
        HtmlSanitizer $htmlSanitizer
    ) {
        $this->publication = $publication;
        $this->repository = $em->getRepository('App\Entities\Publication');
        $this->persister = $persister;
        $this->applicationRepository = $applicationRepository;
        $this->categoryRepository = $categoryRepository;
        $this->htmlSanitizer = $htmlSanitizer;
    }

    /**
     * Gets the data of an publication by id
     * @method getPublication
     * @param string $id
     * @return array
     */
    public function getPublication(string $id): array
    {
        $publication = $this->repository->find($id);

        if (!$publication) {
            return [];
        }

        if (!$this->applicationRepository->appBelongsToUser($publication->getApplication())) {
            return [];
        }

        $data = $publication->toArray();
        $data['category'] = $publication->getCategory()->toArray();
        $data['application'] = $publication->getApplication()->toArray();

        return $data;
    }

    /**
     * Create an QueryBuilder for the publication repository
     * @method createQueryBuilder
     * @param string $alias
     * @return QueryBuilder
     */
    public function createQueryBuilder(string $alias)
    {
        return $this->repository->createQueryBuilder($alias);
    }

    /**
     * Creates a new Publication
     * @method create
     * @param array $data
     * @return Publication
     */
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

    /**
     * Updates an publication
     * @method update
     * @param string $id
     * @param array $data
     * @return bool
     */
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

    /**
     * Deletes an publication
     * @method destroy
     * @param string $id
     * @return bool
     */
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