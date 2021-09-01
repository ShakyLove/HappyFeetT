let indice = 1;
muestraSlides(indice);

function avanzaSlide(n) {
  muestraSlides((indice += n));
}

function muestraSlides(n) {
  let i;
  let slides = document.getElementsByClassName("containerDataProductos");

  if (n > slides.length) {
    indice = 1;
  }
  if (n < 1) {
    indice = slides.length();
  }
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  slides[indice - 1].style.display = "flex";
}

let ubicacionPrincipal = window.pageYOffset;
window.onscroll = function () {
  let desplazamiento = window.pageYOffset;
  if (ubicacionPrincipal >= desplazamiento) {
    document.getElementById("navegacion").style.display = "block";
    document.getElementById("header").style.background = "initial";
  } else {
    document.getElementById("navegacion").style.display = "none";
    document.getElementById("header").style.background = "white";
  }
  ubicacionPrincipal = desplazamiento;
};
