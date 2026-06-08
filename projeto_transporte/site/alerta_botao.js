const modalAlerta = document.getElementById('modalAlerta');

document.getElementById('btnAlerta').addEventListener('click', function() {
    modalAlerta.style.display = 'flex';
});

document.getElementById('fecharAlerta').addEventListener('click', function() {
    modalAlerta.style.display = 'none';
});

document.querySelector('.fecharModal').addEventListener('click', function() {
    modalAlerta.style.display = 'none';
});

window.addEventListener('click', function(e) {
    if (e.target === modalAlerta) {
        modalAlerta.style.display = 'none';
    }
});