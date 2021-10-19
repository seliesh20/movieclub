import React, { Component } from "react";
import axios from 'axios';
import {Config, Alerts} from '../config/Config';
import Movielist from "./Movielist"
import Meetings from "./Meetings"

class Dashboard extends Component{
    constructor(props){
        super(props)        
    }
    
    render(){
        return(
            <div className="d-flex justify-content-center col-12">
                <Movielist 
                    setAlert={this.props.setAlert}
                    unSetUser={this.props.unSetUser}
                    />
                <Meetings />
            </div>            
        )
    }
}
export default Dashboard;