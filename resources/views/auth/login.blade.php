<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Halaman Login</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;900&display=swap">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    :root {
      --accent-color: #28a745;
      --base-color: #fff;
      --text-color: #2E2B41;
      --input-color: #F3F0FF;
      --error-color: #f06272;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html {
      font-family: 'Poppins', sans-serif;
      font-size: 12pt;
      color: var(--text-color);
    }

    body {
      min-height: 100vh;
      background-color: var(--accent-color);
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
      overflow: auto;
    }

    .wrapper {
      background-color: var(--base-color);
      width: 100%;
      max-width: 500px;
      padding: 40px 20px;
      border-radius: 20px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    h1 {
      font-size: 2.2rem;
      font-weight: 900;
      margin-bottom: 10px;
    }

    form {
      margin-top: 20px;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 18px;
    }

    form > div {
      display: flex;
      width: 100%;
      max-width: 400px;
    }

    form label {
      width: 50px;
      height: 50px;
      background-color: var(--accent-color);
      color: var(--base-color);
      display: flex;
      justify-content: center;
      align-items: center;
      border-radius: 10px 0 0 10px;
      flex-shrink: 0;
      cursor: pointer;
    }

    form input {
      flex: 1;
      height: 50px;
      padding: 0 1em;
      border: 2px solid var(--input-color);
      border-left: none;
      border-radius: 0 10px 10px 0;
      font: inherit;
      background-color: var(--input-color);
      transition: border 0.2s;
    }

    form input:focus {
      outline: none;
      border-color: var(--text-color);
    }

    form div:has(input:focus) label {
      background-color: var(--text-color);
    }

    form button {
      margin-top: 10px;
      padding: 0.85em 4em;
      border: none;
      border-radius: 1000px;
      background-color: var(--accent-color);
      color: var(--base-color);
      font-weight: 600;
      text-transform: uppercase;
      cursor: pointer;
      transition: background 0.2s, transform 0.2s;
    }

    form button:hover,
    form button:focus {
      background-color: var(--text-color);
      transform: scale(1.02);
      outline: none;
    }

    a {
      color: var(--accent-color);
      text-decoration: none;
      font-weight: 500;
    }

    a:hover {
      text-decoration: underline;
    }

    #error-message {
      color: var(--error-color);
      font-size: 0.9rem;
      margin-bottom: 10px;
    }

    @media (max-width: 768px) {
      .wrapper {
        width: 100%;
        padding: 30px 15px;
        border-radius: 15px;
      }

      form > div {
        max-width: 100%;
        flex-direction: row;
      }

      form input,
      form label {
        height: 45px;
      }

      form button {
        width: 100%;
      }
    }
  </style>
</head>

<body>
  <div class="wrapper">
    <h1>Login</h1>

    @if ($errors->any())
      <div id="error-message">
        @foreach ($errors->all() as $error)
          <p>{{ $error }}</p>
        @endforeach
      </div>
    @endif

    <form method="POST" action="{{ route('login') }}" id="loginForm">
      @csrf
      <div>
        <label for="email">
          <span>@</span>
        </label>
        <input type="email" name="email" id="email" placeholder="Email" required>
      </div>
      <div>
        <label for="password">
          <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="white">
            <path d="M240-80q-33 0-56.5-23.5T160-160v-400q0-33 23.5-56.5T240-640h40v-80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720v80h40q33 0 56.5 23.5T800-560v400q0 33-23.5 56.5T720-80H240Zm240-200q33 0 56.5-23.5T560-360q0-33-23.5-56.5T480-440q-33 0-56.5 23.5T400-360q0 33 23.5 56.5T480-280ZM360-640h240v-80q0-50-35-85t-85-35q-50 0-85 35t-35 85v80Z" />
          </svg>
        </label>
        <input type="password" name="password" id="password" placeholder="Password" required>
      </div>
      <button type="submit">Login</button>
    </form>
<br>
    <p>Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a></p>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- <script>
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            }
        });

        $("#loginForm").submit(function (event) {
            event.preventDefault();

            let email = $("#email").val().trim();
            let password = $("#password").val().trim();

            // Validasi input
            if (email === "" || password === "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Login Gagal!',
                    text: 'Email dan password tidak boleh kosong!',
                });
                return;
            }

            $.ajax({
                url: "/api/login",
                type: "POST",
                data: {
                    email: email,
                    password: password
                },
                success: function (response) {
                    if (response.token) {
                        localStorage.setItem("auth_token", response.token);
                        localStorage.setItem("user", JSON.stringify(response.user));

                        Swal.fire({
                            icon: 'success',
                            title: 'Login Berhasil!',
                            text: 'Mengarahkan ke dashboard...',
                            showConfirmButton: false,
                            timer: 2000
                        });

                        setTimeout(() => {
                            window.location.href = "/dashboard";
                        }, 2000);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Login Gagal!',
                            text: 'Token tidak diterima, periksa API!',
                        });
                    }
                },
                error: function (xhr) {
                    let errorMessage = "Login gagal! Periksa kembali email dan password.";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Login Gagal!',
                        text: errorMessage,
                    });
                }
            });
        });
    });
  </script> -->
</body>

</html>
