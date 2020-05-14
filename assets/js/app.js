import React, { Component } from "react";
import ReactDOM from "react-dom";
import ImgurContext from "./contexts/ImgurContext";

import CssBaseline from "@material-ui/core/CssBaseline";
import Container from "@material-ui/core/Container";

import '../css/app.css'; // here is where the magic happens

class App extends Component {
  render() {
    return (
      <Container>
        <CssBaseline />
        <ImgurContext />
      </Container>
    );
  }
}

ReactDOM.render(<App />, document.getElementById("root"));
