fetch("SCRIPTS/torneos.json")
  .then(respuesta => respuesta.json())
  .then(data => {
   
    let contenedor = document.getElementById("listaTorneos"); 
 
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
  })
  .catch(error => console.error("Error al cargar torneos.json:", error));

var navLink = document.getElementById("navLink");
function showMenu() {
    navLink.style.right = "0";
}
function hideMenu() {
    navLink.style.right = "-200px";
}

// ====================================================================
// FUNCIÓN 1: CALENDARIO DE PARTIDOS (MUNDIAL DE CLUBES) - MEJORADO
// ====================================================================

function fetchFifaSchedule() {
    
    const scheduleUrl = 'https://site.api.espn.com/apis/site/v2/sports/soccer/fifa.world/scoreboard';
    const container = document.getElementById('fifa-schedule-container');
    const logoImg = document.getElementById('fifa-logo'); // Nuevo: Referencia al logo

    fetch(scheduleUrl)
        .then(response => {
             
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            const events = data.events;
            
            // Nuevo: INYECTAR LOGO DEL TORNEO
            if (data.leagues && data.leagues.length > 0 && data.leagues[0].logos && data.leagues[0].logos.length > 0) {
                // Usamos el logo por defecto
                logoImg.src = data.leagues[0].logos.find(l => l.rel.includes('default')).href || data.leagues[0].logos[0].href;
            }

            let htmlContent = '<table class="schedule-table"><thead><tr><th>Fecha</th><th>Partido</th><th>Estatus/Hora</th></tr></thead><tbody>';

            if (events && events.length > 0) {
                events.forEach(event => {
                    const competition = event.competitions[0];
                    const homeTeam = competition.competitors.find(c => c.homeAway === 'home');
                    const awayTeam = competition.competitors.find(c => c.homeAway === 'away');
                    
                    const matchDate = new Date(event.date);
                    const formattedDate = matchDate.toLocaleDateString('es-ES', { day: '2-digit', month: 'short' });
                    
                    const timeOptions = { hour: '2-digit', minute: '2-digit', timeZoneName: 'short' };
                    const formattedTime = matchDate.toLocaleTimeString('es-MX', timeOptions);

                    let scoreDisplay = competition.status.type.shortDetail; 

                    // Lógica mejorada para mostrar el estado y el score
                    if (competition.status.type.state === 'post') {
                        scoreDisplay = `<span class="status-final">${homeTeam.score} - ${awayTeam.score} (FINAL)</span>`;
                    } else if (competition.status.type.state === 'in') {
                         scoreDisplay = `<span class="status-live">${homeTeam.score} - ${awayTeam.score} (${competition.status.displayClock})</span>`;
                    } else {
                        scoreDisplay = `<span class="status-scheduled">${formattedTime}</span>`;
                    }

                    htmlContent += `
                        <tr>
                            <td>${formattedDate}</td>
                            <td>
                                <div class="team-vs">
                                    <span class="team-name">${homeTeam.team.displayName}</span>
                                    <span class="vs-text">vs</span>
                                    <span class="team-name">${awayTeam.team.displayName}</span>
                                </div>
                            </td>
                            <td>${scoreDisplay}</td>
                        </tr>
                    `;
                });
                
                htmlContent += '</tbody></table>';
                container.innerHTML = htmlContent;
            } else {
                container.innerHTML = '<p class="error-message">No hay partidos programados en el calendario de la FIFA en este momento.</p>';
            }
        })
        .catch(error => {
            console.error('Error fetching FIFA schedule (CORS/Server):', error);
            container.innerHTML = '<p class="error-message">Error al cargar el calendario de la FIFA. Asegúrate de ejecutarlo desde un servidor web.</p>';
        });
}

// ====================================================================
// FUNCIÓN 2: CALENDARIO DE PARTIDOS (LIGA MX) - MEJORADO
// ====================================================================

function fetchMexSchedule() {
    const leagueId = 'mex.1'; 
    const scheduleUrl = `https://site.api.espn.com/apis/site/v2/sports/soccer/${leagueId}/scoreboard`;
    const container = document.getElementById('standings-table-mex'); 
    const logoImg = document.getElementById('mex-logo'); // Nuevo: Referencia al logo

    fetch(scheduleUrl)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            const events = data.events;
            
            // Nuevo: INYECTAR LOGO DEL TORNEO
            if (data.leagues && data.leagues.length > 0 && data.leagues[0].logos && data.leagues[0].logos.length > 0) {
                // Usamos el logo por defecto
                 logoImg.src = data.leagues[0].logos.find(l => l.rel.includes('default')).href || data.leagues[0].logos[0].href;
            }

            let htmlContent = '<table class="schedule-table"><thead><tr><th>Fecha</th><th>Partido</th><th>Estatus/Hora</th></tr></thead><tbody>';

            if (events && events.length > 0) {
                events.forEach(event => {
                    const competition = event.competitions[0];
                    const homeTeam = competition.competitors.find(c => c.homeAway === 'home');
                    const awayTeam = competition.competitors.find(c => c.homeAway === 'away');
                    
                    const matchDate = new Date(event.date);
                    const formattedDate = matchDate.toLocaleDateString('es-ES', { day: '2-digit', month: 'short' });
                    
                    const timeOptions = { hour: '2-digit', minute: '2-digit', timeZoneName: 'short' };
                    const formattedTime = matchDate.toLocaleTimeString('es-MX', timeOptions);

                    let scoreDisplay = competition.status.type.shortDetail; 

                    if (competition.status.type.state === 'post') {
                        scoreDisplay = `<span class="status-final">${homeTeam.score} - ${awayTeam.score} (FINAL)</span>`;
                    } else if (competition.status.type.state === 'in') {
                         scoreDisplay = `<span class="status-live">${homeTeam.score} - ${awayTeam.score} (${competition.status.displayClock})</span>`;
                    } else {
                        scoreDisplay = `<span class="status-scheduled">${formattedTime}</span>`;
                    }

                    htmlContent += `
                        <tr>
                            <td>${formattedDate}</td>
                            <td>
                                <div class="team-vs">
                                    <span class="team-name">${homeTeam.team.displayName}</span>
                                    <span class="vs-text">vs</span>
                                    <span class="team-name">${awayTeam.team.displayName}</span>
                                </div>
                            </td>
                            <td>${scoreDisplay}</td>
                        </tr>
                    `;
                });
                
                htmlContent += '</tbody></table>';
                container.innerHTML = htmlContent;
            } else {
                container.innerHTML = '<p class="error-message">No hay partidos programados para la Liga MX en este momento.</p>';
            }
        })
        .catch(error => {
            console.error('Error fetching LIGA MX schedule:', error);
            container.innerHTML = '<p class="error-message">Error al cargar el calendario de la Liga MX. Asegúrate de que el servidor esté activo.</p>';
        });
}


// Llama a las funciones para cargar los datos cuando la página esté lista
document.addEventListener('DOMContentLoaded', () => {
    fetchFifaSchedule(); 
    fetchMexSchedule(); 
});

/*

fetch("SCRIPTS/torneos.json")
  .then(respuesta => respuesta.json())
  .then(data => {

    let contenedor = document.getElementById("listaTorneos");

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
  })
  .catch(error => console.error("Error al cargar torneos.json:", error));

var navLink = document.getElementById("navLink");
function showMenu() {
    navLink.style.right = "0";
}
function hideMenu() {
    navLink.style.right = "-200px";
}

// ====================================================================
// FUNCIÓN 1: CALENDARIO DE PARTIDOS (MUNDIAL DE CLUBES)
// ====================================================================

function fetchFifaSchedule() {

    const scheduleUrl = 'https://site.api.espn.com/apis/site/v2/sports/soccer/fifa.world/scoreboard';
    const container = document.getElementById('fifa-schedule-container');
    const logoImg = document.getElementById('fifa-logo');

    fetch(scheduleUrl)
        .then(response => {

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            const events = data.events;

            // INYECTAR LOGO DEL TORNEO
            if (data.leagues && data.leagues.length > 0 && data.leagues[0].logos && data.leagues[0].logos.length > 0) {
                logoImg.src = data.leagues[0].logos.find(l => l.rel.includes('default')).href || data.leagues[0].logos[0].href;
            }

            let htmlContent = '<table class="schedule-table"><thead><tr><th>Fecha</th><th>Partido</th><th>Estatus/Hora</th></tr></thead><tbody>';

            if (events && events.length > 0) {
                events.forEach(event => {
                    const competition = event.competitions[0];
                    const homeTeam = competition.competitors.find(c => c.homeAway === 'home');
                    const awayTeam = competition.competitors.find(c => c.homeAway === 'away');

                    const matchDate = new Date(event.date);
                    const formattedDate = matchDate.toLocaleDateString('es-ES', { day: '2-digit', month: 'short' });

                    const timeOptions = { hour: '2-digit', minute: '2-digit', timeZoneName: 'short' };
                    const formattedTime = matchDate.toLocaleTimeString('es-MX', timeOptions);

                    let scoreDisplay = competition.status.type.shortDetail;

                    if (competition.status.type.state === 'post') {
                        scoreDisplay = `<span class="status-final">${homeTeam.score} - ${awayTeam.score} (FINAL)</span>`;
                    } else if (competition.status.type.state === 'in') {
                         scoreDisplay = `<span class="status-live">${homeTeam.score} - ${awayTeam.score} (${competition.status.displayClock})</span>`;
                    } else {
                        scoreDisplay = `<span class="status-scheduled">${formattedTime}</span>`;
                    }

                    htmlContent += `
                        <tr>
                            <td>${formattedDate}</td>
                            <td>
                                <div class="team-vs">
                                    <span class="team-name">${homeTeam.team.displayName}</span>
                                    <span class="vs-text">vs</span>
                                    <span class="team-name">${awayTeam.team.displayName}</span>
                                </div>
                            </td>
                            <td>${scoreDisplay}</td>
                        </tr>
                    `;
                });

                htmlContent += '</tbody></table>';
                container.innerHTML = htmlContent;
            } else {
                container.innerHTML = '<p class="error-message">No hay partidos programados en el calendario de la FIFA en este momento.</p>';
            }
        })
        .catch(error => {
            console.error('Error fetching FIFA schedule (CORS/Server):', error);
            container.innerHTML = '<p class="error-message">Error al cargar el calendario de la FIFA. Asegúrate de ejecutarlo desde un servidor web.</p>';
        });
}

// ====================================================================
// FUNCIÓN 2: CALENDARIO DE PARTIDOS (LIGA MX)
// ====================================================================

function fetchMexSchedule() {
    const leagueId = 'mex.1';
    const scheduleUrl = `https://site.api.espn.com/apis/site/v2/sports/soccer/${leagueId}/scoreboard`;
    const container = document.getElementById('standings-table-mex');
    const logoImg = document.getElementById('mex-logo');

    fetch(scheduleUrl)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            const events = data.events;

            // INYECTAR LOGO DEL TORNEO
            if (data.leagues && data.leagues.length > 0 && data.leagues[0].logos && data.leagues[0].logos.length > 0) {
                 logoImg.src = data.leagues[0].logos.find(l => l.rel.includes('default')).href || data.leagues[0].logos[0].href;
            }

            let htmlContent = '<table class="schedule-table"><thead><tr><th>Fecha</th><th>Partido</th><th>Estatus/Hora</th></tr></thead><tbody>';

            if (events && events.length > 0) {
                events.forEach(event => {
                    const competition = event.competitions[0];
                    const homeTeam = competition.competitors.find(c => c.homeAway === 'home');
                    const awayTeam = competition.competitors.find(c => c.homeAway === 'away');

                    const matchDate = new Date(event.date);
                    const formattedDate = matchDate.toLocaleDateString('es-ES', { day: '2-digit', month: 'short' });

                    const timeOptions = { hour: '2-digit', minute: '2-digit', timeZoneName: 'short' };
                    const formattedTime = matchDate.toLocaleTimeString('es-MX', timeOptions);

                    let scoreDisplay = competition.status.type.shortDetail;

                    if (competition.status.type.state === 'post') {
                        scoreDisplay = `<span class="status-final">${homeTeam.score} - ${awayTeam.score} (FINAL)</span>`;
                    } else if (competition.status.type.state === 'in') {
                         scoreDisplay = `<span class="status-live">${homeTeam.score} - ${awayTeam.score} (${competition.status.displayClock})</span>`;
                    } else {
                        scoreDisplay = `<span class="status-scheduled">${formattedTime}</span>`;
                    }

                    htmlContent += `
                        <tr>
                            <td>${formattedDate}</td>
                            <td>
                                <div class="team-vs">
                                    <span class="team-name">${homeTeam.team.displayName}</span>
                                    <span class="vs-text">vs</span>
                                    <span class="team-name">${awayTeam.team.displayName}</span>
                                </div>
                            </td>
                            <td>${scoreDisplay}</td>
                        </tr>
                    `;
                });

                htmlContent += '</tbody></table>';
                container.innerHTML = htmlContent;
            } else {
                container.innerHTML = '<p class="error-message">No hay partidos programados para la Liga MX en este momento.</p>';
            }
        })
        .catch(error => {
            console.error('Error fetching LIGA MX schedule:', error);
            container.innerHTML = '<p class="error-message">Error al cargar el calendario de la Liga MX. Asegúrate de que el servidor esté activo.</p>';
        });
}

// ====================================================================
// FUNCIÓN DE LÓGICA DE MODALES
// ====================================================================

function setupModals() {
    // 1. Manejar la apertura de modales
    const openBtns = document.querySelectorAll('.open-modal-btn');
    openBtns.forEach(btn => {
        btn.onclick = function() {
            const modalId = this.getAttribute('data-modal');
            const modal = document.getElementById(modalId);
            modal.style.display = 'block';
        }
    });

    // 2. Manejar el cierre del modal (botón X)
    const closeBtns = document.querySelectorAll('.close-btn');
    closeBtns.forEach(span => {
        span.onclick = function() {
            this.closest('.modal').style.display = 'none';
        }
    });

    // 3. Manejar el cierre del modal (clic afuera)
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = 'none';
        }
    }
}
// -------------------------------------------------------------
// LLAMADAS AL FINAL
document.addEventListener('DOMContentLoaded', () => {
    fetchFifaSchedule();
    fetchMexSchedule();
    setupModals(); // ¡Importante para que los botones funcionen!
});

*/

