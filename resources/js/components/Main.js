import React from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';

import Header from '../components/Header';
import Footer from '../components/Footer';
import Advertisment from '../components/Advertisment';

import '../../css/app.css';

function GetAdvertisments() {

    let ads = [];

    for (let i = 0; i < 8; i ++) {
        ads.push(
            <Advertisment key = {i} />
        )
    }

    return ads;
}

class Main extends Component {
    constructor() {
        this.state = {
            isDataFetched: false
        };
    }

    componentDidMount() {
        fetch()
            .then( async (data) => {

            })
            .catch( err => {
                console.log(err)
            })
    }

    render() {
        return isDataFetched ? (
            <div className = "app-container">
                <Header />
                <div className="ad-container">
                    { GetAdvertisments() }
                </div>
                <Footer />
            </div>
        ) : <h1>Data is loading</h1>;
    }
}

export default Main;

if (document.getElementById('app')) {
    ReactDOM.render(<Main />, document.getElementById('app'));
}
