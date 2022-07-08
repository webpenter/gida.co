<div class="filter-like-airbnb center-align">
    <?php
    // homey_listing_taxonomies('listing_type', true, true, 20); 

    // echo "<pre>";
    // print_r( get_term_meta(62) );
    // print_r( wp_get_attachment_image_url(get_term_meta(62)['homey_taxonomy_img'][0]) );
    
    $tax = 'listing_type';
    $terms = get_terms($tax, array('parent' => 0));
    if (!is_wp_error($terms)) {
        $count = count($terms);
        if ($count > 0) {

        //echo '<a href="' . esc_url( get_term_link( $terms[0]->slug, $terms[0]->taxonomy ) ). '">' . esc_attr( $terms[0]->name ) . '</a>';
    
    ?>

    <!-- <section class="custom-filter-section search-wrap hidden-sm hidden-xs">
        <form class="clearfix custom-filter-form" action="<?php // echo homey_get_search_result_page(); ?>" method="GET">
            <div class="search-filters">
                <button type="button" class="btn btn-grey-outlined search-filter-btn custom-btn-btn">
                    <span class="custom-btn-text">
                        <svg viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" style="display:block;height:14px;width:14px;fill:currentColor" aria-hidden="true" role="presentation" focusable="false"><path d="M5 8c1.306 0 2.418.835 2.83 2H14v2H7.829A3.001 3.001 0 1 1 5 8zm0 2a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm6-8a3 3 0 1 1-2.829 4H2V4h6.17A3.001 3.001 0 0 1 11 2zm0 2a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"></path></svg>
                        &nbsp;&nbsp;Filters
                    </span>
                    
                </button>
            </div>
            <?php // get_template_part ('template-parts/search/search-filter-full-width');  ?>
        </form>
    </section> -->

    <section class="splide" aria-labelledby="carousel-heading">
        <div class="splide__track">
            <ul class="splide__list">
                <?php
                foreach ($terms as $term){
                ?>
                <li class="splide__slide">
                    <a href="<?php echo esc_url( get_term_link( $term->slug, $term->taxonomy ) ); ?>">
                        <div class="splide__slide__container">
                            <img src="<?php echo wp_get_attachment_image_url( get_term_meta( $term->term_id )['homey_taxonomy_icon'][0] ); ?>" alt="">
                        </div>
                        <div class="splide__slide__label">
                            <span><?php echo esc_attr( $term->name ); ?></span>
                        </div>
                    </a>
                </li>
                <?php 
                } 
            }
        }
            ?>
            </ul>
        </div>
    </section>
</div>