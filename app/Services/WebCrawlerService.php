<?php
namespace App\Services;

use App\Observers\WebObserver;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\DB;
use Spatie\Crawler\Crawler;

class WebCrawlerService
{
    protected SiteInfoService $info;

    public function __construct(SiteInfoService $info)
    {
        $this->info = $info;
    }

    public function fetchContent() {
        $options = [RequestOptions::ALLOW_REDIRECTS => true, RequestOptions::TIMEOUT => 30];

        // Initiate crawler
        Crawler::create($options)
            ->acceptNofollowLinks()
            ->ignoreRobots()
            ->setCrawlObserver(new WebObserver($this->info))
            ->setMaximumResponseSize(1024 * 1024 * 2) // 2 MB maximum
            ->setDelayBetweenRequests(500)
            ->startCrawling($this->info->url . $this->info->home);

        return true;
    }
}
