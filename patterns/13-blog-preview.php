<?php
/**
 * Title: Blog Preview
 * Slug: medspastarter/blog-preview
 * Categories: medspastarter
 * Description: Latest three blog posts displayed in a card grid.
 * Keywords: blog, posts, articles, news, latest
 */
?>

<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"5rem","bottom":"5rem","left":"1.5rem","right":"1.5rem"}}},"backgroundColor":"white","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull has-white-background-color has-background" style="padding-top:5rem;padding-right:1.5rem;padding-bottom:5rem;padding-left:1.5rem">
<!-- wp:group {"layout":{"type":"constrained","contentSize":"980px"}} -->
<div class="wp-block-group">

<!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"center"},"style":{"spacing":{"margin":{"bottom":"2.5rem"}}}} -->
<div class="wp-block-group" style="margin-bottom:2.5rem">
<!-- wp:group {"style":{"spacing":{"blockGap":"0.375rem"}}} -->
<div class="wp-block-group" style="gap:0.375rem">
<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.75rem","fontWeight":"600","letterSpacing":"0.1em","textTransform":"uppercase"}},"textColor":"primary"} --><p class="has-primary-color has-text-color" style="font-size:0.75rem;font-weight:600;letter-spacing:0.1em;text-transform:uppercase">Our Journal</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":2,"style":{"typography":{"fontFamily":"var(--wp--preset--font-family--heading)","fontSize":"clamp(1.5rem,3vw,2rem)"},"spacing":{"margin":{"top":"0.25rem","bottom":"0"}}}} --><h2 class="wp-block-heading" style="font-family:var(--wp--preset--font-family--heading);font-size:clamp(1.5rem,3vw,2rem);margin-top:0.25rem;margin-bottom:0">Skin Health &amp; Beauty Tips</h2><!-- /wp:heading -->
</div>
<!-- /wp:group -->
<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.875rem","fontWeight":"600"}},"textColor":"primary"} --><p class="has-primary-color has-text-color" style="font-size:0.875rem;font-weight:600"><a href="/blog" class="has-primary-color">View all articles →</a></p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:query {"queryId":1,"query":{"perPage":3,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","inherit":false},"layout":{"type":"default"}} -->
<div class="wp-block-query">

<!-- wp:post-template {"layout":{"type":"grid","columnCount":3}} -->

<!-- wp:group {"style":{"border":{"radius":"1rem"},"spacing":{"padding":{"bottom":"0"}}},"backgroundColor":"neutral-100","className":"overflow-hidden h-full flex flex-col"} -->
<div class="wp-block-group has-neutral-100-background-color has-background overflow-hidden h-full flex flex-col" style="border-radius:1rem">
<!-- wp:post-featured-image {"isLink":true,"height":"200px","style":{"border":{"radius":"1rem 1rem 0 0"}}} /-->
<!-- wp:group {"style":{"spacing":{"padding":{"top":"1.25rem","bottom":"1.5rem","left":"1.25rem","right":"1.25rem"}}},"className":"flex flex-col flex-1"} -->
<div class="wp-block-group flex flex-col flex-1" style="padding-top:1.25rem;padding-right:1.25rem;padding-bottom:1.5rem;padding-left:1.25rem">
<!-- wp:post-terms {"term":"category","style":{"typography":{"fontSize":"0.75rem","fontWeight":"600","letterSpacing":"0.05em","textTransform":"uppercase"}},"textColor":"primary"} /-->
<!-- wp:post-title {"isLink":true,"style":{"typography":{"fontFamily":"var(--wp--preset--font-family--heading)","fontSize":"1.1rem","lineHeight":"1.35"},"spacing":{"margin":{"top":"0.5rem","bottom":"0.5rem"}}},"className":"wp-block-post-title"} /-->
<!-- wp:post-date {"style":{"typography":{"fontSize":"0.8125rem"}},"textColor":"neutral-700","className":"opacity-60"} /-->
<!-- wp:post-excerpt {"moreText":"Read more →","excerptLength":20,"style":{"typography":{"fontSize":"0.875rem","lineHeight":"1.6"},"spacing":{"margin":{"top":"0.75rem"}}},"textColor":"neutral-700","className":"opacity-80"} /-->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- /wp:post-template -->

</div>
<!-- /wp:query -->

</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
