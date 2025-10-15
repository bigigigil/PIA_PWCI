<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body{
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            min-height: 100vh;
            background-image: linear-gradient(rgba(48, 51, 61, 0.7), rgba(48, 51, 61, 0.7)), url('images/registro/inicio6.png');
            background-size: cover;
            background-position: center;
            padding: 20px 0;
        }

        .register-box {
            position: relative;
            width: 450px;
            background: transparent;
            border: 2px solid rgba(255,255,255,0.5);
            border-radius: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(15px);
            padding: 40px;
            margin: 20px 0;
        }

        h2 {
            font-size: 2em;
            color: #fff;
            text-align: center;
            margin-bottom: 30px;
        }

        .photo-upload {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 30px;
        }

        .photo-preview {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 3px solid rgba(255,255,255,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin-bottom: 15px;
            background: rgba(255,255,255,0.1);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .photo-preview:hover {
            border-color: rgba(255,255,255,0.8);
            transform: scale(1.05);
        }

        .photo-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .photo-placeholder {
            color: rgba(255,255,255,0.7);
            font-size: 0.8em;
            text-align: center;
        }

        .photo-upload input[type="file"] {
            display: none;
        }

        .photo-upload label {
            color: #fff;
            font-size: 0.9em;
            cursor: pointer;
            padding: 8px 16px;
            border: 1px solid rgba(255,255,255,0.5);
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .photo-upload label:hover {
            background: rgba(255,255,255,0.1);
            border-color: rgba(255,255,255,0.8);
        }

        .input-box {
            position: relative;
            width: 350px;
            margin: 20px 0;
            border-bottom: 2px solid #fff;
        }

        .input-box label {
            position: absolute;
            top: 50%;
            left: 5px;
            transform: translateY(-50%);
            font-size: 1em;
            color: #fff;
            pointer-events: none;
            transition: 0.3s ease;
        }

        .input-box input:focus~label, 
        .input-box input:valid~label,
        .input-box input:not(:placeholder-shown)~label {
            top: -5px;
            font-size: 0.8em;
        }

        .input-box input {
            width: 100%;
            height: 50px;
            background: transparent;
            border: none;
            outline: none;
            font-size: 1em;
            color: #fff;
            padding: 0 8px 0 5px;
        }

        .input-box input[type="date"] {
            color: #fff;
            font-family: 'Poppins', sans-serif;
        }

        .input-box input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
            cursor: pointer;
        }

        .input-box input::placeholder {
            color: transparent;
        }

        .input-box input.error {
            border-bottom-color: #ff4757;
        }

        .input-box input.success {
            border-bottom-color: #2ed573;
        }

        .error-message {
            color: #ff4757;
            font-size: 0.8em;
            margin-top: 5px;
            display: none;
            position: absolute;
            left: 5px;
        }

        .error-message.show {
            display: block;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        button {
            width: 100%;
            height: 45px;
            background: #fff;
            border: none;
            outline: none;
            border-radius: 40px;
            cursor: pointer;
            font-size: 1em;
            color: #000;
            font-weight: 500;
            margin-top: 25px;
            transition: all 0.3s ease;
        }

        button:hover {
            background: #f1f1f1;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 255, 255, 0.3);
        }

        button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .success-message {
            background: rgba(46, 213, 115, 0.2);
            color: #2ed573;
            padding: 15px;
            border-radius: 10px;
            margin-top: 15px;
            display: none;
            text-align: center;
            border: 1px solid rgba(46, 213, 115, 0.5);
            backdrop-filter: blur(10px);
        }

        .success-message.show {
            display: block;
            animation: slideDown 0.3s ease;
        }

        .login-link {
            font-size: .9em;
            color: #fff;
            text-align: center;
            margin: 25px 0 10px;
        }

        .login-link p a{
            color: #fff;
            text-decoration: none;
            font-weight: 600;
        }

        .login-link p a:hover{
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .register-box {
                width: 95%;
                padding: 30px 20px;
            }
            
            .input-box {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="register-box">
        <form id="registerForm">
            <h2>Registro</h2>
            
            <div class="photo-upload">
                <div class="photo-preview" onclick="document.getElementById('photo').click()">
                    <div class="photo-placeholder" id="photoPlaceholder">
                        Clic para agregar foto
                    </div>
                </div>
                <input type="file" id="photo" accept="image/*">
                <label for="photo">Seleccionar foto</label>
            </div>

            <div class="input-box">
                <input type="text" id="fullname" name="fullname" required>
                <label for="fullname">Nombre Completo</label>
                <div class="error-message" id="fullnameError">El nombre completo es obligatorio</div>
            </div>

            <div class="input-box">
                <input type="text" id="username" name="username" required>
                <label for="username">Nombre de Usuario</label>
                <div class="error-message" id="usernameError">El nombre de usuario es obligatorio</div>
            </div>

            <div class="input-box">
                <input type="email" id="email" name="email" required>
                <label for="email">Correo Electrónico</label>
                <div class="error-message" id="emailError">Ingresa un email válido</div>
            </div>

            <div class="input-box">
                <input type="password" id="password" name="password" required>
                <label for="password">Contraseña</label>
                <div class="error-message" id="passwordError">La contraseña debe tener al menos 6 caracteres</div>
            </div>

            <div class="input-box">
                <input type="date" id="birthdate" name="birthdate" required>
                <label for="birthdate">Fecha de Nacimiento</label>
                <div class="error-message" id="birthdateError">La fecha de nacimiento es obligatoria</div>
            </div>

            <button type="submit" id="registerBtn">Crear Cuenta</button>
            
            <div class="success-message" id="successMessage">
                ¡Registro exitoso! 🎉 Redirigiendo...
            </div>

            <div class="login-link">
                <p><a href="log-In.html">¿Ya tienes cuenta? Inicia sesión</a></p>
            </div>
        </form>
    </div>

    <script>
        const form = document.getElementById('registerForm');
        const fullname = document.getElementById('fullname');
        const username = document.getElementById('username');
        const email = document.getElementById('email');
        const password = document.getElementById('password');
        const birthdate = document.getElementById('birthdate');
        const photo = document.getElementById('photo');
        const registerBtn = document.getElementById('registerBtn');
        const successMessage = document.getElementById('successMessage');
        const photoPlaceholder = document.getElementById('photoPlaceholder');

        
        photo.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const photoPreview = document.querySelector('.photo-preview');
                    photoPreview.innerHTML = `<img src="${e.target.result}" alt="Vista previa">`;
                };
                reader.readAsDataURL(file);
            }
        });

        
        function validateField(input, condition, errorId) {
            const errorElement = document.getElementById(errorId);
            
            if (!condition) {
                input.classList.add('error');
                input.classList.remove('success');
                errorElement.classList.add('show');
                return false;
            } else {
                input.classList.remove('error');
                input.classList.add('success');
                errorElement.classList.remove('show');
                return true;
            }
        }

        
        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        
        function isValidAge(birthdate) {
            const today = new Date();
            const birth = new Date(birthdate);
            const age = today.getFullYear() - birth.getFullYear();
            const monthDiff = today.getMonth() - birth.getMonth();
            
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
                return age - 1 >= 13;
            }
            return age >= 13;
        }

        
        fullname.addEventListener('input', function() {
            validateField(fullname, fullname.value.trim() !== '', 'fullnameError');
        });

        username.addEventListener('input', function() {
            validateField(username, username.value.trim() !== '', 'usernameError');
        });

        email.addEventListener('input', function() {
            const isValid = email.value.trim() !== '' && isValidEmail(email.value);
            validateField(email, isValid, 'emailError');
        });

        password.addEventListener('input', function() {
            validateField(password, password.value.length >= 6, 'passwordError');
        });

        birthdate.addEventListener('change', function() {
            const isValid = birthdate.value !== '' && isValidAge(birthdate.value);
            const errorElement = document.getElementById('birthdateError');
            
            if (!isValid) {
                if (birthdate.value === '') {
                    errorElement.textContent = 'La fecha de nacimiento es obligatoria';
                } else {
                    errorElement.textContent = 'Debes ser mayor de 13 años';
                }
            }
            
            validateField(birthdate, isValid, 'birthdateError');
        });

       
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            
            const isFullnameValid = validateField(fullname, fullname.value.trim() !== '', 'fullnameError');
            const isUsernameValid = validateField(username, username.value.trim() !== '', 'usernameError');
            const isEmailValid = validateField(email, email.value.trim() !== '' && isValidEmail(email.value), 'emailError');
            const isPasswordValid = validateField(password, password.value.length >= 6, 'passwordError');
            const isBirthdateValid = validateField(birthdate, birthdate.value !== '' && isValidAge(birthdate.value), 'birthdateError');

            
            if (isFullnameValid && isUsernameValid && isEmailValid && isPasswordValid && isBirthdateValid) {
                registerBtn.disabled = true;
                registerBtn.textContent = 'Creando cuenta...';

                setTimeout(() => {
                    successMessage.classList.add('show');
                    
                   
                    console.log('Datos de registro:', {
                        photo: photo.files[0] || null,
                        fullname: fullname.value,
                        username: username.value,
                        email: email.value,
                        password: password.value,
                        birthdate: birthdate.value
                    });

                   
                    setTimeout(() => {
                        
                        window.location.href = 'log.html';
                    }, 2000);
                    
                }, 1500);
            }
        });

        // ... (Código JavaScript existente) ...

// Actualizar la función de validación de contraseña (para requisito 5)
function isStrongPassword(password) {
    // Debe tener al menos 8 caracteres, una letra mayúscula, una minúscula y un número
    const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
    return regex.test(password);
}

password.addEventListener('input', function() {
    const isValid = isStrongPassword(password.value);
    const errorElement = document.getElementById('passwordError');
    if (!isValid) {
        errorElement.textContent = 'La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula y un número.';
    } else {
        errorElement.textContent = 'La contraseña debe tener al menos 6 caracteres'; // Mensaje genérico, aunque la condición es más estricta.
    }
    validateField(password, isValid, 'passwordError');
});


// Envio del formulario (Implementación AJAX para Requisito 6)
form.addEventListener('submit', function(e) {
    e.preventDefault();

    // 1. Validar todos los campos (se mantiene la validación frontend)
    const isFullnameValid = validateField(fullname, fullname.value.trim() !== '', 'fullnameError');
    const isUsernameValid = validateField(username, username.value.trim() !== '', 'usernameError');
    const isEmailValid = validateField(email, email.value.trim() !== '' && isValidEmail(email.value), 'emailError');
    const isPasswordValid = validateField(password, isStrongPassword(password.value), 'passwordError');
    const isBirthdateValid = validateField(birthdate, birthdate.value !== '' && isValidAge(birthdate.value), 'birthdateError');

    if (isFullnameValid && isUsernameValid && isEmailValid && isPasswordValid && isBirthdateValid) {
        registerBtn.disabled = true;
        registerBtn.textContent = 'Creando cuenta...';

        // 2. Preparar los datos para la API propia
        const userData = {
            fullname: fullname.value,
            // NOTA: El campo 'username' del HTML debe mapearse al campo 'nombre' en la DB
            // Si el campo 'username' no es requerido por la DB, se podría eliminar o mapear
            // Aquí lo dejaremos en el body JSON, aunque el backend no lo usa actualmente
            username: username.value, 
            email: email.value,
            password: password.value,
            birthdate: birthdate.value,
            // Campos que deberían estar en el formulario o ser predeterminados:
            // gender: document.getElementById('gender').value,
            // country: document.getElementById('country').value 
        };
        
        // 3. Petición AJAX (fetch) a la API propia
        fetch('api/usuarios.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(userData)
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(({ status, body }) => {
            if (status === 201) {
                // Éxito: Mostrar mensaje y redirigir
                successMessage.textContent = '¡Registro exitoso! Redirigiendo a Iniciar Sesión...';
                successMessage.classList.add('show');
                
                setTimeout(() => {
                    window.location.href = 'sign-in.php'; // Redirigir al .php
                }, 2000);
            } else {
                // Error: Mostrar mensaje de error de la API (ej: Email ya existe)
                document.getElementById('fullnameError').textContent = `Error (${status}): ${body.error}`;
                document.getElementById('fullnameError').classList.add('show');

                // Volver a habilitar el botón
                registerBtn.disabled = false;
                registerBtn.textContent = 'Crear Cuenta';
            }
        })
        .catch(error => {
            console.error('Error de conexión:', error);
            // Error de red
            document.getElementById('fullnameError').textContent = 'Error de red. Intenta de nuevo.';
            document.getElementById('fullnameError').classList.add('show');
            registerBtn.disabled = false;
            registerBtn.textContent = 'Crear Cuenta';
        });
    }
});
    </script>

    
</body>
</html>
