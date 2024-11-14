<?php require_once 'config/database.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Sales Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
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
<body class="bg-gray-100">
    <?php include 'includes/sidebar.php'; ?>

    <div class="w-full h-screen overflow-auto p-10 transition-all duration-700" id="main-content">
        <!-- Hamburger Button -->
        <button id="hamburgerButton" class="fixed top-2 left-2 bg-gray-900 text-white p-2 rounded-full z-50">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Page Header -->
        <div class="flex justify-between items-center bg-white p-4 rounded shadow-md relative mb-6">
            <h1 class="text-2xl font-bold">Sales Reports</h1>
            <div class="flex space-x-4">
                <select id="timeRange" class="border rounded px-3 py-1">
                    <option value="7">Last 7 Days</option>
                    <option value="30">Last 30 Days</option>
                    <option value="90">Last 90 Days</option>
                    <option value="365">Last Year</option>
                </select>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Sales Trend Chart -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-bold mb-4">Sales Trend</h2>
                <canvas id="salesTrendChart"></canvas>
            </div>

            <!-- Category Distribution Chart -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-bold mb-4">Sales by Category</h2>
                <canvas id="categoryChart"></canvas>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <?php
            // Fetch summary data
            $totalSales = $conn->query("SELECT COUNT(*) FROM sales")->fetchColumn();
            $totalRevenue = $conn->query("SELECT SUM(total_amount) FROM sales")->fetchColumn();
            $avgOrderValue = $totalSales > 0 ? $totalRevenue / $totalSales : 0;
            $topCategory = $conn->query("
                SELECT p.category, SUM(s.total_amount) as revenue
                FROM sales s
                JOIN products p ON s.product_id = p.id
                GROUP BY p.category
                ORDER BY revenue DESC
                LIMIT 1
            ")->fetch(PDO::FETCH_ASSOC);
            ?>

            <!-- Total Sales Card -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-gray-500 text-sm">Total Sales</h3>
                <p class="text-2xl font-bold"><?php echo number_format($totalSales); ?></p>
            </div>

            <!-- Total Revenue Card -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-gray-500 text-sm">Total Revenue</h3>
                <p class="text-2xl font-bold">$<?php echo number_format($totalRevenue, 2); ?></p>
            </div>

            <!-- Average Order Value Card -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-gray-500 text-sm">Average Order Value</h3>
                <p class="text-2xl font-bold">$<?php echo number_format($avgOrderValue, 2); ?></p>
            </div>

            <!-- Top Category Card -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-gray-500 text-sm">Top Category</h3>
                <p class="text-2xl font-bold"><?php echo $topCategory['category'] ?? 'N/A'; ?></p>
            </div>
        </div>

        <!-- Recent Sales Table -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-bold mb-4">Recent Sales</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="py-3 px-6 text-left">Date</th>
                            <th class="py-3 px-6 text-left">Product</th>
                            <th class="py-3 px-6 text-left">Customer</th>
                            <th class="py-3 px-6 text-left">Quantity</th>
                            <th class="py-3 px-6 text-left">Amount</th>
                        </tr>
                    </thead>
                    <tbody id="recentSalesTableBody">
                        <?php
                        $stmt = $conn->query("
                            SELECT s.*, p.name as product_name, c.name as customer_name
                            FROM sales s
                            JOIN products p ON s.product_id = p.id
                            JOIN customers c ON s.customer_id = c.id
                            ORDER BY s.sale_date DESC
                            LIMIT 10
                        ");
                        
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr class='border-b hover:bg-gray-100'>";
                            echo "<td class='py-3 px-6'>" . date('Y-m-d', strtotime($row['sale_date'])) . "</td>";
                            echo "<td class='py-3 px-6'>{$row['product_name']}</td>";
                            echo "<td class='py-3 px-6'>{$row['customer_name']}</td>";
                            echo "<td class='py-3 px-6'>{$row['quantity']}</td>";
                            echo "<td class='py-3 px-6'>$" . number_format($row['total_amount'], 2) . "</td>";
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
    <script src="js/reports.js"></script>
</body>
</html> 