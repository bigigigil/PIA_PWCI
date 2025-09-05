fetch("torneos.json")
  .then(respuesta => respuesta.json())
  .then(data => {
    let contenedor = document.querySelector(".torneos-row");

    data.forEach(torneo => {
      contenedor.innerHTML += `
        <div class="listaTorneos-cuadro">
          <h2>${torneo.nombre}</h2>
          <p><strong>País:</strong> ${torneo.pais}</p>
          <p><strong>Campeón:</strong> ${torneo.campeon}</p>
          <p><strong>Año:</strong> ${torneo.anio}</p>
          <p><strong>Equipos participantes:</strong> ${torneo.equipos}</p>
          <p>${torneo.descripcion}</p>
        </div>
      `;
    });
  });
