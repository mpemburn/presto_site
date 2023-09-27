<?php

namespace App\Services;
use App\Factories\PopulateTableFactory;
use App\Helpers\SqlHelper;
use App\Sql\OptionsCreate;
use App\Sql\PostCreate;
use App\Sql\PostmetaCreate;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class SiteInfoService
{

    public string $url;
    public string $destUrl;
    public ?string $home = null;
    public string $site;
    public string $db;
    public string $prefix = 'wp_';
    public string $theme = 'twentytwentythree';
    public $blogId;
    public string $yearMonth;
    public string $uploadPath;
    public string $domain;
    public string $pageTitle;
    public string $pageName;
    public string $adminEmail = '';
    public string $currentTable;
    public array $postTables = [];
    public bool $hasHeader = false;

    public function setCommandOptions(array $options): self
    {
        collect($options)->each(function ($option, $key) {
            if ($option) {
                $this->$key = $option;
            }
        });

        return $this;
    }

    public function createSubsite(): self
    {
        $this->blogId = wpmu_create_blog(
            $_SERVER['SERVER_NAME'],
            '/' . $this->site,
            'New Subsite',
            get_current_user_id(),
            ['public' => true]
        );

        return $this;
    }

    public function setDatabaseBlogInfo(): self
    {
        // Get the current highest blog ID from the destination
        $blogs = DB::select('SELECT domain, MAX(blog_id) AS max FROM wp_blogs GROUP BY domain');

        $this->domain = current($blogs)->domain;
        $this->destUrl = 'https://' . $this->domain . '/' . $this->site . '/';
        $this->yearMonth = Carbon::now()->format('Y/m/');
        $this->uploadPath = $this->site . '/' . $this->blogId . '/' . $this->yearMonth;

        return $this;
    }

    // Extract the page name in case there is no <title> available
    public function setPageName(string $pageUrl): void
    {
        $pathParts = pathinfo($pageUrl);

        $this->pageName = $pathParts['filename'];
    }

}
