// on ajoute/retire la classe active aux composants à choisir lorsqu'on constitue une compétence

const competence_composants_list = document.querySelectorAll(".competence-choice");
let composantsToAdd = [];

competence_composants_list.forEach(element => element.addEventListener("click", function() {
    // console.log(element)
    this.classList.toggle("active")
    checkBox(this.dataset.id)
    composantsToAdd = addOrRemove(composantsToAdd, this.dataset.id)
    sessionStorage.setItem('competence_composants_list', JSON.stringify(composantsToAdd))
    // console.log(sessionStorage.getItem('competence_composants_list'))
}))

function addOrRemove(array, value) {
    return array.includes(value) ?
        array.filter(e => e !== value) :
        [...array, value];
}

function checkBox(n) {
    let checkboxTargeted = document.querySelector("#competence_composants_"+n)
    console.log(checkboxTargeted)
    console.log(checkboxTargeted.checked)
    checkboxTargeted.checked == true ?
        checkboxTargeted.checked = false :
        checkboxTargeted.checked = true;
    console.log(checkboxTargeted.checked)
}