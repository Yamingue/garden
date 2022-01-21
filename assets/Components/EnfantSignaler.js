import React, { useEffect, useState } from 'react'
import './comming.css'
import Wave from 'react-wavify';
import getPourcent from '../function/getPourcent';


const EnfantSignaler = ({ data }) => {
    const color = {
        normale: "#81B622",
        danger: "#ff0000",
        warning: "#f0e438",

    }
    const [curentColor, setCurrentColor] = useState(color.danger);
    const [time, setTime] = useState(getPourcent(data.restTime))
    useEffect(() => {
        console.log(time)
        if (time > 40) {
            setCurrentColor(color.normale)
        }
        if (time >= 20 && time <= 39) {
            setCurrentColor(color.warning)
        }
        if (time < 20) {
            setCurrentColor(color.danger)
        }

    }, [time])
    setInterval(ev=>{
        let aux = time - 0.5
        if (aux < 0) {
            setTime(0)
        }else{
            setTime(aux)
        }
    },30_000)
    return (
        <div className={time <= 15 ? 'comming-body pulse' : 'comming-body'} style={{ borderColor: curentColor }}>
            <Wave fill={curentColor}
                paused={false}
                options={{
                    height: 100 - time,
                    amplitude: 10,
                    speed: 0.3,
                    points: 2
                }}
                className='comming-wave'
            />
            <img src={"/"+data.enfant.photo} className='comming-img' />
        </div>
    );
}

export default EnfantSignaler