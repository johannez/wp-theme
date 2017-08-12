<?php
/**
 * Search results page
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

$context = Timber::get_context();

$context['search_query'] = get_search_query();
$context['search_results'] = [];
$context['body_class'] .= ' page--search';

$search_results = Timber::get_posts();

foreach ($search_results as $sr) {
    $search_result = [
        'title' => $sr->title,
        'link' => $sr->link,
    ];

    switch ($sr->type) {
        case 'post':
            $search_result['type'] = 'Blog Post';
            $search_result['summary'] = $sr->post_content;
            break;

        case 'page':
            $search_result['type'] = 'Page';
            $search_result['summary'] = $sr->post_content;
            break;
    }

    if ($search_result) {
        $context['search_results'][] = $search_result;
    }
}


Timber::render('page/search-results.twig', $context);