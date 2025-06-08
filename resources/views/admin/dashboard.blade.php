@extends('layouts.admin.app')

@section('title', 'Dashboard')

@section('content')
    
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-3xl font-bold">Dashboard</h2>
    </div>

     <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-6">
        <x-dashboard-card title="Total Orders" count="{{ $totalOrders }}" color="blue"/>
        <x-dashboard-card title="Total Products" count="{{ $totalProducts }}" color="green"/>
        <x-dashboard-card title="Categories" count="{{ $totalCategories }}" color="purple"/>
        <x-dashboard-card title="Total Users" count="{{ $totalUsers }}" color="yellow"/>
        <x-dashboard-card title="Verified Users" count="{{ $verifiedUsers }}" color="cyan"/>
        <x-dashboard-card title="Messages" count="{{ $totalMessages }}" color="orange"/>
    </div>

    <!-- Charts Section -->
    <div class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-gray-800 p-4 rounded-lg">
            <h2 class="text-lg text-white mb-3">Orders Per Month</h2>
            <canvas id="ordersChart"></canvas>
        </div>

        <div class="bg-gray-800 p-4 rounded-lg">
            <h2 class="text-lg text-white mb-3">Products Per Category</h2>
            <canvas id="categoriesChart"></canvas>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Orders per month
    const ordersChart = new Chart(document.getElementById('ordersChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($ordersByMonth->toArray())) !!},
            datasets: [{
                label: 'Orders',
                data: {!! json_encode(array_values($ordersByMonth->toArray())) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.6)'
            }]
        }
    });

    // Products per category
    const categoriesChart = new Chart(document.getElementById('categoriesChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($productsByCategory->pluck('name')) !!},
            datasets: [{
                data: {!! json_encode($productsByCategory->pluck('products_count')) !!},
                backgroundColor: ['#ff6384', '#36a2eb', '#ffcd56', '#4bc0c0', '#9966ff']
            }]
        }
    });
</script>
@endsection