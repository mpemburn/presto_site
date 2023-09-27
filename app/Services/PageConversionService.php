<?php

namespace App\Services;

use App\Helpers\CurlHelper;
use App\Helpers\ImageHelper;
use App\Helpers\RegexHelper;
use App\Models\Post;
use App\Models\Postmeta;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PageConversionService
{
    protected SiteInfoService $info;
    protected ?string $header = null;

    public function __construct(SiteInfoService $info)
    {
        $this->info = $info;
    }

    public function processPage(string $content)
    {
        $rawContent = $content;
        // Get rid of header and footer
        $content = $this->extractBody($content);
        if (! $this->info->hasHeader) {
            // Extract any local CSS files from header
            (new HeaderService($this->info))->parse($this->getHeader($content, $rawContent));
            $this->info->hasHeader = true;
        }

        if (stripos($content, '<img') !== false) {
            if (preg_match_all('/(<img)(.*)(>)/', $content, $images)) {
                $content = $this->processImages(current($images), $content);
            }
        }

        $this->saveAsPost($content);
    }

    protected function extractBody($content): string
    {
        $body = '';
        $isBody = false;
        collect(explode("\n", $content))
            ->each(function ($line) use (&$body, &$isBody) {
                if (stripos($line, '<title') !== false) {
                    $this->info->pageTitle = $this->extractTitle($line);
                }
                if (! $isBody) {
                    $isBody = stripos($line, '<body') !== false;
                    return;
                }
                if (stripos($line, '</body') !== false) {
                    $isBody = false;
                    return;
                }
                $body .= $line . "\n";
            });

        return $body;
    }

    protected function extractTitle(string $titleLine): string
    {
        $title = preg_replace('/<[^>]*>/', '', $titleLine);

        return $title ?: $this->info->pageName;
    }

    protected function getHeader(string $content, string $rawContent): string
    {
        return str_replace($content, '', $rawContent);
    }

    protected function processImages(array $images, string $content): string
    {
        // Find and replace image paths on page
        collect($images)->each(function ($image) use (&$content) {
            $originalPath = RegexHelper::extractAttribute('src', $image);
            if (! str_starts_with($originalPath, 'http')) {
                $newPath = $this->saveImage($originalPath);
                if ($newPath) {
                    // Create a post record for the image
                    $postId = $this->createAttachment($newPath);
                    $content = str_replace($originalPath, $newPath, $content);
                }
            }
        });

        return $content;
    }

    protected function saveImage($imgSrc): ?string
    {
        if (! CurlHelper::testUrl($this->info->url . $imgSrc)) {
            return null;
        }
        $binary = file_get_contents($this->info->url . $imgSrc);
        Storage::put($this->info->uploadPath . $imgSrc, $binary);

        return 'https://' . $this->info->domain . '/wp-content/uploads/sites/' . $this->info->uploadPath . $imgSrc;
    }

    protected function saveAsPost(string $content): void
    {
        switch_to_blog($this->info->blogId);

        $newPost = [
            'post_content' => trim($content),
            'post_author' => get_current_user_id(),
            'post_title' => trim($this->info->pageTitle),
            'post_excerpt' => '',
            'post_type' => 'page',
            'post_status' => 'publish',
        ];

        wp_insert_post($newPost);

    }

    protected function createAttachment(string $attachmentPath): void
    {
        switch_to_blog($this->info->blogId);

        $pathParts = pathinfo($attachmentPath);

        $wp_filetype = wp_check_filetype($pathParts['filename'], null );
        $attachmentPost = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title' => $pathParts['filename'],
            'post_content' => '',
            'post_status' => 'inherit'
        );

        wp_insert_post($attachmentPost);
    }
}
