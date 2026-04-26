/**
 * Back-to-top button — show/hide on scroll, smooth scroll on click
 */

import { throttle } from '../utils/helpers.js'

export function initBackToTop() {
  const btn = document.querySelector('#back-to-top')
  if (!btn) return

  const onScroll = throttle(() => {
    btn.classList.toggle('visible', window.scrollY > 400)
  }, 100)

  onScroll()
  window.addEventListener('scroll', onScroll, { passive: true })

  btn.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' })
    // Return focus to the skip link / top of page after scroll
    document.querySelector('.screen-reader-text')?.focus({ preventScroll: true })
  })
}
