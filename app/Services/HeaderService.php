<?php

namespace App\Services;
use App\Helpers\CurlHelper;
use App\Models\Post;

class HeaderService
{
    protected SiteInfoService $info;
    protected string $header;
    protected array $cssContent = [];
    protected array $jsContent = [];

    public function __construct(SiteInfoService $info)
    {
        $this->info = $info;
    }

    public function parse(string $header)
    {
        $this->header = $header;

        $this->stripComments();
        $this->extractCss();
        $this->extractJs();

        $this->createCustomCssPost();
    }

    protected function stripComments(): void
    {
        preg_match_all('/(<!--)(.*)(-->)/', $this->header, $matches);
        foreach (current($matches) as $match) {
            $this->header = str_replace($match, '', $this->header);
        }
    }

    protected function extractCss(): void
    {
        $this->extract($this->cssContent , 'link', 'href');
    }

    protected function extractJs(): void
    {
        $this->extract($this->jsContent , 'script', 'src');
    }

    protected function extract(array &$target, string $tag, string $attribute): void
    {
        preg_match_all("/(<{$tag})(.*)(>)/", $this->header, $matches);
        foreach (current($matches) as $match) {
            preg_match('/(' . $attribute . '=")([^">]+)(")/', $match, $href);
            $url = isset($href[2]) ? $this->info->url . ($href[2]) : null;
            if (CurlHelper::testUrl($url)) {
                $content = CurlHelper::getContents($url);
                $target[] = $content;
            }

            // Remove original line
            $this->header = str_replace($match, '', $this->header);
        }
    }

    protected function createCustomCssPost()
    {
//        $post = new Post();
//        $post->setTable($this->info->postTables['posts']);
//        $post->create([
//            'post_content' => implode("\n", $this->cssContent),
//            'post_author' => 1,
//            'post_title' => $this->info->theme,
//            'post_excerpt' => '',
//            'post_type' => 'custom_css',
//            'to_ping' => '',
//            'pinged' => '',
//            'post_content_filtered' => '',
//            'guid' => ''
//        ]);

    }
}
