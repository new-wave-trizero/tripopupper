import React, { Component } from 'react';
import laravelConfig from '../laravel-config';

const validName = name => {
  //if (name === '') {
    //return 'fuck';
  //}
};

export default class NewPopupForm extends Component {
  constructor(props) {
    super(props);
    this.state = {
      name: props.name || '',
      loadingNewName: false,
      nameFocused: false,
      nameError: props.nameError || '',
    };

    this.suggestNewName = this.suggestNewName.bind(this);
    this.handleNameChange = this.handleNameChange.bind(this);
  }

  render() {
    const { action } = this.props;
    const { name, loadingNewName, nameError, nameFocused } = this.state;
    const showNameError = nameError && !loadingNewName;

    return (
      <form method="POST" action={action} noValidate style={{ paddingTop: '0px', marginTop: '0px' }}>

        <input type="hidden" name="_token" value={laravelConfig.csrf_token} />

        <div
          style={{ marginTop: '0px', paddingTop: '0px' }}
          className={`form-group ${showNameError  ? 'has-error' : ''} ${nameFocused ? 'is-focused' : ''}`}>

          <div className="input-group">
            <label className="control-label">Nome</label>
            <input
              type="text"
              autoComplete="off"
              className="form-control"
              name="name"
              placeholder={loadingNewName ? '...' : ''}
              value={loadingNewName ? '' : name}
              onBlur={() => this.setState({ nameFocused: false })}
              onFocus={() => this.setState({ nameFocused: true })}
              onChange={this.handleNameChange}
              disabled={loadingNewName}
            />
            {showNameError && <p className="help-block">{nameError}</p>}

            <span className="input-group-btn" style={{'display':''}}>
              <button
                type="button"
                className="btn btn-fab btn-fab-mini btn-default"
                title="Suggerisci nuovo nome"
                onClick={this.suggestNewName}
                disabled={loadingNewName}>
                <i className="material-icons">refresh</i>
              </button>
            </span>

          </div>

          <div className="progress progress-striped active" style={{ marginTop: '0px', visibility: loadingNewName ? 'visible' : 'hidden' }}>
            <div className="progress-bar trizzy-color" style={{width: '100%'}}></div>
          </div>

        </div>

        <div className="form-group" style={{ marginTop: '0px' }}>
          <button type="submit" className="btn btn-sm btn-default btn-raised"><i className="material-icons">add_box</i> Nuovo popup</button>
        </div>

      </form>
    );
  }

  handleNameChange(e) {
    const name = e.target.value;
    this.setState({ name, nameError: validName(name) });
  }

  suggestNewName() {
    this.setState({ loadingNewName: true });
    $.getJSON(`${laravelConfig.app_url}/popup/suggest-name`)
      .done(name => this.setState({ name, nameError: null }))
      .always(() => this.setState({ loadingNewName: false }));
  }
}
