// Permet d'ajouter un close button dynamique sur le flash message d'une bonne soumission d'un avis

let closeBtn = document.getElementsByClassName("close");
let i;

for (i = 0; i < closeBtn.length; i++) {
  closeBtn[i].addEventListener("click", function() {
  this.parentElement.style.display = 'none';
})}