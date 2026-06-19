<?php
declare(strict_types=1);

get_header();
?>

<main id="main" class="site-main" tabindex="-1">
    <div class="dynamo-container">
        <?php Dynamo_Breadcrumbs::render(); ?>

        <div class="aaa-features">

            <h1 class="aaa-features__heading"><?php esc_html_e( 'Features', 'dynamo' ); ?></h1>

            <div class="aaa-feature">
                <img class="aaa-feature__img"
                     src="<?php echo esc_url( DYNAMO_URL . 'assets/img/AAA-happy-hour.webp' ); ?>"
                     alt="<?php esc_attr_e( 'friends cheering glasses', 'dynamo' ); ?>"
                     width="860" height="340">
                <div class="aaa-feature__text">
                    <h2><?php esc_html_e( 'Happy Hour', 'dynamo' ); ?></h2>
                    <p><?php esc_html_e( 'Friday Happy Hour get free finger food', 'dynamo' ); ?></p>
                    <p><?php esc_html_e( 'Everyday from 3pm to 7pm', 'dynamo' ); ?></p>
                </div>
            </div>

            <div class="aaa-feature aaa-feature--reverse">
                <img class="aaa-feature__img"
                     src="<?php echo esc_url( DYNAMO_URL . 'assets/img/AAA-family-friendly.webp' ); ?>"
                     alt="<?php esc_attr_e( 'little girl enjoying soup', 'dynamo' ); ?>"
                     width="860" height="340">
                <div class="aaa-feature__text">
                    <h2><?php esc_html_e( 'Family Friendly', 'dynamo' ); ?></h2>
                    <p><?php esc_html_e( 'We now offer a family friendly section for you and your little ones', 'dynamo' ); ?></p>
                </div>
            </div>

            <div class="aaa-feature">
                <img class="aaa-feature__img aaa-feature__img--portrait"
                     src="<?php echo esc_url( DYNAMO_URL . 'assets/img/AAA-dining-room.webp' ); ?>"
                     alt="<?php esc_attr_e( 'aaa dining room', 'dynamo' ); ?>"
                     width="398" height="597">
                <div class="aaa-feature__text">
                    <h2><?php esc_html_e( 'Private Events', 'dynamo' ); ?></h2>
                    <p><?php esc_html_e( 'Host your private event with us!', 'dynamo' ); ?></p>
                    <p><?php esc_html_e( 'With our buffet table, fully stocked bar, and open dance area, your event is sure to be a success!', 'dynamo' ); ?></p>
                </div>
            </div>

        </div>
    </div>
</main>

<?php get_footer();
