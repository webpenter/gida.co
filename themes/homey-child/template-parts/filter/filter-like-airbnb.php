<div class="filter-like-airbnb" style="margin: 0 auto">
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