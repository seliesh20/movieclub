import React, { Component } from "react";
import axios from 'axios';
import {Config, Alerts} from '../config/Config';
import { Link } from "react-router-dom";

class Login extends Component{
    constructor(props){
        super(props)
        this.state = {            
            email:"",
            password:"",            
            is_valid(){
                return (this.email.length
                    && this.password.length);
            }
        };

        this.handleSubmit = this.handleSubmit.bind(this);
        this.onInputChange = this.onInputChange.bind(this);
    }
    onInputChange(){

    }

    handleSubmit(){

    }

    render(){
        return(
        <div id="user-login" className="box">                
            <h3>User Login</h3>
            <div className="d-flex justify-content-center col-12">
                <form className="col-md-5 col-lg-3 col-sm-8" onSubmit={this.handleSubmit} noValidate>
                    <div className="form-group">
                        <label>Email</label>
                        <input 
                            type="text" 
                            name="email" 
                            className="form-control" 
                            maxLength="20"
                            onChange={this.onInputChange}
                            defaultValue={this.state.email}
                        />
                        <div></div>
                    </div>
                    <div className="form-group">
                        <label>Password</label>
                        <input 
                            type="password" 
                            name="password" 
                            className="form-control" 
                            maxLength="20"
                            onChange={this.onInputChange}
                            defaultValue={this.state.password}
                        />
                        <div></div>
                    </div>
                    <div className="form-group">
                        <input type="submit" className="btn btn-success disabled" value="Login" disabled/>
                    </div>                    
                </form>
            </div>
            <div className="overlay hide">
                <i className="fa fa-refresh fa-spin"></i>
            </div>
        </div>
        )
    }
}
export default Login;