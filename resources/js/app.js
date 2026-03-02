/*--- GŁÓWNE IMPORTY ---*/
// Importujemy tylko Alpine, resztę bibliotek (GSAP) ładujemy globalnie

import Alpine from 'alpinejs';

// Importy zasobów dla Vite (np. obrazy, fonty)
import.meta.glob(['../images/**', '../fonts/**']);

// Twoje niestandardowe moduły JS
import './menubar.js';
import './footer-accordion.js';
import './archive-filter.js';

/*--- USED ---*/

document.addEventListener('DOMContentLoaded', () => {
  if (document.querySelector('.b-help')) import('./blocks/help');
  if (document.querySelector('.b-team')) import('./blocks/team');
  if (document.querySelector('.b-reviews')) import('./blocks/reviews');
  if (document.querySelector('.b-places')) import('./blocks/places');
  if (document.querySelector('.b-tabs')) import('./blocks/tabs');
  if (document.querySelector('.b-products')) import('./blocks/products');
});

/*--- NOT USED ---*/
import './blocks/works.js';
import './blocks/category-posts.js';
import './blocks/how.js';
import './blocks/overlap.js';
import './blocks/category-slider.js';

/*--- INICJALIZACJA BIBLIOTEK ---*/

// Ustaw Alpine.js na obiekcie window, aby było dostępne globalnie
window.Alpine = Alpine;

// Zarejestruj komponent wyszukiwarki dla Alpine.js
// To MUSI być przed Alpine.start()
document.addEventListener('alpine:init', () => {
  Alpine.data('productSearch', () => ({
    searchQuery: '',
    searchResults: [],
    searchProducts() {
      if (this.searchQuery.length < 2) { // Zmniejszyłem próg do 2 znaków
        this.searchResults = [];
        return;
      }

      // Używamy obiektu URLSearchParams dla bezpieczeństwa i czytelności
      const params = new URLSearchParams({
        action: 'search_products',
        s: this.searchQuery,
      });

      fetch(`/wp-admin/admin-ajax.php?${params.toString()}`)
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            this.searchResults = data.data;
          } else {
            this.searchResults = [];
          }
        })
        .catch(error => {
            console.error('Błąd wyszukiwania AJAX:', error);
            this.searchResults = [];
        });
    },
  }));
});

// Uruchom Alpine.js (tylko raz!)
Alpine.start();

/*--- SKRYPTY URUCHAMIANE PO ZAŁADOWANIU STRONY ---*/

document.addEventListener('DOMContentLoaded', function () {
  // Inicjalizacja baguetteBox.js dla galerii
  if (document.querySelector('.lightbox-gallery')) {
    baguetteBox.run('.lightbox-gallery');
  }

  // Sprawdzenie, czy globalny GSAP istnieje. Jeśli nie, nic nie robimy, aby uniknąć błędów.
  if (typeof gsap === 'undefined') {
    console.error(
      'GSAP nie został załadowany globalnie. Sprawdź plik app/setup.php lub functions.php'
    );
    return;
  }

  // --- TWOJE ISTNIEJĄCE ANIMACJE GSAP (TERAZ POWINNY DZIAŁAĆ) ---
  gsap.utils.toArray("[data-gsap-anim='section']").forEach((section) => {
    const standardImages = section.querySelectorAll(
      "[data-gsap-element='img']"
    );
    standardImages.forEach((img) => {
      gsap.from(img, {
        opacity: 0,
        y: 50,
        filter: 'blur(15px)',
        duration: 1,
        ease: 'power2.out',
        scrollTrigger: {
          trigger: img,
          start: 'top 90%',
          toggleActions: 'play none none none',
          once: true,
        },
      });
    });

    const otherElements = section.querySelectorAll(
      "[data-gsap-element]:not([data-gsap-element*='img']):not([data-gsap-element='stagger'])"
    );
    otherElements.forEach((element, index) => {
      gsap.from(element, {
        opacity: 0,
        y: 50,
        filter: 'blur(15px)',
        duration: 1,
        ease: 'power2.out',
        delay: index * 0.1,
        scrollTrigger: {
          trigger: element,
          start: 'top 90%',
          toggleActions: 'play none none none',
          once: true,
        },
      });
    });

    const staggerElements = section.querySelectorAll(
      "[data-gsap-element='stagger']"
    );
    if (staggerElements.length > 0) {
      const sorted = [...staggerElements].sort((a, b) => {
        const getDelay = (el) => {
          const attr = el.getAttribute('data-gsap-edit');
          return attr && attr.startsWith('delay-')
            ? parseFloat(attr.replace('delay-', '')) || 0
            : 0;
        };
        return getDelay(a) - getDelay(b);
      });

      gsap.set(sorted, { opacity: 0, y: 50 });

      gsap.to(sorted, {
        opacity: 1,
        y: 0,
        filter: 'blur(0px)',
        duration: 1,
        ease: 'power2.out',
        stagger: { amount: 1.5, each: 0.1 },
        scrollTrigger: {
          trigger: section,
          start: 'top 80%',
          toggleActions: 'play none none none',
          once: true,
        },
      });
    }
  });
});

/*--- LINE ----*/

gsap.registerPlugin(ScrollTrigger);

document.addEventListener('DOMContentLoaded', function () {
  const line = document.querySelector('.animated-line');
  if (!line) return;

  const length = line.getTotalLength();

  gsap.set(line, {
    strokeDasharray: length,
    strokeDashoffset: length,
  });

  gsap.to(line, {
    strokeDashoffset: 0,
    duration: 0.5,
    ease: 'power1.inOut',

    scrollTrigger: {
      trigger: line,
      start: 'top 80%',
      end: 'bottom 20%',
      toggleActions: 'play none none none',
      // markers: true,
    },
  });
});

/*--- ADD TO CART - QUANTITY ---*/

function setupShopQuantityButtons() {
  // Używamy delegacji zdarzeń, co jest bardziej wydajne i działa
  // również dla produktów doładowanych przez AJAX.
  document.body.addEventListener('click', function (e) {
    // Sprawdzamy, czy kliknięty element to nasz przycisk '+' lub '-'
    if (e.target.matches('.quantity-button')) {
      e.preventDefault();
      
      const wrapper = e.target.closest('.quantity-wrapper');
      if (!wrapper) return;

      const quantityInput = wrapper.querySelector('.qty');
      // ZMIANA: Szukamy najbliższego formularza lub elementu 'li' jako kontenera.
      const productContainer = e.target.closest('form.cart, li'); 
      if (!productContainer || !quantityInput) return;
      
      // ZMIANA: Przycisk "dodaj do koszyka" może być linkiem <a> lub przyciskiem <button>.
      const addToCartButton = productContainer.querySelector('.add_to_cart_button, .single_add_to_cart_button');

      // Zmieniamy wartość w polu input
      const oldValue = parseFloat(quantityInput.value) || 0;
      const step = parseFloat(quantityInput.step || '1');
      
      let newValue;
      if (e.target.matches('.quantity-plus')) {
        newValue = oldValue + step;
      } else {
        newValue = oldValue - step;
      }

      // Sprawdzamy granice min/max
      const min = parseFloat(quantityInput.min || '0');
      if (newValue < min) {
        newValue = min;
      }

      quantityInput.value = newValue;

      // Aktualizujemy atrybut 'data-quantity' na przycisku koszyka (dla AJAX)
      if (addToCartButton && addToCartButton.hasAttribute('data-quantity')) {
        addToCartButton.setAttribute('data-quantity', newValue);
      }
    }
  });

  // Ta funkcja jest potrzebna, aby zaktualizować 'data-quantity'
  // gdy ktoś wpisze ilość ręcznie.
  document.body.addEventListener('change', function(e) {
    if (e.target.matches('.quantity .qty')) {
      // ZMIANA: Szukamy najbliższego formularza lub elementu 'li' jako kontenera.
      const productContainer = e.target.closest('form.cart, li');
      if (!productContainer) return;

      const addToCartButton = productContainer.querySelector('.add_to_cart_button, .single_add_to_cart_button');
      if (addToCartButton && addToCartButton.hasAttribute('data-quantity')) {
        addToCartButton.setAttribute('data-quantity', e.target.value);
      }
    }
  });
}

// Uruchamiamy funkcję po załadowaniu strony
document.addEventListener('DOMContentLoaded', setupShopQuantityButtons);