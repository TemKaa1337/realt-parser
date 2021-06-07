import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import Header from './header/Header';
import Advertisments from './advertisment/Advertisments';

import '../app.css';

export default class Main extends Component {
    constructor(props) {
        super(props);
    }

    render() {
        return (
            <div className = "app-container">
                <Header />
                <Advertisments />
            </div>
        );
    }
}

if (document.getElementById('app')) {
    ReactDOM.render(<Main />, document.getElementById('app'));
}
