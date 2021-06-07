import React, { Component } from 'react';

export default class Advertisment extends Component {
    constructor(props) {
        super(props);
    }

    render() {
        return (
            <div className = "ad">
                <img src = {this.props.imageUrl} className = 'flat-image'></img>
                <a href = {this.props.link}>{this.props.header}</a>
                <a>{'Цена: ' + (this.props.byn !== null ? this.props.byn + 'р. /' + this.props.usd + '$ в сутки' : 'договорная')}</a>
                <a>{'Телефоны: ' + (this.props.phones.length !== 0 ? this.props.phones.join(', ') : 'нет')}</a>
                <a>{'Эл. почты: ' + (this.props.emails.length !== 0 ? this.props.emails.join(', ') : 'нет')}</a>
                <a>{'Расположение: ' + this.props.location}</a>
                <a>{'Комнат: ' + this.props.roomCount}</a>
                <p>{this.props.description}</p>
                <p>{'Дата размещения: ' + this.props.postedAt}</p>
            </div>
        );
    }
}
