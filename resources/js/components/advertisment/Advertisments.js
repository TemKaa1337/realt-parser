import React, { Component } from 'react';

import Advertisment from './Advertisment';

export default class Advertisments extends Component {
    constructor(props) {
        super(props);

        this.state = {
            error: null,
            isLoaded: false,
            advertisments: []
        };
    }

    componentDidMount() {
        const url = 'https://realt-parser.temkaatrashprojects.tech/api/get/advertisments';

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
        console.log(advertisments);

        return (
            <div className="advertisments-container">
                <div></div>
                    <div className="advertisments">
                        {
                            advertisments.map(advertisment =>
                                <Advertisment
                                    key = {advertisment.id}
                                    imageUrl = {advertisment.image_url}
                                    link = {advertisment.link}
                                    header = {advertisment.header}
                                    byn = {advertisment.byn}
                                    usd = {advertisment.usd}
                                    phones = {advertisment.phones}
                                    emails = {advertisment.emails}
                                    location = {advertisment.location}
                                    roomCount = {advertisment.room_count}
                                    description = {advertisment.description}
                                    postedAt = {advertisment.posted_at}
                                />
                            )
                        }
                    </div>
                <div></div>
            </div>
        )
    }
}
