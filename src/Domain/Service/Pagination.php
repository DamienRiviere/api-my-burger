<?php

namespace App\Domain\Service;

final class Pagination
{

    /** @var int */
    protected $limit;

    /** @var array */
    protected $data;

    /** @var int */
    protected $currentPage;

    /**
     * Pagination Constructor
     * @param int $limit
     * @param array $data
     * @param int $currentPage
     */
    public function __construct(int $limit, array $data, int $currentPage)
    {
        $this->limit = $limit;
        $this->data = $data;
        $this->currentPage = $currentPage;
    }

        /**
     * @return int
     */
    public function getCurrentPage(): int
    {
        return (int)$this->currentPage;
    }

    /**
     * @return int
     */
    public function getLastPage(): int
    {
        $count = count($this->data);
        $lastPage = ceil($count / $this->limit);

        return (int)$lastPage;
    }

    /**
     * @return int
     */
    public function getNextPage(): int
    {
        return $this->getCurrentPage() + 1;
    }

    /**
     * @return int
     */
    public function getPreviousPage(): int
    {
        return $this->getCurrentPage() - 1;
    }
}
