const archiveFilter = {
  init() {
    this.container = document.querySelector('#ajax-product-archive');
    this.filterLinks = document.querySelector('#product-cat-filters');
    if (!this.container || !this.filterLinks) return;
    this.attachEvents();
  },

  attachEvents() {
    // Delegacja zdarzeń dla paginacji
    this.container.addEventListener('click', (e) => {
      const paginationLink = e.target.closest('.page-numbers a');
      if (paginationLink) {
        e.preventDefault();
        
        // --- POCZĄTEK ZMIANY ---
        // Uproszczona logika, która teraz zadziała z nowym formatem linków
        const url = new URL(paginationLink.href);
        const page = url.searchParams.get('paged') || 1;
        // --- KONIEC ZMIANY ---

        const activeCategory = this.container.dataset.currentCategory || 'all';
        
        // Aktualizujemy URL w pasku przeglądarki
        window.history.pushState({}, '', paginationLink.href);

        this.updateProducts(activeCategory, page);
      }
    });

    // Nasłuchiwanie na filtry kategorii (bez zmian)
    this.filterLinks.addEventListener('click', (e) => {
      const target = e.target.closest('.filter-link');
      if (!target) return;
      e.preventDefault();
      const category = target.dataset.category;
      
      this.container.dataset.currentCategory = category;

      window.history.pushState({}, '', target.href);
      this.filterLinks.querySelectorAll('.filter-link').forEach(link => link.classList.remove('active', 'font-bold'));
      target.classList.add('active', 'font-bold');
      
      this.updateProducts(category, 1);
    });
  },

  // Funkcja updateProducts pozostaje bez zmian
  updateProducts(category, page = 1) {
    const nonce = this.container.dataset.nonce;
    const ajaxUrl = this.container.dataset.ajaxUrl;
    if (!nonce || !ajaxUrl) return;

    const data = new FormData();
    data.append('action', 'filter_product_archive');
    data.append('nonce', nonce);
    data.append('category', category);
    data.append('page', page);

    this.container.style.opacity = '0.5';

    fetch(ajaxUrl, { method: 'POST', body: data })
      .then(response => response.json())
      .then(response => {
        if (response.success) {
          this.container.innerHTML = response.data.html;
          const newContainer = document.querySelector('#ajax-product-archive');
          if(newContainer) {
            newContainer.dataset.currentCategory = category;
          }
          this.container.scrollIntoView({ behavior: 'smooth' });
        } else {
          this.container.innerHTML = '<p>Wystąpił błąd.</p>';
        }
        this.container.style.opacity = '1';
      })
      .catch(() => {
        this.container.innerHTML = '<p>Wystąpił błąd serwera.</p>';
        this.container.style.opacity = '1';
      });
  },
};

document.addEventListener('DOMContentLoaded', () => archiveFilter.init());
export default archiveFilter;
