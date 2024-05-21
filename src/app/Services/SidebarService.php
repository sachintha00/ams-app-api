<?php
namespace App\Services;

use App\Repositories\SidebarRepository;

class SidebarService
{
    protected $SidebarRepository;

    public function __construct(SidebarRepository $SidebarRepository)
    {
        $this->SidebarRepository = $SidebarRepository;
    }

    public function getSidebarItem($id)
    {
        return $this->SidebarRepository->getSidebarItem($id);
    }
}