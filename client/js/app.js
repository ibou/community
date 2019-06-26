import React, { Suspense, lazy, Component } from 'react';
import ReactDOM from 'react-dom';
import Cards from './Components/Cards/Cards';
import { BrowserRouter as Router, Route, Switch } from 'react-router-dom';
import ItemPost from "./Components/Posts/itemPost";


class App extends Component {

  constructor() {
    super();
    this.state = {
      entries: []
    };

  }


  render() {
    return (
      <div className="wrapper">
        <div>
          <Router>
            <Suspense fallback={<div>Loading...</div>}>
              <Switch>
                <Route path="/client/films" component={Cards} />
                <Route path="/posts/article/client/uui=:uuid" component={ItemPost} />
              </Switch>
            </Suspense>
          </Router>
        </div>
      </div>


    )
      ;
  }
}
ReactDOM.render(<App />, document.getElementById('root'));