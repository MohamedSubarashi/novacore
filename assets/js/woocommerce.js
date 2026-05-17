/**
 * NovaCore WooCommerce Enhancements
 *
 * @package NovaCore
 * @since 1.0.0
 */

'use strict';

const NovaCoreWC = {
	init() {
		this.initMiniCart();
		this.initQuickView();
	},

	initMiniCart() {
		const cartToggle = document.querySelector('.novacore-cart-toggle');
		const offCanvas = document.querySelector('.novacore-off-canvas');
		const overlay = document.querySelector('.novacore-overlay');

		if (!cartToggle || !offCanvas) return;

		cartToggle.addEventListener('click', (e) => {
			e.preventDefault();
			offCanvas.classList.add('is-open');
			overlay?.classList.add('is-open');
			document.body.classList.add('has-modal-open');
		});

		const closeBtn = offCanvas.querySelector('.novacore-off-canvas__close');
		if (closeBtn) {
			closeBtn.addEventListener('click', () => {
				offCanvas.classList.remove('is-open');
				overlay?.classList.remove('is-open');
				document.body.classList.remove('has-modal-open');
			});
		}
	},

	initQuickView() {
		const btns = document.querySelectorAll('.novacore-quick-view-btn');
		if (!btns.length) return;

		btns.forEach((btn) => {
			btn.addEventListener('click', async () => {
				const productId = btn.dataset.productId;
				if (!productId) return;

				const modal = document.querySelector('.novacore-quick-view');
				if (!modal) return;

				const overlay = modal.querySelector('.novacore-modal__overlay');
				const content = modal.querySelector('.novacore-modal__content');

				try {
					const ncData = window.novacoreData || {};
				const res = await fetch(
						`${ncData.ajaxUrl}?action=novacore_quick_view&product_id=${productId}&nonce=${ncData.nonce}`
					);
					const data = await res.json();
					if (data.success && content) {
						content.innerHTML = data.data.html;
					}
				} catch {
					// fallback
				}

				modal.classList.add('is-open');
				document.body.classList.add('has-modal-open');

				if (overlay) {
					overlay.addEventListener('click', () => {
						modal.classList.remove('is-open');
						document.body.classList.remove('has-modal-open');
					});
				}
			});
		});
	},



};

document.addEventListener('DOMContentLoaded', () => NovaCoreWC.init());
