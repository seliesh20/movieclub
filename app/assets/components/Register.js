import React, {Component} from "react";
import axios from 'axios';
import {Config, Alerts} from '../config/Config';
import { Link } from "react-router-dom";

class Register extends Component{

    constructor(props){
        super(props);
        this.state = {
            name:"",
            email:"",
            password:"",            
            is_valid(){                
                return (this.name.length 
                    && this.email.length
                    && this.password.length
                    && document.querySelector('input[type=password][name=password]').value 
                        == document.querySelector('input[type=password][name=repeatpassword]').value);
            }
        }
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
    handleSubmit = async(event) => {
        event.preventDefault();        
        //loading 
        let th = this;
        //save
        if(this.state.is_valid()){
            document.querySelector('.overlay').className = 'overlay';
            var bodyFormData = new FormData();
            bodyFormData.append('name', this.state.name);
            bodyFormData.append('email', this.state.email);
            bodyFormData.append('password', this.state.password);
            try{
                const response = await axios({
                    method:"post",
                    url: Config.BASE_URL + '/api/register',
                    data: bodyFormData,
                    headers:{'Content-Type':"multipart/form-data"}
                });
                if(response.status == 200){
                    console.log(response.data.status === "success"); 
                    if(response.data.status === "success"){                        
                        //show success message        
                        var link = <Link to="/login">Login</Link>;
                        var username = <strong>{this.state.name}</strong>;
                        th.props.setAlert({
                            0:{
                                type:"success",
                                message:<span>The user {username} is registered successfully!! Please {link} here!!'</span>
                            }
                        });
                        //window.location.href = Config.BASE_URL + '/login';
                    } else {
                        Object.keys(response.data.result.errors).forEach(function(key, i){
                            th.showError(document.querySelector('input[name='+key+']'), response.data.result.errors[key].join('<br/>'));                            
                        });
                    }
                    document.querySelector('.overlay').className = 'overlay hide';
                }
            } catch( error){
                //show error
                th.props.setAlert({
                    0:{
                        type:"danger",
                        message:"An internal error occured!!"                            
                    }
                });
            }            
        }
    }

    onInputChange(event){        
        //validate
        let value = event.target.value.trim();
        let is_valid = true;
        this.hideError(event.target);
        if(value == "" || value.length == 0){
            this.showError(event.target, event.target.name.capitalize()+" cannot be empty!!");
            is_valid = false;
        }        
        //Email Validator
        if(event.target.name == 'email'){
            if(!Config.EMAIL_REGREX.test(value)){
                this.showError(event.target, "Email entered is invalid!!");
                is_valid = false;
            }
        }
        if(event.target.name == 'repeatpassword'
            && event.target.value != this.state.password){
                this.showError(event.target, "Password does not match");                
                is_valid = false;
        }
        if(is_valid && event.target.name != 'repeatpassword'){
            this.setState({
                [event.target.name] :event.target.value
            })
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

    render(){
        return(
            <div id="user-reg" className="box">                
                <h3>User Registration</h3>
                <div className="d-flex justify-content-center col-12">
                    <form className="col-md-5 col-lg-3 col-sm-8" onSubmit={this.handleSubmit} noValidate>
                        <div className="form-group">
                            <label>Name</label>
                            <input 
                                type="text" 
                                name="name" 
                                className="form-control" 
                                maxLength="20"
                                defaultValue={this.state.name} 
                                onChange={this.onInputChange}
                                />
                                <div></div>
                        </div>
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
                            <label>Repeat Password</label>
                            <input 
                                type="password" 
                                name="repeatpassword" 
                                className="form-control" 
                                maxLength="20"
                                onChange={this.onInputChange}
                                defaultValue=""
                                />
                                <div></div>
                        </div>
                        <div className="form-group">
                            <input type="submit" className="btn btn-success disabled" value="Register" disabled/>
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

export default Register;
