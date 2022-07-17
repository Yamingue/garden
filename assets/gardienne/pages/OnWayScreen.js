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
        fetch('/gardienne/api/onway').then(res =>
            res.json()
        ), {
        staleTime: 1000
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
        return <div className="d-flex justify-content-center">
            <div className="spinner-border text-primary" role="status">
                <span className="visually-hidden">Loading...</span>
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
                {data.map(i => <Onway key={i.updateAt} data={i} />)}
            </div>
        </div>
    )
}

const domEl = document.getElementById('garden_way')

if (domEl) {
    ReactDOM.render(<App />, domEl)
}