import React, { useEffect } from 'react'
import reactDom from 'react-dom';

var jQuery = require('jquery')
global.$ = jQuery;
global.jQuery = jQuery;

//import css relier au template
import './assets/vendors/css/vendor.bundle.base.css'
import './assets/css/style.css'
import './assets/vendors/mdi/css/materialdesignicons.min.css'

//import du js relier au template
import './assets/vendors/js/vendor.bundle.base'
import './assets/js/off-canvas'
import './assets/js/hoverable-collapse'
import './assets/js/misc'
import './Supervisor/pages/OnWayScreen'
import './Supervisor/pages/ParkingScreen'



//information lier au remplissage du formulaire
var gardienne_salles = document.getElementById('gardienne_salles')
var enfant_salle = document.getElementById('enfant_salle')
var gardienne_edite_salles = document.getElementById("gardienne_edite_salles")
var enfant_edit_salle = document.getElementById("enfant_edit_salle")

if (enfant_edit_salle) {
    fetch('/super/api/salles')
        .then(data => data.json())
        .then(data => {
            updateSalle(data, enfant_edit_salle)
        })
}
if (gardienne_edite_salles) {
    fetch('/super/api/salles')
        .then(data => data.json())
        .then(data => {
            updateSalle(data, gardienne_edite_salles)
        })
}
if (gardienne_salles) {
    fetch('/super/api/salles')
        .then(data => data.json())
        .then(data => {
            updateSalle(data, gardienne_salles)
        })
}
if (enfant_salle) {
    fetch('/super/api/salles')
        .then(data => data.json())
        .then(data => {
            updateSalle(data, enfant_salle)
        })
}

function updateSalle(data, target) {
    target.innerHTML = ""
    data.forEach(el => {
        let option = document.createElement('option')
        option.value = el.id
        option.innerText = el.nom
        target.appendChild(option)
    });
}