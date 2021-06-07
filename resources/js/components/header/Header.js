import React, { Component } from 'react';

export default class Header extends Component {
    constructor(props) {
        super(props);
    }

    render() {
        return (
            <div className = 'navbar'>
                <a href = '#'>temkaatrashprojects.tech</a>
            </div>
        );
    }
}
