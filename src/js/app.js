import '../css/main.css'

// Dark mode runs at module level (before DOMContentLoaded) to prevent FOUC
import { initDarkMode }     from './components/dark-mode.js'
import { initNavigation }   from './components/navigation.js'
import { initStickyHeader } from './components/sticky-header.js'
import { initBackToTop }    from './components/back-to-top.js'
import { initAnimations }   from './components/animations.js'
import { initCombineMenus } from './components/combine-menus.js'

document.addEventListener('DOMContentLoaded', () => {
  initDarkMode()
  initNavigation()
  initStickyHeader()
  initBackToTop()
  initAnimations()
  initCombineMenus()
  initSearchOverlay()
})

function initSearchOverlay() {
  const overlay    = document.querySelector('#search-overlay')
  if (!overlay) return

  const searchInput = overlay.querySelector('input[type="search"]')

  document.querySelectorAll('[data-search-open]').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault()
      overlay.classList.add('open')
      searchInput?.focus()
    })
  })

  document.querySelectorAll('[data-search-close]').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault()
      overlay.classList.remove('open')
    })
  })

  overlay.addEventListener('click', (e) => {
    if (e.target === overlay) overlay.classList.remove('open')
  })

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && overlay.classList.contains('open')) {
      overlay.classList.remove('open')
    }
  })
}
