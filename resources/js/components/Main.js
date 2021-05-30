import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import Header from '../components/Header';
import Footer from '../components/Footer';
import Advertisment from '../components/Advertisment';

import '../../css/app.css';

export default class Main extends Component {
    constructor(props) {
        super(props);

        this.state = {
            error: null,
            isLoaded: false,
            advertisments: []
        };
    }

    componentDidMount() {
        const url = 'http://127.0.0.1:8000/api/';

        fetch(url)
        .then(response => response.json())
        .then(
            (result) => {
                this.setState({
                    isLoaded: true,
                    advertisments: result
                });
            },
            (error) => {
                this.setState({
                    isLoaded: true,
                    error: error
                });
            }
        )
    }

    render() {
        const {error, isLoaded, advertisments} = this.state;

        if (error) {
            return <h1>An error occured. Message: {error.message}.</h1>;
        }

        if (!isLoaded) {
            return <h1>Loading...</h1>;
        }

        return (
            <div className = "app-container">
                <Header />
                <div className="ad-container">
                    {
                        advertisments.map(advertisment =>
                            <Advertisment
                                key = {advertisment.id}
                            />
                        )
                    }
                </div>
                <Footer />
            </div>
        );
    }
}

if (document.getElementById('app')) {
    ReactDOM.render(<Main />, document.getElementById('app'));
}
