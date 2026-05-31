import { test, expect } from '@wordpress/e2e-test-utils-playwright';

test.describe( 'NExT Disable Admin Bar Hover', () => {
	test( 'プラグインが有効化されている', async ( { admin, page } ) => {
		await admin.visitAdminPage( 'plugins.php' );
		const pluginRow = page.locator( 'tr[data-slug="next-disable-admin-bar-hover"]' );
		await expect( pluginRow ).toHaveClass( /active/ );
	} );

	test( 'ホバーでサブメニューが表示されない', async ( { admin, page } ) => {
		await admin.visitAdminPage( '/' );

		const menuItem = page.locator(
			'#wpadminbar #wp-admin-bar-top-secondary .menupop > .ab-item'
		).first();

		// メニューアイテムが存在する場合のみテスト
		const count = await menuItem.count();
		if ( count === 0 ) {
			test.skip();
			return;
		}

		const menupop = page.locator(
			'#wpadminbar #wp-admin-bar-top-secondary .menupop'
		).first();
		const subMenu = menupop.locator( '.ab-sub-wrapper' ).first();

		// ホバーしてもサブメニューが表示されないことを確認
		await menuItem.hover();
		await expect( subMenu ).toBeHidden();
	} );

	test( 'クリックでサブメニューが表示される', async ( { admin, page } ) => {
		await admin.visitAdminPage( '/' );

		const menuItem = page.locator(
			'#wpadminbar #wp-admin-bar-top-secondary .menupop > .ab-item'
		).first();

		const count = await menuItem.count();
		if ( count === 0 ) {
			test.skip();
			return;
		}

		const menupop = page.locator(
			'#wpadminbar #wp-admin-bar-top-secondary .menupop'
		).first();

		// サブメニューがある場合のみテスト
		const subWrapper = menupop.locator( '.ab-sub-wrapper' );
		const subCount = await subWrapper.count();
		if ( subCount === 0 ) {
			test.skip();
			return;
		}

		// クリックでサブメニューが表示されることを確認
		await menuItem.click();
		await expect( menupop ).toHaveClass( /next-dah-open/ );
		await expect( subWrapper ).toBeVisible();

		// アドミンバー外クリックでサブメニューが閉じることを確認
		await page.locator( '#wpbody' ).click( { position: { x: 10, y: 10 } } );
		await expect( menupop ).not.toHaveClass( /next-dah-open/ );
	} );
} );
