import React, { useEffect } from 'react'
import reactDom from 'react-dom';
import '@fortawesome/fontawesome-free/js/all.js'
// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import 'mdb-ui-kit/js/mdb.min.js'

// start the Stimulus application
import './bootstrap';
import EnfantAttente from './Components/EnfantAttente';
import EnfantSignaler from './Components/EnfantSignaler';
import { QueryClient, QueryClientProvider, useQuery } from 'react-query'
import Parking from './Components/Parking';
import ParkingGardienne from './Pages/Gardiennes/ParkingGardienne';


const queryClient = new QueryClient({
    defaultOptions: {
        queries: {
            staleTime: 20_000,
            refetchOnWindowFocus: false
        }
    }
})


const Example = () => {
    const { isLoading, error, data, refetch } = useQuery('repoData', () =>
        fetch('/gardienne/api/onway').then(res =>
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

    return (<div style={{
        width: "97%",
        margin: '0 auto'
    }}>
        <div className="row">
            <div className='col-md-4'>
                <div className='card'>
                    <div className='card-header bg-dark text-white text-center'>
                        <h5>Waiting Parking</h5>
                    </div>
                    <div className='card-body'>
                        <Parking  />
                    </div>
                </div>
            </div>
            <div className='col-md-8' >
                <div className='card'>
                    <div className='card-header bg-dark text-white text-center'>
                        <h5>On the way</h5>
                    </div>
                    <div className='card-body'>
                        <div className='row'>
                            {
                                data.map(el => <div className='col-sm-3 mb-2' key={el.time}>
                                    <EnfantSignaler data={el} />
                                </div>)

                            }
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    )
}

const MainGarden = (props) => {
    return <QueryClientProvider client={queryClient}>
        <Example />
        {/* <ReactQueryDevtools initialIsOpen={false} /> */}
    </QueryClientProvider>
}

const ParkingG = (props) => {
    return <QueryClientProvider client={queryClient}>
        <ParkingGardienne />
        {/* <ReactQueryDevtools initialIsOpen={false} /> */}
    </QueryClientProvider>
}

const garden = document.getElementById("garden")
if (garden) {

    reactDom.render(<MainGarden />, garden)
}
const garden_parking = document.getElementById("garden_parking")
if (garden_parking) {

    reactDom.render(<ParkingG/>, garden_parking)
}
