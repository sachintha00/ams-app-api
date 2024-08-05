<?php
namespace App\Services;

use App\Repositories\PrefixRepository;

class PrefixService
{
    protected $prefixRepository;

    public function __construct(PrefixRepository $prefixRepository)
    {
        $this->prefixRepository = $prefixRepository;
    }

    public function getAllPrefixes($process_id=0)
    {
        return $this->prefixRepository->getAllPrefixes($process_id);
    }
}