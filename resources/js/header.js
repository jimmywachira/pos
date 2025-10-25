// Small accessibility helpers for the mobile menu
(() => {
    function isVisible(el) {
        return !!(el && el.offsetParent !== null && getComputedStyle(el).display !== 'none');
    }

    function trapFocus(container, firstFocusable) {
        function handleKey(e) {
            if (e.key === 'Tab') {
                const focusable = Array.from(container.querySelectorAll('a, button, [tabindex]:not([tabindex="-1"])')).filter(n => !n.hasAttribute('disabled'));
                if (!focusable.length) return;
                const first = focusable[0];
                const last = focusable[focusable.length - 1];
                if (e.shiftKey && document.activeElement === first) {
                    e.preventDefault();
                    last.focus();
                } else if (!e.shiftKey && document.activeElement === last) {
                    e.preventDefault();
                    first.focus();
                }
            } else if (e.key === 'Escape') {
                // close menu
                const btn = document.querySelector('[data-mobile-menu-button]');
                if (btn) btn.click();
            } else if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                const links = Array.from(container.querySelectorAll('a')).filter(n => n.offsetParent !== null);
                if (!links.length) return;
                e.preventDefault();
                const idx = links.indexOf(document.activeElement);
                if (e.key === 'ArrowDown') {
                    const next = links[(idx + 1) % links.length]; next.focus();
                } else {
                    const prev = links[(idx - 1 + links.length) % links.length]; prev.focus();
                }
            }
        }

        document.addEventListener('keydown', handleKey);
        return () => document.removeEventListener('keydown', handleKey);
    }

    document.addEventListener('DOMContentLoaded', () => {
        const menu = document.querySelector('[data-mobile-menu]');
        const btn = document.querySelector('[data-mobile-menu-button]');
        let releaseTrap = null;

        if (!menu || !btn) return;

        btn.addEventListener('click', () => {
            // slight delay to let Alpine toggle x-show
            setTimeout(() => {
                if (isVisible(menu)) {
                    // focus first link
                    const firstLink = menu.querySelector('a');
                    if (firstLink) firstLink.focus();
                    releaseTrap = trapFocus(menu, firstLink);
                } else {
                    if (releaseTrap) { releaseTrap(); releaseTrap = null; }
                }
            }, 50);
        });

        // close menu on resize to desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768 && isVisible(menu)) {
                // simulate button click to close
                if (btn && btn.getAttribute('aria-expanded') === 'true') btn.click();
            }
        });
    });
})();
