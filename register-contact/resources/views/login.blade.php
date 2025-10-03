@extends('layouts.guest')

@section('content')
    <h1 class="text-2xl font-semibold mb-4">Login</h1>

    <form id="loginForm" class="space-y-4">
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input id="email" name="email" type="email" required class="mt-1 block w-full border rounded px-3 py-2" />
        </div>
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input id="password" name="password" type="password" required class="mt-1 block w-full border rounded px-3 py-2" />
        </div>
        <button type="submit" style="background-color: #007bff; margin-top: 10px;" class="w-full bg-blue-600 text-white px-4 py-2 rounded">Login</button>
        <p id="error" class="text-red-600 text-sm mt-2 hidden"></p>
    </form>

    <script>
        const form = document.getElementById('loginForm');
        const errorEl = document.getElementById('error');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            errorEl.classList.add('hidden');
            errorEl.textContent = '';

            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;

            try {
                const res = await fetch('/api/auth/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ email, password })
                });

                if (!res.ok) {
                    const data = await res.json().catch(() => ({}));
                    const message = (data && (data.message || (data.errors && Object.values(data.errors).flat().join(' ')))) || 'Login failed';
                    throw new Error(message);
                }

                const data = await res.json();
                const token = data.access_token;
                if (!token) throw new Error('No token returned');

                localStorage.setItem('access_token', token);
              
                // Optional: simple redirect after login
                window.location.href = '/';
            } catch (err) {
                errorEl.textContent = err.message || 'Login failed';
                errorEl.classList.remove('hidden');
            }
        });
    </script>
@endsection


