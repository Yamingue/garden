import React, { useState } from "react";
import formatHours from "../functions/formateHours";

export default function Onway(props) {
    const [bg, setBg] = useState('')
    let time = Math.floor(props.data.restTime);
    const enfant = props.data.enfant
    const parent = props.data.parent
    var percent = (time * 100) / 120
    // if (percent >= 50) {
    //     setBg('bg-success')
    // }

    console.log(parent)
    return <>
        <section className="col-md-3 col-sm-4 mb-2">

            <div className="card">
                <div className="card-header">
                    <div className="d-flex justify-content-between align-items-center">
                        <div className="d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                className="bi bi-stopwatch" viewBox="0 0 16 16">
                                <path d="M8.5 5.6a.5.5 0 1 0-1 0v2.9h-3a.5.5 0 0 0 0 1H8a.5.5 0 0 0 .5-.5V5.6z" />
                                <path
                                    d="M6.5 1A.5.5 0 0 1 7 .5h2a.5.5 0 0 1 0 1v.57c1.36.196 2.594.78 3.584 1.64a.715.715 0 0 1 .012-.013l.354-.354-.354-.353a.5.5 0 0 1 .707-.708l1.414 1.415a.5.5 0 1 1-.707.707l-.353-.354-.354.354a.512.512 0 0 1-.013.012A7 7 0 1 1 7 2.071V1.5a.5.5 0 0 1-.5-.5zM8 3a6 6 0 1 0 .001 12A6 6 0 0 0 8 3z" />
                            </svg>
                            <span className="fw-bold"> {formatHours(props.data.restTime)}</span>
                        </div>
                        <div className="">
                            <span className="message">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    className="bi bi-chat-text" viewBox="0 0 16 16">
                                    <path
                                        d="M2.678 11.894a1 1 0 0 1 .287.801 10.97 10.97 0 0 1-.398 2c1.395-.323 2.247-.697 2.634-.893a1 1 0 0 1 .71-.074A8.06 8.06 0 0 0 8 14c3.996 0 7-2.807 7-6 0-3.192-3.004-6-7-6S1 4.808 1 8c0 1.468.617 2.83 1.678 3.894zm-.493 3.905a21.682 21.682 0 0 1-.713.129c-.2.032-.352-.176-.273-.362a9.68 9.68 0 0 0 .244-.637l.003-.01c.248-.72.45-1.548.524-2.319C.743 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7-3.582 7-8 7a9.06 9.06 0 0 1-2.347-.306c-.52.263-1.639.742-3.468 1.105z" />
                                    <path
                                        d="M4 5.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zM4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8zm0 2.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5z" />
                                </svg>
                            </span>

                            <div className="dote" style={{ float: 'right' }}>
                                <div className="dropdown">
                                    <svg type="button" id="dropdownMenu2" data-bs-toggle="dropdown"
                                        aria-expanded="false" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" className="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                        <path
                                            d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z" />
                                    </svg>
                                    <ul className="dropdown-menu" aria-labelledby="dropdownMenu2">
                                        <li><button className="dropdown-item" type="button" data-bs-toggle="modal" data-bs-target={"#modal"+enfant.id}>Current parent info</button></li>
                                    </ul>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div className="bg-image hover-overlay ripple rounded-0" data-mdb-ripple-color="light">
                    <img className="img-fluid" src={'/' + enfant.photo}
                        alt="Card image cap" />
                    <a href="#!">
                        <div className="mask" style={{ backgroundColor: "rgba(251, 251, 251, 0.15)" }}></div>
                    </a>
                </div>
                <div className="progress" style={{ height: 20 }} >
                    <div className={`progress-bar ${percent >= 50 ? "bg-success" : percent > 30 ? "bg-primary" : "bg-danger"} progress-bar-striped progress-bar-animated`} role="progressbar"
                        aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style={{ width: percent + '%' }}>{Math.floor(time)} mn</div>
                </div>
                <div className="card-footer">
                    <div className="fw-bold"> {enfant.nom + " " + enfant.prenom}</div>
                </div>
            </div>

        </section >
        <div className="modal fade" id={"modal"+enfant.id} tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div className="modal-dialog">
                <div className="modal-content">
                    <div className="modal-header">
                        <h5 className="modal-title" id="exampleModalLabel">{parent.nom+ " "+parent.prenom}</h5>
                        <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <img src={"/"+parent.photo} class="img-responsive modal-body" />
                </div>
            </div>
        </div>
    </>

}