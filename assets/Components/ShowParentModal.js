import React from 'react'

export default class ShowParentModal extends React.PureComponent {
    render() {
        const {parent} = this.props
        console.log(parent)
        return <>
            <div className="modal top fade" id="exampleModal" tabIndex={-1} aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="true" data-mdb-keyboard="true">
                <div className="modal-dialog modal-sm ">
                    <div className="modal-content">
                        <div className="modal-header">
                            <h5 className="modal-title" id="exampleModalLabel">Kingatal yamking</h5>
                            <button type="button" className="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div className="modal-body">...</div>
                    </div>
                </div>
            </div>
        </>
    }
}