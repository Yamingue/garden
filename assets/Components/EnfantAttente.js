import { getMessaging, isSupported, onBackgroundMessage } from "firebase/messaging/sw";
import React, { useEffect, useRef, useState } from "react";
import firebaseApp from "../function/firebaseApp";
import './parking.css'

const EnfantAttente = ({ data, route = '/super/notif/' }) => {
    const [minute, setMinute] = useState(0)
    const elRef = useRef()
    console.log(data)
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
    const notifiReady = () => {
        fetch('https://fcm.googleapis.com/fcm/send', {
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'bearer AIzaSyD5gB0xPY4uZOh_dw316UZhb7CoP6_cnJ4',
            },

            body: JSON.stringify({
                "message": {
                    "topic": "test",
                    "notification": {
                        "title": "Background Message Title",
                        "body": "Background message body"
                    }
                }
            }),
            method: 'POST'
        }).then(dta => dta.json).then(json => {
            console.log(json)
        }).catch(e => console.log(e))
        //console.log(route+data.id)
        // getMessaging(firebaseApp).send({
        //     data:{
        //         title:'titre',
        //         message:'message'
        //     },
        //     notification: {
        //         title: 'Notification x',
        //         body: 'corp y'
        //       },
        //     topic:'test'
        // })
        fetch(route + 'ready/' + data.id).then(res => res.json()).then(json => {
            if (json.code == 200) {
                alert("Notifie")
            } else {
                alert("error occure")
            }
        }).catch(() => {
            alert("error occure")
        })
    }

    const closeNotif = () => {
        fetch(route + 'close/' + data.id).then(res => res.json()).then(json => {
            if (json.code == 200) {
                elRef.current.style.opacity = 0

            } else {
                alert("error occure")
            }
        }).catch(() => {
            alert("error occure")
        })
    }
    return (
        <div ref={elRef} className="waiting-body">
            <img className="waiting-img" src={"/" + data.enfant.photo} />
            <div className='waiting-text'>
                <div>{data.enfant.nom + " " + data.enfant.prenom}</div>
                <div className={color}>
                    {minute} minutes
                </div>
            </div>
            <button className="waiting-btn" onClick={e => notifiReady()}>
                OK
            </button>
            <button className="close-btn" onClick={e => closeNotif()}>
                OFF
            </button>
        </div>
    );
}

export default EnfantAttente;