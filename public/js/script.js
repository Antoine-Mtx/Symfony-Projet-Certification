// on ajoute/retire la classe active aux composants à choisir lorsqu'on constitue une compétence

var competence_choice_list = document.querySelectorAll(".competence-choice")
var composantsToAdd = [];

console.log(competence_choice_list)
competence_choice_list.forEach(element => element.addEventListener("click", function() {
    console.log(element)
    this.classList.toggle("active");
    addOrRemove(composantsToAdd, this.dataset);
    console.log(composantsToAdd) 
}))

function addOrRemove(array, value) {
    array.includes(value) ?
        array.filter(element => element !== value) :
        [ ...array, value ];
}