<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Karyawan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body { background-color: #F0E2D2; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { width: 100%; max-width: 400px; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border: none; }
        .login-icon { font-size: 3rem; color: #5B6D92; margin-bottom: 1rem; }
    
        .btn-primary { background-color: #5B6D92 !important; border-color: #5B6D92 !important; }
        .btn-primary:hover { background-color: #4A5978 !important; border-color: #4A5978 !important; }
        .text-primary { color: #5B6D92 !important; }
</style>
</head>
<body>
    <div class="card login-card text-center bg-white">
        <div class="card-body">
            <i class="fa-solid fa-shapes login-icon text-secondary"></i>
            <h3 class="card-title fw-bold text-dark mb-4">ASA JAYA PLAYGROUND</h3>
            
            @if ($errors->any())
                <div class="alert alert-danger text-start py-2" role="alert">
                    <small><i class="fa-solid fa-circle-exclamation"></i> Username atau password salah.</small>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="username" id="username" placeholder="Username" required>
                    <label for="username">Username</label>
                </div>
                <div class="form-floating mb-4 position-relative">
                    <input type="password" class="form-control pe-5" name="password" id="password" placeholder="Password" required>
                    <label for="password">Password</label>
                    <span class="position-absolute top-50 end-0 translate-middle-y me-3 text-secondary" style="cursor: pointer; z-index: 10;" onclick="togglePassword()">
                        <i class="fa-regular fa-eye" id="eyeIcon"></i>
                    </span>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold"><i class="fa-solid fa-right-to-bracket"></i> MASUK</button>
            </form>
            <p class="text-muted mt-4 mb-0" style="font-size: 0.85rem;">Hanya untuk karyawan & manajemen</p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            if (passInput.type === 'password') {
                passInput.type = 'text';
                eyeIcon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passInput.type = 'password';
                eyeIcon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
</body>
</html>