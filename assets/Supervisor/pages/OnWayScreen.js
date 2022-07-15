import React, { useEffect } from 'react';
import Onway from '../Components/onWay';
import ReactDOM from 'react-dom'
import { QueryClient, QueryClientProvider, useQuery } from 'react-query'

const queryClient = new QueryClient()

export default function App() {
    return (
        <QueryClientProvider client={queryClient}>
            <OnWayScreen />
        </QueryClientProvider>
    )
}

function OnWayScreen() {
    const { isLoading, error, data, refetch } = useQuery('repoData', () =>
        fetch('/super/api/onway').then(res =>
            res.json()
        ),{
            staleTime:1000
        }
    )
   var timer = null;

    useEffect(()=>{
        timer = setInterval(()=>{
            refetch()
        },5_000)

        return ()=>{
            clearInterval(timer)
        }
    },[])

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
            {data.map(i => <Onway key={i.updateAt} data={i} />)}
        </div>
    )
}

const domEl = document.getElementById('superWay')

if (domEl) {
    ReactDOM.render(<App />, domEl)
}