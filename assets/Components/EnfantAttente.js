import React, { useEffect, useState } from "react";
import './parking.css'


const EnfantAttente = ({ data }) => {
    const [minute, setMinute] = useState(0)
    useEffect(function () {
        let date = new Date(data.updateAt)
        date = date.getTime();
        let now = new Date();
        now = now.getTime()
        let millice = now - date;
        let seconde = millice/1000
        setMinute(Math.round(seconde/60))
    }, [])
    let color = "text-light"
    if (minute >= 3 && minute <= 10) {
        color = "text-info"
    }
    if (minute >= 10) {
        color = "text-warning"
    }
    return (
        <div className="waiting-body">
            <img className="waiting-img" src={"/" + data.enfant.photo} />
            <div className='waiting-text'>
                <div>{data.enfant.nom + " " + data.enfant.prenom}</div>
                <div className={color}>
                    {minute} minutes
                </div>
            </div>
            <button className="waiting-btn">
                OK
            </button>
        </div>
    );
}

export default EnfantAttente;