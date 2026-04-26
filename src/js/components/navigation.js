/**
 * Navigation — mobile menu toggle, focus trap, sub-menu keyboard support
 */

import { trapFocus } from '../utils/helpers.js'

export function initNavigation() {
  initMobileMenu()
  initDesktopSubMenus()
}

function initMobileMenu() {
  const toggle   = document.querySelector('.menu-toggle')
  const drawer   = document.querySelector('#mobile-menu')
  const overlay  = document.querySelector('#mobile-drawer-overlay')
  const closeBtn = document.querySelector('.drawer-close')
  if (!toggle || !drawer) return

  let releaseTrap = null
  let isOpen = false

  function open() {
    isOpen = true
    drawer.classList.remove('translate-x-full')
    drawer.classList.add('translate-x-0')
    overlay?.classList.remove('opacity-0', 'pointer-events-none')
    overlay?.classList.add('opacity-100', 'pointer-events-auto')
    document.body.style.overflow = 'hidden'
    toggle.setAttribute('aria-expanded', 'true')
    releaseTrap = trapFocus(drawer)
    drawer.querySelector('a, button')?.focus()
  }

  function close() {
    isOpen = false
    drawer.classList.add('translate-x-full')
    drawer.classList.remove('translate-x-0')
    overlay?.classList.add('opacity-0', 'pointer-events-none')
    overlay?.classList.remove('opacity-100', 'pointer-events-auto')
    document.body.style.overflow = ''
    toggle.setAttribute('aria-expanded', 'false')
    releaseTrap?.()
    releaseTrap = null
    toggle.focus()
  }

  toggle.addEventListener('click', () => isOpen ? close() : open())
  closeBtn?.addEventListener('click', close)
  overlay?.addEventListener('click', close)

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && isOpen) close()
  })

  // Sub-menu toggles inside drawer
  drawer.querySelectorAll('.sub-menu-toggle').forEach(btn => {
    btn.addEventListener('click', () => {
      const subMenu = btn.nextElementSibling
      if (!subMenu) return
      const isExpanded = !subMenu.classList.contains('hidden')
      subMenu.classList.toggle('hidden', isExpanded)
      btn.setAttribute('aria-expanded', String(!isExpanded))
      btn.querySelector('svg')?.classList.toggle('rotate-180', !isExpanded)
    })
  })
}

function initDesktopSubMenus() {
  // Allow keyboard activation of desktop dropdown triggers
  document.querySelectorAll('.nav-menu > li.menu-item-has-children > a').forEach(link => {
    link.addEventListener('keydown', (e) => {
      if (e.key !== 'Enter' && e.key !== ' ') return
      const sub = link.parentElement?.querySelector('.sub-menu')
      if (!sub) return
      e.preventDefault()
      const isHidden = sub.classList.contains('hidden')
      sub.classList.toggle('hidden', !isHidden)
      if (isHidden) sub.querySelector('a')?.focus()
    })
  })

  // Close desktop sub-menus on Escape
  document.addEventListener('keydown', (e) => {
    if (e.key !== 'Escape') return
    document.querySelectorAll('.nav-menu .sub-menu:not(.hidden)').forEach(sub => {
      sub.classList.add('hidden')
      sub.closest('li')?.querySelector('a')?.focus()
    })
  })
}
