const players = {
    "Lionel Messi": {
        video: "https://www.youtube.com/embed/nA8wHQvHPJU?autoplay=1&rel=0",
        info: "Considerado uno de los mejores futbolistas de la historia, ganador de múltiples Balones de Oro."
    },
    "Pelé": {
        video: "https://www.youtube.com/embed/m95zMePpesU?autoplay=1&rel=0",
        info: "Leyenda brasileña, tricampeón mundial."
    },
    "Cristiano Ronaldo": {
        video: "https://www.youtube.com/embed/mmeLCAP74KA?autoplay=1&rel=0",
        info: "Máximo goleador histórico de la Champions League."
    },
    "Ronaldhino": {
        video: "https://www.youtube.com/embed/QNgfHB2Vr3w?autoplay=1&rel=0",
        info: "Futbolista brasileño conocido por su estilo de juego alegre."
    },
    "Bukayo Saka": {
        video: "https://www.youtube.com/embed/ogvC3ycptpw?autoplay=1&rel=0",
        info: "Joven talento del Arsenal y la selección inglesa."
    },
    "Johan Cruyff": {
        video: "https://www.youtube.com/embed/jxkr2BX-vIs?autoplay=1&rel=0",
        info: "Icono del fútbol total y leyenda del Ajax y Barcelona."
    },
    "Mohamed Salah": {
        video: "https://www.youtube.com/embed/SMRHiHGaVyw?autoplay=1&rel=0",
        info: "Delantero egipcio estrella del Liverpool."
    },
    "Kylian Mbappé": {
        video: "https://www.youtube.com/embed/ILVV0IH0dwE?autoplay=1&rel=0",
        info: "Joven fenómeno francés, campeón del mundo en 2018."
    },
    "Khvicha Kvaratskhelia": {
        video: "https://www.youtube.com/embed/3pmT8pHUIpA?autoplay=1&rel=0",
        info: "Talento georgiano que destaca en el Nápoles."
    },
    "Pedri": {
        video: "https://www.youtube.com/embed/MPhm8af-wuQ?autoplay=1&rel=0",
        info: "Joven mediocentro español, joya del Barcelona."
    },
    "Dean Huijsen": {
        video: "https://www.youtube.com/embed/dcczqsv_-MY?autoplay=1&rel=0",
        info: "Promesa defensiva del fútbol europeo."
    },
    "Florian Wirtz": {
        video: "https://www.youtube.com/embed/CSMj6VIPGE8?autoplay=1&rel=0",
        info: "Joven talento alemán del Bayer Leverkusen."
    }
};

// Esperar a que el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById("playerModal");
    const modalName = document.getElementById("modalName");
    const modalInfo = document.getElementById("modalInfo");
    const closeBtn = document.querySelector(".close");
    const videoIframe = document.getElementById("modalVideo");

    // Abrir modal
    document.querySelectorAll(".card .product").forEach(card => {
        card.addEventListener("click", function () {
            const name = this.querySelector(".sneakers").textContent.trim();
            const player = players[name];

            if (player && videoIframe) {
                modalName.textContent = name;
                modalInfo.textContent = player.info;
                videoIframe.src = player.video + "?autoplay=1";
                modal.style.display = "block";
            }
        });
    });

    // Cerrar modal
    closeBtn.addEventListener("click", function () {
        modal.style.display = "none";
        if (videoIframe) {
            videoIframe.src = "";
        }
    });

    // Cerrar al hacer clic fuera
    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
            if (videoIframe) {
                videoIframe.src = "";
            }
        }
    });
});