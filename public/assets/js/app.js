/**
 * Hypernex Store - Elite Interaction Engine
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. AJAX Add to Bag
    const cartForms = document.querySelectorAll('.ajax-add-to-cart');
    cartForms.forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const btn = this.querySelector('button');
            const btnText = btn.querySelector('.btn-text');
            const originalText = btnText.innerHTML;
            const url = this.getAttribute('action');
            
            // Loading State
            btnText.innerHTML = 'ADDING...';
            btn.classList.add('disabled');
            
            try {
                const formData = new FormData(this);
                const csrfMeta = document.getElementById('csrf-token');
                const csrfToken = csrfMeta ? csrfMeta.content : '';
                
                const response = await fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });
                
                const data = await response.json();
                
                if (data.status === 'success') {
                    // Update Bag Count in Nav
                    const bagCounts = document.querySelectorAll('[data-bs-target="#offcanvasCart"] .ms-1');
                    bagCounts.forEach(el => el.textContent = `(${data.cartCount})`);

                    // Update Offcanvas Content
                    const offcanvasEl = document.getElementById('offcanvasCart');
                    if (offcanvasEl && data.html) {
                        // Replace the entire offcanvas content or just the body?
                        // For simplicity, let's replace the innerHTML of the offcanvas
                        offcanvasEl.innerHTML = new DOMParser().parseFromString(data.html, 'text/html').getElementById('offcanvasCart').innerHTML;
                        
                        // Show the offcanvas
                        const bsOffcanvas = bootstrap.Offcanvas.getOrCreateInstance(offcanvasEl);
                        bsOffcanvas.show();
                    }
                } else {
                    showNotification(data.message || 'Error adding to bag', 'error');
                }
            } catch (err) {
                console.error(err);
                showNotification('Network error occurred', 'error');
            } finally {
                btnText.innerHTML = originalText;
                btn.classList.remove('disabled');
            }
        });
    });

    // 2. AJAX Remove from Bag
    document.addEventListener('click', async function(e) {
        if (e.target.closest('.remove-item')) {
            const btn = e.target.closest('.remove-item');
            const productId = btn.getAttribute('data-id');
            
            // Show loading state
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-hourglass-split"></i>';
            btn.classList.add('disabled');
            
            try {
                const csrfMeta = document.getElementById('csrf-token');
                const csrfToken = csrfMeta ? csrfMeta.content : '';

                const response = await fetch(`${window.location.origin}/cart/remove/${productId}`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });
                
                const data = await response.json();
                
                if (data.status === 'success') {
                    // Update Bag Count in Nav
                    const bagCounts = document.querySelectorAll('[data-bs-target="#offcanvasCart"] .ms-1');
                    bagCounts.forEach(el => el.textContent = `(${data.cartCount})`);

                    // Update Offcanvas Content
                    const offcanvasEl = document.getElementById('offcanvasCart');
                    if (offcanvasEl && data.html) {
                        offcanvasEl.innerHTML = new DOMParser().parseFromString(data.html, 'text/html').getElementById('offcanvasCart').innerHTML;
                        showNotification('Item removed from selection');
                    }
                }
            } catch (err) {
                console.error(err);
                btn.innerHTML = originalHTML;
                btn.classList.remove('disabled');
                showNotification('Could not remove item', 'error');
            }
        }
    });

    // 3. Notification System
    function showNotification(message, type = 'success') {
        let container = document.querySelector('.notification-container');
        if (!container) {
            container = document.createElement('div');
            container.className = 'notification-container';
            document.body.appendChild(container);
        }
        
        const toast = document.createElement('div');
        toast.className = `vp-toast ${type}`;
        toast.innerHTML = `
            <i class="bi ${type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill'}"></i>
            <span>${message}</span>
        `;
        
        container.appendChild(toast);
        
        setTimeout(() => {
            toast.classList.add('show');
        }, 10);
        
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // 3. Image Hover Parallelism (Pure CSS handled, but could add fallback here)
});

