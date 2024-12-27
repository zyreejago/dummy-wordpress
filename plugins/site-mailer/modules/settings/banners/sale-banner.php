<?php

namespace SiteMailer\Modules\Settings\Banners;

use SiteMailer\Modules\Core\Components\Pointers;
use Throwable;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Sale_Banner
 */
class Sale_Banner {
	const BANNER_POINTER_NAME = 'site_mailer_sale_bf_2024_banner';
	const POINTER_ACTION = 'site_mailer_pointer_dismissed';
	const POINTER_NONCE_KEY = 'site-mailer-pointer-dismissed';

	public static function is_sale_time(): bool {
		$sale_start_time = gmmktime( 13, 0, 0, 11, 26, 2024 );
		$sale_end_time = gmmktime( 9, 59, 0, 12, 4, 2024 );

		$now_time = gmdate( 'U' );

		return $now_time >= $sale_start_time && $now_time <= $sale_end_time;
	}

	public static function user_viewed_banner(): bool {
		return Pointers::is_dismissed( self::BANNER_POINTER_NAME );
	}

	/**
	 * Get banner markup
	 * @throws Throwable
	 */
	public static function get_banner( string $link ) {
		if ( ! self::is_sale_time() || self::user_viewed_banner() ) {
			return;
		}
		$img = plugins_url( '/images/bf-banner-sm-2024.jpg', __FILE__ );
		$url = admin_url( 'admin-ajax.php' );
		$nonce = wp_create_nonce( self::POINTER_NONCE_KEY );

		?>
		<div class="elementor-black-friday-banner">
			<div class="elementor-black-friday-banner-container">
				<img src="<?php echo esc_url( $img ); ?>" alt="Black Friday banner image">
				<a href="<?php echo esc_url( $link ); ?>" target="_blank">
					Claim discount
				</a>
				<button>
					<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path fill-rule="evenodd" clip-rule="evenodd"
							  d="M13.2803 1.28033C13.5732 0.987437 13.5732 0.512563 13.2803 0.21967C12.9874 -0.0732233 12.5126 -0.0732233 12.2197 0.21967L6.75 5.68934L1.28033 0.21967C0.987437 -0.0732233 0.512563 -0.0732233 0.21967 0.21967C-0.0732233 0.512563 -0.0732233 0.987437 0.21967 1.28033L5.68934 6.75L0.21967 12.2197C-0.0732233 12.5126 -0.0732233 12.9874 0.21967 13.2803C0.512563 13.5732 0.987437 13.5732 1.28033 13.2803L6.75 7.81066L12.2197 13.2803C12.5126 13.5732 12.9874 13.5732 13.2803 13.2803C13.5732 12.9874 13.5732 12.5126 13.2803 12.2197L7.81066 6.75L13.2803 1.28033Z"
							  fill="white"/>
					</svg>
				</button>
			</div>
		</div>
		<style>
			.elementor-black-friday-banner {
				overflow: hidden;
				margin: -20px 0 20px -20px;
				background: #000;
			}

			.elementor-black-friday-banner-container {
				position: relative;
				max-width: 1200px;
				margin: 0 auto;
				display: flex;
				justify-content: end;
				align-items: center;
				direction: ltr;
				height: 80px;
			}

			.elementor-black-friday-banner img {
				position: absolute;
				left: 0;
				top: 50%;
				transform: translateY(-50%);
				width: 100%;
			}

			.elementor-black-friday-banner a {
				position: relative;
				display: inline-block;
				padding: 12px 24px;
				font-size: 18px;
				color: #000;
				background-color: #FF7BE5;
				text-decoration: none;
				z-index: 2;
			}

			.elementor-black-friday-banner button {
				position: relative;
				border: none;
				background: none;
				padding: 12px;
				margin: 0 24px;
				cursor: pointer;
				z-index: 2;
			}

			@media (max-width: 768px) {
				.elementor-black-friday-banner a {
					padding: 6px 12px;
					font-size: 14px;
				}

				.elementor-black-friday-banner button {
					margin: 0 12px;
				}
			}
		</style>
		<script>
			document.addEventListener( 'DOMContentLoaded', function() {
				const banner = document.querySelector( '.elementor-black-friday-banner' );
				const button = document.querySelector( '.elementor-black-friday-banner button' );

				const requestData = {
					action: "<?php echo esc_js( self::POINTER_ACTION ); ?>",
					nonce: "<?php echo esc_js( $nonce ); ?>",
					data: {
						pointer: "<?php echo esc_js( self::BANNER_POINTER_NAME ); ?>",
					},
				};

				if (button) {
					button.addEventListener( 'click', function() {
						jQuery.ajax(
							{
								url: '<?php echo esc_js( $url ); ?>',
								method: 'POST',
								data: requestData,
								success: () => banner.remove(),
								error: ( error ) => console.error( 'Error:', error ),
							},
						);
					} );
				}
			} );

		</script>
		<?php
	}
}
