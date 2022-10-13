
var exampleModal = document.getElementById('exampleModal')
exampleModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget

    var modalLink = exampleModal.querySelector('.modal-link')

    // Récupéré l'attribut url
    modalLink.href = button.dataset.url;


})


