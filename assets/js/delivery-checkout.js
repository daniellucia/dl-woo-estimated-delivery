document.addEventListener('DOMContentLoaded', () => {
  const targetNode = document.querySelector('.wc-block-cart'); 

  if (targetNode) {
    
    const observer = new MutationObserver(() => {
      const placeOrderBtn = document.querySelector('.wc-block-components-checkout-place-order-button');
      if (placeOrderBtn && !document.getElementById('dl-estimated-delivery')) {
        // Crear el elemento del mensaje
        const p = document.createElement('p');
        p.id = 'dl-estimated-delivery';
        p.className = 'dl-estimated-delivery';
        p.textContent = dl_estimated_delivery.estimatedDelivery;

        // Insertar antes del botón
        placeOrderBtn.parentNode.insertBefore(p, placeOrderBtn);
      }
    });

    observer.observe(targetNode, { childList: true, subtree: true });
  }
});
