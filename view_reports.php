<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit();
}

if ($_SESSION['user_type'] !== 'admin') {
    header('Location: customer_dashboard.php');
    exit();
}

$admin_name = $_SESSION['full_name'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Reports - Dimi's Donuts</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #FFF5F7 0%, #FFE4EC 100%);
            min-height: 100vh;
        }

        .navbar {
            background: linear-gradient(135deg, #FF6B8B 0%, #FF8FA3 100%);
            box-shadow: 0 4px 20px rgba(255, 107, 139, 0.3);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            margin: 0 0.5rem;
            transition: all 0.3s ease;
        }

        .nav-link:hover,
        .nav-link.active {
            color: white !important;
            transform: translateY(-2px);
        }

        .page-header {
            background: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .page-title {
            font-weight: 700;
            color: #333;
            margin: 0;
        }

        .report-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .report-title {
            font-weight: 700;
            color: #333;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f0f0f0;
        }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-item {
            text-align: center;
            padding: 1.5rem;
            background: linear-gradient(135deg, #FFF5F7 0%, #FFE4EC 100%);
            border-radius: 10px;
        }

        .stat-item-title {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 0.5rem;
        }

        .stat-item-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: #FF6B8B;
        }

        .product-rank {
            display: flex;
            align-items: center;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 10px;
            margin-bottom: 1rem;
        }

        .rank-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #FF6B8B 0%, #FF8FA3 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin-right: 1rem;
        }

        .product-info {
            flex: 1;
        }

        .product-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.25rem;
        }

        .product-sales {
            color: #666;
            font-size: 0.9rem;
        }

        .product-revenue {
            font-weight: 700;
            color: #06D6A0;
            font-size: 1.1rem;
        }

        .loading-spinner {
            text-align: center;
            padding: 3rem;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="admin_dashboard.php">
                <i class="bi bi-shop"></i> Dimi's Donuts Admin
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="admin_dashboard.php">
                            <i class="bi bi-grid"></i> Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="update_stocks.php">
                            <i class="bi bi-box-seam"></i> Inventory
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="view_reports.php">
                            <i class="bi bi-bar-chart"></i> Reports
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" id="logoutLink">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1 class="page-title">
                <i class="bi bi-bar-chart"></i> Business Reports
            </h1>
            <p class="text-muted mb-0">View sales analytics and performance metrics</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container pb-5">
        <div id="loadingSpinner" class="loading-spinner">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Loading reports...</p>
        </div>

        <!-- Export Button -->
        <div class="mb-3" id="exportButtonContainer" style="display: none;">
            <button class="btn btn-primary" id="exportReportBtn"
                style="background: linear-gradient(135deg, #FF6B8B 0%, #FF8FA3 100%); border: none;">
                <i class="bi bi-file-earmark-pdf"></i> Export Report to PDF
            </button>
        </div>

        <div id="reportsContent" style="display: none;">
            <!-- Sales Overview -->
            <div class="report-card">
                <h3 class="report-title">
                    <i class="bi bi-graph-up"></i> Sales Overview
                </h3>
                <div class="stat-grid">
                    <div class="stat-item">
                        <div class="stat-item-title">Total Orders</div>
                        <div class="stat-item-value" id="totalOrders">0</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-item-title">Total Revenue</div>
                        <div class="stat-item-value" id="totalRevenue">₱0</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-item-title">Average Order Value</div>
                        <div class="stat-item-value" id="avgOrderValue">₱0</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-item-title">Completed Orders</div>
                        <div class="stat-item-value" id="completedOrders">0</div>
                    </div>
                </div>
                <!-- Sales over time chart -->
                <div style="margin-top:1.25rem">
                    <canvas id="salesTimeChart" height="120"></canvas>
                </div>
            </div>

            <!-- Top Selling Products -->
            <div class="report-card">
                <h3 class="report-title">
                    <i class="bi bi-trophy"></i> Top Selling Products
                </h3>
                <div id="topProducts"></div>
                <div style="margin-top:1rem">
                    <canvas id="topProductsChart" height="120"></canvas>
                </div>
            </div>

            <!-- Order Status Breakdown -->
            <div class="report-card">
                <h3 class="report-title">
                    <i class="bi bi-pie-chart"></i> Order Status Breakdown
                </h3>
                <div class="stat-grid">
                    <div class="stat-item">
                        <div class="stat-item-title">Pending</div>
                        <div class="stat-item-value" id="pendingCount">0</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-item-title">Confirmed</div>
                        <div class="stat-item-value" id="confirmedCount">0</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-item-title">Delivered</div>
                        <div class="stat-item-value" id="deliveredCount">0</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-item-title">Cancelled</div>
                        <div class="stat-item-value" id="cancelledCount">0</div>
                    </div>
                </div>
                <div style="margin-top:1rem; max-width:480px;">
                    <canvas id="statusChart" height="120"></canvas>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="report-card">
                <h3 class="report-title">
                    <i class="bi bi-clock-history"></i> Recent Transactions
                </h3>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="recentTransactionsBody">
                            <!-- Populated by JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <!-- jsPDF for PDF generation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>

    <script>
        // Use relative API base for portability
        const API_BASE = 'api';

        // Chart instances
        let salesTimeChart = null;
        let topProductsChart = null;
        let statusChart = null;
        let currentReportData = null;

        // Load reports
        async function loadReports() {
            try {
                const response = await fetch(`${API_BASE}/admin/dashboard_stats.php`, {
                    credentials: 'include'
                });

                if (!response.ok) {
                    const body = await response.text();
                    console.error('Dashboard stats API returned non-OK status', response.status, body);
                    document.getElementById('loadingSpinner').style.display = 'none';
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: `Failed to load reports (status ${response.status})`,
                        confirmButtonColor: '#FF6B8B'
                    });
                    return;
                }

                let data;
                try {
                    data = await response.json();
                } catch (err) {
                    const text = await response.text();
                    console.error('Failed to parse JSON from dashboard_stats response', err, text);
                    document.getElementById('loadingSpinner').style.display = 'none';
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Invalid response from server. Check console for details.',
                        confirmButtonColor: '#FF6B8B'
                    });
                    return;
                }

                document.getElementById('loadingSpinner').style.display = 'none';

                if (data.success && data.data) {
                    currentReportData = data.data;
                    document.getElementById('reportsContent').style.display = 'block';
                    document.getElementById('exportButtonContainer').style.display = 'block';
                    displayReports(data.data);
                } else {
                    console.warn('Dashboard stats returned empty or unexpected payload', data);
                    Swal.fire({
                        icon: 'info',
                        title: 'No Data',
                        text: 'No report data available for the selected range.',
                        confirmButtonColor: '#FF6B8B'
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('loadingSpinner').style.display = 'none';
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load reports',
                    confirmButtonColor: '#FF6B8B'
                });
            }
        }

        // Display reports
        function displayReports(stats) {
            // Sales Overview
            const sales = stats.sales || {};
            const totalOrders = sales.total_orders || 0;
            const totalRevenue = sales.total_sales || 0;
            const avgOrderValue = sales.average_order_value || 0;
            const completedOrders = stats.orders_by_status?.delivered || 0;

            document.getElementById('totalOrders').textContent = totalOrders;
            document.getElementById('totalRevenue').textContent = `₱${parseFloat(totalRevenue).toLocaleString()}`;
            document.getElementById('avgOrderValue').textContent = `₱${parseFloat(avgOrderValue).toLocaleString()}`;
            document.getElementById('completedOrders').textContent = completedOrders;

            // Top Products
            const topProducts = stats.top_products || [];
            const topProductsHTML = topProducts.length > 0 ? topProducts.map((product, index) => `
                <div class="product-rank">
                    <div class="rank-number">${index + 1}</div>
                    <div class="product-info">
                        <div class="product-name">${product.product_name}</div>
                        <div class="product-sales">${product.total_quantity} units sold</div>
                    </div>
                    <div class="product-revenue">₱${parseFloat(product.total_revenue).toLocaleString()}</div>
                </div>
            `).join('') : '<p class="text-muted text-center">No sales data available</p>';

            document.getElementById('topProducts').innerHTML = topProductsHTML;

            // Render Top Products Chart (bar of quantities)
            try {
                const tpLabels = topProducts.map(p => p.product_name);
                const tpData = topProducts.map(p => parseFloat(p.total_quantity) || 0);

                const tpCtx = document.getElementById('topProductsChart').getContext('2d');
                if (topProductsChart) topProductsChart.destroy();
                topProductsChart = new Chart(tpCtx, {
                    type: 'bar',
                    data: {
                        labels: tpLabels,
                        datasets: [{
                            label: 'Units Sold',
                            data: tpData,
                            backgroundColor: '#FF6B8B'
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            } catch (err) {
                console.error('Failed to render top products chart', err);
            }

            // Order Status
            const ordersByStatus = stats.orders_by_status || {};
            document.getElementById('pendingCount').textContent = ordersByStatus.pending || 0;
            document.getElementById('confirmedCount').textContent = ordersByStatus.confirmed || 0;
            document.getElementById('deliveredCount').textContent = ordersByStatus.delivered || 0;
            document.getElementById('cancelledCount').textContent = ordersByStatus.cancelled || 0;

            // Render Status Pie Chart
            try {
                const statusLabels = Object.keys(ordersByStatus);
                const statusValues = statusLabels.map(k => ordersByStatus[k] || 0);
                const statusCtx = document.getElementById('statusChart').getContext('2d');
                if (statusChart) statusChart.destroy();
                statusChart = new Chart(statusCtx, {
                    type: 'pie',
                    data: {
                        labels: statusLabels.map(s => s.replace('_', ' ')),
                        datasets: [{
                            data: statusValues,
                            backgroundColor: ['#FFC107', '#0DC5F6', '#6C757D', '#0D6EFD', '#06D6A0',
                                '#DC3545'
                            ]
                        }]
                    },
                    options: {
                        maintainAspectRatio: false
                    }
                });
            } catch (err) {
                console.error('Failed to render status chart', err);
            }

            // Recent Transactions
            const recentOrders = stats.recent_orders || [];
            const recentOrdersHTML = recentOrders.length > 0 ? recentOrders.map(order => `
                <tr>
                    <td><strong>${order.order_number}</strong></td>
                    <td>${order.customer_name}</td>
                    <td>${new Date(order.created_at).toLocaleDateString()}</td>
                    <td>₱${parseFloat(order.total_amount).toLocaleString()}</td>
                    <td><span class="badge bg-${getStatusColor(order.order_status)}">${order.order_status.replace('_', ' ')}</span></td>
                </tr>
            `).join('') : '<tr><td colspan="5" class="text-center text-muted">No recent transactions</td></tr>';

            document.getElementById('recentTransactionsBody').innerHTML = recentOrdersHTML;

            // Render Sales Over Time (using recent orders)
            try {
                const labels = recentOrders.map(o => new Date(o.created_at).toLocaleDateString());
                const values = recentOrders.map(o => parseFloat(o.total_amount) || 0);

                const salesCtx = document.getElementById('salesTimeChart').getContext('2d');
                if (salesTimeChart) salesTimeChart.destroy();
                salesTimeChart = new Chart(salesCtx, {
                    type: 'line',
                    data: {
                        labels: labels.reverse(), // recentOrders is newest first; reverse to show time ascending
                        datasets: [{
                            label: 'Order Amount',
                            data: values.reverse(),
                            borderColor: '#06D6A0',
                            backgroundColor: 'rgba(6,214,160,0.15)',
                            fill: true,
                            tension: 0.3
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            } catch (err) {
                console.error('Failed to render sales time chart', err);
            }
        }

        function getStatusColor(status) {
            switch (status) {
                case 'pending':
                    return 'warning';
                case 'confirmed':
                    return 'info';
                case 'preparing':
                    return 'secondary';
                case 'out_for_delivery':
                    return 'primary';
                case 'delivered':
                    return 'success';
                case 'cancelled':
                    return 'danger';
                default:
                    return 'secondary';
            }
        }

        // Export Report to PDF
        function exportReportToPDF() {
            if (!currentReportData) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Data',
                    text: 'No report data available to export',
                    confirmButtonColor: '#FF6B8B'
                });
                return;
            }

            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF();

            const stats = currentReportData;
            const sales = stats.sales || {};
            const topProducts = stats.top_products || [];
            const ordersByStatus = stats.orders_by_status || {};
            const recentOrders = stats.recent_orders || [];

            // Add title
            doc.setFontSize(18);
            doc.setTextColor(255, 107, 139);
            doc.text("Dimi's Donuts - Business Report", 14, 20);

            // Add date
            doc.setFontSize(10);
            doc.setTextColor(100);
            const today = new Date().toLocaleDateString();
            doc.text(`Generated: ${today}`, 14, 28);

            // Sales Overview Section
            doc.setFontSize(14);
            doc.setTextColor(255, 107, 139);
            doc.text('Sales Overview', 14, 40);

            doc.setFontSize(10);
            doc.setTextColor(0);
            let yPos = 48;
            doc.text(`Total Orders: ${sales.total_orders || 0}`, 14, yPos);
            yPos += 6;
            doc.text(`Total Revenue: ₱${parseFloat(sales.total_sales || 0).toLocaleString()}`, 14, yPos);
            yPos += 6;
            doc.text(`Average Order Value: ₱${parseFloat(sales.average_order_value || 0).toLocaleString()}`, 14, yPos);
            yPos += 6;
            doc.text(`Completed Orders: ${ordersByStatus.delivered || 0}`, 14, yPos);
            yPos += 12;

            // Top Selling Products Section
            doc.setFontSize(14);
            doc.setTextColor(255, 107, 139);
            doc.text('Top Selling Products', 14, yPos);
            yPos += 8;

            if (topProducts.length > 0) {
                const productsData = topProducts.map((product, index) => [
                    (index + 1).toString(),
                    product.product_name,
                    product.total_quantity.toString() + ' units',
                    `₱${parseFloat(product.total_revenue).toLocaleString()}`
                ]);

                doc.autoTable({
                    startY: yPos,
                    head: [
                        ['Rank', 'Product', 'Units Sold', 'Revenue']
                    ],
                    body: productsData,
                    theme: 'grid',
                    headStyles: {
                        fillColor: [255, 107, 139],
                        textColor: 255
                    },
                    alternateRowStyles: {
                        fillColor: [255, 245, 247]
                    },
                    styles: {
                        fontSize: 9
                    }
                });
                yPos = doc.lastAutoTable.finalY + 12;
            } else {
                doc.setFontSize(10);
                doc.setTextColor(100);
                doc.text('No product data available', 14, yPos);
                yPos += 12;
            }

            // Order Status Breakdown
            doc.setFontSize(14);
            doc.setTextColor(255, 107, 139);
            doc.text('Order Status Breakdown', 14, yPos);
            yPos += 8;

            doc.setFontSize(10);
            doc.setTextColor(0);
            doc.text(`Pending: ${ordersByStatus.pending || 0}`, 14, yPos);
            yPos += 6;
            doc.text(`Confirmed: ${ordersByStatus.confirmed || 0}`, 14, yPos);
            yPos += 6;
            doc.text(`Preparing: ${ordersByStatus.preparing || 0}`, 14, yPos);
            yPos += 6;
            doc.text(`Out for Delivery: ${ordersByStatus.out_for_delivery || 0}`, 14, yPos);
            yPos += 6;
            doc.text(`Delivered: ${ordersByStatus.delivered || 0}`, 14, yPos);
            yPos += 6;
            doc.text(`Cancelled: ${ordersByStatus.cancelled || 0}`, 14, yPos);
            yPos += 12;

            // Recent Transactions
            if (yPos > 250 || recentOrders.length > 0) {
                doc.addPage();
                yPos = 20;
            }

            doc.setFontSize(14);
            doc.setTextColor(255, 107, 139);
            doc.text('Recent Transactions', 14, yPos);
            yPos += 8;

            if (recentOrders.length > 0) {
                const transactionsData = recentOrders.slice(0, 15).map(order => [
                    order.order_number,
                    order.customer_name,
                    new Date(order.created_at).toLocaleDateString(),
                    `₱${parseFloat(order.total_amount).toLocaleString()}`,
                    order.order_status.replace('_', ' ').toUpperCase()
                ]);

                doc.autoTable({
                    startY: yPos,
                    head: [
                        ['Order #', 'Customer', 'Date', 'Amount', 'Status']
                    ],
                    body: transactionsData,
                    theme: 'grid',
                    headStyles: {
                        fillColor: [255, 107, 139],
                        textColor: 255
                    },
                    alternateRowStyles: {
                        fillColor: [255, 245, 247]
                    },
                    styles: {
                        fontSize: 9
                    }
                });
            } else {
                doc.setFontSize(10);
                doc.setTextColor(100);
                doc.text('No recent transactions', 14, yPos);
            }

            // Save PDF
            doc.save(`business-report-${new Date().toISOString().split('T')[0]}.pdf`);

            Swal.fire({
                icon: 'success',
                title: 'Report Exported!',
                text: 'Your business report has been downloaded successfully.',
                confirmButtonColor: '#06D6A0'
            });
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadReports();

            // Export PDF
            document.getElementById('exportReportBtn').addEventListener('click', exportReportToPDF);

            // Logout
            document.getElementById('logoutLink').addEventListener('click', (e) => {
                e.preventDefault();
                Swal.fire({
                    title: 'Logout?',
                    text: 'Are you sure you want to logout?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#FF6B8B',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, logout',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'logout.php';
                    }
                });
            });
        });
    </script>
</body>

</html>