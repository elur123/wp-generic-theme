/**
 * Scroll-triggered fade-in animations via IntersectionObserver.
 *
 * Opt elements in with the `data-animate` attribute.
 * Optional `data-animate-delay="200"` for stagger (ms).
 *
 * CSS for these states lives in main.css ([data-animate] / .is-animated).
 */

export function initAnimations() {
  const elements = document.querySelectorAll('[data-animate]')
  if (!elements.length) return

  // Respect reduced-motion preference — skip animation, show immediately
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    elements.forEach(el => el.classList.add('is-animated'))
    return
  }

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach(entry => {
        if (!entry.isIntersecting) return
        const delay = entry.target.dataset.animateDelay ?? 0
        setTimeout(() => {
          entry.target.classList.add('is-animated')
        }, Number(delay))
        observer.unobserve(entry.target)
      })
    },
    { threshold: 0.1, rootMargin: '0px 0px -40px 0px' }
  )

  elements.forEach(el => observer.observe(el))
}
