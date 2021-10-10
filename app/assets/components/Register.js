import React, {Component} from "react";
import axios from 'axios';
import { objectOf } from "prop-types";
import {Config} from './Config';

class Register extends Component{

    constructor(){
        super();
        this.state = {
            name:"",
            email:"",
            password:"",
            repeatpassword:""
        }
        this.handleSubmit = this.handleSubmit.bind(this);
        this.onInputChange = this.onInputChange.bind(this);
    }

    handleSubmit(event) {
        event.preventDefault();
        let is_valid = true;
        //validate        
        Object.keys(this.state).forEach((key) => {
            let value = this.state[key].trim();
            if(value == "" || value.length == 0){
                is_valid = false;
            }            
        });
        //save
        if(is_valid){
            axios.post(Config.BASE_URL + '/api/register', this.state).then((response) => {
                if(response.status == 'success'){

                } else {
                    console.log(response.data.errors);
                }
            });
        }
    }

    onInputChange(event){        
        this.setState({
            [event.target.name] :event.target.value
        })
    }

    render(){
        return(
            <div>
                <h3>User Registration</h3>
                <div className="d-flex justify-content-center col-12">
                    <form className="col-md-5 col-lg-3 col-sm-8" onSubmit={this.handleSubmit}>
                        <div className="form-group">
                            <label>Name</label>
                            <input 
                                type="text" 
                                name="name" 
                                className="form-control" 
                                maxLength="20"
                                defaultValue={this.state.name} 
                                onChange={this.onInputChange}/>
                        </div>
                        <div className="form-group">
                            <label>Email</label>
                            <input 
                                type="text" 
                                name="email" 
                                className="form-control" 
                                maxLength="20"
                                onChange={this.onInputChange}
                                defaultValue={this.state.email}/>
                        </div>
                        <div className="form-group">
                            <label>Password</label>
                            <input 
                                type="password" 
                                name="password" 
                                className="form-control" 
                                maxLength="20"
                                onChange={this.onInputChange}
                                defaultValue={this.state.password}/>
                        </div>
                        <div className="form-group">
                            <label>Repeat Password</label>
                            <input 
                                type="password" 
                                name="repeatpassword" 
                                className="form-control" 
                                maxLength="20"
                                onChange={this.onInputChange}
                                defaultValue=""/>
                        </div>
                        <div className="form-group">
                            <input type="submit" className="btn btn-success" value="Register"/>
                        </div>
                    </form>
                </div>
            </div>
        )
    }
}

export default Register;
