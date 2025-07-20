<x-layout>
    <main class="flex-1 flex flex-col items-center justify-center px-4">
        <div class="w-full max-w-md mt-12 mb-12 bg-white p-8 rounded-xl shadow-lg">
            <h2 class="text-2xl font-bold text-center text-orange-600 mb-6">Create Account</h2>
            @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-center">
                {{ session('success') }}
            </div>
            @endif
            @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-center">
                {{ $errors->first() }}
            </div>
            @endif
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="block font-medium text-indigo-600">Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                        class="mt-1 w-full px-4 py-2 border border-indigo-200 rounded focus:outline-none focus:ring-2 focus:ring-indigo-400" />
                </div>
                <div>
                    <label for="email" class="block font-medium text-indigo-600">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required
                        class="mt-1 w-full px-4 py-2 border border-indigo-200 rounded focus:outline-none focus:ring-2 focus:ring-indigo-400" />
                </div>
                <div>
                    <label for="password" class="block font-medium text-indigo-600">Password</label>
                    <input id="password" name="password" type="password" required
                        class="mt-1 w-full px-4 py-2 border border-indigo-200 rounded focus:outline-none focus:ring-2 focus:ring-indigo-400" />
                </div>
                <div>
                    <label for="password_confirmation" class="block font-medium text-indigo-600">Confirm
                        Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                        class="mt-1 w-full px-4 py-2 border border-indigo-200 rounded focus:outline-none focus:ring-2 focus:ring-indigo-400" />
                </div>
                <button type="submit"
                    class="w-full py-2 bg-gradient-to-r from-indigo-500 to-indigo-800 text-white font-semibold rounded hover:from-indigo-600 hover:to-indigo-900 transition">Register</button>
            </form>
        </div>
    </main>
</x-layout>