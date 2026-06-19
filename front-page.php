<?php
declare(strict_types=1);

get_header();
$menu_url = esc_url( get_permalink( wc_get_page_id( 'shop' ) ) );
?>

<main id="main" class="site-main" tabindex="-1">
    <div class="dynamo-container">
        <div class="aaa-home">

            <h1 class="aaa-home__heading"><?php esc_html_e( 'Exclusive Takeout Offers', 'dynamo' ); ?></h1>
            <p class="aaa-home__subtitle"><?php esc_html_e( '(before GST and not applicable on Skip The Dishes)', 'dynamo' ); ?></p>

            <div class="aaa-cta-phone-row">
                <div class="aaa-phone-group">
                    <p class="aaa-phone-group__call"><?php esc_html_e( 'Call', 'dynamo' ); ?></p>
                    <p class="aaa-phone-group__number">780-454-0829</p>
                </div>
                <span class="aaa-cta-or"><?php esc_html_e( 'or', 'dynamo' ); ?></span>
                <div class="aaa-order-cta">
                    <a class="aaa-order-cta__button" href="<?php echo $menu_url; ?>"><?php esc_html_e( 'Order Online', 'dynamo' ); ?></a>
                </div>
            </div>

            <div class="aaa-spend-deals">
                <div class="aaa-spend-deal">
                    <p class="aaa-spend-deal__amount"><?php esc_html_e( 'Spend $60', 'dynamo' ); ?></p>
                    <p class="aaa-spend-deal__get"><?php esc_html_e( 'get', 'dynamo' ); ?></p>
                    <p class="aaa-spend-deal__item"><?php esc_html_e( 'Free Deep Fried Wontons', 'dynamo' ); ?></p>
                </div>
                <div class="aaa-spend-deal">
                    <p class="aaa-spend-deal__amount"><?php esc_html_e( 'Spend $70', 'dynamo' ); ?></p>
                    <p class="aaa-spend-deal__get"><?php esc_html_e( 'get', 'dynamo' ); ?></p>
                    <p class="aaa-spend-deal__item"><?php esc_html_e( 'Free Chicken Fried Rice', 'dynamo' ); ?></p>
                </div>
                <div class="aaa-spend-deal">
                    <p class="aaa-spend-deal__amount"><?php esc_html_e( 'Spend $80', 'dynamo' ); ?></p>
                    <p class="aaa-spend-deal__get"><?php esc_html_e( 'get', 'dynamo' ); ?></p>
                    <p class="aaa-spend-deal__item"><?php esc_html_e( 'Free Dry Garlic Ribs', 'dynamo' ); ?></p>
                </div>
            </div>

            <div class="aaa-delivery">
                <p class="aaa-delivery__title"><?php esc_html_e( 'Free Delivery', 'dynamo' ); ?></p>
                <p class="aaa-delivery__detail"><?php esc_html_e( 'on orders over $40 and within 5 mile radius of Edmonton or St. Albert City Limits on deliveries after 4pm', 'dynamo' ); ?></p>
                <p class="aaa-delivery__pickup"><?php esc_html_e( '10% off pickup orders over $55 of Chinese Food ONLY', 'dynamo' ); ?></p>
            </div>

        </div>
    </div>
</main>

<?php get_footer();
