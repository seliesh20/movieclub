import React, { Component } from "react";
import axios from 'axios';
import {Config, Alerts} from '../config/Config';
import { Link } from "react-router-dom";
import { NIL, v4 as uuidv4 } from 'uuid';

class Movielist extends Component{
    constructor(props){
        super(props)
        this.state = {
            isAdd:false,
            page:1,
            movielists:{}
        }        
        this.handleAddMovieClick = this.handleAddMovieClick.bind(this);
        this.goBackMovesList = this.goBackMovesList.bind(this);
        this.getMovies = this.getMovies.bind(this);
        this.getMovies();
    }   

    handleAddMovieClick(event) {
        event.preventDefault()
        this.setState({
            isAdd:true
        })
    }
    goBackMovesList() {        
        this.setState({
            isAdd:false
        })
    }
    getMovies = () =>{
        let th = this;
        try{
            axios({
                method:"get",
                url: Config.BASE_URL + '/api/movie_lists',                
                headers:{
                    'Accept':"application/hal+json",
                    'Authorization': 'Bearer '+ Config.USER_KEY
                }                
            }).then(response => {
                if(response.status == 200){      
                    //update
                    this.setState({
                        movielists:response.data
                    })  
                    document.querySelector('.overlay').className = 'overlay hide';
                }
            }).catch(error=>{
                if(error.response.data.code == 401 && error.response.data.message == 'Expired JWT Token'){
                    th.props.unSetUser();
                    th.props.setAlert({
                        0:{
                            type:"danger",
                            message:"Session Expired!!"
                        }
                    });                    
                } else {
                    th.props.setAlert({
                        0:{
                            type:"danger",
                            message:error.response.data.message
                        }
                    });
                    this.setState({
                        movielists:{}
                    })
                    document.querySelector('.overlay').classList.add('hide');
                }                
            });
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
            document.querySelector('.overlay').classList.add('hide');
        }
    }

    render(){
        let th = this;
        return(<div className="col-md-6 col-lg-6 col-sm-12 box">            
            {this.state.isAdd?(
                <MovielistAdd 
                    goBackMovesList={this.goBackMovesList} 
                    unSetUser={this.props.unSetUser}
                    setAlert={this.props.setAlert}></MovielistAdd>
            ):(
                <div>
                    <h4>MovieList <input type="button" className="btn btn-primary" onClick={this.handleAddMovieClick} value={'Add Movie'}/></h4>
                    {(Object.keys(this.state.movielists).length && this.state.movielists.totalItems > 0)?(
                        Object.keys(this.state.movielists._embedded.item).map(function(i) {                            
                            return <MovieSingle 
                                key={uuidv4()} 
                                movie={th.state.movielists._embedded.item[i]} 
                                unSetUser={th.props.unSetUser}
                                setAlert={th.props.setAlert}
                                />
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
        return(<div className="d-flex col-4 box float-left">
            <img src={this.props.movie.image_url} width="100%" height="160px"/>
            <span className="bottom-front"><b>{this.props.movie.title}{this.props.movie.description}</b></span>
        </div>)
    }
}

/**
 * Movie Add Form
 */
class MovielistAdd extends Component{


    constructor(props){
        super(props)
        this.state = {
            movieslist:{}
        }
        this.onInputSearchChange = this.onInputSearchChange.bind(this);
        this.handleMovieSearchSubmit = this.handleMovieSearchSubmit.bind(this);
        this.getMovies = this.getMovies.bind(this);
    }
    onInputSearchChange(event){
        let search = document.querySelector('input[name=search]').value.trim();
        document.querySelector('input[type=submit]').classList.add('diabled');
        document.querySelector('input[type=submit]').setAttribute('disabled', true);
        if(search.length){
            document.querySelector('input[type=submit]').classList.remove('diabled');
            document.querySelector('input[type=submit]').removeAttribute('disabled');
        }
    }
    handleMovieSearchSubmit(event){
        event.preventDefault();
        this.getMovies();
    }   
    getMovies = () => {
        let th = this;
        let search = document.querySelector('input[name=search]').value;
        document.querySelector('.overlay').classList.remove('hide');

        axios({
            method: "get",
            url: Config.IMDB_URL + search
        }).then(response => {
            if (response.status == 200 && response.data.results != null) {
                //update
                th.setState({
                    movieslist:response.data.results
                })
            }
            if (response.status == 200 && response.data.results == null) {
                //error
                th.props.setAlert({
                    0: {
                        type: "danger",
                        message: response.errorMessage
                    }
                });
            }
            document.querySelector('.overlay').classList.add('hide');
        }).catch(error => {
            th.props.setAlert({
                0: {
                    type: "danger",
                    message: error.response.data.message
                }
            });
            document.querySelector('.overlay').classList.add('hide');
        });
    }
    render(){
        let th = this;
        return (<div>
            <form onSubmit={this.handleMovieSearchSubmit} noValidate>
                <div className="form-group">
                    <h4>Search Movie Title</h4>
                    <input
                        type="text"
                        name="search"
                        className="form-control"
                        maxLength="20"
                        onChange={this.onInputSearchChange}
                        defaultValue={this.state.email}
                    />
                    <i className="clearfix"></i>
                    <div className="form-group">
                        <input type="submit" className="btn btn-success disabled float-right" value="Search" disabled />
                        <input type="button" 
                            className="btn btn-primary float-right" 
                            value="Back" 
                            onClick={th.props.goBackMovesList}/>
                    </div>
                    <i className="clearfix"></i><br/>
                    <div id="list-movies">
                        {(Object.keys(this.state.movieslist).length)?(
                            Object.keys(this.state.movieslist).map(function(i) {                            
                                return <MovieSearchSingle 
                                    key={uuidv4()} 
                                    goBackMovesList={th.props.goBackMovesList} 
                                    movie={th.state.movieslist[i]} 
                                    setAlert={th.props.setAlert}
                                    unSetUser={th.props.unSetUser}/>
                            })
                        ):null}
                    </div>
                </div>
            </form><br/>
            <div id="movie-list-imdb">
                
            </div>
        </div>);
    }
}
/**
 * Single Movie View Component
 */
 class MovieSearchSingle extends Component{
    constructor(props){
        super(props)
        this.handleAddMovie = this.handleAddMovie.bind(this);
        this.checkAdded = this.checkAdded.bind(this);
        this.state = {
            showAdd:false
        }
        this.checkAdded();
    }
    checkAdded(){
        let th = this;
        let formData = new FormData();
        formData.append('imdbId', this.props.movie.id);
        axios({
            method: "post",
            url: Config.BASE_URL + '/api/check_movie',
            data:formData,
            headers:{
                'Content-Type':"application/json",
                'Authorization': 'Bearer '+ Config.USER_KEY
            }
        }).then(response => {
            if(response.status == 200 && response.data.status == 'success'){
                th.setState({
                    showAdd:true
                })
            }
        }).catch(error=>{
            if(error.response.data.code == 401 && error.response.data.message == 'Expired JWT Token'){
                th.props.unSetUser();
                th.props.setAlert({
                    0:{
                        type:"danger",
                        message:"Session Expired!!"
                    }
                });                    
            } else {
                th.props.setAlert({
                    0:{
                        type:"danger",
                        message:error.response.data.message
                    }
                });
                this.setState({
                    movielists:{}
                })                
            }
        });
    }
    handleAddMovie(event){
        event.preventDefault();
        let th = this;
        document.querySelector('.overlay').classList.remove('hide');        
        let now = new Date();
        let now_utc = new Date(now.toUTCString().slice(0, -4));
        axios({
            method: "post",
            url: Config.BASE_URL + '/api/movie_lists',
            data: {
                title: th.props.movie.title,
                description: th.props.movie.description,
                imageUrl: th.props.movie.image,
                imdbId: th.props.movie.id,
                postTime: now_utc,
                user: "/api/users/" + Config.USER_ID
            },
            headers: {
                'Accept': "application/hal+json",
                'Authorization': 'Bearer ' + Config.USER_KEY
            }
        }).then(response => {
            if (response.status == 201) {
                th.props.setAlert({
                    0: {
                        type: "success",
                        message: "Movie " + th.props.movie.title + " Added!!"
                    }
                });
                th.props.goBackMovesList();
                document.querySelector('.overlay').classList.add('hide');
            }
        }).catch(error => {
            console.log(error);
            if (error.response.data.code == 401 && error.response.data.message == 'Expired JWT Token') {
                th.props.unSetUser();
                th.props.setAlert({
                    0: {
                        type: "danger",
                        message: "Session Expired!!"
                    }
                });
            } else {
                th.props.setAlert({
                    0: {
                        type: "danger",
                        message: error.response.data.message
                    }
                });
                this.setState({
                    movielists: {}
                })
                document.querySelector('.overlay').classList.add('hide');
            }
        })
              
    }
    render(){
        return(<div className="d-flex col-4 box float-left">
            <img src={this.props.movie.image} width="100%" height="160px"/>
            {this.state.showAdd?(
                <Link to="/" onClick={this.handleAddMovie} className="top-add-button btn btn-success"><i className="fa fa-plus"></i> Add</Link>
            ):null}            
            <span className="bottom-front"><b>{this.props.movie.title}{this.props.movie.description}</b></span>
        </div>)
    }
}

export default Movielist;