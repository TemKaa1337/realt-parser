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
// componentDidMount
function Main() {
    console.log(React.version);
    
    return (
        <div className = "app-container">
            <Header />
            <div className="ad-container">
                { GetAdvertisments() }
            </div>
            <Footer />
        </div>
    );
}

export default Main;

if (document.getElementById('app')) {
    ReactDOM.render(<Main />, document.getElementById('app'));
}
