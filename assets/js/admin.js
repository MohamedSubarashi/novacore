/**
 * NovaCore Admin JavaScript
 *
 * @package NovaCore
 * @since 1.0.0
 */

'use strict';

const NovaCoreAdmin = {
	init() {
		this.initTabs();
		this.initDemoImport();
		this.initExportSettings();
		this.initImportSettings();
		this.initPluginInstall();
	},

	initTabs() {
		document.querySelectorAll('.novacore-tab-btn').forEach((btn) => {
			btn.addEventListener('click', (e) => {
				const href = btn.getAttribute('href');
				if (href && href.includes('tab=')) {
					window.location.href = href;
					return;
				}
				e.preventDefault();
				const tabId = btn.dataset.tab;
				document.querySelectorAll('.novacore-tab-btn').forEach((b) => b.classList.remove('active'));
				document.querySelectorAll('.novacore-tab-panel').forEach((p) => p.classList.remove('active'));
				btn.classList.add('active');
				document.getElementById(tabId)?.classList.add('active');
			});
		});
	},

	initDemoImport() {
		document.querySelectorAll('.novacore-import-demo').forEach((btn) => {
			btn.addEventListener('click', async () => {
				const demo = btn.dataset.demo;
				if (!confirm('Import demo content? This may take a few minutes.')) return;

				btn.disabled = true;
				btn.textContent = 'Importing...';

				try {
					const res = await fetch(ajaxurl, {
						method: 'POST',
						headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
						body: new URLSearchParams({
							action: 'novacore_import_demo',
							demo: demo,
							_ajax_nonce: window.novacoreAdmin?.nonce || '',
						}),
					});
					const data = await res.json();
					alert(data.success ? 'Demo imported successfully!' : 'Import failed: ' + data.data);
				} catch {
					alert('Import failed. Check console for details.');
				} finally {
					btn.disabled = false;
					btn.textContent = 'Import Demo';
				}
			});
		});
	},

	initExportSettings() {
		const exportBtn = document.querySelector('.novacore-export-settings');
		if (!exportBtn) return;

		exportBtn.addEventListener('click', async () => {
			exportBtn.disabled = true;
			exportBtn.textContent = 'Exporting...';

			try {
				const res = await fetch(ajaxurl, {
					method: 'POST',
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
					body: new URLSearchParams({
						action: 'novacore_export_settings',
						_ajax_nonce: window.novacoreAdmin?.nonce || '',
					}),
				});
				const data = await res.json();

				if (data.success) {
					const blob = new Blob([JSON.stringify(data.data, null, 2)], { type: 'application/json' });
					const url = URL.createObjectURL(blob);
					const a = document.createElement('a');
					a.href = url;
					a.download = 'novacore-settings-' + new Date().toISOString().slice(0, 10) + '.json';
					a.click();
					URL.revokeObjectURL(url);
				} else {
					alert('Export failed: ' + data.data);
				}
			} catch {
				alert('Export failed. Check console.');
			} finally {
				exportBtn.disabled = false;
				exportBtn.textContent = 'Download Settings';
			}
		});
	},

	initImportSettings() {
		const form = document.querySelector('.novacore-import-form');
		if (!form) return;

		form.addEventListener('submit', async (e) => {
			e.preventDefault();

			const fileInput = form.querySelector('input[type="file"]');
			if (!fileInput.files.length) {
				alert('Please select a JSON file.');
				return;
			}

			const submitBtn = form.querySelector('button[type="submit"]');
			submitBtn.disabled = true;
			submitBtn.textContent = 'Importing...';

			try {
				const body = new FormData();
				body.append('action', 'novacore_import_settings');
				body.append('_ajax_nonce', window.novacoreAdmin?.nonce || '');
				body.append('file', fileInput.files[0]);

				const res = await fetch(ajaxurl, {
					method: 'POST',
					body,
				});
				const data = await res.json();
				alert(data.success ? 'Settings imported successfully!' : 'Import failed: ' + data.data);
			} catch {
				alert('Import failed. Check console.');
			} finally {
				submitBtn.disabled = false;
				submitBtn.textContent = 'Upload & Import';
			}
		});
	},

	initPluginInstall() {
		document.querySelectorAll('.novacore-install-plugin').forEach((btn) => {
			btn.addEventListener('click', async () => {
				const plugin = btn.dataset.plugin;
				btn.disabled = true;
				btn.textContent = 'Installing...';

				try {
					const res = await fetch(ajaxurl, {
						method: 'POST',
						headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
						body: new URLSearchParams({
							action: 'novacore_install_plugin',
							plugin: plugin,
							_ajax_nonce: window.novacoreAdmin?.nonce || '',
						}),
					});
					const data = await res.json();
					if (data.success) {
						btn.textContent = 'Activated';
						btn.classList.add('active');
					} else {
						alert('Installation failed: ' + data.data);
						btn.disabled = false;
						btn.textContent = 'Install Plugin';
					}
				} catch {
					alert('Installation failed.');
					btn.disabled = false;
					btn.textContent = 'Install Plugin';
				}
			});
		});
	},
};

document.addEventListener('DOMContentLoaded', () => NovaCoreAdmin.init());
