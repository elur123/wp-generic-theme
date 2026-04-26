/**
 * Combine menus — clones top-bar contact links (phone, email, location)
 * into the bottom of the mobile menu so they're reachable on small screens.
 *
 * Only runs below the lg breakpoint (1024px) to avoid duplicating links
 * on desktop where the top bar is already visible.
 */

export function initCombineMenus() {
  if (window.innerWidth >= 1024) return

  const topBarLinks = document.querySelectorAll('.top-bar a')
  const mobileMenuInner = document.querySelector('#mobile-menu > div')
  if (!topBarLinks.length || !mobileMenuInner) return

  const wrapper = document.createElement('div')
  wrapper.className = 'border-t border-neutral-100 dark:border-neutral-800 mt-2 pt-3 flex flex-col gap-1'
  wrapper.setAttribute('aria-label', 'Contact info')

  topBarLinks.forEach(link => {
    const clone = link.cloneNode(true)
    // Reset top-bar classes; apply mobile-friendly style
    clone.className = 'flex items-center gap-2 text-sm text-neutral-600 dark:text-neutral-400 hover:text-primary dark:hover:text-primary-light py-1.5 no-underline transition-colors'
    wrapper.appendChild(clone)
  })

  mobileMenuInner.appendChild(wrapper)
}
