<?php

namespace App\Factories;

use App\Interfaces\PopulatorInterface;
use App\Services\Populators\OptionsPopulator;
use App\Services\SiteInfoService;

class PopulateTableFactory
{
    public static function build(string $key, SiteInfoService $infoService): ?PopulatorInterface
    {
        return match ($key) {
            'blogmeta' => null,
            'blogs' => null,
            'commentmeta' => null,
            'comments' => null,
            'links' => null,
            'options' => new OptionsPopulator($key, $infoService),
            'postmeta' => null,
            'posts' => null,
            'registration_log' => null,
            'signups' => null,
            'site' => null,
            'sitemeta' => null,
            'term_relationships' => null,
            'term_taxonomy' => null,
            'termmeta' => null,
            'terms' => null,
            'usermeta' => null,
            'users' => null,
        };
    }

}
