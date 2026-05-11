<?php

declare(strict_types=1);

/**
 * Image path or absolute URL for templates (local files live under img/).
 */
function ep_article_image_src(?string $image): string
{
    $image = trim((string) $image);
    if ($image === '') {
        return 'img/carousel-2.jpg';
    }
    if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
        return $image;
    }

    return 'img/' . ltrim($image, '/');
}

/** Newest submissions first (by submission_id). */
function ep_sort_submissions_newest(array $articles): array
{
    usort($articles, static function ($a, $b) {
        return ($b['submission_id'] ?? 0) <=> ($a['submission_id'] ?? 0);
    });

    return $articles;
}
