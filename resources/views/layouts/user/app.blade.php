<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - E-Commerce</title>
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    @yield('css')
</head>
<body class="bg-gray-900 text-white">
    <!-- Header -->
    <header class="bg-gray-800 py-6 px-6 flex justify-between items-center flex-wrap gap-3">
        <h1 class="text-2xl font-bold"><a href="/">E-Commerce</a></h1>
        <nav>
            <ul class="flex space-x-6 items-center">
                <li><a href="{{ route('home') }}" class="hover:text-blue-400 {{ request()->routeIs('home') ? 'text-blue-400' : '' }}">Home</a></li>
                <li><a href="{{ route('shop') }}" class="hover:text-blue-400 {{ request()->routeIs('shop') ? 'text-blue-400' : '' }}">Shop</a></li>
                <li class="relative">
                    <a href="{{ route('cart') }}" class="hover:text-blue-400 {{ request()->routeIs('cart') ? 'text-blue-400' : '' }}">
                        <i class="fa fa-shopping-cart"></i>
                        <span id="cart-badge" class="w-5 h-5 flex items-center justify-center absolute -top-2 -right-2 bg-red-500 text-sm text-white p-1 rounded-full hidden">0</span>
                    </a>
                </li>
                @hasPermissionTo(\App\Enums\PermissionsEnum::VIEW_DASHBOARD->value)
                    <li><a href="{{ route('admin.dashboard') }}" class="hover:text-blue-400 {{ request()->routeIs('admin.dashboard') ? 'text-blue-400' : '' }}"><i class="fa fa-user-gear"></i></a></li>
                @endhasPermissionTo
                <li><a href="{{ auth()->user() ? route('profile') : route('login') }}" class="hover:text-blue-400 {{ request()->routeIs('profile') || request()->routeIs('login') ? 'text-blue-400' : '' }}"><i class="fa fa-user"></i></a></li>
                @auth
                    <form action="{{route('logout')}}" method="post">
                        @csrf
                        <button type="submit" class="bg-red-600 text-sm text-white px-3 py-2 rounded">Logout</button>
                    </form>
                @endauth
            </ul>
        </nav>
    </header>

    @yield('content')


    <script src="{{ asset('assets/js/cart.js') }}"></script>
        <script>
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "timeOut": "3500"
            };
        document.addEventListener("DOMContentLoaded", function() {
            @if(session('success'))
                toastr.success("{{ session('success') }}");
            @endif

            @if(session('error'))
                toastr.error("{{ session('error') }}");
            @endif

            @if(session('warning'))
                toastr.warning("{{ session('warning') }}");
            @endif

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    toastr.warning("{{ $error }}");
                @endforeach
            @endif

            @auth
                @if(session('order_success'))
                    clearCart();
                @endif
            @endauth
        });
    </script>
    <script>
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