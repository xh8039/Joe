// ThemeManager 类封装
class ThemeManager {
	constructor() {
		this.systemQuery = window.matchMedia('(prefers-color-scheme: dark)');
		this.init();
	}

	init() {
		this.handleSystemChange();
		this.systemQuery.addEventListener('change', () => this.handleSystemChange());
	}

	get currentTheme() {
		return localStorage.getItem('theme') || (this.systemQuery.matches ? 'dark' : 'light');
	}

	setTheme(theme) {
		if (theme === 'system') {
			localStorage.removeItem('theme');
			document.documentElement.style.colorScheme = this.systemQuery.matches ? 'dark' : 'light';
		} else {
			localStorage.setItem('theme', theme);
			document.documentElement.style.colorScheme = theme;
			if (theme == 'dark') {
				document.body.classList.add('dark-theme');
			} else {
				document.body.classList.remove('dark-theme');
			}
		}
	}

	handleSystemChange() {
		if (!localStorage.getItem('theme')) {
			document.documentElement.style.colorScheme = this.systemQuery.matches ? 'dark' : 'light';
		}
	}

	toggle() {
		const theme = this.currentTheme === 'dark' ? 'light' : 'dark';
		this.setTheme(theme);
		return theme;
	}
}