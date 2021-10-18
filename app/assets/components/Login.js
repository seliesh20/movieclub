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
    showError(input, message){
        let value = input.value.trim();        
        let message_div = input.nextElementSibling;
        input.className += " is-invalid";
        message_div.className = "invalid-feedback";
        message_div.innerHTML = message;
    }
    hideError(input){
        let value = input.value.trim();        
        let message_div = input.nextElementSibling;
        input.className = input.className.replace('is-invalid', ' ').trim();
        message_div.className = "";
        message_div.innerHTML = "";    
    }
    onInputChange(event){
        let value = event.target.value.trim();
        let is_valid = true;
        this.hideError(event.target);
        if(value == "" || value.length == 0){
            this.showError(event.target, event.target.name.capitalize()+" cannot be empty!!");
            is_valid = false;
        }
        if(is_valid){
            this.setState({
                [event.target.name]: event.target.value
            });
        }
        let submitClassNames = document.querySelector('input[type=submit]').className;
        submitClassNames = submitClassNames.replaceAll('disabled', ' ').trim();
        submitClassNames = submitClassNames.split(' ').filter((x, i, a) => a.indexOf(x) ==i).join(" ");
        if(this.state.is_valid()){            
            document.querySelector('input[type=submit]').className = submitClassNames;
            document.querySelector('input[type=submit]').removeAttribute('disabled');
        } else {
            document.querySelector('input[type=submit]').className = submitClassNames + ' disabled';
            document.querySelector('input[type=submit]').setAttribute('disabled', true);
        }
    }

    handleSubmit= async(event) => {
        event.preventDefault();
        let th = this;
        if(this.state.is_valid()){
            document.querySelector('.overlay').className = 'overlay';
            var bodyFormData = new FormData();            
            bodyFormData.append('email', this.state.email);
            bodyFormData.append('password', this.state.password);
            try{
                const response = await axios({
                    method:"post",
                    url: Config.BASE_URL + '/api/login_check',
                    data: {username:this.state.email, password:this.state.password},
                    headers:{'Content-Type':"application/json"}
                });
                if(response.status == 200){                                        
                    th.props.setUserKey(response.data.token);                    
                    document.querySelector('.overlay').className = 'overlay hide';
                    //window.location = Config.BASE_URL+'/dashboard';                    
                }                  
            } catch(error){
                if(typeof error.response != "undefined"){
                    th.props.setAlert({
                        0:{
                            type:"danger",
                            message:error.response.data.message
                        }
                    });
                    document.querySelector('.overlay').className = 'overlay hide';
                }                
            }
        }
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