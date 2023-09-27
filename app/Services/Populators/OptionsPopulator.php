<?php

namespace App\Services\Populators;
class OptionsPopulator extends Populator
{
    protected array $replace = [
        'siteurl' => '',
        'home' => '',
        'blogname' => '',
        'blogdescription' => '',
        '' => '',
        'template' => '',
        'stylesheet' => '',
    ];

    protected function doReplacements(): void
    {
        $this->replace['siteurl'] = $this->infoService->destUrl;
        $this->replace['home'] = $this->infoService->destUrl;
        $this->replace['blogname'] = $this->infoService->site;
        $this->replace['template'] = $this->infoService->theme;
        $this->replace['stylesheet'] = $this->infoService->theme;
        $this->replace['admin_email'] = $this->infoService->adminEmail;
    }
}
