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
            alerts:{},
            user:(Config.USER_KEY != null)
        }      
        this.setAlert = this.setAlert.bind(this);
        this.setUserKey = this.setUserKey.bind(this);        
        this.unSetUserKey = this.unSetUserKey.bind(this);        
    }  
    setAlert(alertData){        
        this.setState({alerts:alertData});       
    }    
    setUserKey(key){
        Config.USER_KEY = key;
        localStorage.setItem("user.key", key);
        this.setState({user:true});
    }
    unSetUserKey(e){
        e.preventDefault();
        Config.USER_KEY = null;
        localStorage.removeItem("user.key");
        this.setState({user:false});
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
                               
                           </li>                           
                       </ul>                       
                   </div>
                   {this.state.user == false?(
                       <div>
                        <Link className={"btn btn-primary"} to={"/register"}> Register </Link>                    
                        <Link className={"btn btn-primary"} to={"/login"}> Login </Link>
                        </div>
                    ):(
                        <div>
                        <Link className={"btn btn-primary"} to={"/logout"} onClick={this.unSetUserKey}> Logout </Link>
                        </div>
                    )}
               </nav>
               <div id="alertbox">                                             
                    {Object.keys(this.state.alerts).length === 0?(<br/>):(                                                
                        Object.keys(this.state.alerts).map(function(alert, i) {                            
                            return <Alerts key={uuidv4()} type={th.state.alerts[i].type} message={th.state.alerts[i].message}/>
                        })  
                    )}
               </div>
               {this.state.user == true?(
                <Switch>
                    <Redirect exact from="/" to="/dashboard" />
                    <Redirect exact from="/login" to="/dashboard" />
                    <Route path="/dashboard">
                        <Dashboard setAlert={this.setAlert}/>
                    </Route>
                    <Route path="/movielists">
                        <Movielist setAlert={this.setAlert}/>
                    </Route>
                </Switch>    
               ):(
                <Switch>
                    <Redirect exact from="/dashboard" to="/login" />
                    <Route path="/register">
                        <Register setAlert={this.setAlert}/>
                    </Route>
                    <Route path="/login">                           
                        <Login setAlert={this.setAlert} setUserKey={this.setUserKey}/>
                    </Route>
                </Switch>    
               )}               
           </div>
        )
    }
}

export default Home;