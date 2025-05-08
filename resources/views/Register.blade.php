<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Signup</title>
  <link rel="stylesheet" href="style.css">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="wrapper">
    <h1>Register</h1>
    <p id="error-message"></p>
    <form id="registerForm" novalidate>
        <div class="input-group">
            <label for="name"><span>@</span></label>
            <input type="text" name="name" id="name" placeholder="Full Name" required autocomplete="name">
        </div>

        <div class="input-group">
            <label for="email"><span>@</span></label>
            <input type="email" name="email" id="email" placeholder="Email" required autocomplete="email">
        </div>

        <div class="input-group">
            <label for="password">
            <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="white">
              <path d="M240-80q-33 0-56.5-23.5T160-160v-400q0-33 23.5-56.5T240-640h40v-80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720v80h40q33 0 56.5 23.5T800-560v400q0 33-23.5 56.5T720-80H240Zm240-200q33 0 56.5-23.5T560-360q0-33-23.5-56.5T480-440q-33 0-56.5 23.5T400-360q0 33 23.5 56.5T480-280ZM360-640h240v-80q0-50-35-85t-85-35q-50 0-85 35t-35 85v80Z"/>
            </svg>
            </label>
            <input type="password" name="password" id="password" placeholder="Password" required autocomplete="new-password">
        </div>

        <div class="input-group">
            <label for="password_confirmation">
            <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="white">
              <path d="M240-80q-33 0-56.5-23.5T160-160v-400q0-33 23.5-56.5T240-640h40v-80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720v80h40q33 0 56.5 23.5T800-560v400q0 33-23.5 56.5T720-80H240Zm240-200q33 0 56.5-23.5T560-360q0-33-23.5-56.5T480-440q-33 0-56.5 23.5T400-360q0 33 23.5 56.5T480-280ZM360-640h240v-80q0-50-35-85t-85-35q-50 0-85 35t-35 85v80Z"/>
            </svg>
            </label>
            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Repeat Password" required autocomplete="new-password">
        </div>

        <div class="input-group">
          <label for="profile_picture">📷</label>
          <input type="file" name="profile_picture" id="profile_picture" required>
        </div>
        <div class="role-selection">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="role" id="userRole" value="user">
                I agree all statements in Terms of service
            </div>
        </div>
        <button type="submit">Register</button>
    </form>

    <p>Sudah punya akun? <a href="login">Login</a></p>
</div>

<script>
  $(document).ready(function() {
      $.ajaxSetup({
          headers: {
              "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
          }
      });

      $("#registerForm").submit(function(event) {
          event.preventDefault();

          let name = $("#name").val();
          let email = $("#email").val();
          let password = $("#password").val();
          let password_confirmation = $("#password_confirmation").val();
          let role = $('input[name="role"]:checked').val(); // Ambil nilai dari radio button role

          if (!role) {
              Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text: 'I agree all statements in Terms of service.',
              });
              return;
          }

          if (password !== password_confirmation) {
              Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text: 'Password tidak cocok!',
              });
              return;
          }

          let profile_picture = $("#profile_picture")[0].files[0];
          let formData = new FormData();
          formData.append("name", name);
          formData.append("email", email);
          formData.append("password", password);
          formData.append("password_confirmation", password_confirmation);
          formData.append("role", role);
          if (profile_picture) {
              formData.append("profile_picture", profile_picture);
          }

          $.ajax({
              url: "/api/register",
              type: "POST",
              data: formData,
              processData: false,
              contentType: false,
              success: function(response) {
                  Swal.fire({
                      icon: 'success',
                      title: 'Registrasi Berhasil!',
                      text: 'Silakan login.',
                      timer: 2000,
                      showConfirmButton: false
                  });

                  setTimeout(() => {
                      window.location.href = "/login";
                  }, 2000);
              },
              error: function(xhr) {
                  let errorMessage = "Terjadi kesalahan, coba lagi!";
                  if (xhr.responseJSON && xhr.responseJSON.errors) {
                      errorMessage = Object.values(xhr.responseJSON.errors)
                          .map(error => error.join(", "))
                          .join("\n");
                  }
                  Swal.fire({
                      icon: 'error',
                      title: 'Registrasi Gagal!',
                      text: errorMessage,
                  });
              }
          });
      });
  });
</script>

</body>
</html>

<style>
      @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;900&display=swap');

:root {
  --accent-color: #8672FF;
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

form div.incorrect label {
  background-color: var(--error-color);
}

form div.incorrect input {
  border-color: var(--error-color);
}

.role-selection {
  width: 100%;
  display: flex;
  justify-content: center;
  font-size: 0.9rem;
  padding: 5px 10px;
  color: #555;
}

.form-check {
  display: flex;
  align-items: center;
  gap: 8px;
}

/* Tambahan styling khusus input file */
input[type="file"] {
  padding: 0.7em 1em;
  font: inherit;
  background-color: var(--input-color);
  border: 2px solid var(--input-color);
  border-left: none;
  border-radius: 0 10px 10px 0;
  height: 50px;
  display: flex;
  align-items: center;
  cursor: pointer;
}

input[type="file"]::file-selector-button {
  display: none;
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

  input[type="file"] {
    height: 45px;
  }

  form button {
    width: 100%;
  }
}
    </style>