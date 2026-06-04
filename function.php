<?php

/**
 * @Packge     : TRAVIL
 * @Version    : 1.0
 * @Author     : TRAVIL
 * @Author URI : https://themeforest.net/user/ModinaTheme/portfolio
 *
 */

// Block direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Include File
 *
 */

// Constants
require_once get_parent_theme_file_path() . '/inc/travil-constants.php';

//theme setup
require_once TRAVIL_DIR_PATH_INC . 'theme-setup.php';

//essential scripts
require_once TRAVIL_DIR_PATH_INC . 'travil-essential-scripts.php';

//NavWalker
require_once TRAVIL_DIR_PATH_INC . 'travil-navwalker.php';

// plugin activation
require_once TRAVIL_DIR_PATH_FRAM . 'plugins-activation/travil-active-plugins.php';

// meta options
require_once TRAVIL_DIR_PATH_FRAM . 'travil-meta/travil-config.php';

// page breadcrumbs
require_once TRAVIL_DIR_PATH_INC . 'travil-breadcrumbs.php';

// sidebar register
require_once TRAVIL_DIR_PATH_INC . 'travil-widgets-reg.php';

//essential functions
require_once TRAVIL_DIR_PATH_INC . 'travil-functions.php';
require_once TRAVIL_DIR_PATH_INC . 'travil-woo.php';

// theme dynamic css
require_once TRAVIL_DIR_PATH_INC . 'travil-commoncss.php';

// helper function
require_once TRAVIL_DIR_PATH_INC . 'wp-html-helper.php';

// Demo Data
require_once TRAVIL_DEMO_DIR_PATH . 'demo-import.php';


// hooks
require_once TRAVIL_DIR_PATH_HOOKS . 'hooks.php';

// hooks funtion
require_once TRAVIL_DIR_PATH_HOOKS . 'hooks-functions.php';


function travil_register_block_patterns() {
    register_block_pattern(
        'travil/hero-section',
        array(
            'title'       => __( 'Hero Section', 'travil' ),
            'description' => _x( 'A custom hero section with image and text', 'Block pattern description', 'travil' ),
            'content'     => '<!-- wp:paragraph --><p>Your content here...</p><!-- /wp:paragraph -->',		
        )
    );
}
add_action( 'init', 'travil_register_block_patterns' );


add_filter( 'wp_travel_engine_booking_fields_display', function( $booking_fields ) {
    $booking_fields['billing_phone'] = array(
        'type'        => 'tel',
        'label'       => __( 'Phone Number', 'wp-travel-engine' ),
        'name'        => 'wte_phone',
        'id'          => 'billing_phone',
        'placeholder' => __( 'e.g. (800) 555-0199', 'wp-travel-engine' ),
        'priority'    => 35,
    );
    return $booking_fields;
} );

add_action( 'wp_travel_engine_after_traveller_information_save', function( $booking_id ) {
    if ( isset( $_POST['wte_phone'] ) ) {
        $phone = sanitize_text_field( $_POST['wte_phone'] );
        update_post_meta( $booking_id, 'billing_phone', $phone );
    }
} );

add_action('wp_footer', function () {
?>
<script>
document.addEventListener('DOMContentLoaded', function () {

    function addDateHint() {

        const dateBox = document.querySelector('.wte-booking-dates');

        if (!dateBox || document.getElementById('wte_date_hint')) {
            return;
        }

        const hint = document.createElement('div');
        hint.id = 'wte_date_hint';
        hint.innerHTML = 'Please select your start date only. The end date will be calculated automatically based on the tour duration.';

        hint.style.cssText = `
            background:#f9f9f9;
            border:1px solid #ddd;
            padding:10px;
            margin-bottom:10px;
            border-radius:5px;
            font-size:13px;
            color:#666;
        `;

        dateBox.parentNode.insertBefore(hint, dateBox);
    }

    addDateHint();

    const observer = new MutationObserver(addDateHint);
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });

});
</script>
<?php
});

add_action( 'wp_footer', function () {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const MAX_TOTAL = 20;

        function showMessage() {
            let msg = document.getElementById('wte_max_msg');
            if ( ! msg ) {
                msg = document.createElement('p');
                msg.id = 'wte_max_msg';
                msg.style.cssText = 'color:#c0392b; background:#fdecea; border:1px solid #e74c3c; padding:10px 15px; border-radius:6px; font-size:14px; text-align:center; margin-top:10px;';
                msg.innerText = '⚠️ Maximum group size is 20 travellers. Please contact us for larger groups.';
                const countersWrapper = document.querySelector('.wte-booking-pc-counter')?.closest('[class*="select"]') || document.querySelector('.wte-booking-pc-counter')?.parentElement?.parentElement;
                if ( countersWrapper ) countersWrapper.appendChild(msg);
            }
            msg.style.display = 'block';
            clearTimeout(msg._timeout);
            msg._timeout = setTimeout(() => msg.style.display = 'none', 4000);
        }

        function applyLimit() {
            const counters = document.querySelectorAll('.wte-qty-number.wte-booking-pc-counter');
            if ( counters.length < 2 ) return;

            counters.forEach(counter => {
                const plusBtn = counter.querySelector('.wte-up');
                if ( ! plusBtn || plusBtn._wte_limited ) return;
                plusBtn._wte_limited = true;

                plusBtn.addEventListener('click', function () {
                    setTimeout(function () {
                        let total = 0;
                        counters.forEach(c => {
                            const inp = c.querySelector('input');
                            if ( inp ) total += parseInt( inp.value ) || 0;
                        });

                        if ( total > MAX_TOTAL ) {
                            const minusBtn = counter.querySelector('.wte-down');
                            if ( minusBtn ) minusBtn.click();
                            showMessage();
                        }
                    }, 50);
                });
            });
        }

        const observer = new MutationObserver(applyLimit);
        observer.observe(document.body, { childList: true, subtree: true });
        applyLimit();
    });
    </script>
    <?php
} );
