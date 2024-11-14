document.addEventListener('DOMContentLoaded', function() {
    loadSalesTrends();
});

// Function to load sales trends
async function loadSalesTrends() {
    try {
        const response = await fetch('api/sales_trends.php');
        const data = await response.json();
        
        if(data.status === 'success') {
            // Create the chart
            const ctx = document.getElementById('salesTrendsChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Revenue',
                        data: data.revenues,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Revenue ($)'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Products'
                            }
                        }
                    }
                }
            });
        }
    } catch (error) {
        console.error('Error loading sales trends:', error);
    }
}

// Sidebar functionality
const sidebar = document.getElementById('sidebar');
const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');
const sidebarToggleBtn = document.getElementById('hamburgerButton');

function openSidebar() {
    sidebar.classList.remove('-translate-x-full');
    sidebar.classList.add('translate-x-0');
    const navItems = document.getElementById('nav-items');
    navItems.classList.remove('opacity-0');
    sidebarToggleBtn.classList.add('hidden');
}

function closeSidebar() {
    sidebar.classList.add('-translate-x-full');
    sidebar.classList.remove('translate-x-0');
    const navItems = document.getElementById('nav-items');
    navItems.classList.add('opacity-0');
    sidebarToggleBtn.classList.remove('hidden');
}

sidebarCloseBtn.addEventListener('click', closeSidebar);

sidebarToggleBtn.addEventListener('click', () => {
    if (sidebar.classList.contains('-translate-x-full')) {
        openSidebar();
    } else {
        closeSidebar();
    }
});

window.addEventListener('DOMContentLoaded', () => {
    closeSidebar();
});
