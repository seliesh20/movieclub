import React, {Component} from "react";
import axios from 'axios';
import {Config} from '../config/Config';

class Register extends Component{

    constructor(){
        super();
        this.state = {
            name:"",
            email:"",
            password:"",            
            is_valid(){                
                return (this.name.length 
                    && this.email.length
                    && this.password.length
                    && this.password == document.querySelector('input[type=password][name=repeatpassword]').value);
            }
        }
        this.handleSubmit = this.handleSubmit.bind(this);
        this.onInputChange = this.onInputChange.bind(this);
    }

    handleSubmit(event) {
        event.preventDefault();        
        //save
        if(this.state.is_valid()){
            axios.post(Config.BASE_URL + '/api/register', {
                name:this.state.name,
                email:this.state.email,
                name:this.state.password,
            }).then((response) => {
                if(response.status == 'success'){

                } else {
                    console.log(response.data.errors);
                }
            });
        } else {
            //document.getElementsByTagName('form')[0].className += "";

        }
    }

    onInputChange(event){     
        //validate
        let value = event.target.value.trim();
        let is_valid = true;
        let message_div = event.target.nextElementSibling;
        event.target.className = event.target.className.replace('is-invalid', ' ').trim();
        message_div.className = "";
        message_div.innerHTML = "";
        if(value == "" || value.length == 0){
            event.target.className = event.target.className + ' is-invalid';
            message_div.className = "invalid-feedback";
            message_div.innerHTML = event.target.name.capitalize()+" cannot be empty!!";
            is_valid = false;
        }        
        //Email Validator
        if(event.target.name == 'email'){
            if(!Config.EMAIL_REGREX.test(value)){
                event.target.className = event.target.className + ' is-invalid';
                message_div.className = "invalid-feedback";
                message_div.innerHTML = "Email entered is invalid";
                is_valid = false;
            }
        }
        if(event.target.name == 'repeatpassword'
            && event.target.value != this.state.password){
                event.target.className = event.target.className + ' is-invalid';
                message_div.className = "invalid-feedback";
                message_div.innerHTML = "Password does not match";
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
            document.querySelector('input[type=submit]').setAttribute('disabled');
        }
    }

    render(){
        return(
            <div>
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
            </div>
        )
    }
}

export default Register;
