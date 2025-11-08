document.addEventListener('DOMContentLoaded', () => {
    fetch('API/perfil.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('No se pudo obtener la información del perfil.');
            }
            return response.json();
        })
        .then(data => {
            document.getElementById('perfilUsername').textContent = data.username || data.nombre;
            document.getElementById('perfilRole').textContent = data.rol;
            document.getElementById('perfilFullname').textContent = data.nombre;
            document.getElementById('perfilEmail').textContent = data.email;
            document.getElementById('perfilBirthdate').textContent = data.fechaNacimiento;
            document.getElementById('perfilRegistrationDate').textContent = new Date(data.fechaRegistro).toLocaleDateString();

            let genderText = '';
            switch (data.genero) {
                case 'M': genderText = 'Masculino'; break;
                case 'F': genderText = 'Femenino'; break;
                case 'O': 
                default: genderText = 'Otro / No especificado'; break;
            }
            document.getElementById('perfilGender').textContent = genderText;
            
            document.getElementById('perfilCountry').textContent = `${data.paisNacimiento} (${data.nacionalidad})`;

            if (data.photo) {
                 document.getElementById('perfilPhoto').src = data.photo;
            } else {
                 document.getElementById('perfilPhoto').src = 'images/logo.png'; 
            }

        })
        .catch(error => {
            console.error('Error al cargar el perfil:', error);
            document.getElementById('perfilUsername').textContent = 'Error al cargar';
            
            if (error.message.includes('401')) {
                 window.location.href = 'log-In.html';
            }
        });
});