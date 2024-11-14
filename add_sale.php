<?php require_once 'config/database.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Sale - Sales Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn { animation: fadeIn 0.7s ease-out forwards; }
        .text-shadow { text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.6); }
        #sidebar.active { transform: translateX(0); }
        .no-transition { transition: none !important; }
    </style>
</head>
<body class="bg-gray-100">
    <?php include 'includes/sidebar.php'; ?>

    <div class="w-full h-screen overflow-auto p-10 transition-all duration-700" id="main-content">
        <!-- Hamburger Button -->
        <button id="hamburgerButton" class="fixed top-2 left-2 bg-gray-900 text-white p-2 rounded-full z-50">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Page Header -->
        <div class="flex justify-between items-center bg-white p-4 rounded shadow-md relative mb-6">
            <h1 class="text-2xl font-bold">Add New Sale</h1>
        </div>

        <!-- Add Sale Form -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form id="addSaleForm" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Customer Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Customer</label>
                        <select name="customer_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Customer</option>
                            <?php
                            $customers = $conn->query("SELECT * FROM customers ORDER BY name");
                            while($customer = $customers->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='{$customer['id']}'>{$customer['name']} ({$customer['email']})</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Product Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Product</label>
                        <select name="product_id" id="product_select" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Product</option>
                            <?php
                            $products = $conn->query("SELECT * FROM products WHERE quantity > 0 ORDER BY name");
                            while($product = $products->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='{$product['id']}' data-price='{$product['price']}' data-max='{$product['quantity']}'>";
                                echo "{$product['name']} (\${$product['price']} - {$product['quantity']} in stock)";
                                echo "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Quantity -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                        <input type="number" name="quantity" id="quantity_input" min="1" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Total Amount (Auto-calculated) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Total Amount</label>
                        <input type="number" id="total_amount" name="total_amount" readonly 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="window.location.href='customers.php'" 
                            class="px-6 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Add Sale
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script src="script.js"></script>
    <script>
        // Auto-calculate total amount
        const productSelect = document.getElementById('product_select');
        const quantityInput = document.getElementById('quantity_input');
        const totalAmountInput = document.getElementById('total_amount');

        function calculateTotal() {
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            if(selectedOption.value) {
                const price = parseFloat(selectedOption.dataset.price);
                const quantity = parseInt(quantityInput.value) || 0;
                const maxQuantity = parseInt(selectedOption.dataset.max);
                
                // Limit quantity to available stock
                if(quantity > maxQuantity) {
                    quantityInput.value = maxQuantity;
                }
                
                totalAmountInput.value = (price * quantityInput.value).toFixed(2);
            }
        }

        productSelect.addEventListener('change', calculateTotal);
        quantityInput.addEventListener('input', calculateTotal);

        // Handle form submission
        document.getElementById('addSaleForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData.entries());

            try {
                const response = await fetch('api/sales.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                
                if(result.status === 'success') {
                    alert('Sale added successfully!');
                    window.location.href = 'customers.php';
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while adding the sale');
            }
        });
    </script>
</body>
</html> 