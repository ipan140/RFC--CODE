<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

  <div class="wrapper">
    <h1>Login</h1>
    <p id="error-message"></p>
    <form id="loginForm">
        <div>
            <label for="email">
                <span>@</span>
            </label>
            <input type="email" name="email" id="email" placeholder="Email" required>
        </div>
        <div>
            <label for="password">
                <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" aria-label="Password Icon">
                    <path d="M240-80q-33 0-56.5-23.5T160-160v-400q0-33 23.5-56.5T240-640h40v-80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720v80h40q33 0 56.5 23.5T800-560v400q0 33-23.5 56.5T720-80H240Zm240-200q33 0 56.5-23.5T560-360q0-33-23.5-56.5T480-440q-33 0-56.5 23.5T400-360q0 33 23.5 56.5T480-280ZM360-640h240v-80q0-50-35-85t-85-35q-50 0-85 35t-35 85v80Z"/>
                </svg>
            </label>
            <input type="password" name="password" id="password" placeholder="Password" required>
        </div>
        <button type="submit">Login</button>
    </form>
    <p>New here? <a href="/Register">Create an Account</a></p>
</div>

<!-- JavaScript -->
<script>
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
</script>
</body>
<!-- Tombol Login -->
</html>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
:root{
  --accent-color: #8672FF;
  --base-color: white;
  --text-color: #2E2B41;
  --input-color: #F3F0FF;
}
*{
  margin: 0;
  padding: 0;
}
html{
  font-family: Poppins, Segoe UI, sans-serif;
  font-size: 12pt;
  color: var(--text-color);
  text-align: center;
}
body{
  min-height: 100vh;
  background-size: cover;
  background-position: right;
  overflow: hidden;
  background-color: #8672FF;
}
.wrapper{
  box-sizing: border-box;
  background-color: var(--base-color);
  height: 100vh;
  width: max(40%, 600px);
  padding: 10px;
  border-radius: 0 20px 20px 0;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}
h1{
  font-size: 3rem;
  font-weight: 900;
  text-transform: uppercase;
}
form{
  width: min(400px, 100%);
  margin-top: 20px;
  margin-bottom: 50px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 10px;
}
form > div{
  width: 100%;
  display: flex;
  justify-content: center;
}
form label{
  flex-shrink: 0;
  height: 50px;
  width: 50px;
  background-color: var(--accent-color);
  fill: var(--base-color);
  color: var(--base-color);
  border-radius: 10px 0 0 10px;
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 1.5rem;
  font-weight: 500;
}
form input{
  box-sizing: border-box;
  flex-grow: 1;
  min-width: 0;
  height: 50px;
  padding: 1em;
  font: inherit;
  border-radius: 0 10px 10px 0;
  border: 2px solid var(--input-color);
  border-left: none;
  background-color: var(--input-color);
  transition: 150ms ease;
}
form input:hover{
  border-color: var(--accent-color);
}
form input:focus{
  outline: none;
  border-color: var(--text-color);
}
div:has(input:focus) > label{
  background-color: var(--text-color);
}
form input::placeholder{
  color: var(--text-color);
}
form button{
  margin-top: 10px;
  border: none;
  border-radius: 1000px;
  padding: .85em 4em;
  background-color: var(--accent-color);
  color: var(--base-color);
  font: inherit;
  font-weight: 600;
  text-transform: uppercase;
  cursor: pointer;
  transition: 150ms ease;
}
form button:hover{
  background-color: var(--text-color);
}
form button:focus{
  outline: none;
  background-color: var(--text-color);
}
a{
  text-decoration: none;
  color: var(--accent-color);
}
a:hover{
  text-decoration: underline;
}
@media(max-width: 1100px){
  .wrapper{
    width: min(600px, 100%);
    border-radius: 0;
  }
}
form div.incorrect label{
  background-color: #f06272;
}
form div.incorrect input{
  border-color: #f06272;
}
#error-message{
  color:#f06272;
}
</style>