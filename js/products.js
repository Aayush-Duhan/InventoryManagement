document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchProduct');
    if(searchInput) {
        searchInput.addEventListener('input', filterProducts);
    }
});

function filterProducts() {
    const searchTerm = document.getElementById('searchProduct').value.toLowerCase();
    const productCards = document.querySelectorAll('.product-card');

    productCards.forEach(card => {
        const productName = card.querySelector('h3').textContent.toLowerCase();
        const productCategory = card.querySelector('.bg-blue-100').textContent.toLowerCase();
        
        if(productName.includes(searchTerm) || productCategory.includes(searchTerm)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

async function editProduct(id) {
    window.location.href = `edit_product.php?id=${id}`;
}

async function deleteProduct(id) {
    if(confirm('Are you sure you want to delete this product?')) {
        try {
            const response = await fetch(`api/products.php?id=${id}`, {
                method: 'DELETE'
            });
            const result = await response.json();
            
            if(result.status === 'success') {
                alert('Product deleted successfully!');
                window.location.reload();
            } else {
                alert('Error: ' + result.message);
            }
        } catch(error) {
            console.error('Error:', error);
            alert('An error occurred while deleting the product');
        }
    }
} 