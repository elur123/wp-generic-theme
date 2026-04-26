/**
 * Shared utility functions
 */

export function debounce(fn, delay = 150) {
  let timer
  return (...args) => {
    clearTimeout(timer)
    timer = setTimeout(() => fn(...args), delay)
  }
}

export function throttle(fn, limit = 100) {
  let inThrottle
  return (...args) => {
    if (!inThrottle) {
      fn(...args)
      inThrottle = true
      setTimeout(() => { inThrottle = false }, limit)
    }
  }
}

/**
 * Trap keyboard focus within an element (dialogs, open menus).
 * Returns a cleanup function that removes the listener.
 */
export function trapFocus(element) {
  const selector = 'a[href], button:not([disabled]), input:not([disabled]), textarea:not([disabled]), select:not([disabled]), [tabindex]:not([tabindex="-1"])'
  const focusable = [...element.querySelectorAll(selector)]
  const first = focusable[0]
  const last  = focusable[focusable.length - 1]

  function onKey(e) {
    if (e.key !== 'Tab') return
    if (e.shiftKey) {
      if (document.activeElement === first) { e.preventDefault(); last?.focus() }
    } else {
      if (document.activeElement === last)  { e.preventDefault(); first?.focus() }
    }
  }

  element.addEventListener('keydown', onKey)
  return () => element.removeEventListener('keydown', onKey)
}
