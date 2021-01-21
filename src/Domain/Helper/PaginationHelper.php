<?php

namespace App\Domain\Helper;

use Symfony\Component\HttpFoundation\Request;
use App\Domain\Common\Exception\PageNotFoundException;

final class PaginationHelper
{

    public static function checkPage(Request $request, int $totalPage): int
    {
        $page = $request->query->getInt("page");

        if ($page < 1) {
            $page = 1;
        }

        if ($page > $totalPage) {
            throw new PageNotFoundException("Cette page n'existe pas !");
        }

        return $page;
    }
}
