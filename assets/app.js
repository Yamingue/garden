/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import '@fortawesome/fontawesome-free/js/all.js'
// start the Stimulus application
import './bootstrap';
import 'mdb-ui-kit/js/mdb.min.js'

var copys  = document.querySelectorAll('#copy')

copys.forEach(el => {
    el.addEventListener('click',(ev)=>{
        ev.preventDefault()
        navigator.clipboard.writeText(el.textContent)
        alert("Copy done")
    })
});
