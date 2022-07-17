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
        fetch('/gardienne/api/parking').then(res =>
            res.json()
        ), {
        staleTime: 100
    }
    )
    var timer = null;

    useEffect(() => {
        timer = setInterval(() => {
            refetch()
        }, 5_000)

        return () => {
            clearInterval(timer)
        }
    }, [])

    if (isLoading) {
        return <div className='container'>
            <div className="d-flex justify-content-center">
                <div className="spinner-border text-primary" role="status">
                    <span className="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    }

    if (error) return 'An error has occurred: ' + error.message

    return (
        <div className='container'>
             <div className='btn btn-block btn-primary mb-2 d-flex justify-content-center align-items-center'>
                On way
            </div>
            <div className='row'>
                {data.map(i => <Parking key={i.updateAt} data={i} />)}
            </div>
        </div>
    )
}

const domElParking = document.getElementById('garden_parking')

if (domElParking) {
    ReactDOM.render(<AppParking />, domElParking)
}