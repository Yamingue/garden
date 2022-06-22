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

import EnfantAttente from './Components/EnfantAttente';
import EnfantSignaler from './Components/EnfantSignaler';
import { QueryClient, QueryClientProvider, useQuery } from 'react-query'
import ParkingOnlySuper from './Components/parkingOnlySuper';
import SuperWay from './Components/SuperWay';
import ParkingSuper from './Components/parkingSuper';

const queryClient = new QueryClient()
const Supervisor = (props) => {
    const { isLoading, error, data, refetch } = useQuery('onwaySuper', () =>
        fetch('/super/api/onway').then(res =>
            res.json()
        )
    )
    let timer = setInterval(() => {
        refetch()
    }, 60_000)
    useEffect(() => {
        if (data) {
            setInterval(() => {
                refetch()
            }, 15_000)
            clearInterval(timer)
        }
    }, [])

    if (isLoading) {
        return <div className="d-flex justify-content-center">
            <div className="spinner-border text-primary" role="status">
                <span className="visually-hidden">Loading...</span>
            </div>
        </div>
    }


    if (error) return 'An error has occurred: ' + error.message

    return (
        <div style={{
            width: "97%",
            margin: '0 auto'
        }}>
            <div className="row">
                <div className='col-md-4'>
                    <div className='card'>
                        <div className='card-header bg-dark text-white text-center'>
                            <h5>Waiting Parking</h5>
                        </div>
                        <div className='card-body'>
                            <ParkingSuper />
                        </div>
                    </div>
                </div>
                <div className='col-md-8' >
                    <div className='card'>
                        <div className='card-header bg-dark text-white text-center'>
                        </div>
                        <div className='card-body'>
                            <div className='row'>
                                {data.map(el => <div className='col-sm-3 mb-2'>
                                    <EnfantSignaler data={el} key={el.updateAt} />
                                </div>)}

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    )

}
const MainSuper = (props) => {
    return <QueryClientProvider client={queryClient}>
        <Supervisor />
    </QueryClientProvider>
}
const el = document.getElementById("super")
const superParking = document.getElementById("superParking");
const superWay = document.getElementById("superWay")
const superParkingOnly = document.getElementById("superParkingOnly")
if (superParkingOnly) {
    let Parking = (props) => {
        return <QueryClientProvider client={queryClient}>
            <div className='container'>
                <div className='row'>
                    <ParkingOnlySuper />
                </div>
            </div>
        </QueryClientProvider>
    }
    reactDom.render(<Parking />, superParkingOnly)
}
if (superWay) {
    let OnWay = (props) => {
        return <QueryClientProvider client={queryClient}>
            <div className='container'>
                <div className='row'>
                    <SuperWay />
                </div>
            </div>
        </QueryClientProvider>
    }
    reactDom.render(<OnWay />, superWay)
}
if (superParking) {
    let Parking = (props) => {
        return <QueryClientProvider client={queryClient}>
            <div className='container'>
                <ParkingSuper />
            </div>
        </QueryClientProvider>
    }
    reactDom.render(<Parking />, superParking)
}
if (el) {

    reactDom.render(<MainSuper />, el)
}

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