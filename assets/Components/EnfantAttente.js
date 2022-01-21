import React, { useState } from "react";
import './parking.css'


const EnfantAttente = ({ data })=>{
    const [minute, setMinute] = useState(11)
    let color = "text-light"
    if (minute >=3 && minute <=10) {
        color = "text-info"
    }
    if (minute >=10) {
        color = "text-warning"
    } 
    return (
        <div className="waiting-body">
            <img className="waiting-img" src={"/"+data.enfant.photo}/>
            <div className='waiting-text'>
                <div>{data.enfant.nom +" "+data.enfant.prenom}</div>
                <div className={color}>
                    {minute} mn
                </div>
            </div>
            <button className="waiting-btn">
                OK
            </button>
        </div>
    );
}

export default EnfantAttente;