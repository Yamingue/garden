import React, { Component, useEffect } from 'react'
import { useQuery } from 'react-query'
import ParkingOnly from '../../Components/ParkingOnly'

const ParkingGardienne = (props) => {
    const { isLoading, error, data, refetch } = useQuery('parkingOnly', () =>
        fetch('/gardienne/api/parking').then(res =>
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
    return (<div className='container'>
        {
            data.map(el => <div className='col-sm-3 mb-2'  key={el.updateAt}>
                <ParkingOnly data={el}/>
            </div>)
        }
    </div>);
}

export default ParkingGardienne