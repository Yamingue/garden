import React, { useEffect, useRef, useState } from "react";
import './parking.css'


const EnfantAttente = ({ data, route='/super/notif/' }) => {
    const [minute, setMinute] = useState(0)
    const elRef = useRef()
    useEffect(function () {
        let date = new Date(data.updateAt)
        date = date.getTime();
        let now = new Date();
        now = now.getTime()
        let millice = now - date;
        let seconde = millice / 1000
        setMinute(Math.round(seconde / 60))
    }, [])
    let color = "text-light"
    if (minute >= 3 && minute <= 10) {
        color = "text-info"
    }
    if (minute >= 10) {
        color = "text-warning"
    }
    const notifiReady = ()=>{
        //console.log(route+data.id)
       fetch(route+'ready/'+data.id).then(res=>res.json()).then(json=>{
           if (json.code == 200 ) {
               alert("Notifie")
           }else{
               alert("error occure")
           }
       }).catch(()=>{
        alert("error occure")
       })
    }

    const closeNotif = ()=>{
        fetch(route+'close/'+data.id).then(res=>res.json()).then(json=>{
            if (json.code == 200 ) {
                elRef.current.style.opacity = 0

            }else{
                alert("error occure")
            }
        }).catch(()=>{
         alert("error occure")
        })
    }
    console.log(data)
    return (
        <div ref={elRef} className="waiting-body">
            <img className="waiting-img" src={"/" + data.enfant.photo} />
            <div className='waiting-text'>
                <div>{data.enfant.nom + " " + data.enfant.prenom}</div>
                <div className={color}>
                    {minute} minutes
                </div>
            </div>
            <button className="waiting-btn" onClick={e=> notifiReady()}>
                OK
            </button>
            <button className="close-btn" onClick={e=>closeNotif()}>
                OFF
            </button>
        </div>
    );
}

export default EnfantAttente;