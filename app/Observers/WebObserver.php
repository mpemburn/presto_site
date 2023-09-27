<?php

namespace App\Observers;

use App\Helpers\SourceHelper;
use App\Services\PageConversionService;
use App\Services\SiteInfoService;
use DOMDocument;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Spatie\Crawler\CrawlObservers\CrawlObserver;

class WebObserver extends CrawlObserver
{
    protected SiteInfoService $info;
    protected PageConversionService $pageService;

    public function __construct(SiteInfoService $info)
    {
        $this->info = $info;
        $this->pageService = new PageConversionService($info);
    }

    /**
     * Called when the crawler will crawl the url.
     *
     */
    public function willCrawl(UriInterface $url): void
    {
    }

    /**
     * Called when the crawler has crawled the given url successfully.
     *
     */
    public function crawled(
        UriInterface      $url,
        ResponseInterface $response,
        ?UriInterface     $foundOnUrl = null
    ): void
    {
        if (stripos($url, $this->info->url) === false) {
            return;
        }
        $pathParts = pathinfo($url);
        if (
            isset($pathParts['extension'])
            && ! in_array($pathParts['extension'], SourceHelper::SERVER_PAGE_EXTENSIONS)
        ) {
            return;
        }

        echo 'Reading: ' . $url . PHP_EOL;
        $this->info->setPageName($url);

        $doc = new DOMDocument();
        $body = $response->getBody();

        if (strlen($body) < 1) {
            return;
        }

        @$doc->loadHTML($body);
        //# save HTML
        $content = $doc->saveHTML();

        $this->pageService->processPage($content);

    }

    /**
     * Called when the crawler had a problem crawling the given url.
     *
     */
    public function crawlFailed(
        UriInterface     $url,
        RequestException $requestException,
        ?UriInterface    $foundOnUrl = null
    ): void
    {
        //echo 'crawlFailed: ' . $url . PHP_EOL;
    }

    /**
     * Called when the crawl has ended.
     */
    public function finishedCrawling(): void
    {
    }

}
