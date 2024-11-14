<?php require_once 'config/database.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Sales Admin</title>
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
            <h1 class="text-2xl font-bold">Products</h1>
            <div class="flex space-x-4">
                <input type="text" id="searchProduct" placeholder="Search products..." 
                       class="border rounded px-3 py-1">
                <button onclick="window.location.href='add_product.php'" 
                        class="bg-blue-500 text-white px-4 py-1 rounded hover:bg-blue-600">
                    <i class="fas fa-plus mr-2"></i>Add Product
                </button>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php
            $stmt = $conn->query("
                SELECT p.*, COALESCE(SUM(s.quantity), 0) as total_sold
                FROM products p
                LEFT JOIN sales s ON p.id = s.product_id
                GROUP BY p.id
                ORDER BY p.created_at DESC
            ");

            while ($product = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $revenue = $product['price'] * $product['total_sold'];
                ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden product-card">
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-lg font-semibold"><?php echo htmlspecialchars($product['name']); ?></h3>
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                <?php echo htmlspecialchars($product['category']); ?>
                            </span>
                        </div>
                        <p class="text-gray-600 text-sm mb-4"><?php echo htmlspecialchars($product['description']); ?></p>
                        <div class="flex justify-between items-center text-sm">
                            <span class="font-bold text-lg">$<?php echo number_format($product['price'], 2); ?></span>
                            <span class="text-gray-500">Stock: <?php echo $product['quantity']; ?></span>
                        </div>
                        <div class="mt-2 text-sm text-gray-500">
                            <div>Total Sold: <?php echo $product['total_sold']; ?></div>
                            <div>Revenue: $<?php echo number_format($revenue, 2); ?></div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 flex justify-end space-x-2">
                        <button onclick="editProduct(<?php echo $product['id']; ?>)" 
                                class="text-blue-500 hover:text-blue-700">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteProduct(<?php echo $product['id']; ?>)" 
                                class="text-red-500 hover:text-red-700">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script src="script.js"></script>
    <script src="js/products.js"></script>
</body>
</html> 