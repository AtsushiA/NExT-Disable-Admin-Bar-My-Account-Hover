<?php
/**
 * Plugin Name: NExT Disable Admin Bar Hover
 * Plugin URI: https://next-season.net
 * Description: アドミンバーのトップセカンダリメニューのマウスオーバー機能を無効化します
 * Version: 1.1.1
 * Requires at least: 6.5
 * Requires PHP: 7.4
 * Author: NExT-Season
 * Author URI: https://next-season.net
 * License: GPL v2 or later
 * Text Domain: disable-adminbar-my-account-hover
 *
 * @package Disable_AdminBar_Hover
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * アドミンバーのホバー動作を無効化するメインクラス。
 */
class Disable_AdminBar_Hover {

	/**
	 * コンストラクタ。アクションフックを登録する。
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_styles' ) );
		add_action( 'wp_footer', array( $this, 'enqueue_footer_scripts' ) );
		add_action( 'admin_footer', array( $this, 'enqueue_footer_scripts' ) );
	}

	/**
	 * 管理画面用のカスタムスタイルを登録する。
	 *
	 * @return void
	 */
	public function enqueue_admin_styles() {
		if ( is_admin_bar_showing() ) {
			wp_add_inline_style( 'admin-bar', $this->get_custom_css() );
		}
	}

	/**
	 * フロントエンド用のカスタムスタイルを登録する。
	 *
	 * @return void
	 */
	public function enqueue_frontend_styles() {
		if ( is_admin_bar_showing() ) {
			wp_enqueue_style( 'admin-bar' );
			wp_add_inline_style( 'admin-bar', $this->get_custom_css() );
		}
	}

	/**
	 * フッターにカスタムJavaScriptを出力する。
	 *
	 * @return void
	 */
	public function enqueue_footer_scripts() {
		if ( is_admin_bar_showing() ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_custom_js() returns hardcoded JS with no user input.
			echo '<script>' . $this->get_custom_js() . '</script>';
		}
	}

	/**
	 * ホバー無効化用のカスタムCSSを返す。
	 *
	 * @return string
	 */
	private function get_custom_css() {
		return '
			/* ホバー時の背景色変更を無効化 */
			#wpadminbar #wp-admin-bar-top-secondary > .menupop:hover > .ab-item,
			#wpadminbar #wp-admin-bar-top-secondary > .menupop.hover > .ab-item {
				background: transparent !important;
			}

			/* サブメニューをデフォルトで非表示 */
			#wpadminbar #wp-admin-bar-top-secondary .menupop > .ab-sub-wrapper {
				display: none !important;
			}

			/* クリックで開いた場合はサブメニューを表示 */
			#wpadminbar #wp-admin-bar-top-secondary .menupop.next-dah-open > .ab-sub-wrapper {
				display: block !important;
			}

			/* ホバー時のポインターイベントを無効化 */
			#wpadminbar #wp-admin-bar-top-secondary .menupop {
				pointer-events: none !important;
			}

			/* トリガーリンクとオープン時のサブメニューはクリック有効 */
			#wpadminbar #wp-admin-bar-top-secondary .menupop > .ab-item,
			#wpadminbar #wp-admin-bar-top-secondary .menupop.next-dah-open .ab-sub-wrapper,
			#wpadminbar #wp-admin-bar-top-secondary .menupop.next-dah-open .ab-item {
				pointer-events: auto !important;
			}
		';
	}

	/**
	 * クリック操作制御用のカスタムJavaScriptを返す。
	 *
	 * @return string
	 */
	private function get_custom_js() {
		return '(function() {
    function init() {
        var items = document.querySelectorAll(
            "#wpadminbar #wp-admin-bar-top-secondary .menupop > .ab-item"
        );
        items.forEach(function(item) {
            item.addEventListener("click", function(e) {
                var menupop = item.closest(".menupop");
                if (!menupop || !menupop.querySelector(".ab-sub-wrapper")) {
                    return;
                }
                e.preventDefault();
                var isOpen = menupop.classList.contains("next-dah-open");
                document.querySelectorAll(
                    "#wpadminbar #wp-admin-bar-top-secondary .menupop.next-dah-open"
                ).forEach(function(m) {
                    m.classList.remove("next-dah-open");
                });
                if (!isOpen) {
                    menupop.classList.add("next-dah-open");
                }
            });
        });

        document.addEventListener("click", function(e) {
            if (!e.target.closest("#wpadminbar #wp-admin-bar-top-secondary")) {
                document.querySelectorAll(
                    "#wpadminbar #wp-admin-bar-top-secondary .menupop.next-dah-open"
                ).forEach(function(m) {
                    m.classList.remove("next-dah-open");
                });
            }
        });
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", init);
    } else {
        init();
    }
})();';
	}
}

new Disable_AdminBar_Hover();
