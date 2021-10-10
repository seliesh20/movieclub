import React, {Component} from "react";
import {Route, Switch,Redirect, Link, withRouter} from 'react-router-dom';
import Register from "./Register";

class Home extends Component{
    render() {
        return (
           <div>
               <nav className="navbar navbar-expand-lg navbar-dark bg-dark">
                   <Link className={"navbar-brand"} to={"/"}> MovieClub </Link>
                   <div className="collapse navbar-collapse" id="navbarText">
                       <ul className="navbar-nav mr-auto">
                           <li className="nav-item">
                               <Link className={"nav-link"} to={"/posts"}> Posts </Link>
                           </li>                           
                       </ul>                       
                   </div>
                   <Link className={"btn btn-primary"} to={"/register"}> Register </Link>
                   &nbsp;&nbsp;
                   <Link className={"btn btn-primary"} to={"/login"}> Login </Link>
               </nav>
               <Switch>            
                   <Route path="/register" component={Register} />                   
               </Switch>
           </div>
        )
    }
}

export default Home;