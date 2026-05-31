<?php

namespace NextDisableAdminbarHover\Tests\Integration;

use WP_UnitTestCase;

class PluginTest extends WP_UnitTestCase {

	public function test_plugin_class_exists(): void {
		$this->assertTrue( class_exists( 'Disable_AdminBar_Hover' ) );
	}

	public function test_admin_enqueue_scripts_hooked(): void {
		$this->assertNotFalse( has_action( 'admin_enqueue_scripts' ) );
	}

	public function test_wp_enqueue_scripts_hooked(): void {
		$this->assertNotFalse( has_action( 'wp_enqueue_scripts' ) );
	}

	public function test_wp_footer_hooked(): void {
		$this->assertNotFalse( has_action( 'wp_footer' ) );
	}

	public function test_admin_footer_hooked(): void {
		$this->assertNotFalse( has_action( 'admin_footer' ) );
	}
}
