import React, { PureComponent } from 'react'
import ShowParentModal from './ShowParentModal'

export default class ParkingOnly extends PureComponent {

    notifiReady = ()=>{
        const route = '/super/notif/'
        //console.log(route+data.id)
       fetch(route+'ready/'+this.props.data.id).then(res=>res.json()).then(json=>{
           if (json.code == 200 ) {
               alert("Notifie")
           }else{
               alert("error occure")
           }
       }).catch(()=>{
        alert("error occure")
       })
    }

    closeNotif = ()=>{
        const route = '/super/notif/'
        fetch(route+'close/'+this.props.data.id).then(res=>res.json()).then(json=>{
            if (json.code == 200 ) {
                alert("Close")
            }else{
                alert("error occure")
            }
        }).catch((e)=>{
            alert("error occure")
        console.log('error',e)
        })
    }
    render() {
        const {data} = this.props
        console.log(data)
        return <>
            <div className="card">
                <div className="bg-image hover-overlay ripple" data-mdb-ripple-color="light">
                    <img src={'/'+data.enfant.photo} className="img-fluid" />
                    <a href="#!">
                        <div className="mask" style={{ backgroundColor: 'rgba(251, 251, 251, 0.15)' }} />
                    </a>
                </div>
                {/* <div className="d-grid gap-2">
                    <button className="btn btn-primary" type="button" data-mdb-toggle="modal" data-mdb-target="#exampleModal">Show parent</button>
                </div> */}
                <div className="card-body">
                <h5 className="card-title">{data.enfant.nom} {data.enfant.prenom}</h5>
                    <div className="card-text">
                    classroom: {data.enfant.salle}
                    </div>
                </div>
                <div className="card-footer d-flex flex-row justify-content-between">
                        <button className='btn btn-success' onClick={e=>this.notifiReady()}>Ready</button>
                        <button className='btn btn-warning' onClick={e=>this.closeNotif()}>Close</button>
                    
                </div>
            </div>
            <ShowParentModal />
        </>
    }
}