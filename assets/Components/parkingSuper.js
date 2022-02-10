import React,{ useEffect } from 'react'
import { useQuery } from 'react-query';
import EnfantAttente from './EnfantAttente';


const ParkingSuper = (props)=>{
    const { isLoading, error, data, refetch} = useQuery('parkingSuper', () =>
    fetch('/super/api/parking').then(res =>
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
        }, 30_000)
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
    return(<>
    {
        data.map(el=><EnfantAttente data={el} key={el.updateAt}/>)
    }
    </>);
}

export default ParkingSuper