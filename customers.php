<?php require_once 'config/database.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers - Sales Admin</title>
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
            <h1 class="text-2xl font-bold">Customers</h1>
            <div class="flex space-x-4">
                <button onclick="window.location.href='add_sale.php'" 
                        class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                    <i class="fas fa-cart-plus mr-2"></i>Add Sale
                </button>
                <button onclick="window.location.href='add_customer.php'" 
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    <i class="fas fa-plus mr-2"></i>Add Customer
                </button>
            </div>
        </div>

        <!-- Customers Table -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="py-3 px-6 text-left">Name</th>
                            <th class="py-3 px-6 text-left">Email</th>
                            <th class="py-3 px-6 text-left">Phone</th>
                            <th class="py-3 px-6 text-left">Total Orders</th>
                            <th class="py-3 px-6 text-left">Actions</th>
                            <th class="py-3 px-6 text-left">Orders</th>
                        </tr>
                    </thead>
                    <tbody id="customersTableBody">
                        <?php
                        $stmt = $conn->query("
                            SELECT c.*, COUNT(s.id) as total_orders 
                            FROM customers c 
                            LEFT JOIN sales s ON c.id = s.customer_id 
                            GROUP BY c.id
                            ORDER BY c.created_at DESC
                        ");
                        
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr class='border-b hover:bg-gray-100'>";
                            echo "<td class='py-3 px-6'>{$row['name']}</td>";
                            echo "<td class='py-3 px-6'>{$row['email']}</td>";
                            echo "<td class='py-3 px-6'>{$row['phone']}</td>";
                            echo "<td class='py-3 px-6'>{$row['total_orders']}</td>";
                            echo "<td class='py-3 px-6'>
                                    <button onclick=\"editCustomer({$row['id']})\" class='text-blue-500 hover:text-blue-700 mr-2'>
                                        <i class='fas fa-edit'></i>
                                    </button>
                                    <button onclick=\"deleteCustomer({$row['id']})\" class='text-red-500 hover:text-red-700'>
                                        <i class='fas fa-trash'></i>
                                    </button>
                                  </td>";
                            echo "<td class='py-3 px-6'>
                                    <button onclick=\"viewOrders({$row['id']})\" 
                                            class='bg-indigo-500 text-white px-3 py-1 rounded hover:bg-indigo-600'>
                                        <i class='fas fa-shopping-bag mr-1'></i> View Orders
                                    </button>
                                  </td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script src="script.js"></script>
    <script src="js/customers.js"></script>

    <!-- Add this modal HTML at the bottom of the body -->
    <div id="ordersModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold" id="modalCustomerName">Customer Orders</h3>
                <button onclick="closeOrdersModal()" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="ordersContent" class="overflow-x-auto">
                <!-- Orders will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Add this JavaScript before the closing body tag -->
    <script>
    async function viewOrders(customerId) {
        try {
            const response = await fetch(`api/customer_orders.php?customer_id=${customerId}`);
            const data = await response.json();
            
            if(data.status === 'success') {
                const modal = document.getElementById('ordersModal');
                const content = document.getElementById('ordersContent');
                const customerName = document.getElementById('modalCustomerName');
                
                customerName.textContent = `Orders - ${data.customer_name}`;
                
                // Create orders table
                let html = `
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="py-2 px-4 text-left">Date</th>
                                <th class="py-2 px-4 text-left">Product</th>
                                <th class="py-2 px-4 text-left">Quantity</th>
                                <th class="py-2 px-4 text-left">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                `;
                
                let totalAmount = 0;
                data.orders.forEach(order => {
                    totalAmount += parseFloat(order.total_amount);
                    html += `
                        <tr class="border-b hover:bg-gray-100">
                            <td class="py-2 px-4">${new Date(order.sale_date).toLocaleDateString()}</td>
                            <td class="py-2 px-4">${order.product_name}</td>
                            <td class="py-2 px-4">${order.quantity}</td>
                            <td class="py-2 px-4">$${parseFloat(order.total_amount).toFixed(2)}</td>
                        </tr>
                    `;
                });
                
                html += `
                        </tbody>
                        <tfoot class="bg-gray-100">
                            <tr>
                                <td colspan="3" class="py-2 px-4 font-bold text-right">Total:</td>
                                <td class="py-2 px-4 font-bold">$${totalAmount.toFixed(2)}</td>
                            </tr>
                        </tfoot>
                    </table>
                `;
                
                content.innerHTML = html;
                modal.classList.remove('hidden');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error loading orders');
        }
    }

    function closeOrdersModal() {
        document.getElementById('ordersModal').classList.add('hidden');
    }

    // Close modal when clicking outside
    document.getElementById('ordersModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeOrdersModal();
        }
    });
    </script>
</body>
</html> 