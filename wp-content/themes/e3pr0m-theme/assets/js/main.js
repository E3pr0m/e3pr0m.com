/**
 * e3pr0m Theme — main.js
 *
 * @package   e3pr0m-theme
 * @author    e3pr0m
 */

document.addEventListener('DOMContentLoaded', () => {

  // =========================================
  // EFFETTO TYPING HERO
  // =========================================
  const heroTitle = document.querySelector('.hero__title em');

  if (heroTitle) {
    const words = ['WordPress Dev', 'Plugin Builder', 'Problem Solver', 'Coffee Consumer', 'Tech Enthusiast' , 'Zero Framework Zone' , 'Whitespace Pedant'];
    let wordIndex = 0;
    let charIndex = 0;
    let isDeleting = false;

    function typeEffect() {
      const currentWord = words[wordIndex];

      if (isDeleting) {
        heroTitle.textContent = currentWord.substring(0, charIndex - 1);
        charIndex--;
      } else {
        heroTitle.textContent = currentWord.substring(0, charIndex + 1);
        charIndex++;
      }

      let speed = isDeleting ? 60 : 120;

      if (!isDeleting && charIndex === currentWord.length) {
        speed = 2000; // pausa alla fine della parola
        isDeleting = true;
      } else if (isDeleting && charIndex === 0) {
        isDeleting = false;
        wordIndex = (wordIndex + 1) % words.length;
        speed = 400;
      }

      setTimeout(typeEffect, speed);
    }

    typeEffect();
  }

  // =========================================
  // HEADER — scroll shadow
  // =========================================
  const header = document.querySelector('.site-header');

  if (header) {
    const onScroll = () => {
      header.classList.toggle('is-scrolled', window.scrollY > 20);
    };
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  // =========================================
  // HIGHLIGHT LINK ATTIVO NEL MENU
  // =========================================
  const navLinks = document.querySelectorAll('.nav__link');
  const currentUrl = window.location.href;

  navLinks.forEach(link => {
    if (link.href === currentUrl) {
      link.classList.add('active');
    }
  });

});
