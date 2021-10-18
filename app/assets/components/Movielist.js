import React, { Component } from "react";
import axios from 'axios';
import {Config, Alerts} from '../config/Config';
import { Link } from "react-router-dom";
import { v4 as uuidv4 } from 'uuid';

class Movielist extends Component{
    constructor(props){
        super(props)
        this.state = {
            isAdd:false,
            page:1,
            movielists:{}
        }
        console.log(props);
        this.getMovies();
    }   

    getMovies = async(event) =>{
        let th = this;
        try{
            const response = await axios({
                method:"get",
                url: Config.BASE_URL + '/api/movie_lists',
                data: {},
                headers:{
                    'Accept':"application/hal+json",
                    'Authorization': 'Bearer '+ Config.USER_KEY
                }                
            });
            if(response.status == 200){      
                  //update
                  this.setState({
                    movielists:response.data
                  })  
                  document.querySelector('.overlay').className = 'overlay hide';
            }
        } catch(error){
            th.props.setAlert({
                0:{
                    type:"danger",
                    message:error.response.data.message
                }
            });
            this.setState({
                movielists:{}
            })
            document.querySelector('.overlay').className = 'overlay hide';
        }
    }

    render(){
        let th = this;
        return(<div className="col-md-6 col-lg-6 col-sm-12 box">
            {this.isAdd?(
                <MovielistAdd></MovielistAdd>
            ):(
                <div>
                    <h4>MovieList</h4>                    
                    {(Object.keys(this.state.movielists).length && this.state.movielists.totalItems > 0)?(
                        Object.keys(this.state.movielists._embedded.item).map(function(i) {                            
                            return <MovieSingle key={uuidv4()} movie={th.state.movielists._embedded.item[i]} />
                        })
                    ):null}                                    
                </div>
            )}
            <div className="overlay">
                <i className="fa fa-refresh fa-spin"></i>
            </div>
        </div>);
    }
}


/**
 * Single Movie View Component
 */
class MovieSingle extends Component{
    constructor(props){
        super(props)
        console.log(props)
    }
    render(){
        return(<div className="d-flex col-4 box">
            <img src={this.props.movie.image_url} width="250px"/>
            <span className="bottom-front"><b>{this.props.movie.title}{this.props.movie.description}</b></span>
        </div>)
    }
}

/**
 * Movie Add Form
 */
class MovielistAdd extends Component{


    render(){
        return(<form onSubmit={this.handleSubmit} noValidate> 

        </form>);
    }
}

export default Movielist;