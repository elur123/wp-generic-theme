/**
 * Sticky header — adds `is-scrolled` class once the user scrolls past the top bar
 */

import { throttle } from '../utils/helpers.js'

export function initStickyHeader() {
  if (!window.genericstarter_options?.sticky_header) return

  const header = document.querySelector('#masthead')
  if (!header) return

  const onScroll = throttle(() => {
    header.classList.toggle('is-scrolled', window.scrollY > 20)
  }, 100)

  // Run once on init in case the page loads mid-scroll
  onScroll()
  window.addEventListener('scroll', onScroll, { passive: true })
}
