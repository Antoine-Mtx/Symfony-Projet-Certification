
// on récupère tous les composants susceptibles d'être ajoutés / retirés de la compétence
const competence_composants_list = document.querySelectorAll(".competence-choice");

competence_composants_list.forEach(element => element.addEventListener("click", function() {
    // on effectue une bascule de la classe active sur les composants sélectionnables lorsqu'on constitue une compétence. Les éléments avec la classe active qui seront ajoutés auront un affichage relié au succès, tandis que ceux qui seront ôtés auront un affichage relié au danger
    this.classList.toggle("active")
    // on effectue une bascule sur l'état de la checkbox reliée au composant en question
    checkBox(this.dataset.id)
}))

function checkBox(id) {
    // on récupère la checkbox dont la valeur est l'id passée en argument
    let checkboxTargeted = document.querySelector("#competence_composants_"+id)
    // si elle est cochée, on la décoche, sinon on la coche
    checkboxTargeted.checked == true ?
        checkboxTargeted.checked = false :
        checkboxTargeted.checked = true;
}

// function addOrRemove(array, value) {
//     return array.includes(value) ?
//     array.filter(e => e !== value) :
//     [...array, value];
// }

// let composantsToAdd = [];

// composantsToAdd = addOrRemove(composantsToAdd, this.dataset.id)
// sessionStorage.setItem('competence_composants_list', JSON.stringify(composantsToAdd))