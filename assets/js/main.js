/**
 * NovaCore Main JavaScript
 *
 * @package NovaCore
 * @since 1.0.0
 */

'use strict';

const NovaCore = {
	/** Cache of DOM references */
	els: {},

	/** Config from wp_localize_script */
	config: {},

	/**
	 * Initialize the theme
	 */
	init() {
		this.config = window.novacoreData || {};
		this.cacheEls();
		this.initDarkMode();
		this.initStickyHeader();
		this.initScrollTop();
		this.initSearchPopup();
		this.initAnimations();
		this.initFaq();
		this.initReadingProgress();
		this.initSmoothScroll();
		this.initLazyLoading();
		this.initSliders();
		this.initTopBarToggle();
		this.initLanguageDropdown();
		this.initSubscribeForm();
		this.initAjaxPagination();
		this.initInfiniteScroll();
		this.bindEvents();
	},

	/**
	 * Cache frequently-used elements
	 */
	cacheEls() {
		this.els = {
			body: document.body,
			header: document.querySelector('.novacore-header'),
			darkToggle: document.querySelector('.novacore-dark-toggle'),
			searchToggle: document.querySelector('.novacore-search-toggle'),
			searchPopup: document.querySelector('.novacore-search-popup'),
			searchClose: document.querySelector('.novacore-search-popup__close'),
			searchInput: document.querySelector('.novacore-search-popup__input'),
			searchResults: document.querySelector('.novacore-search-popup__results'),
			scrollTop: document.querySelector('.novacore-scroll-top'),
			overlay: document.querySelector('.novacore-overlay'),
			offCanvas: document.querySelector('.novacore-off-canvas'),
			faqItems: document.querySelectorAll('.novacore-faq__item'),
			progressBar: document.querySelector('.novacore-reading-progress'),
		};
	},

	/**
	 * Bind global events
	 */
	bindEvents() {
		document.addEventListener('click', (e) => this.onDocumentClick(e));
		window.addEventListener('scroll', () => this.onScroll(), { passive: true });
		window.addEventListener('resize', () => this.onResize(), { passive: true });
	},

	// ========================================
	// Dark Mode
	// ========================================
	initDarkMode() {
		if (!this.els.darkToggle) return;

		const mode = this.config.darkMode || 'system';
		const saved = localStorage.getItem('novacore-dark-mode');

		if (saved === 'dark') {
			this.els.body.classList.add('dark-mode');
			this.els.body.classList.remove('dark-mode-system');
		} else if (saved === 'light') {
			this.els.body.classList.remove('dark-mode', 'dark-mode-system');
		} else if (mode === 'system') {
			this.els.body.classList.add('dark-mode-system');
		} else if (mode === 'dark') {
			this.els.body.classList.add('dark-mode');
		}

		this.els.darkToggle.addEventListener('click', () => this.toggleDarkMode());
	},

	toggleDarkMode() {
		if (this.els.body.classList.contains('dark-mode')) {
			this.els.body.classList.remove('dark-mode');
			localStorage.setItem('novacore-dark-mode', 'light');
		} else {
			this.els.body.classList.add('dark-mode');
			this.els.body.classList.remove('dark-mode-system');
			localStorage.setItem('novacore-dark-mode', 'dark');
		}
	},

	// ========================================
	// Sticky Header
	// ========================================
	initStickyHeader() {
		if (!this.els.header || !this.config.stickyHeader) return;

		const observer = new IntersectionObserver(
			([entry]) => {
				this.els.header.classList.toggle('is-sticky', !entry.isIntersecting);
			},
			{ threshold: 0 }
		);

		const sentinel = document.createElement('div');
		sentinel.style.height = '1px';
		sentinel.style.position = 'absolute';
		sentinel.style.top = '0';
		this.els.header.parentNode.insertBefore(sentinel, this.els.header);
		observer.observe(sentinel);
	},

	// ========================================
	// Scroll to Top
	// ========================================
	initScrollTop() {
		if (!this.els.scrollTop) return;

		this.els.scrollTop.addEventListener('click', () => {
			window.scrollTo({ top: 0, behavior: 'smooth' });
		});
	},

	// ========================================
	// Search Popup
	// ========================================
	initSearchPopup() {
		if (!this.els.searchToggle || !this.els.searchPopup) return;

		this.els.searchToggle.addEventListener('click', () => {
			this.els.searchPopup.classList.add('is-open');
			this.els.body.classList.add('has-modal-open');
			setTimeout(() => this.els.searchInput?.focus(), 100);
		});

		if (this.els.searchClose) {
			this.els.searchClose.addEventListener('click', () => this.closeSearchPopup());
		}

		if (this.els.searchInput) {
			let timer;
			this.els.searchInput.addEventListener('input', () => {
				clearTimeout(timer);
				timer = setTimeout(() => this.doSearch(this.els.searchInput.value), 300);
			});
		}
	},

	closeSearchPopup() {
		if (this.els.searchPopup) {
			this.els.searchPopup.classList.remove('is-open');
		}
		this.els.body.classList.remove('has-modal-open');
	},

	async doSearch(query) {
		if (!this.els.searchResults) return;

		if (query.length < 2) {
			this.els.searchResults.innerHTML = '';
			return;
		}

		try {
			const res = await fetch(
				`${this.config.ajaxUrl}?action=novacore_ajax_search&s=${encodeURIComponent(query)}&nonce=${this.config.nonce}`
			);
			const data = await res.json();

			if (data.success && data.data.length) {
				this.els.searchResults.innerHTML = data.data
					.map(
						(item) => `
						<a href="${item.url}" class="novacore-search-popup__result-item">
							${item.thumbnail ? item.thumbnail : ''}
							<div>
								<div class="novacore-search-popup__result-item-title">${item.title}</div>
								<div class="novacore-search-popup__result-item-type">${item.type}</div>
							</div>
						</a>`
					)
					.join('');
			} else {
				this.els.searchResults.innerHTML =
					'<div class="novacore-search-popup__result-item">No results found</div>';
			}
		} catch {
			this.els.searchResults.innerHTML = '';
		}
	},

	// ========================================
	// Scroll Animations (Intersection Observer)
	// ========================================
	initAnimations() {
		if (!this.config.animations) return;

		const els = document.querySelectorAll('[data-nc-reveal]');
		if (!els.length) return;

		const observer = new IntersectionObserver(
			(entries) => {
				entries.forEach((entry) => {
					if (entry.isIntersecting) {
						entry.target.classList.add('nc-revealed');
						observer.unobserve(entry.target);
					}
				});
			},
			{ threshold: 0.15, rootMargin: '0px 0px -50px 0px' }
		);

		els.forEach((el) => observer.observe(el));
	},

	initFaq() {
		this.els.faqItems?.forEach((item) => {
			const question = item.querySelector('.novacore-faq__question');
			if (!question) return;
			question.addEventListener('click', () => {
				const isActive = item.classList.contains('is-active');
				// Close all other items
				this.els.faqItems.forEach((other) => other.classList.remove('is-active'));
				if (!isActive) {
					item.classList.add('is-active');
				}
			});
		});
	},

	// ========================================
	// Reading Progress Bar
	// ========================================
	initReadingProgress() {
		if (!this.els.progressBar) return;

		window.addEventListener(
			'scroll',
			() => {
				const scrollTop = window.scrollY;
				const docHeight = document.documentElement.scrollHeight - window.innerHeight;
				const progress = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
				this.els.progressBar.style.width = `${Math.min(progress, 100)}%`;
			},
			{ passive: true }
		);
	},

	// ========================================
	// Smooth Scroll for anchor links
	// ========================================
	initSmoothScroll() {
		document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
			const href = anchor.getAttribute('href');
			if (!href || href === '#') return;
			anchor.addEventListener('click', (e) => {
				const target = document.querySelector(href);
				if (target) {
					e.preventDefault();
					target.scrollIntoView({ behavior: 'smooth', block: 'start' });
				}
			});
		});
	},

	// ========================================
	// Native lazy loading enhancement
	// ========================================
	initLazyLoading() {
		if ('loading' in HTMLImageElement.prototype) {
			document.querySelectorAll('img:not([loading])').forEach((img) => {
				img.setAttribute('loading', 'lazy');
			});
		}
	},

	// ========================================
	// Slider / Carousel
	// ========================================
	initSliders() {
		document.querySelectorAll('.novacore-slider').forEach((slider) => {
			const track = slider.querySelector('.novacore-slider__track');
			const slides = slider.querySelectorAll('.novacore-slider__slide');
			const prevBtn = slider.querySelector('.novacore-slider__arrow--prev');
			const nextBtn = slider.querySelector('.novacore-slider__arrow--next');
			const dots = slider.querySelectorAll('.novacore-slider__dot');
			const autoplay = slider.dataset.autoplay === '1';
			const autoplaySpeed = parseInt(slider.dataset.autoplaySpeed, 10) || 5000;
			const total = slides.length;
			let current = 0;
			let interval;

			if (total < 2) return;

			const goTo = (index) => {
				if (index < 0) index = total - 1;
				if (index >= total) index = 0;
				current = index;

				track.style.transform = `translateX(-${current * 100}%)`;

				slides.forEach((s, i) => {
					s.classList.toggle('is-active', i === current);
				});

				dots.forEach((d, i) => {
					d.classList.toggle('is-active', i === current);
				});
			};

			const next = () => goTo(current + 1);
			const prev = () => goTo(current - 1);

			if (nextBtn) nextBtn.addEventListener('click', () => { next(); resetAutoplay(); });
			if (prevBtn) prevBtn.addEventListener('click', () => { prev(); resetAutoplay(); });

			dots.forEach((dot) => {
				dot.addEventListener('click', () => {
					goTo(parseInt(dot.dataset.slide, 10));
					resetAutoplay();
				});
			});

			const startAutoplay = () => {
				if (!autoplay) return;
				interval = setInterval(next, autoplaySpeed);
			};

			const resetAutoplay = () => {
				clearInterval(interval);
				startAutoplay();
			};

			slider.addEventListener('mouseenter', () => clearInterval(interval));
			slider.addEventListener('mouseleave', startAutoplay);

			startAutoplay();
		});
	},

	// ========================================
	// Infinite Scroll
	// ========================================
	initInfiniteScroll() {
		const trigger = document.querySelector('.novacore-infinite-scroll-trigger');
		if (!trigger) return;

		const nav = document.querySelector('.novacore-pagination');
		let loading = false;
		let currentPage = 1;

		const observer = new IntersectionObserver(async (entries) => {
			const entry = entries[0];
			if (!entry.isIntersecting || loading) return;
			loading = true;

			const nextLink = nav?.querySelector('a.next');
			if (!nextLink) { observer.disconnect(); return; }

			try {
				const urlObj = new URL(nextLink.href);
				const page = urlObj.searchParams.get('paged') || urlObj.pathname.match(/\/page\/(\d+)/)?.[1] || (currentPage + 1);
				currentPage = parseInt(page, 10);

				const res = await fetch(this.config.ajaxUrl, {
					method: 'POST',
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
					body: new URLSearchParams({
						action: 'novacore_ajax_pagination',
						page,
						query_vars: this.config.currentQuery || '',
						nonce: this.config.nonce,
					}),
				});

				const data = await res.json();

				if (data.success) {
					const container = document.getElementById('novacore-posts-container');
					if (container) {
						container.insertAdjacentHTML('beforeend', data.data.html);
					}
					if (nav) {
						nav.outerHTML = data.data.pagination;
					}
					if (page >= data.data.max_pages) {
						observer.disconnect();
					}
					this.initLazyLoading();
				}
			} catch {
				observer.disconnect();
			} finally {
				loading = false;
			}
		}, { rootMargin: '200px' });

		observer.observe(trigger);
	},

	// ========================================
	// Language Dropdown
	// ========================================
	initLanguageDropdown() {
		document.querySelectorAll('.novacore-lang-dropdown').forEach((dropdown) => {
			const toggle = dropdown.querySelector('.novacore-lang-dropdown__toggle');
			const menu = dropdown.querySelector('.novacore-lang-dropdown__menu');
			if (!toggle || !menu) return;

			toggle.addEventListener('click', (e) => {
				e.stopPropagation();
				const isOpen = menu.classList.contains('is-open');
				menu.classList.toggle('is-open');
				toggle.setAttribute('aria-expanded', !isOpen);
			});

			menu.querySelectorAll('.novacore-lang-dropdown__link').forEach((link) => {
				link.addEventListener('click', (e) => {
					e.preventDefault();
					const lang = link.dataset.lang;
					document.cookie = 'novacore_lang=' + lang + '; path=/; max-age=31536000';
					location.reload();
				});
			});
		});

		document.addEventListener('click', () => {
			document.querySelectorAll('.novacore-lang-dropdown__menu.is-open').forEach((m) => {
				m.classList.remove('is-open');
				m.closest('.novacore-lang-dropdown')?.querySelector('.novacore-lang-dropdown__toggle')?.setAttribute('aria-expanded', 'false');
			});
		});
	},

	// ========================================
	// Subscribe Form
	// ========================================
	initSubscribeForm() {
		document.querySelectorAll('.novacore-subscribe-form').forEach((form) => {
			form.addEventListener('submit', async (e) => {
				e.preventDefault();
				const input = form.querySelector('input[name="email"]');
				const msg = form.querySelector('.novacore-subscribe-msg');
				const btn = form.querySelector('button[type="submit"]');
				if (!input || !msg) return;

				const email = input.value.trim();
				if (!email) return;

				msg.textContent = '';
				msg.className = 'novacore-subscribe-msg';
				btn.disabled = true;

				try {
					const ncData = window.novacoreData || {};
					const res = await fetch(ncData.ajaxUrl, {
						method: 'POST',
						headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
						body: new URLSearchParams({
							action: 'novacore_newsletter_subscribe',
							email,
							nonce: ncData.nonce,
						}),
					});
					const data = await res.json();
					msg.textContent = data.data;
					msg.classList.add(data.success ? 'is-success' : 'is-error');
					if (data.success) {
						input.value = '';
					}
				} catch {
					msg.textContent = 'Subscription failed. Please try again.';
					msg.classList.add('is-error');
				} finally {
					btn.disabled = false;
				}
			});
		});
	},

	// ========================================
	// Top Bar Toggle
	// ========================================
	initTopBarToggle() {
		const toggle = document.querySelector('.novacore-top-bar-toggle');
		const panel = document.querySelector('.novacore-top-bar-panel');
		if (!toggle || !panel) return;

		toggle.addEventListener('click', (e) => {
			e.stopPropagation();
			const isOpen = panel.classList.contains('is-open');
			panel.classList.toggle('is-open');
			panel.setAttribute('aria-hidden', isOpen ? 'true' : 'false');
			toggle.setAttribute('aria-expanded', !isOpen);
			this.els.body.classList.toggle('has-modal-open', !isOpen);
		});

		document.addEventListener('click', (e) => {
			if (panel.classList.contains('is-open') &&
				!panel.contains(e.target) &&
				!toggle.contains(e.target)) {
				panel.classList.remove('is-open');
				panel.setAttribute('aria-hidden', 'true');
				toggle.setAttribute('aria-expanded', 'false');
				this.els.body.classList.remove('has-modal-open');
			}
		});
	},

	// ========================================
	// AJAX Pagination
	// ========================================
	initAjaxPagination() {
		if (!this.config.ajaxPagination) return;

		const container = document.getElementById('novacore-posts-container');
		if (!container) return;

		const doAjax = async (url) => {
			const urlObj = new URL(url);
			const page = urlObj.searchParams.get('paged') || urlObj.pathname.match(/\/page\/(\d+)/)?.[1] || 1;

			try {
				container.style.opacity = '0.5';

				const res = await fetch(this.config.ajaxUrl, {
					method: 'POST',
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
					body: new URLSearchParams({
						action: 'novacore_ajax_pagination',
						page,
						query_vars: this.config.currentQuery || '',
						nonce: this.config.nonce,
					}),
				});

				const data = await res.json();

				if (data.success) {
					container.innerHTML = data.data.html;

					const oldNav = document.querySelector('.novacore-pagination');
					if (oldNav) {
						oldNav.outerHTML = data.data.pagination;
					}

					window.scrollTo({ top: container.offsetTop - 50, behavior: 'smooth' });
					history.pushState({ page }, '', url);

					this.initLazyLoading();
				}
			} catch {
				window.location.href = url;
			} finally {
				container.style.opacity = '1';
			}
		};

		document.addEventListener('click', (e) => {
			const link = e.target.closest('.novacore-pagination a');
			if (!link) return;
			e.preventDefault();
			doAjax(link.href);
		});

		window.addEventListener('popstate', () => {
			const currentUrl = window.location.href;
			doAjax(currentUrl);
		});
	},

	// ========================================
	// Event Handlers
	// ========================================
	onDocumentClick(e) {
		// Close search popup on overlay click
		if (this.els.searchPopup?.classList.contains('is-open')) {
			if (e.target.closest('.novacore-search-popup__inner') === null &&
				e.target.closest('.novacore-search-toggle') === null) {
				this.closeSearchPopup();
			}
		}

		// Close off-canvas on overlay
		if (this.els.offCanvas?.classList.contains('is-open')) {
			if (e.target.closest('.novacore-off-canvas') === null) {
				this.els.offCanvas.classList.remove('is-open');
				this.els.overlay?.classList.remove('is-open');
				this.els.body.classList.remove('has-modal-open');
			}
		}
	},

	onScroll() {
		// Scroll to top button visibility
		if (this.els.scrollTop) {
			this.els.scrollTop.classList.toggle('is-visible', window.scrollY > 400);
		}
	},

	onResize() {},
};

document.addEventListener('DOMContentLoaded', () => NovaCore.init());
