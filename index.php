<?php require_once 'config/database.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Admin Dashboard</title>
    <link rel="icon" type="image/png" href="./assets/android-chrome-512x512.png">
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
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
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <!-- Include Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content Start -->
    <div class="w-full h-screen overflow-auto p-10 transition-all duration-700" id="main-content">
        <!-- Hamburger Button -->
        <button id="hamburgerButton" class="fixed top-2 left-2 bg-gray-900 text-white p-2 rounded-full z-50">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Top Navigation Bar -->
        <div class="flex justify-between items-center bg-white p-4 rounded shadow-md relative">
            <h1 class="text-2xl font-bold">Dashboard</h1>
            <div class="flex items-center space-x-4 relative">
                <button id="profileDropdownButton" class="focus:outline-none">
                    <img src="https://static.vecteezy.com/system/resources/previews/009/397/835/non_2x/man-avatar-clipart-illustration-free-png.png"
                        alt="Profile Image" class="w-10 h-10 rounded-full cursor-pointer">
                </button>
            </div>
        </div>

        <!-- Key Metrics Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
            <?php
            // Fetch metrics from database
            $totalProducts = $conn->query("SELECT COUNT(*) FROM products")->fetchColumn();
            $totalCustomers = $conn->query("SELECT COUNT(*) FROM customers")->fetchColumn();
            $totalQuantities = $conn->query("SELECT SUM(quantity) FROM sales")->fetchColumn();
            $totalRevenue = $conn->query("SELECT SUM(total_amount) FROM sales")->fetchColumn();
            ?>
            
            <!-- Total Products Card -->
            <div class="bg-gradient-to-r from-green-400 to-blue-500 text-white rounded-lg p-6 shadow-lg">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-shadow">Products</h2>
                        <p class="mt-2 text-lg text-shadow"><?php echo $totalProducts; ?></p>
                    </div>
                    <i class="fas fa-dollar-sign text-4xl opacity-50"></i>
                </div>
            </div>

            <!-- Total Customers Card -->
            <div class="bg-gradient-to-r from-yellow-400 to-red-500 text-white rounded-lg p-6 shadow-lg">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-shadow">Total Customers</h2>
                        <p class="mt-2 text-lg text-shadow"><?php echo $totalCustomers; ?></p>
                    </div>
                    <i class="fas fa-users text-4xl opacity-50"></i>
                </div>
            </div>

            <!-- Total Quantities Card -->
            <div class="bg-gradient-to-r from-indigo-400 to-purple-500 text-white rounded-lg p-6 shadow-lg">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-shadow">Quantities Sold</h2>
                        <p class="mt-2 text-lg text-shadow"><?php echo $totalQuantities ?? 0; ?></p>
                    </div>
                    <i class="fas fa-shopping-cart text-4xl opacity-50"></i>
                </div>
            </div>

            <!-- Revenue Card -->
            <div class="bg-gradient-to-r from-pink-400 to-yellow-600 text-white rounded-lg p-6 shadow-lg">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-shadow">Revenue</h2>
                        <p class="mt-2 text-lg text-shadow">$<?php echo number_format($totalRevenue ?? 0, 2); ?></p>
                    </div>
                    <i class="fas fa-chart-line text-4xl opacity-50"></i>
                </div>
            </div>
        </div>

        <!-- Charts and Tables Section -->
        <div class="mt-10 grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Sales Trends Section -->
            <div class="bg-white p-6 rounded-lg shadow-lg mb-6">
                <h2 class="text-xl font-bold mb-4">Sales Trends</h2>
                <canvas id="salesTrendsChart" class="h-64 w-full"></canvas>
            </div>

            <!-- Top Products Table -->
            <div class="bg-white p-6 rounded-lg shadow-lg mb-6">
                <h2 class="text-xl font-bold mb-4">Top Products</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b">
                                <th class="p-2">Product</th>
                                <th class="p-2">Category</th>
                                <th class="p-2">Quantity</th>
                                <th class="p-2">Revenue</th>
                            </tr>
                        </thead>
                        <tbody id="topProductsTableBody">
                            <?php
                            // Fetch top 5 products by revenue
                            $stmt = $conn->query("
                                SELECT p.name, p.category, SUM(s.quantity) as total_quantity, 
                                       SUM(s.total_amount) as total_revenue
                                FROM products p
                                LEFT JOIN sales s ON p.id = s.product_id
                                GROUP BY p.id
                                ORDER BY total_revenue DESC
                                LIMIT 5
                            ");
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr class='border-b hover:bg-gray-100'>";
                                echo "<td class='p-2'>{$row['name']}</td>";
                                echo "<td class='p-2'>{$row['category']}</td>";
                                echo "<td class='p-2'>{$row['total_quantity']}</td>";
                                echo "<td class='p-2'>$" . number_format($row['total_revenue'], 2) . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Products Section -->
        <div class="bg-white p-6 rounded-lg shadow-lg mt-6">
            <h2 class="text-xl font-bold mb-4">Recent Products</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="py-3 px-6 text-left">Product Name</th>
                            <th class="py-3 px-6 text-left">Category</th>
                            <th class="py-3 px-6 text-left">Price</th>
                            <th class="py-3 px-6 text-left">Quantity</th>
                            <th class="py-3 px-6 text-left">Revenue</th>
                        </tr>
                    </thead>
                    <tbody id="recentProductsTableBody">
                        <?php
                        // Fetch recent products
                        $stmt = $conn->query("
                            SELECT p.*, COALESCE(SUM(s.total_amount), 0) as revenue
                            FROM products p
                            LEFT JOIN sales s ON p.id = s.product_id
                            GROUP BY p.id
                            ORDER BY p.created_at DESC
                            LIMIT 10
                        ");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr class='border-b hover:bg-gray-100'>";
                            echo "<td class='py-3 px-6'>{$row['name']}</td>";
                            echo "<td class='py-3 px-6'>{$row['category']}</td>";
                            echo "<td class='py-3 px-6'>$" . number_format($row['price'], 2) . "</td>";
                            echo "<td class='py-3 px-6'>{$row['quantity']}</td>";
                            echo "<td class='py-3 px-6'>$" . number_format($row['revenue'], 2) . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script src="script.js"></script>
</body>
</html> 