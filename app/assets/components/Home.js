import React, {Component} from "react";
import {Route, Switch,Redirect, Link, withRouter} from 'react-router-dom';
import Alerts, { Config} from "../config/Config";
import { v4 as uuidv4 } from 'uuid';

/* Components */
import Register from "./Register";
import Login from "./Login";
import Dashboard from "./Dashboard";
import Movielist from "./Movielist";

class Home extends Component{
    constructor(){
        super();   
        this.state = {
            alerts:{}
        }      
        this.setAlert = this.setAlert.bind(this);        
    }  
    setAlert(alertData){        
        this.setState({alerts:alertData});       
    }    
    render() {
        let th = this;
        return (
           <div>
               <nav className="navbar navbar-expand-lg navbar-dark bg-dark">
                   <Link className={"navbar-brand"} to={"/"}> MovieClub </Link>
                   <div className="collapse navbar-collapse" id="navbarText">
                       <ul className="navbar-nav mr-auto">
                           <li className="nav-item">
                               <Link className={"nav-link"} to={"/movielist"}> Movies </Link>
                           </li>                           
                       </ul>                       
                   </div>
                   <Link className={"btn btn-primary"} to={"/register"}> Register </Link>
                   &nbsp;&nbsp;
                   <Link className={"btn btn-primary"} to={"/login"}> Login </Link>
               </nav>
               <div id="alertbox">                                             
                    {Object.keys(this.state.alerts).length === 0?(<br/>):(                                                
                        Object.keys(this.state.alerts).map(function(alert, i) {                            
                            return <Alerts key={uuidv4()} type={th.state.alerts[i].type} message={th.state.alerts[i].message}/>
                        })  
                    )}
               </div>
               <Switch>            
                    <Route path="/register">
                        <Register setAlert={this.setAlert}/>
                    </Route>
                    <Route path="/login">
                        <Login setAlert={this.setAlert}/>
                    </Route>
                    <Route path="/dashboard">
                        <Dashboard setAlert={this.setAlert}/>
                    </Route>
                    <Route path="/movielist">
                        <Movielist setAlert={this.setAlert}/>
                    </Route>
               </Switch>
           </div>
        )
    }
}

export default Home;