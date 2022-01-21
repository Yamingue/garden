import React, { useEffect, useState } from "react";
import { useQuery } from 'react-query'
import EnfantSignaler from "./EnfantSignaler";

const SuperWay = (props) => {
    const [isClear, setClear] = useState(false)
    const { isLoading, error, data, refetch } = useQuery('onwaySuper', () =>
        fetch('/super/api/onway').then(res =>
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
            if (!isClear) {
                console.log(notClear)
                clearInterval(timer)
            }
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
    return <>
        {
            data.map(el => <div className='col-sm-3 mb-2' key={el.time+el.restTime}>
                <EnfantSignaler data={el} />
            </div>)
        }
    </>

}

export default SuperWay;