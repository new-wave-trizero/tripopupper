import React, { Component } from 'react';

export default class Yeah extends Component {
  constructor(props) {
    super(props);
    this.state = {
      name: 'Giova',
    };
  }

  render() {
    //const x = 'Giova';
    //console.log(this.state.name);
    return (
      <div>
          <button onClick={() => this.setState({ name: this.state.name + ' X ' })}>X</button>
          <form>
            <button
               className="btn btn-danger btn-fab btn-fab-mini ask-confirm"
               data-confirm-title={`Popup ${this.state.name}`}
               data-confirm-body="Sei sicuro di voler eliminare il popup <strong>{{ $popup->name }}</strong>?"
               data-confirm-btn-title="Elimina"
               data-confirm-btn-class="btn-danger"
               title="Elimina popup {{ $popup->name }}">
              <i className="material-icons">delete</i></button>
          </form>
      </div>
    );
  }
}
