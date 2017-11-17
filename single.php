<?php
/**
 * The Template for displaying all single posts
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context = Timber::get_context();
$post = Timber::query_post();
$context['post'] = $post;

if ( post_password_required( $post->ID ) ) {
    Timber::render( 'single-password.twig', $context );
} else {
    $site = $context['site'];
    $viewMethod = 'view' . ucfirst($post->post_type);

    // Check if there is template and a function for this post type that
    // formats the raw data.
    if (method_exists($site, $viewMethod)) {
        $context[$post->post_type] = $site->{$viewMethod}($post);
        Timber::render($post->post_type . '/view.twig', $context);
    }
    else {
        Timber::render( array( 'single.twig' ), $context );
    }
}
