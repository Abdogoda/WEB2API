@extends('layouts.user.app')

@section('title', 'My Profile')
@section('style')
@endsection

@section('content')
    <section class="pt-12 px-6">
        <h2 class="text-3xl font-bold text-center mb-8 relative group text-gray-800 transition-all duration-300 hover:text-blue-600 w-fit mx-auto pb-3">
            My Profile
            <span class="absolute bottom-[-6px] left-1/2 -translate-x-1/2 w-1/2 h-1 bg-blue-600 transition-all duration-300 group-hover:w-full group-hover:-translate-x-1/2"></span>
        </h2>
        <div class="bg-gray-800 rounded-lg shadow-md mb-4">
            <div class="text-sm font-medium text-center text-gray-500 border-gray-700">
                <ul class="flex flex-wrap -mb-px">
                    <li class="me-2">
                        <button onclick="showTab('profileTab')" class="tab-button inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-400 hover:border-gray-600 dark:hover:text-gray-300">Update Profile</button>
                    </li>
                    <li class="me-2">
                        <button onclick="showTab('passwordTab')" class="tab-button inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-400 hover:border-gray-600 dark:hover:text-gray-300">Change Password</button>
                    </li>
                    <li class="me-2">
                        <button onclick="showTab('securityTab')" class="tab-button inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-400 hover:border-gray-600 dark:hover:text-gray-300">Account & Security</button>
                    </li>
                    <li class="me-2">
                        <button onclick="showTab('ordersTab')" class="tab-button inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-400 hover:border-gray-600 dark:hover:text-gray-300">My Orders</button>
                    </li>
                </ul>
            </div>
        </div>

        <div class="bg-gray-800 rounded-lg shadow-md p-4">
            <div id="profileTab" class="tab-content">
                <div class="flex gap-3 mb-4">
                    <h2 class="text-xl font-semibold">📝 Update Profile</h2>
                    @if(auth()->user()->hasVerifiedEmail())
                        <button class="bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded-md">Verified</button>
                    @else
                        <button onclick="openModal('verifyAccountModal')" class="bg-red-500 text-white text-xs font-semibold px-2 py-1 rounded-md">Not Verified</button>
                    @endif
                </div>
                <form action="{{ url('profile') }}" method="POST">
                    @csrf
                    @method('put')
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="mb-4">
                            <label for="name" class="block text-gray-300">Name</label>
                            <input type="text" id="name" name="name" value="{{auth()->user()->name}}" autofocus autocomplete="off" class="w-full p-3 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('name')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-gray-300">Email</label>
                            <input type="text" id="email" name="email" value="{{auth()->user()->email}}" autocomplete="off" class="w-full p-3 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('email')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label for="phone" class="block text-gray-300">Phone</label>
                            <input type="text" id="phone" name="phone" value="{{auth()->user()->phone}}" autocomplete="off" class="w-full p-3 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('phone')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="country" class="block text-gray-300">Country</label>
                            <input type="text" id="country" name="country" value="{{auth()->user()->country}}" autofocus autocomplete="off" class="w-full p-3 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('country')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label for="state" class="block text-gray-300">State</label>
                            <input type="text" id="state" name="state" value="{{auth()->user()->state}}" autocomplete="off" class="w-full p-3 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('state')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label for="city" class="block text-gray-300">City</label>
                            <input type="text" id="city" name="city" value="{{auth()->user()->city}}" autocomplete="off" class="w-full p-3 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('city')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="my-4">
                        <label for="address" class="block text-gray-300">Address In Details</label>
                        <textarea type="text" id="address" name="address" autocomplete="off" class="w-full p-3 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">{{auth()->user()->address}}</textarea>
                        @error('address')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update Profile</button>
                </form>
            </div>

            <div id="passwordTab" class="tab-content hidden">
                <h2 class="text-xl font-semibold mb-4">🔐 Change Password</h2>
                <form action="{{url('change-password')}}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="current_password" class="block text-gray-300">Current Password</label>
                        <input type="password" id="current_password" name="current_password" autofocus autocomplete="current-password" class="w-full p-3 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('current_password')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="new_password" class="block text-gray-300">New Password</label>
                        <input type="password" id="new_password" name="new_password" autocomplete="new-password" class="w-full p-3 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('new_password')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="new_password_confirmation" class="block text-gray-300">Confirm New Password</label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="w-full p-3 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Change Password</button>
                </form>
            </div>

            <div id="securityTab" class="tab-content hidden">
                <h2 class="text-xl font-semibold mb-4">🛡 Account & Security</h2>

                <div class="bg-gray-700 p-4 rounded-md shadow mb-4">
                    <h3 class="text-lg font-semibold text-gray-200">Logout from Other Devices</h3>
                    <p class="text-gray-400 text-sm mt-1">If you've logged in from another device and want to secure your account, you can log out from all devices except the current one.</p>
                    <button onclick="openModal('logoutOtherDevicesModal')" class="bg-red-600 text-white px-4 py-2 rounded mt-3">
                        Logout from Other Devices
                    </button>
                </div>  

                <div class="bg-gray-700 p-4 rounded-md shadow">
                    <h3 class="text-lg font-semibold text-red-500">Delete Account</h3>
                    <p class="text-gray-400 text-sm mt-1">Once you delete your account, there is no going back. Please be certain before proceeding.</p>
                    <button onclick="openModal('deleteAccountModal')" class="bg-red-600 text-white px-4 py-2 rounded mt-3">
                        Delete My Account
                    </button>
                </div>
            </div>

            <div id="ordersTab" class="tab-content hidden">
                <h2 class="text-xl font-semibold mb-4">📦 My Orders</h2>

                @if ($orders->isEmpty())
                    <p class="text-gray-400">You have no orders yet.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full border border-gray-700 text-gray-300">
                            <thead class="bg-gray-800 border-b border-gray-700 text-white">
                                <tr>
                                    <th class="py-2 px-4 text-left">#</th>
                                    <th class="py-2 px-4 text-left">Order Number</th>
                                    <th class="py-2 px-4 text-left">Date</th>
                                    <th class="py-2 px-4 text-left">Total</th>
                                    <th class="py-2 px-4 text-left">Status</th>
                                    <th class="py-2 px-4 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr class="border-b border-gray-700 hover:bg-gray-800 transition">
                                        <td class="p-4">{{ $loop->iteration }}</td>
                                        <td class="p-4 font-bold">#{{ $order->order_number }}</td>
                                        <td class="p-4">{{ $order->created_at->format('M d, Y h:i A') }}</td>
                                        <td class="p-4 text-green-400">${{ number_format($order->total_amount, 2) }}</td>
                                        <td class="p-4">
                                            <span class="px-2 py-1 text-white text-sm rounded 
                                                {{ $order->statuses->first()->status == 'pending' ? 'bg-yellow-600' : 
                                                ($order->statuses->first()->status == 'processing' ? 'bg-blue-600' : 
                                                ($order->statuses->first()->status == 'completed' ? 'bg-green-600' : 'bg-red-600')) }}">
                                                {{ ucfirst($order->statuses->first()->status) }}
                                            </span>
                                        </td>
                                        <td class="py-2 px-4">
                                            <a href="{{ route('orders.show', $order->order_number) }}" class="bg-blue-400 hover:bg-blue-300 text-gray-900 px-4 py-2 rounded">View Details</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </section>

    <div id="verifyAccountModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center">
        <div class="bg-gray-800 p-6 rounded shadow-md w-96">
            <h3 class="text-xl font-bold mb-4">Verify Account?</h3>
            <p>We will send you an OTP to your email in order to verify your account!</p>
            <form id="deleteRoleForm" action="{{ route('verification.send')  }}" method="POST">
                @csrf
                <div class="flex justify-end space-x-2 mt-4">
                    <button type="button" onclick="closeModal('verifyAccountModal')" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Verify</button>
                </div>
            </form>
        </div>
    </div>

    <div id="logoutOtherDevicesModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-gray-900 p-6 rounded-md shadow-lg w-96">
            <h3 class="text-lg font-semibold text-red-500 mb-2">Confirm Logout</h3>
            <p class="text-gray-400 text-sm">Are you sure you want to logout from other devices?</p>

            <form action="{{ route('logout-other-devices') }}" method="POST" class="mt-4">
                @csrf
                <label for="password" class="block text-gray-300 mb-1">Password</label>
                <input type="password" id="password" name="password" class="w-full p-3 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:ring-2 focus:ring-red-500">
                
                <div class="mt-4 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('logoutOtherDevicesModal')" class="bg-gray-600 text-white px-4 py-2 rounded">Cancel</button>
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Logout</button>
                </div>
            </form>
        </div>
    </div>

    <div id="deleteAccountModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-gray-900 p-6 rounded-md shadow-lg w-96">
            <h3 class="text-lg font-semibold text-red-500 mb-2">Confirm Account Deletion</h3>
            <p class="text-gray-400 text-sm">Enter your password to confirm deletion.</p>

            <form action="{{ route('delete-account') }}" method="POST" class="mt-4">
                @csrf
                <label for="password" class="block text-gray-300 mb-1">Password</label>
                <input type="password" id="password" name="password" class="w-full p-3 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:ring-2 focus:ring-red-500">
                
                <div class="mt-4 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('deleteAccountModal')" class="bg-gray-600 text-white px-4 py-2 rounded">Cancel</button>
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Delete</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const activeTab = localStorage.getItem('activeTab') || 'profileTab';
            showTab(activeTab);
        });
        function showTab(tabId) {
            const tabs = document.querySelectorAll('.tab-content');
            tabs.forEach(tab => {
                tab.classList.add('hidden');
            });
            const activeTabLink = document.querySelectorAll('button.tab-button');
            activeTabLink.forEach(link => {
                link.classList.remove('border-blue-500', 'text-blue-500');
            });
            document.getElementById(tabId).classList.remove('hidden');
            const activeLink = document.querySelector(`button.tab-button[onclick="showTab('${tabId}')"]`);
            activeLink.classList.add('border-blue-500', 'text-blue-500');
            localStorage.setItem('activeTab', tabId);
        }
    </script>
@endsection
