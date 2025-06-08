<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'E-Commerce')</title>
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/all.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    @yield('style')
</head>
<body class="bg-gray-900 text-white flex">

    <!-- Sidebar -->
    <div id="sidebar" class="bg-gray-800 w-64 min-h-screen p-5 fixed transition-transform transform -translate-x-full md:translate-x-0">
        <h2 class="text-xl font-bold text-white mb-6">E-Commerce</h2>
        <ul class="space-y-4 flex-grow">
            <li><a href="{{ route('admin.dashboard') }}" class="block p-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : '' }}"><i class="fa fa-chart-line text-blue-600 pr-1"></i> Dashboard</a></li>
            <li><a href="{{ route('admin.roles.index') }}" class="block p-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.roles.index') ? 'bg-gray-700' : '' }}"><i class="fa fa-user-shield text-blue-600 pr-1"></i> Roles</a></li>
            <li><a href="{{ route('admin.users.index') }}" class="block p-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.users.index') ? 'bg-gray-700' : '' }}"><i class="fa fa-users text-blue-600 pr-1"></i> Users</a></li>
            <li><a href="{{ route('admin.categories.index') }}" class="block p-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.categories.index') ? 'bg-gray-700' : '' }}"><i class="fa fa-list text-blue-600 pr-1"></i> Categories</a></li>
            <li><a href="{{ route('admin.products.index') }}" class="block p-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.products.index') ? 'bg-gray-700' : '' }}"><i class="fa fa-box text-blue-600 pr-1"></i> Products</a></li>
            <li><a href="{{ route('admin.orders.index') }}" class="block p-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.orders.index') ? 'bg-gray-700' : '' }}"><i class="fa fa-list-check text-blue-600 pr-1"></i> Orders</a></li>
            <li><a href="{{ route('admin.messages.index') }}" class="block p-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.messages.index') ? 'bg-gray-700' : '' }}"><i class="fa fa-envelope text-blue-600 pr-1"></i> Messages</a></li>
            
            <hr class="border-gray-600 my-4">
            <li><a href="{{ route('profile') }}" class="block p-2 rounded hover:bg-gray-700 {{ request()->routeIs('profile') ? 'bg-gray-700' : '' }}"><i class="fa fa-user text-blue-600 pr-1"></i> Profile</a></li>
            <li><form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                        class="block w-full text-left p-2 rounded bg-red-600 hover:bg-red-700">
                    <i class="fa fa-sign-out-alt"></i> Logout
                </button>
            </form></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="flex-1 md:ml-64 p-6">
        <button id="toggleSidebar" class="md:hidden text-white mb-4 bg-gray-700 p-2 rounded">
            <i class="fa fa-bars"></i>
        </button>
        
        <div class="container mx-auto mt-10">
            @yield('content')
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "timeOut": "3500"
            };
            
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    toastr.warning("{{ $error }}");
                @endforeach
            @endif

            @if(session('success'))
                toastr.success("{{ session('success') }}");
            @endif

            @if(session('error'))
                toastr.error("{{ session('error') }}");
            @endif

            @if(session('warning'))
                toastr.warning("{{ session('warning') }}");
            @endif
        });
    </script>
    <script>
        const sidebar = document.getElementById("sidebar");
        const toggleSidebar = document.getElementById("toggleSidebar");

        toggleSidebar.addEventListener("click", (event) => {
            sidebar.classList.toggle("-translate-x-full");
        });

        document.addEventListener("click", (event) => {
            if (!sidebar.contains(event.target) && !toggleSidebar.contains(event.target)) {
                sidebar.classList.add("-translate-x-full");
            }
        });
        


        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }
    </script>

    @yield('script')

</body>
</html>
