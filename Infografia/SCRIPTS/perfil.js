document.addEventListener('DOMContentLoaded', () => {

    let userData = {};

    fetch('API/perfil.php')
        .then(response => {
            if (!response.ok) throw new Error('Error API');
            return response.json();
        })
        .then(data => {
            document.getElementById('perfilUsername').textContent = data.username || data.nombre;
            document.getElementById('perfilRole').textContent = data.rol;
            document.getElementById('perfilFullname').textContent = data.nombre;
            document.getElementById('perfilEmail').textContent = data.email;
            document.getElementById('perfilBirthdate').textContent = data.fechaNacimiento;
            document.getElementById('perfilRegistrationDate').textContent = new Date(data.fechaRegistro).toLocaleDateString();
            userData = data;

            let g = 'Otro';
            if (data.genero === 'M') g = 'Masculino';
            if (data.genero === 'F') g = 'Femenino';
            document.getElementById('perfilGender').textContent = g;

            document.getElementById('perfilCountry').textContent = `${data.paisNacimiento} (${data.nacionalidad})`;

            if (data.photo) {
                document.getElementById('perfilPhoto').src = data.photo;
            }
        })
        .catch(err => {
            console.error(err);
            if (String(err).includes('401')) window.location.href = 'log-In.html';
        });


    const editBtn = document.querySelector('.edit-btn-perfil');
    const modal = document.getElementById('modalEditPerfil');
    const closeBtn = modal.querySelector('.close-btn');

    if (editBtn) {
        editBtn.addEventListener('click', function (e) {
            e.preventDefault();

            document.getElementById('p_nombre').value = userData.nombre || '';
            document.getElementById('p_pais').value = userData.paisNacimiento || '';
            document.getElementById('p_nacionalidad').value = userData.nacionalidad || '';
            
            const generoSelect = document.getElementById('p_genero'); 
            if (generoSelect) {
                generoSelect.value = userData.genero || 'O'; 
            }

            modal.style.display = 'block';
        });
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', function () {
            modal.style.display = 'none';
        });
    }

    window.addEventListener('click', function (e) {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });

    const form = document.getElementById('formEditPerfil');
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const btn = form.querySelector('button');
            btn.disabled = true;
            btn.textContent = 'Guardando...';

            fetch('API/perfil.php', { method: 'POST', body: formData })
                .then(r => r.json())
                .then(data => {
                    alert(data.message || data.error);
                    if (!data.error) location.reload();
                })
                .catch(err => alert('Error al actualizar'))
                .finally(() => {
                    btn.disabled = false;
                    btn.textContent = 'Guardar Cambios';
                });
        });
    }
});