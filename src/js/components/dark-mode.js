/**
 * Dark mode — class-based toggle with localStorage persistence.
 *
 * applyInitialDarkMode() runs at module level (before DOMContentLoaded)
 * to prevent a flash of light theme on dark-preference users.
 */

applyInitialDarkMode()

function applyInitialDarkMode() {
  const saved      = localStorage.getItem('genericstarter-dark-mode')
  const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches
  const isDark     = saved === 'dark' || (saved === null && prefersDark)
  document.documentElement.classList.toggle('dark', isDark)
}

export function initDarkMode() {
  const toggles = document.querySelectorAll('[data-dark-toggle]')
  if (!toggles.length) return

  function syncToggles() {
    const isDark = document.documentElement.classList.contains('dark')
    toggles.forEach(btn => btn.setAttribute('aria-pressed', String(isDark)))
  }

  syncToggles()

  toggles.forEach(btn => {
    btn.addEventListener('click', () => {
      const isDark = document.documentElement.classList.toggle('dark')
      localStorage.setItem('genericstarter-dark-mode', isDark ? 'dark' : 'light')
      syncToggles()
    })
  })

  // Follow system preference changes only when the user hasn't manually set a preference
  window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
    if (localStorage.getItem('genericstarter-dark-mode') !== null) return
    document.documentElement.classList.toggle('dark', e.matches)
    syncToggles()
  })
}
