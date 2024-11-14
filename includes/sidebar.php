<div id="sidebar" class="fixed w-64 h-screen bg-gradient-to-b from-gray-900 via-gray-800 to-gray-900 text-gray-300 shadow-2xl flex flex-col top-0 left-0 overflow-hidden transform -translate-x-full z-30 transition-transform duration-300">
    <!-- Logo / Branding -->
    <div class="p-6 bg-gray-800 flex items-center justify-between shadow-inner">
        <h1 class="text-3xl font-bold text-white tracking-wide">Sales Admin</h1>
        <button id="sidebarCloseBtn" class="text-white focus:outline-none block">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Navigation Items -->
    <ul id="nav-items" class="mt-12 flex flex-col space-y-4 opacity-0 md:opacity-100">
        <li class="group animate-fadeIn delay-[0.2s]">
            <a href="./index.php" class="flex items-center p-4 rounded-r-full bg-gray-800 hover:bg-purple-700 hover:pl-8 transition-all duration-300 transform hover:scale-105 shadow-md">
                <i class="fas fa-tachometer-alt text-lg mr-4 text-purple-400 group-hover:text-white transform group-hover:rotate-12 transition-transform duration-300 ease-in-out"></i>
                <span class="text-lg group-hover:text-white">Dashboard</span>
            </a>
        </li>
        <li class="group animate-fadeIn delay-[0.4s]">
            <a href="./reports.php" class="flex items-center p-4 rounded-r-full bg-gray-800 hover:bg-green-600 hover:pl-8 transition-all duration-300 transform hover:scale-105 shadow-md">
                <i class="fas fa-chart-line text-lg mr-4 text-green-400 group-hover:text-white transform group-hover:rotate-12 transition-transform duration-300 ease-in-out"></i>
                <span class="text-lg group-hover:text-white">Reports</span>
            </a>
        </li>
        <li class="group animate-fadeIn delay-[0.6s]">
            <a href="./customers.php" class="flex items-center p-4 rounded-r-full bg-gray-800 hover:bg-yellow-500 hover:pl-8 transition-all duration-300 transform hover:scale-105 shadow-md">
                <i class="fas fa-users text-lg mr-4 text-yellow-400 group-hover:text-white transform group-hover:rotate-12 transition-transform duration-300 ease-in-out"></i>
                <span class="text-lg group-hover:text-white">Customers</span>
            </a>
        </li>
        <li class="group animate-fadeIn delay-[0.6s]">
            <a href="./products.php" class="flex items-center p-4 rounded-r-full bg-gray-800 hover:bg-blue-600 hover:pl-8 transition-all duration-300 transform hover:scale-105 shadow-md">
                <i class="fas fa-boxes text-lg mr-4 text-blue-400 group-hover:text-white transform group-hover:rotate-12 transition-transform duration-300 ease-in-out"></i>
                <span class="text-lg group-hover:text-white">Products</span>
            </a>
        </li>
        <li class="group animate-fadeIn delay-[0.6s]">
            <a href="./add_product.php" class="flex items-center p-4 rounded-r-full bg-gray-800 hover:bg-red-700 hover:pl-8 transition-all duration-300 transform hover:scale-105 shadow-md">
                <i class="fas fa-plus text-lg mr-4 text-red-400 group-hover:text-white transform group-hover:rotate-12 transition-transform duration-300 ease-in-out"></i>
                <span class="text-lg group-hover:text-white">Add Products</span>
            </a>
        </li>
    </ul>
</div> 