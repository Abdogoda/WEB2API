<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/png">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://www.google.com/recaptcha/api.js"></script>
</head>
<body class="bg-gray-900 text-white flex items-center justify-center h-screen">
  <div class="w-full max-w-md p-8 space-y-6 bg-gray-800 rounded-lg shadow-lg">
    <h2 class="text-3xl font-bold text-center">Login Form</h2>
    @session('error')
        <div class="bg-red-500 text-white rounded p-3 my-1">{{session("error")}}</div>
    @enderror
    @session('success')
        <div class="bg-green-500 text-white rounded p-3 my-1">{{session("success")}}</div>
    @enderror
    <form action="{{url("login")}}" method="POST" class="space-y-4" id="login-form">
      @csrf
      <div>
        <label for="email" class="block mb-2 text-sm font-medium">Email</label>
        <input type="text" id="email" name="email" autocomplete="off" autofocus class="w-full p-3 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
        @error('email')
            <span class="text-red-500 text-sm mt-1">{{$message}}</span>
        @enderror
      </div>
      <div>
        <label for="password" class="block mb-2 text-sm font-medium">Password</label>
        <input type="password" id="password" name="password" autocomplete="current-password" class="w-full p-3 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
        @error('password')
            <span class="text-red-500 text-sm mt-1">{{$message}}</span>
        @enderror
      </div>
      <p class="mt-4 text-sm">Forgot your passsword? <a href="{{route("password.request")}}" class="text-blue-400 hover:underline">Reset now</a></p>
      
      <div class="flex items-center mb-4">
          <input type="checkbox" name="remember" id="remember">
          <label for="remember" class="block text-gray-300 ml-1">Remember Me</label>
          @error('remember')
              <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
          @enderror
      </div>
      
      <button type="submit" class="w-full py-3 mt-4 bg-blue-600 rounded-lg font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Login</button>
      
      <p class="mt-4 text-sm text-center">Don’t have an account? <a href="{{route("register")}}" class="text-blue-400 hover:underline">Register</a></p>
    </form>
  </div>
</body>
</html>
