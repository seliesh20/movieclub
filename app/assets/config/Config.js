import React, {Component} from "react";


String.prototype.capitalize = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
}

export const Config = {
    BASE_URL:BASE_URL,
    EMAIL_REGREX:/^[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[A-Za-z]+$/,
    USER_KEY:localStorage.getItem('user.USER_KEY'),
    USER_ID:localStorage.getItem('user.USER_ID'),
    IMDB_URL:IMDB_URL
}

class Alerts extends Component{     
    constructor(props){
        super(props);
        this.state = {
            display:true
        };
        this.hideMessage = this.hideMessage.bind(this);
        setTimeout(this.hideMessage, 500);
    }
    hideMessage(){
        this.setState({display:false});
    }
    render(){
        return this.state.display?(
            <div className={"alert alert-"+this.props.type}>
                {this.props.message}
                <button type="button" className="close" onClick={this.hideMessage} data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        ):null
    }       
}
export default Alerts;