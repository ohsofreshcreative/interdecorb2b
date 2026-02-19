const initializeProductBlock = (productBlock) => {
  if (productBlock.dataset.initialized === 'true') {
    return;
  }
  productBlock.dataset.initialized = 'true';

  const filterContainer = productBlock.querySelector('.product-categories-filter');
  const productListContainer = productBlock.querySelector('#product-list-container');
  
  const ajaxUrl = productBlock.dataset.ajaxUrl;
  const nonce = productBlock.dataset.nonce;

  if (!filterContainer || !productListContainer || !ajaxUrl || !nonce) {
    console.error('Product block is missing required elements or data attributes.');
    return;
  }

  const fetchProducts = (categoryId, page = 1) => {
    productListContainer.classList.add('opacity-50', 'pointer-events-none');

    const formData = new FormData();
    formData.append('action', 'filter_products');
    formData.append('security', nonce);
    formData.append('category_id', categoryId);
    formData.append('paged', page);

    fetch(ajaxUrl, {
      method: 'POST',
      body: formData,
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        productListContainer.innerHTML = data.data.html;
      // productListContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    })
    .catch(error => console.error('Error:', error))
    .finally(() => {
      productListContainer.classList.remove('opacity-50', 'pointer-events-none');
    });
  };

 filterContainer.addEventListener('click', (e) => {
    const toggleButton = e.target.closest('.category-toggle-btn');
    if (toggleButton) {
      e.preventDefault();
      const parentItem = toggleButton.closest('.category-item');
      const subcategoryList = parentItem.querySelector('.subcategory-list');
      const arrow = toggleButton.querySelector('svg');

      if (subcategoryList) {
        subcategoryList.classList.toggle('hidden');
        arrow.classList.toggle('-rotate-180');
      }
      return; 
    }

    // --- OBSŁUGA FILTROWANIA (kliknięcie w nazwę) ---
    const filterButton = e.target.closest('.category-filter-btn');
    if (filterButton) {
      e.preventDefault();
      const categoryId = filterButton.dataset.categoryId;

      // Ustawienie aktywnego przycisku
      filterContainer.querySelectorAll('.category-filter-btn').forEach(btn => btn.classList.remove('active'));
      filterButton.classList.add('active');
      
      // Zawsze uruchamiaj filtrowanie AJAX
      fetchProducts(categoryId);

      // --- ZMIANA TUTAJ: Przełączanie widoczności listy podkategorii ---
      const parentItem = filterButton.closest('.category-item');
      if (parentItem) {
        const subcategoryList = parentItem.querySelector('.subcategory-list');
        const arrow = parentItem.querySelector('.category-toggle-btn svg');

        // Jeśli istnieje lista podkategorii, przełącz jej widoczność
        if (subcategoryList) {
          subcategoryList.classList.toggle('hidden');
          if (arrow) {
            arrow.classList.toggle('-rotate-180');
          }
        }
      }
    }
  });

  productListContainer.addEventListener('click', (e) => {
    if (e.target.matches('.pagination a')) {
      e.preventDefault();
      const url = new URL(e.target.href);
      const page = url.searchParams.get('paged') || 1;
      const activeCategoryBtn = filterContainer.querySelector('.category-filter-btn.active');
      const categoryId = activeCategoryBtn ? activeCategoryBtn.dataset.categoryId : 'all';
      fetchProducts(categoryId, page);
    }
  });
};

// Inicjalizujemy każdy blok produktów na stronie
document.querySelectorAll('.b-products').forEach(initializeProductBlock);