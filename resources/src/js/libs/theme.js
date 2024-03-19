(() => {
    'use strict'

    const getStoredTheme = () => {
        const theme = document.documentElement.getAttribute('data-bs-theme');
        return theme;
    }

    const getPreferredTheme = () => {
        const storedTheme = getStoredTheme()
        if (storedTheme) {
            return storedTheme
        }

        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
    }

    const setTheme = theme => {
        if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.setAttribute('data-bs-theme', 'dark')
        } else {
            document.documentElement.setAttribute('data-bs-theme', theme)
        }
    }

    const saveTheme = theme => {
        var xhttp = new XMLHttpRequest()

        xhttp.open('GET', '/api/users/preferences?theme=' + theme, true);
        xhttp.send()
    }
    
    // setTheme(getPreferredTheme())

    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
        const storedTheme = getStoredTheme()
        if (storedTheme !== 'light' && storedTheme !== 'dark') {
            setTheme(getPreferredTheme())
        }
    })

    window.addEventListener('DOMContentLoaded', () => {
        const toggleButtons = document.querySelectorAll('[data-bs-theme-value]');

        toggleButtons.forEach(toggle => {
            toggle.addEventListener('click', () => {
                const theme = toggle.getAttribute('data-bs-theme-value');
                setTheme(theme);
                saveTheme(theme);

                toggleButtons.forEach(btn => {
                    if (btn === toggle) {
                        btn.classList.add('btn-prime');
                    } else {
                        btn.classList.remove('btn-prime');
                    }
                });
            });
        });
    });
})()

// To trigger the tooltip
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
tooltipTriggerList.forEach((tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl));