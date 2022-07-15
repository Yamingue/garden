import React, { useEffect } from 'react';
import ReactDOM from 'react-dom'
import { QueryClient, QueryClientProvider, useQuery } from 'react-query'
import Parking from '../Components/Parking';

const queryClient = new QueryClient()

export default function AppParking() {
    return (
        <QueryClientProvider client={queryClient}>
            <ParkingScreen />
        </QueryClientProvider>
    )
}

function ParkingScreen() {
    const { isLoading, error, data, refetch } = useQuery('parking', () =>
        fetch('/super/api/parking').then(res =>
            res.json()
        ),{
            staleTime:100
        }
    )
   var timer = null;

    // useEffect(()=>{
    //     timer = setInterval(()=>{
    //         refetch()
    //     },3000)

    //     return ()=>{
    //         clearInterval(timer)
    //     }
    // },[])

    if (isLoading) {
        return <div className="d-flex justify-content-center">
            <div className="spinner-border text-primary" role="status">
                <span className="visually-hidden">Loading...</span>
            </div>
        </div>
    }

    if (error) return 'An error has occurred: ' + error.message

    return (
        <div className='row'>
            {data.map(i => <Parking key={i.updateAt} data={i} />)}
        </div>
    )
}

const domElParking = document.getElementById('superParkingOnly')

if (domElParking) {
    ReactDOM.render(<AppParking />, domElParking)
}